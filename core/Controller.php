<?php
/**
 * Base Controller Class
 * 
 * All controllers will extend this class
 */
class Controller
{
    protected $db;
    protected $session;
    protected $language;
    protected $view;
    protected $data = [];

    /**
     * Constructor
     * 
     * @param Database $db Database instance
     * @param Session $session Session instance
     * @param Language $language Language instance
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        $this->db = $db ?? new Database();
        $this->session = $session ?? new Session();
        $this->language = $language ?? new Language();
        $this->view = new View();
        
        // Set default template data
        $this->setTemplateData();
    }

    /**
     * Set default template data
     */
    protected function setTemplateData()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load available languages
        $languageModel = $this->loadModel('LanguageModel');
        $languages = $languageModel->getActiveLanguages();
        
        // Load settings
        $settingsModel = $this->loadModel('Settings');
        $settings = $settingsModel->getAllSettings();
        
        // Set template data
        $this->data['currentLang'] = $langCode;
        $this->data['languages'] = $languages;
        $this->data['settings'] = $settings;
        $this->data['appUrl'] = APP_URL;
        $this->data['adminUrl'] = ADMIN_URL;
        $this->data['cssUrl'] = CSS_URL;
        $this->data['jsUrl'] = JS_URL;
        $this->data['imgUrl'] = IMG_URL;
        $this->data['uploadsUrl'] = UPLOADS_URL;
        
        // Check if user is logged in for admin area
        $this->data['isLoggedIn'] = $this->session->get('user_id') ? true : false;
        
        // Load translations for the current language
        $translationModel = $this->loadModel('Translation');
        $this->data['translations'] = $translationModel->getTranslationsForLanguage($langCode);
    }

    /**
     * Load a model
     * 
     * @param string $model Model name
     * @return object Model instance
     */
    protected function loadModel($model)
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
     * Render a view
     * 
     * @param string $view View name
     * @param array $data Data to pass to the view
     * @param string $layout Layout name
     */
    protected function render($view, $data = [], $layout = 'default')
    {
        // Include session in the controller data
        $this->data['session'] = $this->session;
        
        // Add controller and action names to the data
        $this->data['controllerName'] = $this->getControllerName();
        $this->data['actionName'] = $this->getActionName();
        
        // Merge controller data with view data
        $viewData = array_merge($this->data, $data);
        
        // Render the view
        $this->view->render($view, $viewData, $layout);
    }

    /**
     * Redirect to a URL
     * 
     * @param string $url URL to redirect to
     */
    protected function redirect($url)
    {
        header('Location: ' . APP_URL . '/' . $url);
        exit;
    }

    /**
     * Get POST data
     * 
     * @param string $key POST key
     * @param mixed $default Default value
     * @return mixed POST value
     */
    protected function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data
     * 
     * @param string $key GET key
     * @param mixed $default Default value
     * @return mixed GET value
     */
    protected function get($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        
        return $_GET[$key] ?? $default;
    }

    /**
     * Get file upload data
     * 
     * @param string $key File key
     * @return array File data
     */
    protected function file($key)
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Check if request is Ajax
     * 
     * @return bool Is Ajax request
     */
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Return JSON response
     * 
     * @param mixed $data Data to return
     * @param int $status HTTP status code
     */
    protected function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /**
     * Check if user is logged in
     * 
     * @return bool Is logged in
     */
    protected function isLoggedIn()
    {
        return (bool) $this->session->get('user_id');
    }

    /**
     * Require user to be logged in
     */
    protected function requireLogin()
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('admin/login');
        }
    }
    
    /**
     * Get controller name
     * 
     * @return string Controller name
     */
    public function getControllerName()
    {
        $class = get_class($this);
        return str_replace('Controller', '', $class);
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
}