<?php
/**
 * Admin Gallery Controller
 * 
 * Handles gallery management in admin panel
 */
class AdminGalleryController extends Controller
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
        return 'AdminGallery';
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
     * Index action - list all gallery items
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $galleryModel = $this->loadModel('Gallery');
        $categoryModel = $this->loadModel('Category');
        $tourModel = $this->loadModel('Tour');
        
        // Get page from query string
        $page = (int) ($this->get('page', 1));
        if ($page < 1) $page = 1;
        
        // Set limit and offset
        $limit = 16;
        $offset = ($page - 1) * $limit;
        
        // Get filters
        $tourId = $this->get('tour_id');
        $categoryId = $this->get('category');
        $search = $this->get('search');
        
        // Prepare conditions
        $conditions = [];
        
        if ($tourId) {
            $conditions['g.tour_id'] = $tourId;
        }
        
        // Gallery items
        $galleryItems = [];
        $totalItems = 0;
        
        // Get gallery items based on filters
        if ($search) {
            // Search in gallery details (title/description)
            $galleryItems = $galleryModel->searchGallery($search, $langCode, $limit, $offset);
            $totalItems = $galleryModel->countSearchGallery($search, $langCode);
        } elseif ($categoryId) {
            // Get by category
            $galleryItems = $galleryModel->getByCategory($categoryId, $langCode, $limit, $offset);
            $totalItems = $galleryModel->countByCategory($categoryId, $langCode);
        } else {
            // Get all gallery items
            $galleryItems = $galleryModel->getAllWithDetails($langCode, $conditions, 'g.id DESC', $limit, $offset);
            $totalItems = $galleryModel->count($conditions);
        }
        
        // Get all categories for filter
        $categories = $categoryModel->getAllWithDetails($langCode, ['c.is_active' => 1], 'cd.name ASC');
        
        // Get tours for the create form
        $tours = $tourModel->getAllWithDetails($langCode, ['t.is_active' => 1], 'td.name ASC');
        
        // Calculate pagination
        $totalPages = ceil($totalItems / $limit);
        
        // Render view
        $this->render('admin/gallery/index', [
            'pageTitle' => __('gallery'),
            'galleryItems' => $galleryItems,
            'categories' => $categories,
            'tours' => $tours,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'tourId' => $tourId,
            'categoryId' => $categoryId,
            'search' => $search,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Create action - create a new gallery item
     */
    public function create()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $galleryModel = $this->loadModel('Gallery');
        $tourModel = $this->loadModel('Tour');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get tours
        $tours = $tourModel->getAllWithDetails($langCode, ['t.is_active' => 1], 'td.name ASC');
        
        // Get languages
        $languages = $languageModel->getActiveLanguages();
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $tourId = $this->post('tour_id');
            $orderNumber = $this->post('order_number', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Handle image upload
            $image = $this->file('image');
            $imageName = null;
            
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $imageName = $this->uploadImage($image, 'gallery');
                
                if (!$imageName) {
                    $this->session->setFlash('error', __('image_upload_failed'));
                    $this->redirect('admin/gallery/create');
                    return;
                }
            }
            
            // Validate inputs
            $errors = [];
            
            if (!$imageName) {
                $errors[] = __('image_required');
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again
                $this->render('admin/gallery/create', [
                    'pageTitle' => __('add_image'),
                    'languages' => $languages,
                    'tours' => $tours,
                    'details' => $details,
                    'tourId' => $tourId,
                    'orderNumber' => $orderNumber,
                    'isActive' => $isActive,
                    'currentLang' => $langCode
                ], 'admin');
                
                return;
            }
            
            // Prepare gallery data
            $galleryData = [
                'tour_id' => $tourId ?: null,
                'image' => $imageName,
                'order_number' => $orderNumber,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Create gallery item with details for all languages
            $galleryId = $galleryModel->addWithDetails($galleryData, $details);
            
            if ($galleryId) {
                $this->session->setFlash('success', __('gallery_item_added'));
                $this->redirect('admin/gallery');
            } else {
                $this->session->setFlash('error', __('gallery_item_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/gallery/create', [
            'pageTitle' => __('add_image'),
            'languages' => $languages,
            'tours' => $tours,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Edit action - edit a gallery item
     * 
     * @param int $id Gallery item ID
     */
    public function edit($id)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $galleryModel = $this->loadModel('Gallery');
        $tourModel = $this->loadModel('Tour');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get gallery item
        $gallery = $galleryModel->getWithDetails($id, $langCode);
        
        // Check if gallery item exists
        if (!$gallery) {
            $this->session->setFlash('error', __('gallery_item_not_found'));
            $this->redirect('admin/gallery');
        }
        
        // Get tours
        $tours = $tourModel->getAllWithDetails($langCode, ['t.is_active' => 1], 'td.name ASC');
        
        // Get languages
        $languages = $languageModel->getActiveLanguages();
        
        // Get gallery details for all languages
        $galleryDetails = [];
        
        foreach ($languages as $lang) {
            $langGallery = $galleryModel->getWithDetails($id, $lang['code']);
            if ($langGallery) {
                $galleryDetails[$lang['id']] = [
                    'title' => $langGallery['title'],
                    'description' => $langGallery['description']
                ];
            }
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $tourId = $this->post('tour_id');
            $orderNumber = $this->post('order_number', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Handle image upload
            $image = $this->file('image');
            $imageName = $gallery['image'];
            
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $imageName = $this->uploadImage($image, 'gallery');
                
                if (!$imageName) {
                    $this->session->setFlash('error', __('image_upload_failed'));
                    $this->redirect('admin/gallery/edit/' . $id);
                    return;
                }
                
                // Delete old image if exists
                if ($gallery['image']) {
                    $oldImagePath = BASE_PATH . '/public/uploads/gallery/' . $gallery['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
            
            // Prepare gallery data
            $galleryData = [
                'tour_id' => $tourId ?: null,
                'image' => $imageName,
                'order_number' => $orderNumber,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Update gallery item
            $result = $galleryModel->updateWithDetails($id, $galleryData, $details);
            
            if ($result) {
                $this->session->setFlash('success', __('gallery_item_updated'));
                $this->redirect('admin/gallery');
            } else {
                $this->session->setFlash('error', __('gallery_item_update_failed'));
            }
        }
        
        // Render view
        $this->render('admin/gallery/edit', [
            'pageTitle' => __('edit_image'),
            'gallery' => $gallery,
            'galleryDetails' => $galleryDetails,
            'languages' => $languages,
            'tours' => $tours,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Delete action - delete a gallery item
     * 
     * @param int $id Gallery item ID
     */
    public function delete($id)
    {
        // Load gallery model
        $galleryModel = $this->loadModel('Gallery');
        
        // Get gallery item
        $gallery = $galleryModel->getById($id);
        
        // Check if gallery item exists
        if (!$gallery) {
            $this->session->setFlash('error', __('gallery_item_not_found'));
            $this->redirect('admin/gallery');
        }
        
        // Delete gallery item
        $result = $galleryModel->deleteWithDetails($id);
        
        if ($result) {
            // Delete gallery image
            if ($gallery['image']) {
                $imagePath = BASE_PATH . '/public/uploads/gallery/' . $gallery['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $this->session->setFlash('success', __('gallery_item_deleted'));
        } else {
            $this->session->setFlash('error', __('gallery_item_delete_failed'));
        }
        
        // Redirect to gallery list
        $this->redirect('admin/gallery');
    }
    
    /**
     * Toggle status action
     * 
     * @param int $id Gallery item ID
     */
    public function toggleStatus($id)
    {
        // Load gallery model
        $galleryModel = $this->loadModel('Gallery');
        
        // Get gallery item
        $gallery = $galleryModel->getById($id);
        
        // Check if gallery item exists
        if (!$gallery) {
            $this->session->setFlash('error', __('gallery_item_not_found'));
            $this->redirect('admin/gallery');
        }
        
        // Toggle status
        $newStatus = $gallery['is_active'] ? 0 : 1;
        $result = $galleryModel->update(['is_active' => $newStatus], ['id' => $id]);
        
        if ($result) {
            $statusText = $newStatus ? __('activated') : __('deactivated');
            $this->session->setFlash('success', __('gallery_item') . ' ' . $statusText);
        } else {
            $this->session->setFlash('error', __('status_update_failed'));
        }
        
        // Redirect to gallery list
        $this->redirect('admin/gallery');
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