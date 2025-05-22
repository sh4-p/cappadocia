<?php
/**
 * Contact Log Reader Tool
 * 
 * This tool helps decrypt and read contact form logs
 * Usage: php read_contact_logs.php
 */

// Define base path
define('BASE_PATH', dirname(dirname(__FILE__)));

// Include required files
require_once BASE_PATH . '/config/config.php';

// Initialize encryption key
$encryptionKey = LOG_ENCRYPTION_KEY;

class ContactLogReader
{
    private $encryptionKey;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->encryptionKey = LOG_ENCRYPTION_KEY;
    }
    
    /**
     * Read and decrypt log file
     */
    public function readLogs()
    {
        $logFile = BASE_PATH . '/logs/contact.log';
        
        if (!file_exists($logFile)) {
            echo "Log file not found.\n";
            return;
        }
        
        // Read log file
        $logs = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if (empty($logs)) {
            echo "No logs found.\n";
            return;
        }
        
        echo "=== Contact Form Logs ===\n\n";
        
        foreach ($logs as $index => $log) {
            // Extract timestamp and encrypted data
            preg_match('/\[(.*?)\] (.*)/', $log, $matches);
            
            if (count($matches) < 3) {
                // Try to decrypt old format logs
                echo "Log #" . ($index + 1) . " (Unencrypted - Old Format):\n";
                echo $log . "\n\n";
                continue;
            }
            
            $timestamp = $matches[1];
            $encryptedData = $matches[2];
            
            // Try to decrypt
            $decryptedData = $this->decryptLogData($encryptedData);
            
            if ($decryptedData === null) {
                // If decryption fails, it might be an old unencrypted log
                echo "Log #" . ($index + 1) . " (Unencrypted):\n";
                echo "Timestamp: $timestamp\n";
                echo "Data: $encryptedData\n\n";
            } else {
                // Display decrypted data
                echo "Log #" . ($index + 1) . " (Decrypted):\n";
                echo "Logged at: $timestamp\n";
                echo "To: " . ($decryptedData['to'] ?? 'N/A') . "\n";
                echo "From: " . ($decryptedData['from_name'] ?? 'N/A') . " <" . ($decryptedData['from_email'] ?? 'N/A') . ">\n";
                echo "Subject: " . ($decryptedData['subject'] ?? 'N/A') . "\n";
                echo "Timestamp: " . ($decryptedData['timestamp'] ?? 'N/A') . "\n\n";
            }
        }
    }
    
    /**
     * Decrypt log data
     * 
     * @param string $encryptedData Encrypted data
     * @return array|null Decrypted data
     */
    private function decryptLogData($encryptedData)
    {
        // Create a hash of the key for consistent length
        $key = hash('sha256', $this->encryptionKey, true);
        
        // Decode the data
        $data = @base64_decode($encryptedData);
        
        if ($data === false || strlen($data) < 16) {
            return null;
        }
        
        // Extract IV and encrypted content
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        
        // Decrypt
        $decrypted = @openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
        
        if ($decrypted === false) {
            return null;
        }
        
        $jsonData = @json_decode($decrypted, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }
        
        return $jsonData;
    }
    
    /**
     * Clear all logs (with confirmation)
     */
    public function clearLogs()
    {
        echo "Are you sure you want to clear all contact logs? (yes/no): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        
        if (trim($line) === 'yes') {
            $logFile = BASE_PATH . '/logs/contact.log';
            file_put_contents($logFile, '');
            echo "Logs cleared successfully.\n";
        } else {
            echo "Operation cancelled.\n";
        }
        
        fclose($handle);
    }
}

// Check if running from command line
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.\n");
}

// Create reader instance
$reader = new ContactLogReader();

// Check command line arguments
$action = isset($argv[1]) ? $argv[1] : 'read';

switch ($action) {
    case 'read':
        $reader->readLogs();
        break;
    case 'clear':
        $reader->clearLogs();
        break;
    default:
        echo "Usage: php read_contact_logs.php [read|clear]\n";
        echo "  read  - Read and decrypt contact logs (default)\n";
        echo "  clear - Clear all contact logs\n";
        break;
}
