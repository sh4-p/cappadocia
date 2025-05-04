
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
     * Run the application
     */
    public function run()
    {
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
                if ($url[0] !== 'admin') {
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