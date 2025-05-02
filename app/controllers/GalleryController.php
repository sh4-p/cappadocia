<?php
/**
 * Gallery Controller
 * 
 * Handles the gallery page
 */
class GalleryController extends Controller
{
    private $galleryModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Load models
        $this->galleryModel = $this->loadModel('Gallery');
    }
    
    /**
     * Index action - display gallery page
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get page from query string
        $page = (int) ($this->get('page', 1));
        if ($page < 1) $page = 1;
        
        // Set limit and offset
        $limit = 16;
        $offset = ($page - 1) * $limit;
        
        // Get filter
        $filter = $this->get('filter');
        
        // Get gallery categories (tour categories)
        $categoryModel = $this->loadModel('Category');
        $categories = $categoryModel->getAllWithDetails($langCode, ['c.is_active' => 1], 'c.order_number ASC');
        
        // Get gallery items
        if ($filter) {
            // Get gallery items by category
            $galleryItems = $this->galleryModel->getByCategory($filter, $langCode, $limit, $offset);
            $totalItems = $this->galleryModel->countByCategory($filter);
            
            // Get category details
            $category = $categoryModel->getBySlug($filter, $langCode);
            $filterName = $category ? $category['name'] : '';
        } else {
            // Get all gallery items
            $galleryItems = $this->galleryModel->getAllWithDetails($langCode, ['g.is_active' => 1], 'g.id DESC', $limit, $offset);
            $totalItems = $this->galleryModel->count(['is_active' => 1]);
            $filterName = '';
        }
        
        // Calculate pagination
        $totalPages = ceil($totalItems / $limit);
        
        // Set page data
        $data = [
            'galleryItems' => $galleryItems,
            'categories' => $categories,
            'filter' => $filter,
            'filterName' => $filterName,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'pageTitle' => $filterName ? sprintf(__('gallery_category'), $filterName) : __('gallery'),
            'metaDescription' => $filterName ? sprintf(__('gallery_category_description'), $filterName) : __('gallery_description'),
            'additionalCss' => [
                'gallery.css'
            ],
            'additionalJs' => [
                'gallery.js'
            ]
        ];
        
        // Render view
        $this->render('gallery/index', $data);
    }
    
    /**
     * Tour action - display gallery for a specific tour
     * 
     * @param int $id Tour ID
     */
    public function tour($id)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get tour details
        $tourModel = $this->loadModel('Tour');
        $tour = $tourModel->getWithDetails($id, $langCode);
        
        // Check if tour exists
        if (!$tour) {
            // Redirect to gallery page
            $this->redirect('gallery');
        }
        
        // Get gallery items for this tour
        $galleryItems = $this->galleryModel->getByTour($id, $langCode);
        
        // Set page data
        $data = [
            'tour' => $tour,
            'galleryItems' => $galleryItems,
            'pageTitle' => sprintf(__('tour_gallery'), $tour['name']),
            'metaDescription' => sprintf(__('tour_gallery_description'), $tour['name']),
            'additionalCss' => [
                'gallery.css'
            ],
            'additionalJs' => [
                'gallery.js'
            ]
        ];
        
        // Render view
        $this->render('gallery/tour', $data);
    }
    
    /**
     * Ajax action - load more gallery items
     */
    public function ajaxLoad()
    {
        // Check if request is Ajax
        if (!$this->isAjax()) {
            $this->redirect('gallery');
        }
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get page from query string
        $page = (int) ($this->get('page', 1));
        if ($page < 1) $page = 1;
        
        // Set limit and offset
        $limit = 8;
        $offset = ($page - 1) * $limit;
        
        // Get filter
        $filter = $this->get('filter');
        
        // Get gallery items
        if ($filter) {
            // Get gallery items by category
            $galleryItems = $this->galleryModel->getByCategory($filter, $langCode, $limit, $offset);
        } else {
            // Get all gallery items
            $galleryItems = $this->galleryModel->getAllWithDetails($langCode, ['g.is_active' => 1], 'g.id DESC', $limit, $offset);
        }
        
        // Format results for JSON response
        $html = '';
        
        foreach ($galleryItems as $item) {
            $html .= '
            <div class="gallery-item" data-aos="fade-up">
                <img src="' . UPLOADS_URL . '/gallery/' . $item['image'] . '" alt="' . ($item['title'] ?: __('gallery_image')) . '" class="gallery-image">
                <div class="gallery-overlay">
                    <i class="material-icons gallery-icon">zoom_in</i>
                </div>
            </div>';
        }
        
        // Return JSON response
        $this->json([
            'success' => true,
            'html' => $html,
            'count' => count($galleryItems)
        ]);
    }
}