
<?php
/**
 * Tours Controller
 * 
 * Handles tour-related operations
 */
class ToursController extends Controller
{
    private $tourModel;
    private $categoryModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Load models
        $this->tourModel = $this->loadModel('Tour');
        $this->categoryModel = $this->loadModel('Category');
    }
    
    /**
     * Index action - display all tours
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get page from query string
        $page = (int) ($this->get('page', 1));
        if ($page < 1) $page = 1;
        
        // Set limit and offset
        $limit = 9;
        $offset = ($page - 1) * $limit;
        
        // Get category filter
        $categorySlug = $this->get('category');
        
        // Get tours
        if ($categorySlug) {
            $tours = $this->tourModel->getByCategory($categorySlug, $langCode, $limit, $offset);
            $totalTours = count($this->tourModel->getByCategory($categorySlug, $langCode));
            
            // Get category details
            $category = $this->categoryModel->getBySlug($categorySlug, $langCode);
        } else {
            $tours = $this->tourModel->getAllWithDetails($langCode, ['t.is_active' => 1], 't.id DESC', $limit, $offset);
            $totalTours = $this->tourModel->countTours(['is_active' => 1]);
            $category = null;
        }
        
        // Get all categories for filter
        $categories = $this->categoryModel->getAllWithDetails($langCode, ['c.is_active' => 1], 'c.order_number ASC');
        
        // Calculate pagination
        $totalPages = ceil($totalTours / $limit);
        
        // Set view data
        $data = [
            'tours' => $tours,
            'categories' => $categories,
            'category' => $category,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalTours' => $totalTours,
            'pageTitle' => $category ? $category['name'] : __('tours'),
            'metaDescription' => $category ? $category['meta_description'] : __('tours_meta_description')
        ];
        
        // Render view
        $this->render('tours/index', $data);
    }
    
    /**
     * Detail action - display tour details
     * 
     * @param string $slug Tour slug
     */
    public function detail($slug)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get tour details
        $tour = $this->tourModel->getWithDetails($slug, $langCode);
        
        // Check if tour exists
        if (!$tour) {
            // Redirect to tours page
            $this->redirect('tours');
        }
        
        // Get tour gallery
        $gallery = $this->tourModel->getGallery($tour['id'], $langCode);
        
        // Get related tours
        $relatedTours = $this->tourModel->getAllWithDetails(
            $langCode, 
            [
                't.is_active' => 1,
                't.id !=' => $tour['id'],
                't.category_id' => $tour['category_id']
            ],
            'RAND()',
            3
        );
        
        // Set view data
        $data = [
            'tour' => $tour,
            'gallery' => $gallery,
            'relatedTours' => $relatedTours,
            'pageTitle' => $tour['name'],
            'metaDescription' => $tour['meta_description'] ?: substr(strip_tags($tour['short_description']), 0, 160)
        ];
        
        // Render view
        $this->render('tours/detail', $data);
    }
    
    /**
     * Search action - search tours
     */
    public function search()
    {
        // Check if request is GET
        if ($this->get('q') === null) {
            $this->redirect('tours');
        }
        
        // Get search query
        $query = $this->get('q');
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get page from query string
        $page = (int) ($this->get('page', 1));
        if ($page < 1) $page = 1;
        
        // Set limit and offset
        $limit = 9;
        $offset = ($page - 1) * $limit;
        
        // Search tours
        $tours = $this->tourModel->search($query, $langCode, $limit, $offset);
        
        // Count total search results
        $sql = "SELECT COUNT(*) 
                FROM tours t
                JOIN tour_details td ON t.id = td.tour_id
                JOIN languages l ON td.language_id = l.id
                WHERE l.code = :langCode
                AND t.is_active = 1
                AND (td.name LIKE :query OR td.short_description LIKE :query OR td.description LIKE :query)";
        
        $totalTours = $this->db->getValue($sql, [
            'langCode' => $langCode,
            'query' => '%' . $query . '%'
        ]);
        
        // Calculate pagination
        $totalPages = ceil($totalTours / $limit);
        
        // Get all categories for filter
        $categories = $this->categoryModel->getAllWithDetails($langCode, ['c.is_active' => 1], 'c.order_number ASC');
        
        // Set view data
        $data = [
            'tours' => $tours,
            'categories' => $categories,
            'query' => $query,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalTours' => $totalTours,
            'pageTitle' => sprintf(__('search_results_for'), $query),
            'metaDescription' => sprintf(__('search_results_meta_description'), $query)
        ];
        
        // Render view
        $this->render('tours/search', $data);
    }
    
    /**
     * Ajax search action - search tours via Ajax
     */
    public function ajaxSearch()
    {
        // Get search query
        $query = $this->get('q');
        
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Search tours (limit to 5)
        $tours = $this->tourModel->search($query, $langCode, 5);
        
        // Load settings model for currency
        $settingsModel = $this->loadModel('Settings');
        $settings = $settingsModel->getAllSettings();
        $currencySymbol = isset($settings['currency_symbol']) ? $settings['currency_symbol'] : '€';
        
        // Format results for JSON response
        $results = [];
        
        foreach ($tours as $tour) {
            // Format prices with currency symbol
            $price = $currencySymbol . number_format($tour['price'], 2);
            $discountPrice = $tour['discount_price'] ? $currencySymbol . number_format($tour['discount_price'], 2) : null;
            
            // Build full URLs with correct APP_URL
            $imageUrl = '/uploads/tours/' . $tour['featured_image']; // Relative path for JS to fix
            
            $results[] = [
                'id' => $tour['id'],
                'name' => $tour['name'],
                'slug' => $tour['slug'],
                'price' => $price,
                'discount_price' => $discountPrice,
                'image' => $imageUrl,
                'url' => APP_URL . '/' . $langCode . '/tours/' . $tour['slug']
            ];
        }
        
        // Set header for JSON response
        header('Content-Type: application/json');
        
        // Return JSON response
        echo json_encode($results);
        exit;
    }
}