<?php
/**
 * Admin Categories Controller
 * 
 * Handles categories management in admin panel
 */
class AdminCategoriesController extends Controller
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
        return 'AdminCategories';
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
     * Index action - list all categories
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load category model
        $categoryModel = $this->loadModel('Category');
        
        // Get all categories
        $categories = $categoryModel->getAllWithDetails($langCode);
        
        // Render view
        $this->render('admin/categories/index', [
            'pageTitle' => __('categories'),
            'categories' => $categories
        ], 'admin');
    }
    
    /**
     * Create action - create a new category
     */
    public function create()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $categoryModel = $this->loadModel('Category');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get all languages
        $languages = $languageModel->getActiveLanguages();
        
        // Get parent categories
        $parentCategories = $categoryModel->getAllWithDetails($langCode, ['c.parent_id IS NULL' => null]);
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $parentId = $this->post('parent_id');
            $orderNumber = $this->post('order_number', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Handle image upload
            $image = $this->file('image');
            $imageName = null;
            
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $imageName = $this->uploadImage($image, 'categories');
                
                if (!$imageName) {
                    $this->session->setFlash('error', __('image_upload_failed'));
                    $this->redirect('admin/categories/create');
                    return;
                }
            }
            
            // Validate inputs
            $errors = [];
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['name'])) {
                    $errors[] = sprintf(__('name_required_for_lang'), $lang['name']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again
                $this->render('admin/categories/create', [
                    'pageTitle' => __('add_category'),
                    'languages' => $languages,
                    'categories' => $parentCategories,
                    'details' => $details,
                    'currentLang' => $langCode
                ], 'admin');
                
                return;
            }
            
            // Prepare category data
            $categoryData = [
                'parent_id' => $parentId ? $parentId : null,
                'image' => $imageName,
                'order_number' => $orderNumber,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Generate slugs if not provided
            foreach ($details as $langId => &$langDetails) {
                if (empty($langDetails['slug'])) {
                    $langDetails['slug'] = $categoryModel->generateSlug($langDetails['name'], $langId);
                }
            }
            
            // Create category
            $categoryId = $categoryModel->addWithDetails($categoryData, $details);
            
            if ($categoryId) {
                $this->session->setFlash('success', __('category_added'));
                $this->redirect('admin/categories');
            } else {
                $this->session->setFlash('error', __('category_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/categories/create', [
            'pageTitle' => __('add_category'),
            'languages' => $languages,
            'categories' => $parentCategories,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Edit action - edit a category
     * 
     * @param int $id Category ID
     */
    public function edit($id)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $categoryModel = $this->loadModel('Category');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get category
        $category = $categoryModel->getWithDetails($id, $langCode);
        
        // Check if category exists
        if (!$category) {
            $this->session->setFlash('error', __('category_not_found'));
            $this->redirect('admin/categories');
        }
        
        // Get all languages
        $languages = $languageModel->getActiveLanguages();
        
        // Get parent categories (excluding this category and its children)
        $parentCategories = $categoryModel->getAllWithDetails($langCode, ['c.id !=' => $id]);
        
        // Get category details for all languages
        $categoryDetails = [];
        
        foreach ($languages as $lang) {
            $langCategory = $categoryModel->getWithDetails($id, $lang['code']);
            if ($langCategory) {
                $categoryDetails[$lang['id']] = [
                    'name' => $langCategory['name'],
                    'slug' => $langCategory['slug'],
                    'description' => $langCategory['description'],
                    'meta_title' => $langCategory['meta_title'],
                    'meta_description' => $langCategory['meta_description']
                ];
            }
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $parentId = $this->post('parent_id');
            $orderNumber = $this->post('order_number', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Handle image upload
            $image = $this->file('image');
            $imageName = $category['image'];
            
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $imageName = $this->uploadImage($image, 'categories');
                
                if (!$imageName) {
                    $this->session->setFlash('error', __('image_upload_failed'));
                    $this->redirect('admin/categories/edit/' . $id);
                    return;
                }
                
                // Delete old image if exists
                if ($category['image']) {
                    $oldImagePath = BASE_PATH . '/public/uploads/categories/' . $category['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
            
            // Validate inputs
            $errors = [];
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['name'])) {
                    $errors[] = sprintf(__('name_required_for_lang'), $lang['name']);
                }
            }
            
            // Check for circular reference
            if ($parentId && $this->isChildCategory($id, $parentId)) {
                $errors[] = __('circular_reference');
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Update category details with POST data
                $categoryDetails = $details;
                
                // Render view again
                $this->render('admin/categories/edit', [
                    'pageTitle' => __('edit_category'),
                    'category' => $category,
                    'categoryDetails' => $categoryDetails,
                    'languages' => $languages,
                    'parentCategories' => $parentCategories,
                    'currentLang' => $langCode
                ], 'admin');
                
                return;
            }
            
            // Prepare category data
            $categoryData = [
                'parent_id' => $parentId ? $parentId : null,
                'image' => $imageName,
                'order_number' => $orderNumber,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Generate slugs if not provided
            foreach ($details as $langId => &$langDetails) {
                if (empty($langDetails['slug'])) {
                    $langDetails['slug'] = $categoryModel->generateSlug($langDetails['name'], $langId, $id);
                }
            }
            
            // Update category
            $result = $categoryModel->updateWithDetails($id, $categoryData, $details);
            
            if ($result) {
                $this->session->setFlash('success', __('category_updated'));
                $this->redirect('admin/categories');
            } else {
                $this->session->setFlash('error', __('category_update_failed'));
            }
        }
        
        // Render view
        $this->render('admin/categories/edit', [
            'pageTitle' => __('edit_category'),
            'category' => $category,
            'categoryDetails' => $categoryDetails,
            'languages' => $languages,
            'parentCategories' => $parentCategories,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Delete action - delete a category
     * 
     * @param int $id Category ID
     */
    public function delete($id)
    {
        // Load category model
        $categoryModel = $this->loadModel('Category');
        
        // Get category
        $category = $categoryModel->getById($id);
        
        // Check if category exists
        if (!$category) {
            $this->session->setFlash('error', __('category_not_found'));
            $this->redirect('admin/categories');
        }
        
        // Delete category
        $result = $categoryModel->deleteWithDetails($id);
        
        if ($result) {
            // Delete category image
            if ($category['image']) {
                $imagePath = BASE_PATH . '/public/uploads/categories/' . $category['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $this->session->setFlash('success', __('category_deleted'));
        } else {
            $this->session->setFlash('error', __('category_delete_failed'));
        }
        
        // Redirect to categories list
        $this->redirect('admin/categories');
    }
    
    /**
     * Toggle status action
     * 
     * @param int $id Category ID
     */
    public function toggleStatus($id)
    {
        // Load category model
        $categoryModel = $this->loadModel('Category');
        
        // Get category
        $category = $categoryModel->getById($id);
        
        // Check if category exists
        if (!$category) {
            $this->session->setFlash('error', __('category_not_found'));
            $this->redirect('admin/categories');
        }
        
        // Toggle status
        $newStatus = $category['is_active'] ? 0 : 1;
        $result = $categoryModel->update(['is_active' => $newStatus], ['id' => $id]);
        
        if ($result) {
            $statusText = $newStatus ? __('activated') : __('deactivated');
            $this->session->setFlash('success', __('category') . ' ' . $statusText);
        } else {
            $this->session->setFlash('error', __('status_update_failed'));
        }
        
        // Redirect to categories list
        $this->redirect('admin/categories');
    }
    
    /**
     * Update order action (Ajax)
     */
    public function updateOrder()
    {
        // Check if request is Ajax
        if (!$this->isAjax()) {
            $this->redirect('admin/categories');
        }
        
        // Load category model
        $categoryModel = $this->loadModel('Category');
        
        // Get order data
        $orders = $this->post('order', []);
        
        // Update order for each category
        $success = true;
        
        foreach ($orders as $id => $orderNumber) {
            $result = $categoryModel->update(['order_number' => $orderNumber], ['id' => $id]);
            
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
    
    /**
     * Check if category is a child of another category
     * 
     * @param int $categoryId Category ID
     * @param int $parentId Parent category ID
     * @return bool Is child
     */
    private function isChildCategory($categoryId, $parentId)
    {
        if ($categoryId == $parentId) {
            return true;
        }
        
        $categoryModel = $this->loadModel('Category');
        $children = $categoryModel->getAll(['parent_id' => $categoryId]);
        
        foreach ($children as $child) {
            if ($this->isChildCategory($child['id'], $parentId)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Upload image
     * 
     * @param array $file Uploaded file
     * @param string $folder Folder name
     * @return string|false File name or false
     */
    private function uploadImage($file, $folder)
    {
        // Check if file exists
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        // Get file info
        $fileInfo = pathinfo($file['name']);
        $fileName = strtolower(str_replace(' ', '-', $fileInfo['filename']));
        $fileName = preg_replace('/[^a-z0-9\-]/', '', $fileName);
        $fileName = $fileName . '-' . uniqid() . '.' . strtolower($fileInfo['extension']);
        
        // Check file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array(strtolower($fileInfo['extension']), $allowedTypes)) {
            return false;
        }
        
        // Create upload directory if not exists
        $uploadDir = BASE_PATH . '/public/uploads/' . $folder . '/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
            return $fileName;
        }
        
        return false;
    }
}