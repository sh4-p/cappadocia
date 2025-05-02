<?php
/**
 * Page Controller
 * 
 * Handles displaying custom pages
 */
class PageController extends Controller
{
    private $pageModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Load models
        $this->pageModel = $this->loadModel('Page');
    }
    
    /**
     * Show action - display a specific page
     * 
     * @param string $slug Page slug
     */
    public function show($slug)
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get page
        $page = $this->pageModel->getBySlug($slug, $langCode);
        
        // Check if page exists and is active
        if (!$page || !$page['is_active']) {
            // Redirect to 404 page
            $this->redirect('error/404');
        }
        
        // Get template
        $template = $page['template'] ?: 'default';
        
        // Set page data
        $data = [
            'page' => $page,
            'pageTitle' => $page['title'],
            'metaTitle' => $page['meta_title'] ?: $page['title'],
            'metaDescription' => $page['meta_description'] ?: ''
        ];
        
        // Render view based on template
        $this->render('page/' . $template, $data);
    }
}