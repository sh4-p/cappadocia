<?php
/**
 * Admin Languages Controller
 * 
 * Handles languages management in admin panel
 */
class AdminLanguagesController extends Controller
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
        return 'AdminLanguages';
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
     * Index action - list all languages
     */
    public function index()
    {
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get all languages
        $languages = $languageModel->getAllLanguages();
        
        // Render view
        $this->render('admin/languages/index', [
            'pageTitle' => __('languages'),
            'languages' => $languages
        ], 'admin');
    }
    
    /**
     * Create action - create a new language
     */
    public function create()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Load language model
            $languageModel = $this->loadModel('LanguageModel');
            
            // Get form data
            $code = $this->post('code');
            $name = $this->post('name');
            $isDefault = $this->post('is_default', 0);
            $isActive = $this->post('is_active', 1);
            
            // Handle flag upload
            $flag = $this->file('flag');
            $flagName = $code . '.png';
            
            // Validate inputs
            $errors = [];
            
            if (empty($code)) {
                $errors[] = __('code_required');
            } elseif (strlen($code) !== 2) {
                $errors[] = __('code_must_be_2_chars');
            } elseif ($languageModel->getByCode($code)) {
                $errors[] = __('code_already_exists');
            }
            
            if (empty($name)) {
                $errors[] = __('name_required');
            }
            
            if (empty($flag) || $flag['error'] !== UPLOAD_ERR_OK) {
                $errors[] = __('flag_required');
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again
                $this->render('admin/languages/create', [
                    'pageTitle' => __('add_language')
                ], 'admin');
                
                return;
            }
            
            // Upload flag
            $uploadDir = BASE_PATH . '/public/uploads/flags/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            move_uploaded_file($flag['tmp_name'], $uploadDir . $flagName);
            
            // Prepare language data
            $languageData = [
                'code' => $code,
                'name' => $name,
                'flag' => $flagName,
                'is_default' => $isDefault,
                'is_active' => $isActive
            ];
            
            // Add language
            $result = $languageModel->addLanguage($languageData);
            
            if ($result) {
                $this->session->setFlash('success', __('language_added'));
                
                // If new language is set as default, copy translations from current default
                if ($isDefault) {
                    $translationModel = $this->loadModel('Translation');
                    $defaultLanguage = $languageModel->getDefaultLanguage();
                    
                    if ($defaultLanguage && $defaultLanguage['id'] != $result) {
                        $translationModel->copyTranslations($defaultLanguage['id'], $result);
                    }
                }
                
                // Redirect to languages list
                $this->redirect('admin/languages');
            } else {
                $this->session->setFlash('error', __('language_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/languages/create', [
            'pageTitle' => __('add_language')
        ], 'admin');
    }
    
    /**
     * Edit action - edit a language
     * 
     * @param int $id Language ID
     */
    public function edit($id)
    {
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get language
        $language = $languageModel->getById($id);
        
        // Check if language exists
        if (!$language) {
            $this->session->setFlash('error', __('language_not_found'));
            $this->redirect('admin/languages');
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $name = $this->post('name');
            $isDefault = $this->post('is_default', 0);
            $isActive = $this->post('is_active', 1);
            
            // Handle flag upload
            $flag = $this->file('flag');
            $flagName = $language['flag'];
            
            // Validate inputs
            $errors = [];
            
            if (empty($name)) {
                $errors[] = __('name_required');
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Return to edit page
                $this->render('admin/languages/edit', [
                    'pageTitle' => __('edit_language'),
                    'language' => $language
                ], 'admin');
                
                return;
            }
            
            // Upload new flag if provided
            if ($flag && $flag['error'] === UPLOAD_ERR_OK) {
                $uploadDir = BASE_PATH . '/public/uploads/flags/';
                move_uploaded_file($flag['tmp_name'], $uploadDir . $flagName);
            }
            
            // Prepare language data
            $languageData = [
                'name' => $name,
                'is_default' => $isDefault,
                'is_active' => $isActive
            ];
            
            // Update language
            $result = $languageModel->updateLanguage($id, $languageData);
            
            if ($result) {
                $this->session->setFlash('success', __('language_updated'));
                
                // Redirect to languages list
                $this->redirect('admin/languages');
            } else {
                $this->session->setFlash('error', __('language_update_failed'));
            }
        }
        
        // Render view
        $this->render('admin/languages/edit', [
            'pageTitle' => __('edit_language'),
            'language' => $language
        ], 'admin');
    }
    
    /**
     * Delete action - delete a language
     * 
     * @param int $id Language ID
     */
    public function delete($id)
    {
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get language
        $language = $languageModel->getById($id);
        
        // Check if language exists
        if (!$language) {
            $this->session->setFlash('error', __('language_not_found'));
            $this->redirect('admin/languages');
        }
        
        // Check if language is default
        if ($language['is_default']) {
            $this->session->setFlash('error', __('cannot_delete_default_language'));
            $this->redirect('admin/languages');
        }
        
        // Delete language
        $result = $languageModel->deleteLanguage($id);
        
        if ($result) {
            $this->session->setFlash('success', __('language_deleted'));
        } else {
            $this->session->setFlash('error', __('language_delete_failed'));
        }
        
        // Redirect to languages list
        $this->redirect('admin/languages');
    }
    
    /**
     * Set as default action
     * 
     * @param int $id Language ID
     */
    public function setDefault($id)
    {
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get language
        $language = $languageModel->getById($id);
        
        // Check if language exists
        if (!$language) {
            $this->session->setFlash('error', __('language_not_found'));
            $this->redirect('admin/languages');
        }
        
        // Set as default
        $result = $languageModel->setAsDefault($id);
        
        if ($result) {
            $this->session->setFlash('success', __('language_set_as_default'));
        } else {
            $this->session->setFlash('error', __('language_set_as_default_failed'));
        }
        
        // Redirect to languages list
        $this->redirect('admin/languages');
    }
    
    /**
     * Toggle status action
     * 
     * @param int $id Language ID
     */
    public function toggleStatus($id)
    {
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get language
        $language = $languageModel->getById($id);
        
        // Check if language exists
        if (!$language) {
            $this->session->setFlash('error', __('language_not_found'));
            $this->redirect('admin/languages');
        }
        
        // Check if language is default
        if ($language['is_default'] && $language['is_active']) {
            $this->session->setFlash('error', __('cannot_deactivate_default_language'));
            $this->redirect('admin/languages');
        }
        
        // Toggle status
        $result = $languageModel->updateStatus($id, !$language['is_active']);
        
        if ($result) {
            $this->session->setFlash('success', __('language_status_updated'));
        } else {
            $this->session->setFlash('error', __('language_status_update_failed'));
        }
        
        // Redirect to languages list
        $this->redirect('admin/languages');
    }
}