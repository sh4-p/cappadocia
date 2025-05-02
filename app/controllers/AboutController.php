<?php
/**
 * About Controller
 * 
 * Handles the about page
 */
class AboutController extends Controller
{
    private $pageModel;
    private $testimonialModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Load models
        $this->pageModel = $this->loadModel('Page');
        $this->testimonialModel = $this->loadModel('Testimonial');
    }
    
    /**
     * Index action - display about page
     */
    public function index()
    {
        // Get current language
        $langCode = $this->language->getCurrentLanguage();
        
        // Get about page content
        $aboutPage = $this->pageModel->getBySlug('about', $langCode);
        
        // Get team members (can be from a separate table or from page content)
        $teamMembers = [
            [
                'name' => 'John Doe',
                'position' => __('tour_manager'),
                'image' => 'team-1.jpg',
                'bio' => __('team_member_1_bio'),
                'social' => [
                    'facebook' => 'https://facebook.com',
                    'instagram' => 'https://instagram.com',
                    'twitter' => 'https://twitter.com'
                ]
            ],
            [
                'name' => 'Jane Smith',
                'position' => __('marketing_manager'),
                'image' => 'team-2.jpg',
                'bio' => __('team_member_2_bio'),
                'social' => [
                    'facebook' => 'https://facebook.com',
                    'instagram' => 'https://instagram.com',
                    'linkedin' => 'https://linkedin.com'
                ]
            ],
            [
                'name' => 'Michael Brown',
                'position' => __('tour_guide'),
                'image' => 'team-3.jpg',
                'bio' => __('team_member_3_bio'),
                'social' => [
                    'facebook' => 'https://facebook.com',
                    'instagram' => 'https://instagram.com'
                ]
            ],
            [
                'name' => 'Sarah Johnson',
                'position' => __('customer_relations'),
                'image' => 'team-4.jpg',
                'bio' => __('team_member_4_bio'),
                'social' => [
                    'facebook' => 'https://facebook.com',
                    'twitter' => 'https://twitter.com',
                    'linkedin' => 'https://linkedin.com'
                ]
            ]
        ];
        
        // Get testimonials
        $testimonials = $this->testimonialModel->getAllWithDetails($langCode, ['t.is_active' => 1], 't.id DESC', 3);
        
        // Set page data
        $data = [
            'aboutPage' => $aboutPage,
            'teamMembers' => $teamMembers,
            'testimonials' => $testimonials,
            'pageTitle' => $aboutPage ? $aboutPage['title'] : __('about_us'),
            'metaDescription' => $aboutPage ? $aboutPage['meta_description'] : __('about_us_description'),
            'additionalCss' => [
                'about.css'
            ]
        ];
        
        // Render view
        $this->render('about/index', $data);
    }
}