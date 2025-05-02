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
        
        // Redirect to referer or homepage
        $referer = $_SERVER['HTTP_REFERER'] ?? null;
        
        if ($referer) {
            // Extract current language from URL if exists
            $currentLang = $this->language->getCurrentLanguage();
            $refererParts = parse_url($referer);
            $path = $refererParts['path'] ?? '';
            $pathParts = explode('/', trim($path, '/'));
            
            // Check if first part is a language code
            $availableLanguages = $this->language->getAvailableLanguages();
            if (!empty($pathParts) && array_key_exists($pathParts[0], $availableLanguages)) {
                // Replace language code in URL
                $pathParts[0] = $lang;
                $newPath = '/' . implode('/', $pathParts);
                
                // Rebuild URL
                $newUrl = $refererParts['scheme'] . '://' . $refererParts['host'];
                if (isset($refererParts['port'])) {
                    $newUrl .= ':' . $refererParts['port'];
                }
                $newUrl .= $newPath;
                if (isset($refererParts['query'])) {
                    $newUrl .= '?' . $refererParts['query'];
                }
                
                // Redirect to new URL
                header('Location: ' . $newUrl);
                exit;
            }
        }
        
        // If no referer or language not found in URL, redirect to homepage with new language
        $this->redirect($lang);
    }
}