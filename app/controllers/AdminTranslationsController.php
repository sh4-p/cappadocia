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
        
        // Get all languages
        $languages = $languageModel->getActiveLanguages();
        
        // Get all translation keys with values for all languages
        $translations = $translationModel->getAllTranslations();
        
        // Render view
        $this->render('admin/translations/index', [
            'pageTitle' => __('translations'),
            'languages' => $languages,
            'translations' => $translations
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
            
            // Redirect to translations list
            $this->redirect('admin/translations');
        }
        
        // Get translations for this language
        $translations = $translationModel->getByLanguage($language['id']);
        
        // Render view
        $this->render('admin/translations/edit', [
            'pageTitle' => sprintf(__('edit_translations_for'), $language['name']),
            'language' => $language,
            'translations' => $translations
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