<?php
/**
 * Admin Translations Controller
 * 
 * Handles translations management in admin panel
 */
class AdminTranslationsController extends Controller
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
        return 'AdminTranslations';
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
     * Index action - list all translation keys and languages
     */
    public function index()
    {
        // Load translation model
        $translationModel = $this->loadModel('Translation');
        
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get all languages - convert to indexed array for JavaScript
        $languages = array_values($languageModel->getActiveLanguages());
        
        // Get pagination parameters
        $page = max(1, (int)($this->get('page', 1)));
        $perPage = 50; // Items per page
        $search = trim($this->get('search', ''));
        
        // Get paginated translation keys with values for all languages
        $result = $translationModel->getPaginatedTranslations($page, $perPage, $search);
        
        // Render view
        $this->render('admin/translations/index', [
            'pageTitle' => __('translations'),
            'languages' => $languages,
            'translations' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search
        ], 'admin');
    }
    
    /**
     * Edit action - edit translations for a specific language
     * 
     * @param string $lang Language code
     */
    public function edit($lang)
    {
        // Load translation model
        $translationModel = $this->loadModel('Translation');
        
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get language
        $language = $languageModel->getByCode($lang);
        
        // Check if language exists
        if (!$language) {
            $this->session->setFlash('error', __('language_not_found'));
            $this->redirect('admin/translations');
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $translations = $this->post('translations', []);
            
            // Update translations
            foreach ($translations as $keyId => $value) {
                $translationModel->updateTranslation($keyId, $language['id'], $value);
            }
            
            $this->session->setFlash('success', __('translations_updated'));
            
            // Redirect back to same page with same pagination
            $page = $this->get('page', 1);
            $search = $this->get('search', '');
            $redirectUrl = "admin/translations/edit/$lang";
            if ($page > 1 || !empty($search)) {
                $params = [];
                if ($page > 1) $params[] = "page=$page";
                if (!empty($search)) $params[] = "search=" . urlencode($search);
                $redirectUrl .= '?' . implode('&', $params);
            }
            $this->redirect($redirectUrl);
        }
        
        // Get pagination parameters
        $page = max(1, (int)($this->get('page', 1)));
        $perPage = 50; // Items per page
        $search = trim($this->get('search', ''));
        
        // Get paginated translations for this language
        $result = $translationModel->getPaginatedByLanguage($language['id'], $page, $perPage, $search);
        
        // Render view
        $this->render('admin/translations/edit', [
            'pageTitle' => sprintf(__('edit_translations_for'), $language['name']),
            'language' => $language,
            'translations' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search
        ], 'admin');
    }
    
    /**
     * Add key action - add a new translation key
     */
    public function addKey()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/translations');
        }
        
        // Load translation model
        $translationModel = $this->loadModel('Translation');
        
        // Get form data
        $keyName = $this->post('key_name');
        
        // Validate inputs
        if (empty($keyName)) {
            $this->session->setFlash('error', __('key_name_required'));
            $this->redirect('admin/translations');
        }
        
        // Add key
        $result = $translationModel->addKey($keyName);
        
        if ($result) {
            $this->session->setFlash('success', __('key_added'));
        } else {
            $this->session->setFlash('error', __('key_add_failed'));
        }
        
        // Redirect to translations list
        $this->redirect('admin/translations');
    }
    
    /**
     * Delete key action - delete a translation key
     * 
     * @param int $keyId Key ID
     */
    public function deleteKey($keyId)
    {
        // Load translation model
        $translationModel = $this->loadModel('Translation');
        
        // Delete key
        $result = $translationModel->deleteKey($keyId);
        
        if ($result) {
            $this->session->setFlash('success', __('key_deleted'));
        } else {
            $this->session->setFlash('error', __('key_delete_failed'));
        }
        
        // Redirect to translations list
        $this->redirect('admin/translations');
    }
    
    /**
     * Import action - import translations from a file
     */
    public function import()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/translations');
        }
        
        // Load translation model
        $translationModel = $this->loadModel('Translation');
        
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get form data
        $languageId = $this->post('language_id');
        $file = $this->file('import_file');
        
        // Validate inputs
        $errors = [];
        
        if (!$languageId) {
            $errors[] = __('language_required');
        }
        
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = __('file_required');
        }
        
        // If there are errors, set error message and return
        if (!empty($errors)) {
            $this->session->setFlash('error', implode('<br>', $errors));
            $this->redirect('admin/translations');
        }
        
        // Get file content
        $content = file_get_contents($file['tmp_name']);
        
        // Parse JSON
        $translations = json_decode($content, true);
        
        if (!$translations || !is_array($translations)) {
            $this->session->setFlash('error', __('invalid_file_format'));
            $this->redirect('admin/translations');
        }
        
        // Import translations
        $result = $translationModel->importTranslations($translations, $languageId);
        
        if ($result) {
            $this->session->setFlash('success', __('translations_imported'));
        } else {
            $this->session->setFlash('error', __('translations_import_failed'));
        }
        
        // Redirect to translations list
        $this->redirect('admin/translations');
    }
    
    /**
     * AJAX Load action - load translations with pagination
     */
    public function ajaxLoad()
    {
        // Set JSON response headers
        header('Content-Type: application/json');
        
        // Check if request is AJAX
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }
        
        // Load translation model
        $translationModel = $this->loadModel('Translation');
        
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get all languages - convert to indexed array for JavaScript
        $languages = array_values($languageModel->getActiveLanguages());
        
        // Get pagination parameters
        $page = max(1, (int)($this->get('page', 1)));
        $perPage = 50; // Items per page
        $search = trim($this->get('search', ''));
        
        // Get paginated translation keys with values for all languages
        $result = $translationModel->getPaginatedTranslations($page, $perPage, $search);
        
        // Return JSON response
        echo json_encode([
            'success' => true,
            'data' => $result['data'],
            'pagination' => $result['pagination'],
            'languages' => $languages
        ]);
        exit;
    }
    
    /**
     * AJAX Update action - update a single translation
     */
    public function ajaxUpdate()
    {
        // Set JSON response headers
        header('Content-Type: application/json');
        
        // Check if request is AJAX POST
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            exit;
        }
        
        // Load translation model
        $translationModel = $this->loadModel('Translation');
        
        // Get form data
        $keyId = $this->post('key_id');
        $languageId = $this->post('language_id');
        $value = $this->post('value');
        
        // Validate inputs
        if (!$keyId || !$languageId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters']);
            exit;
        }
        
        // Update translation
        $result = $translationModel->updateTranslation($keyId, $languageId, $value);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => __('translation_updated')]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update translation']);
        }
        exit;
    }

    /**
     * Export action - export translations to a file
     * 
     * @param int $languageId Language ID
     */
    public function export($languageId)
    {
        // Load translation model
        $translationModel = $this->loadModel('Translation');
        
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get language
        $language = $languageModel->getById($languageId);
        
        // Check if language exists
        if (!$language) {
            $this->session->setFlash('error', __('language_not_found'));
            $this->redirect('admin/translations');
        }
        
        // Get translations
        $translations = $translationModel->exportTranslations($languageId);
        
        // Generate JSON
        $json = json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        
        // Set headers for download
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="translations_' . $language['code'] . '.json"');
        
        // Output JSON
        echo $json;
        exit;
    }
}