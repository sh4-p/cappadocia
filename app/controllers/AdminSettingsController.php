<?php
/**
 * Admin Settings Controller
 * 
 * Handles settings management in admin panel
 */
class AdminSettingsController extends Controller
{
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Require login
        $this->requireLogin();
    }
    
    /**
     * Get controller name
     * 
     * @return string Controller name
     */
    public function getControllerName()
    {
        return 'AdminSettings';
    }
    
    /**
     * Get action name
     * 
     * @return string Action name
     */
    public function getActionName()
    {
        $action = '';
        
        if (isset($_GET['url'])) {
            $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            
            if (count($url) >= 2) {
                $action = $url[1];
            }
        }
        
        return $action ?: 'index';
    }
    
    /**
     * Index action - display settings
     */
    public function index()
    {
        // Load settings model
        $settingsModel = $this->loadModel('Settings');
        
        // Get all settings
        $settings = $settingsModel->getAllSettings();
        
        // Load language model for language settings
        $languageModel = $this->loadModel('LanguageModel');
        $languages = $languageModel->getAllLanguages();
        
        // Render view
        $this->render('admin/settings/index', [
            'pageTitle' => __('settings'),
            'settings' => $settings,
            'languages' => $languages
        ], 'admin');
    }
    
    /**
     * Update action - update settings
     */
    public function update()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/settings');
        }
        
        // Load settings model
        $settingsModel = $this->loadModel('Settings');
        
        // Get form data
        $settings = $this->post('settings', []);
        
        // Handle checkbox values - if not posted, set to 0
        $checkboxSettings = [
            'payment_card',
            'payment_paypal', 
            'payment_bank',
            'payment_cash',
            'stripe_test_mode',
            'paypal_sandbox',
            'maintenance_mode',
            'debug_mode',
            'email_notification_booking',
            'email_notification_contact',
            'email_notification_registration',
            'email_notification_newsletter',
            'smtp_enabled',
            'smtp_auth'
        ];
        
        foreach ($checkboxSettings as $key) {
            if (!isset($settings[$key])) {
                $settings[$key] = '0';
            }
        }
        
        // Handle regular file uploads (logo, favicon)
        $uploadedFiles = [
            'logo' => $this->file('logo'),
            'favicon' => $this->file('favicon')
        ];
        
        // Process file uploads
        foreach ($uploadedFiles as $key => $file) {
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                // Define upload directory
                $uploadDir = BASE_PATH . '/public/img/';
                
                // Create directory if not exists
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                // Get file extension
                $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                
                // Validate file type and generate filename
                if ($key === 'logo') {
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'svg'];
                    if (!in_array($fileExtension, $allowedTypes)) {
                        $this->session->setFlash('error', __('invalid_logo_format'));
                        $this->redirect('admin/settings');
                        return;
                    }
                    
                    // Validate file size (max 5MB)
                    if ($file['size'] > 5 * 1024 * 1024) {
                        $this->session->setFlash('error', __('logo_file_too_large'));
                        $this->redirect('admin/settings');
                        return;
                    }
                    
                    $fileName = 'logo.' . $fileExtension;
                    
                } elseif ($key === 'favicon') {
                    $allowedTypes = ['ico', 'png'];
                    if (!in_array($fileExtension, $allowedTypes)) {
                        $this->session->setFlash('error', __('invalid_favicon_format'));
                        $this->redirect('admin/settings');
                        return;
                    }
                    
                    // Validate file size (max 1MB)
                    if ($file['size'] > 1024 * 1024) {
                        $this->session->setFlash('error', __('favicon_file_too_large'));
                        $this->redirect('admin/settings');
                        return;
                    }
                    
                    $fileName = 'favicon.' . $fileExtension;
                }
                
                $uploadPath = $uploadDir . $fileName;
                
                // Delete old file if exists (different extension)
                $existingFiles = glob($uploadDir . pathinfo($fileName, PATHINFO_FILENAME) . '.*');
                foreach ($existingFiles as $existingFile) {
                    if (file_exists($existingFile)) {
                        unlink($existingFile);
                        // File deletion success - no logging needed
                    }
                }
                
                // Upload file
                if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $settings[$key] = $fileName;
                    
                    // Log successful upload
                    // File upload success - no logging needed
                    
                    // Set flash message for successful upload
                    $uploadType = $key === 'logo' ? 'Logo' : 'Favicon';
                    $this->session->setFlash('success', $uploadType . ' uploaded successfully!');
                    
                } else {
                    $this->session->setFlash('error', __('file_upload_failed') . ': ' . $key);
                    $this->redirect('admin/settings');
                    return;
                }
            }
        }
        
        // Handle homepage images
        if (isset($_FILES['homepage_images'])) {
            $homepageImages = $_FILES['homepage_images'];
            
            // Process each homepage image
            foreach ($homepageImages['name'] as $key => $name) {
                if ($homepageImages['error'][$key] === UPLOAD_ERR_OK) {
                    $uploadDir = BASE_PATH . '/public/img/';
                    
                    // Create directory if not exists
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    
                    $fileExtension = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                    
                    if (!in_array($fileExtension, $allowedTypes)) {
                        continue; // Skip invalid files
                    }
                    
                    // Validate file size (max 10MB)
                    if ($homepageImages['size'][$key] > 10 * 1024 * 1024) {
                        continue; // Skip large files
                    }
                    
                    $fileName = $key . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $fileName;
                    
                    // Delete old file if exists (different extension)
                    $existingFiles = glob($uploadDir . $key . '.*');
                    foreach ($existingFiles as $existingFile) {
                        if (file_exists($existingFile)) {
                            unlink($existingFile);
                        }
                    }
                    
                    // Upload file
                    if (move_uploaded_file($homepageImages['tmp_name'][$key], $uploadPath)) {
                        $settings[$key] = $fileName;
                        // Image upload success - no logging needed
                    }
                }
            }
        }
        
        // Validate required email settings if SMTP is enabled
        if (!empty($settings['smtp_enabled']) && $settings['smtp_enabled'] == '1') {
            $requiredSMTPFields = ['smtp_host', 'smtp_port', 'email_from_address', 'email_from_name'];
            foreach ($requiredSMTPFields as $field) {
                if (empty($settings[$field])) {
                    $this->session->setFlash('error', __('smtp_required_fields_missing'));
                    $this->redirect('admin/settings');
                    return;
                }
            }
            
            // Validate email format
            if (!filter_var($settings['email_from_address'], FILTER_VALIDATE_EMAIL)) {
                $this->session->setFlash('error', __('invalid_email_format'));
                $this->redirect('admin/settings');
                return;
            }
        }
        
        // Save settings
        try {
            $result = $settingsModel->saveMultipleSettings($settings);
            
            if ($result) {
                $this->session->setFlash('success', __('settings_updated'));
                // Settings save success - no logging needed
            } else {
                $this->session->setFlash('error', __('settings_update_failed'));
                writeLog("Failed to save settings", 'admin-settings');
            }
        } catch (Exception $e) {
            $this->session->setFlash('error', __('settings_update_failed') . ': ' . $e->getMessage());
            writeLog("Exception saving settings: " . $e->getMessage(), 'admin-settings');
        }
        
        // Redirect back to settings page
        $this->redirect('admin/settings');
    }
    
    /**
     * Test email action - test SMTP configuration
     */
    public function testEmail()
    {
        // Check if request is POST and Ajax
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAjax()) {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        
        // Get JSON data
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->json(['success' => false, 'message' => 'No data received'], 400);
        }
        
        // Validate required fields
        if (empty($input['test_email'])) {
            $this->json(['success' => false, 'message' => 'Test email address is required'], 400);
        }
        
        if (!filter_var($input['test_email'], FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Invalid email address'], 400);
        }
        
        // Load Email class
        require_once BASE_PATH . '/core/Email.php';
        $email = new Email();
        
        // Enable debug mode for testing
        $email->setDebug(true);
        
        // Configure SMTP if enabled
        if (!empty($input['smtp_enabled']) && $input['smtp_enabled'] == '1') {
            if (empty($input['smtp_host']) || empty($input['smtp_port'])) {
                $this->json(['success' => false, 'message' => 'SMTP host and port are required'], 400);
            }
            
            $smtpConfig = [
                'host' => trim($input['smtp_host']),
                'port' => intval($input['smtp_port']),
                'security' => $input['smtp_security'] ?? 'tls',
                'auth' => !empty($input['smtp_auth']) && $input['smtp_auth'] == '1',
                'username' => trim($input['smtp_username'] ?? ''),
                'password' => $input['smtp_password'] ?? '',
                'timeout' => intval($input['smtp_timeout'] ?? 30)
            ];
            
            // Validate SMTP auth fields if auth is enabled
            if ($smtpConfig['auth'] && (empty($smtpConfig['username']) || empty($smtpConfig['password']))) {
                $this->json(['success' => false, 'message' => 'SMTP username and password are required when authentication is enabled'], 400);
            }
            
            $email->configureSMTP($smtpConfig);
        }
        
        // Set sender
        $fromEmail = trim($input['email_from_address'] ?? '');
        $fromName = trim($input['email_from_name'] ?? '');
        
        if (empty($fromEmail)) {
            $this->json(['success' => false, 'message' => 'Sender email address is required'], 400);
        }
        
        if (!filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => 'Invalid sender email address'], 400);
        }
        
        $email->setFrom($fromEmail, $fromName);
        
        // Set reply-to if provided
        if (!empty($input['email_reply_to'])) {
            $email->setReplyTo(trim($input['email_reply_to']));
        }
        
        // Send test email
        try {
            $result = $email->sendTestEmail($input['test_email']);
            
            if ($result) {
                $this->json([
                    'success' => true, 
                    'message' => 'Test email sent successfully! Please check your inbox (and spam folder).'
                ]);
            } else {
                $error = $email->getError();
                $this->json([
                    'success' => false, 
                    'message' => $error ?: 'Failed to send test email. Please check your SMTP settings.'
                ]);
            }
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Test SMTP connection action
     */
    public function testConnection()
    {
        // Check if request is POST and Ajax
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAjax()) {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        
        // Get JSON data
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            $this->json(['success' => false, 'message' => 'No data received'], 400);
        }
        
        // Check if SMTP is enabled
        if (empty($input['smtp_enabled']) || $input['smtp_enabled'] != '1') {
            $this->json(['success' => false, 'message' => 'SMTP is not enabled'], 400);
        }
        
        // Validate required fields
        if (empty($input['smtp_host']) || empty($input['smtp_port'])) {
            $this->json(['success' => false, 'message' => 'SMTP host and port are required'], 400);
        }
        
        // Load Email class
        require_once BASE_PATH . '/core/Email.php';
        $email = new Email();
        
        // Configure SMTP
        $smtpConfig = [
            'host' => trim($input['smtp_host']),
            'port' => intval($input['smtp_port']),
            'security' => $input['smtp_security'] ?? 'tls',
            'timeout' => intval($input['smtp_timeout'] ?? 30)
        ];
        
        $email->configureSMTP($smtpConfig);
        
        // Test connection
        try {
            $result = $email->testSMTPConnection();
            $this->json($result);
        } catch (Exception $e) {
            $this->json([
                'success' => false, 
                'message' => 'Connection test failed: ' . $e->getMessage(),
                'details' => []
            ]);
        }
    }
    
    /**
     * Generate default files if they don't exist
     */
    public function generateDefaults()
    {
        $imgDir = BASE_PATH . '/public/img/';
        
        // Create directory if not exists
        if (!is_dir($imgDir)) {
            mkdir($imgDir, 0755, true);
        }
        
        // Generate default favicon if not exists
        $faviconPath = $imgDir . 'favicon.ico';
        if (!file_exists($faviconPath)) {
            // Create a simple 16x16 favicon
            $this->generateDefaultFavicon($faviconPath);
        }
        
        // You can also generate a default logo here if needed
        
        $this->session->setFlash('success', 'Default files generated successfully');
        $this->redirect('admin/settings');
    }
    
    /**
     * Generate a simple default favicon
     */
    private function generateDefaultFavicon($path)
    {
        // Create a 16x16 image
        $image = imagecreate(16, 16);
        
        // Allocate colors
        $bg = imagecolorallocate($image, 255, 107, 53); // Primary color
        $fg = imagecolorallocate($image, 255, 255, 255); // White
        
        // Fill background
        imagefill($image, 0, 0, $bg);
        
        // Add a simple "T" for travel
        imagestring($image, 2, 4, 2, 'T', $fg);
        
        // Output as ICO format (simplified - you might want to use a proper ICO library)
        // For now, save as PNG and rename
        $tempPath = $path . '.png';
        imagepng($image, $tempPath);
        rename($tempPath, $path);
        
        imagedestroy($image);
    }
}