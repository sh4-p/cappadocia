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
            'email_notification_contact'
        ];
        
        foreach ($checkboxSettings as $key) {
            if (!isset($settings[$key])) {
                $settings[$key] = '0';
            }
        }
        
        // Handle regular file uploads
        $uploadedFiles = [
            'logo' => $this->file('logo'),
            'favicon' => $this->file('favicon')
        ];
        
        // Process file uploads
        foreach ($uploadedFiles as $key => $file) {
            if ($file && $file['error'] === UPLOAD_ERR_OK) {
                $uploadDir = BASE_PATH . '/public/uploads/';
                $fileName = $key . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
                
                // Upload file
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
                    $settings[$key] = $fileName;
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
                    $fileExtension = pathinfo($name, PATHINFO_EXTENSION);
                    $fileName = $key . '.' . $fileExtension;
                    
                    // Upload file
                    if (move_uploaded_file($homepageImages['tmp_name'][$key], $uploadDir . $fileName)) {
                        $settings[$key] = $fileName;
                    }
                }
            }
        }
        
        
        // Save settings
        $result = $settingsModel->saveMultipleSettings($settings);
        
        if ($result) {
            $this->session->setFlash('success', __('settings_updated'));
        } else {
            $this->session->setFlash('error', __('settings_update_failed'));
        }
        
        // Redirect back to settings page
        $this->redirect('admin/settings');
    }
}