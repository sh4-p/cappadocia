<?php
/**
 * Main Application Class
 * 
 * Handles the application initialization and routing
 */
class App
{
    protected $controller = DEFAULT_CONTROLLER;
    protected $action = DEFAULT_ACTION;
    protected $params = [];
    protected $router;
    protected $db;
    protected $session;
    protected $language;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Initialize components
        $this->router = new Router();
        $this->db = new Database();
        $this->session = new Session();
        $this->language = new Language();
        
        // Set up language from session or default
        $this->initializeLanguage();
    }

    /**
     * Initialize the application language
     */
    private function initializeLanguage()
    {
        // Get language from session if exists
        $langCode = $this->session->get('lang');
        
        // If no language in session, try to detect from browser
        if (!$langCode) {
            $langCode = $this->detectBrowserLanguage();
        }
        
        // Set language
        $this->language->setLanguage($langCode);
    }
    
    /**
     * Load a model
     * 
     * @param string $model Model name
     * @return object Model instance
     */
    private function loadModel($model)
    {
        // Check if model file exists
        $modelFile = BASE_PATH . '/app/models/' . $model . '.php';
        
        if (file_exists($modelFile)) {
            require_once $modelFile;
            return new $model($this->db);
        }
        
        throw new Exception("Model '$model' not found");
    }

    /**
     * Detect browser language
     * 
     * @return string Language code
     */
    private function detectBrowserLanguage()
    {
        $availableLangs = json_decode(AVAILABLE_LANGUAGES, true);
        $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2);
        
        if (isset($availableLangs[$browserLang])) {
            return $browserLang;
        }
        
        return DEFAULT_LANGUAGE;
    }

    /**
     * Check if user is admin
     * 
     * @return bool Is admin
     */
    private function isAdmin()
    {
        return (bool) $this->session->get('user_id');
    }

    /**
     * Check maintenance mode
     */
    private function checkMaintenanceMode()
    {
        // Load Settings model
        $settingsModel = $this->loadModel('Settings');
        $maintenanceMode = $settingsModel->getSetting('maintenance_mode', '0');
        
        // If maintenance mode is enabled and user is not admin
        if ($maintenanceMode === '1' && !$this->isAdmin()) {
            // Parse URL to check if we're trying to access admin
            $url = $this->parseUrl();
            
            // Allow access to admin login
            if (!empty($url) && $url[0] === 'admin') {
                return; // Allow admin access
            }
            
            // Show maintenance page for regular users
            $this->showMaintenancePage();
        }
    }

    /**
     * Set debug mode
     */
    private function setDebugMode()
    {
        // Load Settings model
        $settingsModel = $this->loadModel('Settings');
        $debugMode = $settingsModel->getSetting('debug_mode', '0');
        
        if ($debugMode === '1') {
            // Enable error reporting
            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
        } else {
            // Disable error reporting for production
            error_reporting(0);
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
        }
    }

    /**
     * Show maintenance page
     */
    private function showMaintenancePage()
    {
        // Set HTTP status code
        http_response_code(503);
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load Settings model
        $settingsModel = $this->loadModel('Settings');
        $settings = $settingsModel->getAllSettings();
        
        // Load Translation model
        $translationModel = $this->loadModel('Translation');
        $translations = $translationModel->getTranslationsForLanguage($langCode);
        
        // Make language instance available globally for LanguageHelper
        global $language;
        $language = $this->language;
        
        // Include language helper
        require_once BASE_PATH . '/app/helpers/LanguageHelper.php';
        
        // Set data for maintenance page
        $data = [
            'settings' => $settings,
            'translations' => $translations,
            'appUrl' => APP_URL,
            'cssUrl' => CSS_URL,
            'jsUrl' => JS_URL,
            'imgUrl' => IMG_URL,
            'currentLang' => $langCode,
            'pageTitle' => __('maintenance_mode_title'),
            'metaDescription' => __('maintenance_mode_description')
        ];
        
        // Extract data for view
        extract($data);
        
        // Load maintenance view
        $maintenanceFile = BASE_PATH . '/app/views/maintenance.php';
        if (file_exists($maintenanceFile)) {
            include $maintenanceFile;
        } else {
            // Fallback maintenance message
            echo '<!DOCTYPE html>
<html lang="' . $langCode . '">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . __('maintenance_mode_title') . '</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f5f5f5; }
        .maintenance-container { max-width: 600px; margin: 0 auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .maintenance-icon { font-size: 64px; color: #ff6b35; margin-bottom: 20px; }
        h1 { color: #333; margin-bottom: 20px; }
        p { color: #666; font-size: 18px; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🔧</div>
        <h1>' . __('maintenance_mode_title') . '</h1>
        <p>' . __('maintenance_mode_message') . '</p>
        <p>' . __('maintenance_mode_back_soon') . '</p>
    </div>
</body>
</html>';
        }
        exit;
    }

    /**
     * Run the application
     */
    public function run()
    {
        // Set debug mode first
        $this->setDebugMode();
        
        // Check maintenance mode
        $this->checkMaintenanceMode();
        
        // Parse the URL
        $url = $this->parseUrl();
        
        // Handle language code in URL if present
        if (!empty($url)) {
            $availableLanguages = json_decode(AVAILABLE_LANGUAGES, true);
            if (isset($url[0]) && array_key_exists($url[0], $availableLanguages)) {
                // Valid language in URL
                $this->language->setLanguage($url[0]);
                $this->session->set('lang', $url[0]);
                array_shift($url);
            } else {
                // No valid language in URL, add language and redirect
                $lang = $this->session->get('lang') ?: $this->detectBrowserLanguage();
                $this->language->setLanguage($lang);
                
                // Only redirect if not in admin area
                if (empty($url) || $url[0] !== 'admin') {
                    $requestUri = $_SERVER['REQUEST_URI'];
                    $redirectUrl = rtrim(APP_URL, '/') . '/' . $lang . $requestUri;
                    header('Location: ' . $redirectUrl);
                    exit;
                }
            }
        }
        
        // Route the request
        $route = $this->router->route($url);
        
        // Set controller, action and params
        $this->controller = $route['controller'];
        $this->action = $route['action'];
        $this->params = $route['params'];
        
        // Load and initialize controller
        $controllerFile = BASE_PATH . '/app/controllers/' . $this->controller . 'Controller.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            $controllerClass = $this->controller . 'Controller';
            $controller = new $controllerClass($this->db, $this->session, $this->language);
            
            // Check if method exists
            if (method_exists($controller, $this->action)) {
                call_user_func_array([$controller, $this->action], $this->params);
            } else {
                // Method not found, show error page
                $this->showErrorPage(404);
            }
        } else {
            // Controller not found, show error page
            $this->showErrorPage(404);
        }
    }

    /**
     * Parse URL and return parts
     * 
     * @return array URL parts
     */
    private function parseUrl()
    {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        
        return [];
    }

    /**
     * Show error page
     * 
     * @param int $code Error code
     */
    private function showErrorPage($code)
    {
        http_response_code($code);
        
        require_once BASE_PATH . '/app/controllers/ErrorController.php';
        $errorController = new ErrorController($this->db, $this->session, $this->language);
        $errorController->index($code);
        exit;
    }
}