<?php
/**
 * Custom Logger Class
 * Replaces error_log() for hosting environments that don't support it
 */
class Logger 
{
    private static $instance = null;
    private $logDirectory;

    private function __construct() 
    {
        $this->logDirectory = dirname(__DIR__) . '/logs';
        $this->ensureLogDirectoryExists();
    }

    public static function getInstance() 
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function ensureLogDirectoryExists() 
    {
        if (!is_dir($this->logDirectory)) {
            mkdir($this->logDirectory, 0755, true);
        }
    }

    /**
     * Log a message to file
     * @param string $message The message to log
     * @param string $level Log level (ERROR, WARNING, INFO, DEBUG)
     * @param string $file Optional filename for specific log files
     */
    public function log($message, $level = 'ERROR', $file = null) 
    {
        try {
            $timestamp = date('Y-m-d H:i:s');
            $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
            
            // Determine log file
            if ($file) {
                $logFile = $this->logDirectory . '/' . $file . '.log';
            } else {
                $logFile = $this->logDirectory . '/' . strtolower($level) . '_' . date('Y-m-d') . '.log';
            }
            
            // Write to log file
            file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
            
        } catch (Exception $e) {
            // Fallback - if we can't write to file, fail silently
            // to prevent breaking the application
        }
    }

    /**
     * Log error message
     * @param string $message
     * @param string $file Optional filename
     */
    public function error($message, $file = null) 
    {
        $this->log($message, 'ERROR', $file);
    }

    /**
     * Log warning message
     * @param string $message
     * @param string $file Optional filename
     */
    public function warning($message, $file = null) 
    {
        $this->log($message, 'WARNING', $file);
    }

    /**
     * Log info message
     * @param string $message
     * @param string $file Optional filename
     */
    public function info($message, $file = null) 
    {
        $this->log($message, 'INFO', $file);
    }

    /**
     * Log debug message
     * @param string $message
     * @param string $file Optional filename
     */
    public function debug($message, $file = null) 
    {
        $this->log($message, 'DEBUG', $file);
    }

    /**
     * Static helper function to replace error_log() calls
     * @param string $message
     * @param string $file Optional filename
     */
    public static function writeLog($message, $file = null) 
    {
        self::getInstance()->error($message, $file);
    }
}

/**
 * Global helper function to replace error_log() calls
 * @param string $message
 * @param string $file Optional filename
 */
function writeLog($message, $file = null) 
{
    Logger::writeLog($message, $file);
}
?>