<?php
/**
 * Admin Pages Controller
 * 
 * Handles pages management in admin panel
 */
class AdminPagesController extends Controller
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
        return 'AdminPages';
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
     * Index action - list all pages
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load page model
        $pageModel = $this->loadModel('Page');
        
        // Get all pages
        $pages = $pageModel->getAllWithDetails($langCode);
        
        // Render view
        $this->render('admin/pages/index', [
            'pageTitle' => __('pages'),
            'pages' => $pages
        ], 'admin');
    }
    
    /**
     * Create action - create a new page
     */
    public function create()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load page model
        $pageModel = $this->loadModel('Page');
        
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get all languages
        $languages = $languageModel->getActiveLanguages();
        
        // Get templates
        $templates = $pageModel->getTemplates();
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $template = $this->post('template');
            $orderNumber = $this->post('order_number', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Validate inputs
            $errors = [];
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['title'])) {
                    $errors[] = sprintf(__('title_required_for_lang'), $lang['name']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Return to create page
                $this->render('admin/pages/create', [
                    'pageTitle' => __('add_page'),
                    'languages' => $languages,
                    'templates' => $templates,
                    'details' => $details
                ], 'admin');
                
                return;
            }
            
            // Generate slugs if not provided
            foreach ($details as $langId => &$langDetails) {
                if (empty($langDetails['slug'])) {
                    $langDetails['slug'] = $pageModel->generateSlug($langDetails['title'], $langId);
                }
            }
            
            // Prepare page data
            $pageData = [
                'template' => $template,
                'order_number' => $orderNumber,
                'is_active' => $isActive
            ];
            
            // Create page
            $pageId = $pageModel->addWithDetails($pageData, $details);
            
            if ($pageId) {
                $this->session->setFlash('success', __('page_added'));
                
                // Redirect to pages list
                $this->redirect('admin/pages');
            } else {
                $this->session->setFlash('error', __('page_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/pages/create', [
            'pageTitle' => __('add_page'),
            'languages' => $languages,
            'templates' => $templates
        ], 'admin');
    }
    
    /**
     * Edit action - edit a page
     * 
     * @param int $id Page ID
     */
    public function edit($id)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load page model
        $pageModel = $this->loadModel('Page');
        
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get page
        $page = $pageModel->getWithDetails($id, $langCode);
        
        // Check if page exists
        if (!$page) {
            $this->session->setFlash('error', __('page_not_found'));
            $this->redirect('admin/pages');
        }
        
        // Get all languages
        $languages = $languageModel->getActiveLanguages();
        
        // Get templates
        $templates = $pageModel->getTemplates();
        
        // Get page details for all languages
        $pageDetails = [];
        
        foreach ($languages as $lang) {
            $langPage = $pageModel->getWithDetails($id, $lang['code']);
            if ($langPage) {
                $pageDetails[$lang['id']] = [
                    'title' => $langPage['title'],
                    'slug' => $langPage['slug'],
                    'content' => $langPage['content'],
                    'meta_title' => $langPage['meta_title'],
                    'meta_description' => $langPage['meta_description']
                ];
            }
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $template = $this->post('template');
            $orderNumber = $this->post('order_number', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Validate inputs
            $errors = [];
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['title'])) {
                    $errors[] = sprintf(__('title_required_for_lang'), $lang['name']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Update page details with POST data
                $pageDetails = $details;
                
                // Return to edit page
                $this->render('admin/pages/edit', [
                    'pageTitle' => __('edit_page'),
                    'page' => $page,
                    'pageDetails' => $pageDetails,
                    'languages' => $languages,
                    'templates' => $templates
                ], 'admin');
                
                return;
            }
            
            // Generate slugs if not provided
            foreach ($details as $langId => &$langDetails) {
                if (empty($langDetails['slug'])) {
                    $langDetails['slug'] = $pageModel->generateSlug($langDetails['title'], $langId, $id);
                }
            }
            
            // Prepare page data
            $pageData = [
                'template' => $template,
                'order_number' => $orderNumber,
                'is_active' => $isActive
            ];
            
            // Update page
            $result = $pageModel->updateWithDetails($id, $pageData, $details);
            
            if ($result) {
                $this->session->setFlash('success', __('page_updated'));
                
                // Redirect to pages list
                $this->redirect('admin/pages');
            } else {
                $this->session->setFlash('error', __('page_update_failed'));
            }
        }
        
        // Render view
        $this->render('admin/pages/edit', [
            'pageTitle' => __('edit_page'),
            'page' => $page,
            'pageDetails' => $pageDetails,
            'languages' => $languages,
            'templates' => $templates
        ], 'admin');
    }
    
    /**
     * Delete action - delete a page
     * 
     * @param int $id Page ID
     */
    public function delete($id)
    {
        // Load page model
        $pageModel = $this->loadModel('Page');
        
        // Delete page
        $result = $pageModel->deleteWithDetails($id);
        
        if ($result) {
            $this->session->setFlash('success', __('page_deleted'));
        } else {
            $this->session->setFlash('error', __('page_delete_failed'));
        }
        
        // Redirect to pages list
        $this->redirect('admin/pages');
    }
    
    /**
     * Toggle status action
     * 
     * @param int $id Page ID
     */
    public function toggleStatus($id)
    {
        // Load page model
        $pageModel = $this->loadModel('Page');
        
        // Get page
        $page = $pageModel->getById($id);
        
        // Check if page exists
        if (!$page) {
            $this->session->setFlash('error', __('page_not_found'));
            $this->redirect('admin/pages');
        }
        
        // Toggle status
        $result = $pageModel->updateStatus($id, !$page['is_active']);
        
        if ($result) {
            $this->session->setFlash('success', __('page_status_updated'));
        } else {
            $this->session->setFlash('error', __('page_status_update_failed'));
        }
        
        // Redirect to pages list
        $this->redirect('admin/pages');
    }
    
    /**
     * Update order action
     */
    public function updateOrder()
    {
        // Check if request is POST and is AJAX
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->isAjax()) {
            $this->redirect('admin/pages');
        }
        
        // Load page model
        $pageModel = $this->loadModel('Page');
        
        // Get form data
        $order = $this->post('order', []);
        
        // Update order
        $success = true;
        
        foreach ($order as $id => $orderNumber) {
            $result = $pageModel->updateOrder($id, $orderNumber);
            
            if (!$result) {
                $success = false;
            }
        }
        
        // Return JSON response
        $this->json([
            'success' => $success,
            'message' => $success ? __('order_updated') : __('order_update_failed')
        ]);
    }
}