<?php
/**
 * Home Controller
 * 
 * Handles the homepage and other main pages
 */
class HomeController extends Controller
{
    private $tourModel;
    private $testimonialModel;
    private $galleryModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Load models
        $this->tourModel = $this->loadModel('Tour');
        $this->testimonialModel = $this->loadModel('Testimonial');
        $this->galleryModel = $this->loadModel('Gallery');
    }
    
    /**
     * Index action - display homepage
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get featured tours
        $featuredTours = $this->tourModel->getFeatured($langCode, 6);
        
        // Get featured tours for the layout (limited to 3)
        $this->data['featuredTours'] = $this->tourModel->getFeatured($langCode, 3);
        
        // Get destinations (categories with image)
        $categoryModel = $this->loadModel('Category');
        $destinations = $categoryModel->getAllWithDetails($langCode, [
            'c.is_active' => 1,
            'c.image IS NOT NULL' => null
        ], 'c.order_number ASC', 4);
        
        // Get testimonials
        $testimonials = $this->testimonialModel->getAllWithDetails($langCode, ['t.is_active' => 1], 't.id DESC', 5);
        
        // Get gallery items
        $galleryItems = $this->galleryModel->getHomeGallery($langCode, 8);
        
        // Set page data
        $data = [
            'featuredTours' => $featuredTours,
            'destinations' => $destinations,
            'testimonials' => $testimonials,
            'galleryItems' => $galleryItems,
            'pageTitle' => $this->data['settings']['site_title'] ?? 'Cappadocia Travel Agency',
            'metaDescription' => $this->data['settings']['site_description'] ?? 'Explore the magical landscapes of Cappadocia with our expert guided tours',
            'additionalCss' => [
                'home.css'
            ],
            'additionalJs' => [
                'home.js'
            ]
        ];
        
        // Render view
        $this->render('home/index', $data);
    }
    
    /**
     * Error action - display error page
     * 
     * @param int $code Error code
     */
    public function error($code = 404)
    {
        // Set HTTP status code
        http_response_code($code);
        
        // Set page data
        $data = [
            'code' => $code,
            'pageTitle' => 'Error ' . $code,
            'metaDescription' => 'Error ' . $code
        ];
        
        // Render view
        $this->render('error/index', $data);
    }
    
    /**
     * Switch language action
     * 
     * @param string $lang Language code
     */
    public function switchLanguage($lang)
    {
        // Set language in session
        $this->session->set('lang', $lang);
        
        // Get referer URL
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        
        if (!empty($referer)) {
            // Parse URL
            $parts = parse_url($referer);
            $path = isset($parts['path']) ? $parts['path'] : '';
            
            // Get current language
            $currentLang = $this->language->getCurrentLanguage();
            
            // Get base URL path
            $basePath = parse_url(APP_URL, PHP_URL_PATH) ?: '';
            $langPrefix = $basePath . '/' . $currentLang;
            
            // Check if path starts with language code
            if (strpos($path, $langPrefix) === 0) {
                // Replace language code in path
                $newPath = $basePath . '/' . $lang . substr($path, strlen($langPrefix));
                
                // Build new URL
                $newUrl = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';
                $newUrl .= isset($parts['host']) ? $parts['host'] : '';
                if (isset($parts['port'])) {
                    $newUrl .= ':' . $parts['port'];
                }
                $newUrl .= $newPath;
                if (isset($parts['query'])) {
                    $newUrl .= '?' . $parts['query'];
                }
                
                // Redirect to new URL
                header('Location: ' . $newUrl);
                exit;
            }
        }
        
        // Default: redirect to homepage with new language
        $this->redirect($lang);
    }
}