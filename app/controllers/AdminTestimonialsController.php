<?php
/**
 * Admin Testimonials Controller
 * 
 * Handles testimonials management in admin panel
 */
class AdminTestimonialsController extends Controller
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
        return 'AdminTestimonials';
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
     * Index action - list all testimonials
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load testimonial model
        $testimonialModel = $this->loadModel('Testimonial');
        
        // Get all testimonials
        $testimonials = $testimonialModel->getAllWithDetails($langCode);
        
        // Render view
        $this->render('admin/testimonials/index', [
            'pageTitle' => __('testimonials'),
            'testimonials' => $testimonials
        ], 'admin');
    }
    
    /**
     * Create action - create a new testimonial
     */
    public function create()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $testimonialModel = $this->loadModel('Testimonial');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get all languages
        $languages = $languageModel->getActiveLanguages();
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $name = $this->post('name');
            $position = $this->post('position');
            $rating = $this->post('rating', 5);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Handle image upload
            $image = $this->file('image');
            $imageName = null;
            
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $imageName = $this->uploadImage($image, 'testimonials');
                
                if (!$imageName) {
                    $this->session->setFlash('error', __('image_upload_failed'));
                }
            }
            
            // Validate inputs
            $errors = [];
            
            if (empty($name)) {
                $errors[] = __('name_required');
            }
            
            if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
                $errors[] = __('valid_rating_required');
            }
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['content'])) {
                    $errors[] = sprintf(__('content_required_for_lang'), $lang['name']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again
                $this->render('admin/testimonials/create', [
                    'pageTitle' => __('add_testimonial'),
                    'languages' => $languages,
                    'details' => $details,
                    'name' => $name,
                    'position' => $position,
                    'rating' => $rating,
                    'isActive' => $isActive,
                    'currentLang' => $langCode
                ], 'admin');
                
                return;
            }
            
            // Prepare testimonial data
            $testimonialData = [
                'name' => $name,
                'position' => $position,
                'image' => $imageName,
                'rating' => $rating,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Create testimonial
            $testimonialId = $testimonialModel->addWithDetails($testimonialData, $details);
            
            if ($testimonialId) {
                $this->session->setFlash('success', __('testimonial_added'));
                $this->redirect('admin/testimonials');
            } else {
                $this->session->setFlash('error', __('testimonial_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/testimonials/create', [
            'pageTitle' => __('add_testimonial'),
            'languages' => $languages,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Edit action - edit a testimonial
     * 
     * @param int $id Testimonial ID
     */
    public function edit($id)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Load models
        $testimonialModel = $this->loadModel('Testimonial');
        $languageModel = $this->loadModel('LanguageModel');
        
        // Get testimonial
        $testimonial = $testimonialModel->getWithDetails($id, $langCode);
        
        // Check if testimonial exists
        if (!$testimonial) {
            $this->session->setFlash('error', __('testimonial_not_found'));
            $this->redirect('admin/testimonials');
        }
        
        // Get all languages
        $languages = $languageModel->getActiveLanguages();
        
        // Get testimonial details for all languages
        $testimonialDetails = [];
        
        foreach ($languages as $lang) {
            $langTestimonial = $testimonialModel->getWithDetails($id, $lang['code']);
            if ($langTestimonial) {
                $testimonialDetails[$lang['id']] = [
                    'content' => $langTestimonial['content']
                ];
            }
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $name = $this->post('name');
            $position = $this->post('position');
            $rating = $this->post('rating', 5);
            $isActive = $this->post('is_active', 0);
            $details = $this->post('details', []);
            
            // Handle image upload
            $image = $this->file('image');
            $imageName = $testimonial['image'];
            
            if ($image && $image['error'] === UPLOAD_ERR_OK) {
                $imageName = $this->uploadImage($image, 'testimonials');
                
                if (!$imageName) {
                    $this->session->setFlash('error', __('image_upload_failed'));
                } else {
                    // Delete old image if exists
                    if ($testimonial['image']) {
                        $oldImagePath = BASE_PATH . '/public/uploads/testimonials/' . $testimonial['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }
                }
            }
            
            // Validate inputs
            $errors = [];
            
            if (empty($name)) {
                $errors[] = __('name_required');
            }
            
            if (!is_numeric($rating) || $rating < 1 || $rating > 5) {
                $errors[] = __('valid_rating_required');
            }
            
            // Validate details for each language
            foreach ($languages as $lang) {
                if (empty($details[$lang['id']]['content'])) {
                    $errors[] = sprintf(__('content_required_for_lang'), $lang['name']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Update testimonial details with POST data
                $testimonialDetails = $details;
                
                // Render view again
                $this->render('admin/testimonials/edit', [
                    'pageTitle' => __('edit_testimonial'),
                    'testimonial' => $testimonial,
                    'testimonialDetails' => $testimonialDetails,
                    'languages' => $languages,
                    'currentLang' => $langCode
                ], 'admin');
                
                return;
            }
            
            // Prepare testimonial data
            $testimonialData = [
                'name' => $name,
                'position' => $position,
                'image' => $imageName,
                'rating' => $rating,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Update testimonial
            $result = $testimonialModel->updateWithDetails($id, $testimonialData, $details);
            
            if ($result) {
                $this->session->setFlash('success', __('testimonial_updated'));
                $this->redirect('admin/testimonials');
            } else {
                $this->session->setFlash('error', __('testimonial_update_failed'));
            }
        }
        
        // Render view
        $this->render('admin/testimonials/edit', [
            'pageTitle' => __('edit_testimonial'),
            'testimonial' => $testimonial,
            'testimonialDetails' => $testimonialDetails,
            'languages' => $languages,
            'currentLang' => $langCode
        ], 'admin');
    }
    
    /**
     * Delete action - delete a testimonial
     * 
     * @param int $id Testimonial ID
     */
    public function delete($id)
    {
        // Load testimonial model
        $testimonialModel = $this->loadModel('Testimonial');
        
        // Get testimonial
        $testimonial = $testimonialModel->getById($id);
        
        // Check if testimonial exists
        if (!$testimonial) {
            $this->session->setFlash('error', __('testimonial_not_found'));
            $this->redirect('admin/testimonials');
        }
        
        // Delete testimonial
        $result = $testimonialModel->deleteWithDetails($id);
        
        if ($result) {
            // Delete testimonial image
            if ($testimonial['image']) {
                $imagePath = BASE_PATH . '/public/uploads/testimonials/' . $testimonial['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $this->session->setFlash('success', __('testimonial_deleted'));
        } else {
            $this->session->setFlash('error', __('testimonial_delete_failed'));
        }
        
        // Redirect to testimonials list
        $this->redirect('admin/testimonials');
    }
    
    /**
     * Toggle status action
     * 
     * @param int $id Testimonial ID
     */
    public function toggleStatus($id)
    {
        // Load testimonial model
        $testimonialModel = $this->loadModel('Testimonial');
        
        // Get testimonial
        $testimonial = $testimonialModel->getById($id);
        
        // Check if testimonial exists
        if (!$testimonial) {
            $this->session->setFlash('error', __('testimonial_not_found'));
            $this->redirect('admin/testimonials');
        }
        
        // Toggle status
        $newStatus = $testimonial['is_active'] ? 0 : 1;
        $result = $testimonialModel->updateStatus($id, $newStatus);
        
        if ($result) {
            $statusText = $newStatus ? __('activated') : __('deactivated');
            $this->session->setFlash('success', __('testimonial') . ' ' . $statusText);
        } else {
            $this->session->setFlash('error', __('status_update_failed'));
        }
        
        // Redirect to testimonials list
        $this->redirect('admin/testimonials');
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