<?php
/**
 * Admin Tours Controller
 * 
 * Handles tours management in admin panel
 */
class AdminToursController extends Controller
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
        return 'AdminTours';
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
     * Index action - list all tours
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $tourModel = $this->loadModel('Tour');
        $categoryModel = $this->loadModel('Category');
        
        // Get filter
        $categoryId = $this->get('category');
        $search = $this->get('search');
        
        // Prepare conditions
        $conditions = [];
        
        if ($categoryId) {
            $conditions['t.category_id'] = $categoryId;
        }
        
        if ($search) {
            // Search is handled differently, by tour name
            // This will be implemented directly in the searchTours method
        }
        
        // Get tours based on filter
        $tours = $search 
            ? $tourModel->searchTours($search, $langCode)
            : $tourModel->getAllWithDetails($langCode, $conditions);
        
        // Get categories for filter
        $categories = $categoryModel->getAllWithDetails($langCode, ['c.is_active' => 1], 'cd.name ASC');
        
        // Render view
        $this->render('admin/tours/index', [
            'pageTitle' => __('tours'),
            'tours' => $tours,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'search' => $search
        ], 'admin');
    }
    
    /**
     * Create action - create a new tour
     */
    public function create()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $tourModel = $this->loadModel('Tour');
        $categoryModel = $this->loadModel('Category');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get categories
        $categories = $categoryModel->getAllWithDetails($langCode, ['c.is_active' => 1], 'cd.name ASC');
        
        // Get languages
        $languages = $languageModel->getActiveLanguages();
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $categoryId = $this->post('category_id');
            $price = $this->post('price');
            $discountPrice = $this->post('discount_price');
            $duration = $this->post('duration');
            $isFeatured = $this->post('is_featured', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Handle featured image upload
            $featuredImage = $this->file('featured_image');
            $featuredImageName = null;
            
            if ($featuredImage && $featuredImage['error'] === UPLOAD_ERR_OK) {
                $featuredImageName = $this->uploadImage($featuredImage, 'tours');
                
                if (!$featuredImageName) {
                    $this->session->setFlash('error', __('image_upload_failed'));
                    $this->redirect('admin/tours/create');
                    return;
                }
            }
            
            // Validate inputs
            $errors = [];
            
            if (empty($price) || !is_numeric($price) || $price <= 0) {
                $errors[] = __('valid_price_required');
            }
            
            if (!empty($discountPrice) && (!is_numeric($discountPrice) || $discountPrice <= 0 || $discountPrice >= $price)) {
                $errors[] = __('valid_discount_price_required');
            }
            
            if (!$featuredImageName) {
                $errors[] = __('featured_image_required');
            }
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['name'])) {
                    $errors[] = sprintf(__('name_required_for_lang'), $lang['name']);
                }
                
                if (empty($details[$lang['id']]['short_description'])) {
                    $errors[] = sprintf(__('short_description_required_for_lang'), $lang['name']);
                }
                
                if (empty($details[$lang['id']]['description'])) {
                    $errors[] = sprintf(__('description_required_for_lang'), $lang['name']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again
                $this->render('admin/tours/create', [
                    'pageTitle' => __('add_tour'),
                    'languages' => $languages,
                    'categories' => $categories,
                    'details' => $details,
                    'categoryId' => $categoryId,
                    'price' => $price,
                    'discountPrice' => $discountPrice,
                    'duration' => $duration,
                    'isFeatured' => $isFeatured,
                    'isActive' => $isActive,
                    'currentLang' => $langCode
                ], 'admin');
                
                return;
            }
            
            // Prepare tour data
            $tourData = [
                'category_id' => $categoryId ?: null,
                'featured_image' => $featuredImageName,
                'price' => $price,
                'discount_price' => $discountPrice ?: null,
                'duration' => $duration,
                'is_featured' => $isFeatured ? 1 : 0,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Generate slugs if not provided
            foreach ($details as $langId => &$langDetails) {
                if (empty($langDetails['slug'])) {
                    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]/', '-', $langDetails['name'])));
                    $slug = preg_replace('/-+/', '-', $slug);
                    $slug = trim($slug, '-');
                    $langDetails['slug'] = $slug;
                }
            }
            
            // Create tour
            $tourId = $tourModel->addWithDetails($tourData, $details);
            
            if ($tourId) {
                // Handle gallery uploads
                $galleryImages = $this->file('gallery_images');
                
                if ($galleryImages && $galleryImages['name'][0]) {
                    $this->uploadGalleryImages($galleryImages, $tourId);
                }
                
                $this->session->setFlash('success', __('tour_added'));
                $this->redirect('admin/tours');
            } else {
                $this->session->setFlash('error', __('tour_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/tours/create', [
            'pageTitle' => __('add_tour'),
            'languages' => $languages,
            'categories' => $categories,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Edit action - edit a tour
     * 
     * @param int $id Tour ID
     */
    public function edit($id)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $tourModel = $this->loadModel('Tour');
        $categoryModel = $this->loadModel('Category');
        $languageModel = $this->loadModel('LanguageModel');
        $galleryModel = $this->loadModel('Gallery');
        
        // Get tour
        $tour = $tourModel->getWithDetails($id, $langCode);
        
        // Check if tour exists
        if (!$tour) {
            $this->session->setFlash('error', __('tour_not_found'));
            $this->redirect('admin/tours');
        }
        
        // Get categories
        $categories = $categoryModel->getAllWithDetails($langCode, ['c.is_active' => 1], 'cd.name ASC');
        
        // Get languages
        $languages = $languageModel->getActiveLanguages();
        
        // Get gallery items
        $galleryItems = $galleryModel->getByTour($id, $langCode);
        
        // Get tour details for all languages
        $tourDetails = [];
        
        foreach ($languages as $lang) {
            $langTour = $tourModel->getWithDetails($id, $lang['code']);
            if ($langTour) {
                $tourDetails[$lang['id']] = [
                    'name' => $langTour['name'],
                    'slug' => $langTour['slug'],
                    'short_description' => $langTour['short_description'],
                    'description' => $langTour['description'],
                    'includes' => $langTour['includes'],
                    'excludes' => $langTour['excludes'],
                    'itinerary' => $langTour['itinerary'],
                    'meta_title' => $langTour['meta_title'],
                    'meta_description' => $langTour['meta_description']
                ];
            }
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $categoryId = $this->post('category_id');
            $price = $this->post('price');
            $discountPrice = $this->post('discount_price');
            $duration = $this->post('duration');
            $isFeatured = $this->post('is_featured', 0);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Handle featured image upload
            $featuredImage = $this->file('featured_image');
            $featuredImageName = $tour['featured_image'];
            
            if ($featuredImage && $featuredImage['error'] === UPLOAD_ERR_OK) {
                $featuredImageName = $this->uploadImage($featuredImage, 'tours');
                
                if (!$featuredImageName) {
                    $this->session->setFlash('error', __('image_upload_failed'));
                    $this->redirect('admin/tours/edit/' . $id);
                    return;
                }
                
                // Delete old image if exists
                if ($tour['featured_image']) {
                    $oldImagePath = BASE_PATH . '/public/uploads/tours/' . $tour['featured_image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
            
            // Validate inputs
            $errors = [];
            
            if (empty($price) || !is_numeric($price) || $price <= 0) {
                $errors[] = __('valid_price_required');
            }
            
            if (!empty($discountPrice) && (!is_numeric($discountPrice) || $discountPrice <= 0 || $discountPrice >= $price)) {
                $errors[] = __('valid_discount_price_required');
            }
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['name'])) {
                    $errors[] = sprintf(__('name_required_for_lang'), $lang['name']);
                }
                
                if (empty($details[$lang['id']]['short_description'])) {
                    $errors[] = sprintf(__('short_description_required_for_lang'), $lang['name']);
                }
                
                if (empty($details[$lang['id']]['description'])) {
                    $errors[] = sprintf(__('description_required_for_lang'), $lang['name']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Update tour details with POST data
                $tourDetails = $details;
                
                // Render view again
                $this->render('admin/tours/edit', [
                    'pageTitle' => __('edit_tour'),
                    'tour' => $tour,
                    'tourDetails' => $tourDetails,
                    'languages' => $languages,
                    'categories' => $categories,
                    'galleryItems' => $galleryItems,
                    'currentLang' => $langCode
                ], 'admin');
                
                return;
            }
            
            // Prepare tour data
            $tourData = [
                'category_id' => $categoryId ?: null,
                'featured_image' => $featuredImageName,
                'price' => $price,
                'discount_price' => $discountPrice ?: null,
                'duration' => $duration,
                'is_featured' => $isFeatured ? 1 : 0,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Generate slugs if not provided
            foreach ($details as $langId => &$langDetails) {
                if (empty($langDetails['slug'])) {
                    $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]/', '-', $langDetails['name'])));
                    $slug = preg_replace('/-+/', '-', $slug);
                    $slug = trim($slug, '-');
                    $langDetails['slug'] = $slug;
                }
            }
            
            // Update tour
            $result = $tourModel->updateWithDetails($id, $tourData, $details);
            
            if ($result) {
                // Handle gallery uploads
                $galleryImages = $this->file('gallery_images');
                
                if ($galleryImages && $galleryImages['name'][0]) {
                    $this->uploadGalleryImages($galleryImages, $id);
                }
                
                $this->session->setFlash('success', __('tour_updated'));
                $this->redirect('admin/tours');
            } else {
                $this->session->setFlash('error', __('tour_update_failed'));
            }
        }
        
        // Render view
        $this->render('admin/tours/edit', [
            'pageTitle' => __('edit_tour'),
            'tour' => $tour,
            'tourDetails' => $tourDetails,
            'languages' => $languages,
            'categories' => $categories,
            'galleryItems' => $galleryItems,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Delete action - delete a tour
     * 
     * @param int $id Tour ID
     */
    public function delete($id)
    {
        // Load tour model
        $tourModel = $this->loadModel('Tour');
        
        // Get tour
        $tour = $tourModel->getById($id);
        
        // Check if tour exists
        if (!$tour) {
            $this->session->setFlash('error', __('tour_not_found'));
            $this->redirect('admin/tours');
        }
        
        // Delete tour
        $result = $tourModel->deleteWithDetails($id);
        
        if ($result) {
            // Delete tour image
            if ($tour['featured_image']) {
                $imagePath = BASE_PATH . '/public/uploads/tours/' . $tour['featured_image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $this->session->setFlash('success', __('tour_deleted'));
        } else {
            $this->session->setFlash('error', __('tour_delete_failed'));
        }
        
        // Redirect to tours list
        $this->redirect('admin/tours');
    }
    
    /**
     * Toggle featured action
     * 
     * @param int $id Tour ID
     */
    public function toggleFeatured($id)
    {
        // Load tour model
        $tourModel = $this->loadModel('Tour');
        
        // Get tour
        $tour = $tourModel->getById($id);
        
        // Check if tour exists
        if (!$tour) {
            $this->session->setFlash('error', __('tour_not_found'));
            $this->redirect('admin/tours');
        }
        
        // Toggle featured
        $newStatus = $tour['is_featured'] ? 0 : 1;
        $result = $tourModel->update(['is_featured' => $newStatus], ['id' => $id]);
        
        if ($result) {
            $statusText = $newStatus ? __('featured') : __('unfeatured');
            $this->session->setFlash('success', __('tour') . ' ' . $statusText);
        } else {
            $this->session->setFlash('error', __('update_failed'));
        }
        
        // Redirect to tours list
        $this->redirect('admin/tours');
    }
    
    /**
     * Toggle status action
     * 
     * @param int $id Tour ID
     */
    public function toggleStatus($id)
    {
        // Load tour model
        $tourModel = $this->loadModel('Tour');
        
        // Get tour
        $tour = $tourModel->getById($id);
        
        // Check if tour exists
        if (!$tour) {
            $this->session->setFlash('error', __('tour_not_found'));
            $this->redirect('admin/tours');
        }
        
        // Toggle status
        $newStatus = $tour['is_active'] ? 0 : 1;
        $result = $tourModel->update(['is_active' => $newStatus], ['id' => $id]);
        
        if ($result) {
            $statusText = $newStatus ? __('activated') : __('deactivated');
            $this->session->setFlash('success', __('tour') . ' ' . $statusText);
        } else {
            $this->session->setFlash('error', __('status_update_failed'));
        }
        
        // Redirect to tours list
        $this->redirect('admin/tours');
    }
    
    /**
     * Delete gallery image
     * 
     * @param int $id Gallery item ID
     * @param int $tourId Tour ID
     */
    public function deleteGalleryImage($id, $tourId)
    {
        // Load gallery model
        $galleryModel = $this->loadModel('Gallery');
        
        // Get gallery item
        $galleryItem = $galleryModel->getById($id);
        
        // Check if gallery item exists
        if (!$galleryItem) {
            $this->session->setFlash('error', __('gallery_item_not_found'));
            $this->redirect('admin/tours/edit/' . $tourId);
        }
        
        // Delete gallery item
        $result = $galleryModel->deleteWithDetails($id);
        
        if ($result) {
            // Delete gallery image
            if ($galleryItem['image']) {
                $imagePath = BASE_PATH . '/public/uploads/gallery/' . $galleryItem['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $this->session->setFlash('success', __('gallery_item_deleted'));
        } else {
            $this->session->setFlash('error', __('gallery_item_delete_failed'));
        }
        
        // Redirect to tour edit page
        $this->redirect('admin/tours/edit/' . $tourId);
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
    
    /**
     * Upload gallery images
     * 
     * @param array $files Uploaded files
     * @param int $tourId Tour ID
     * @return bool Success
     */
    private function uploadGalleryImages($files, $tourId)
    {
        // Check if files exist
        if (!$files || !is_array($files['name'])) {
            return false;
        }
        
        // Load models
        $galleryModel = $this->loadModel('Gallery');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get languages
        $languages = $languageModel->getActiveLanguages();
        
        // Upload each file
        $success = true;
        
        for ($i = 0; $i < count($files['name']); $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK) {
                continue;
            }
            
            // Create file array for a single file
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];
            
            // Upload image
            $imageName = $this->uploadImage($file, 'gallery');
            
            if ($imageName) {
                // Prepare gallery data
                $galleryData = [
                    'tour_id' => $tourId,
                    'image' => $imageName,
                    'order_number' => 0,
                    'is_active' => 1
                ];
                
                // Prepare details data for all languages
                $detailsData = [];
                
                foreach ($languages as $lang) {
                    $detailsData[$lang['id']] = [
                        'title' => '',
                        'description' => ''
                    ];
                }
                
                // Add gallery item
                $result = $galleryModel->addWithDetails($galleryData, $detailsData);
                
                if (!$result) {
                    $success = false;
                }
            } else {
                $success = false;
            }
        }
        
        return $success;
    }
    
    /**
     * Search tours (for Ajax)
     * 
     * This method is used for searching tours by name and returning JSON response
     */
    public function search()
    {
        // Check if request is Ajax
        if (!$this->isAjax()) {
            $this->redirect('admin/tours');
        }
        
        // Get query
        $query = $this->get('q');
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load tour model
        $tourModel = $this->loadModel('Tour');
        
        // Search tours
        $tours = $tourModel->search($query, $langCode, 10);
        
        // Format results
        $results = [];
        
        foreach ($tours as $tour) {
            $results[] = [
                'id' => $tour['id'],
                'name' => $tour['name'],
                'price' => $tour['price'],
                'discount_price' => $tour['discount_price'],
                'category' => $tour['category_name'] ?? '',
                'image' => $tour['featured_image'] ? UPLOADS_URL . '/tours/' . $tour['featured_image'] : '',
                'url' => APP_URL . '/admin/tours/edit/' . $tour['id']
            ];
        }
        
        // Return JSON response
        $this->json($results);
    }
    
    /**
     * Helper method to search tours by name
     * 
     * This is used internally for full-text search
     * 
     * @param string $query Search query
     * @param string $langCode Language code
     * @return array Tours
     */
    private function searchTours($query, $langCode)
    {
        $sql = "SELECT t.*, td.name, td.slug, td.short_description, 
                       c.id as category_id, cd.name as category_name
                FROM tours t
                JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN category_details cd ON c.id = cd.category_id AND cd.language_id = (
                    SELECT id FROM languages WHERE code = :langCode
                )
                WHERE td.language_id = (
                    SELECT id FROM languages WHERE code = :langCode
                )
                AND (
                    td.name LIKE :query OR 
                    td.short_description LIKE :query OR 
                    td.description LIKE :query
                )
                ORDER BY t.id DESC";
        
        return $this->db->getRows($sql, [
            'langCode' => $langCode,
            'query' => '%' . $query . '%'
        ]);
    }
}