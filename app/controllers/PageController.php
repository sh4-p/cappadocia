<?php
/**
 * Page Controller - Improved with HTML content support
 * 
 * Handles displaying custom pages with rich text content
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
     * Get configuration value (helper method)
     * 
     * @param string $key Configuration key
     * @param mixed $default Default value
     * @return mixed Configuration value
     */
    protected function getConfigValue($key, $default = null)
    {
        // Try different methods to get config
        if (method_exists($this, 'getConfig')) {
            return $this->getConfig($key);
        }
        
        // Fallback to environment variables or constants
        switch ($key) {
            case 'app_url':
                $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
                return $protocol . $_SERVER['HTTP_HOST'];
            case 'site_name':
                return $_SERVER['SERVER_NAME'] ?? $_SERVER['HTTP_HOST'];
            default:
                return $default;
        }
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
            // Redirect to 404 page or show error
            header("HTTP/1.0 404 Not Found");
            $this->render('error/404');
            return;
        }
        
        // Get template
        $template = $page['template'] ?: 'default';
        
        // Process content - sanitize but allow safe HTML
        $page['content'] = $this->sanitizeContent($page['content']);
        
        // Generate breadcrumb
        $breadcrumb = $this->getBreadcrumb($page);
        
        // Set page data
        $data = [
            'page' => $page,
            'pageTitle' => $page['title'],
            'metaTitle' => $page['meta_title'] ?: $page['title'],
            'metaDescription' => $page['meta_description'] ?: $this->generateDescription($page['content']),
            'canonicalUrl' => $this->generateCanonicalUrl($page['slug']),
            'breadcrumb' => $breadcrumb
        ];
        
        // Add structured data for SEO
        $data['structuredData'] = $this->generateStructuredData($page);
        
        // Render view based on template
        $this->render('page/' . $template, $data);
    }
    
    /**
     * Sanitize content while preserving safe HTML
     * 
     * @param string $content Raw content
     * @return string Sanitized content
     */
    private function sanitizeContent($content)
    {
        if (empty($content)) {
            return '';
        }
        
        // Allowed HTML tags and attributes
        $allowedTags = [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
            'p', 'br', 'hr',
            'strong', 'b', 'em', 'i', 'u', 's', 'sub', 'sup',
            'ul', 'ol', 'li',
            'a', 'img',
            'table', 'thead', 'tbody', 'tfoot', 'tr', 'th', 'td',
            'blockquote', 'cite',
            'div', 'span',
            'code', 'pre',
            'figure', 'figcaption'
        ];
        
        $allowedAttributes = [
            'a' => ['href', 'title', 'target', 'rel'],
            'img' => ['src', 'alt', 'title', 'width', 'height', 'style'],
            'table' => ['class', 'style'],
            'th' => ['colspan', 'rowspan', 'style'],
            'td' => ['colspan', 'rowspan', 'style'],
            'div' => ['class', 'style'],
            'span' => ['class', 'style'],
            'p' => ['style'],
            'h1' => ['style'], 'h2' => ['style'], 'h3' => ['style'],
            'h4' => ['style'], 'h5' => ['style'], 'h6' => ['style'],
            'ul' => ['style'], 'ol' => ['style'], 'li' => ['style'],
            'blockquote' => ['style'],
            'figure' => ['class', 'style'],
            'figcaption' => ['style']
        ];
        
        // Use HTMLPurifier if available, otherwise use strip_tags with allowed tags
        if (class_exists('HTMLPurifier')) {
            $config = HTMLPurifier_Config::createDefault();
            $config->set('HTML.Allowed', implode(',', $allowedTags));
            $config->set('HTML.AllowedAttributes', $allowedAttributes);
            $config->set('CSS.AllowedProperties', [
                'color', 'background-color', 'font-size', 'font-weight', 'font-style',
                'text-align', 'text-decoration', 'margin', 'padding',
                'border', 'border-color', 'border-style', 'border-width',
                'width', 'height', 'max-width', 'max-height'
            ]);
            
            $purifier = new HTMLPurifier($config);
            return $purifier->purify($content);
        } else {
            // Fallback: basic sanitization
            $content = strip_tags($content, '<' . implode('><', $allowedTags) . '>');
            
            // Remove dangerous attributes
            $content = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/i', '', $content);
            $content = preg_replace('/\s*javascript\s*:/i', '', $content);
            
            return $content;
        }
    }
    
    /**
     * Generate meta description from content
     * 
     * @param string $content Page content
     * @return string Meta description
     */
    private function generateDescription($content)
    {
        if (empty($content)) {
            return '';
        }
        
        // Strip HTML tags and get plain text
        $text = strip_tags($content);
        
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        // Limit to 160 characters
        if (strlen($text) > 160) {
            $text = substr($text, 0, 157) . '...';
        }
        
        return $text;
    }
    
    /**
     * Generate canonical URL for the page
     * 
     * @param string $slug Page slug
     * @return string Canonical URL
     */
    private function generateCanonicalUrl($slug)
    {
        $langCode = $this->language->getCurrentLanguage();
        
        // Get base URL from server variables
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . $host;
        
        return $baseUrl . '/' . $langCode . '/page/' . $slug;
    }
    
    /**
     * Generate structured data for SEO
     * 
     * @param array $page Page data
     * @return array Structured data
     */
    private function generateStructuredData($page)
    {
        // Get base URL from server variables
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . $host;
        
        // Get site name from server name or use host
        $siteName = $_SERVER['SERVER_NAME'] ?? $host;
        
        $structuredData = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page['title'],
            'description' => $page['meta_description'] ?: $this->generateDescription($page['content']),
            'url' => $this->generateCanonicalUrl($page['slug']),
            'inLanguage' => $this->language->getCurrentLanguage(),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => $siteName,
                'url' => $baseUrl
            ]
        ];
        
        // Add date information if available
        if (!empty($page['updated_at'])) {
            $structuredData['dateModified'] = date('c', strtotime($page['updated_at']));
        }
        
        if (!empty($page['created_at'])) {
            $structuredData['datePublished'] = date('c', strtotime($page['created_at']));
        }
        
        return $structuredData;
    }
    
    /**
     * Get list of pages for navigation
     * 
     * @param array $conditions Additional conditions
     * @return array Pages list
     */
    public function getPagesList($conditions = [])
    {
        $langCode = $this->language->getCurrentLanguage();
        
        // Default conditions - only active pages
        $defaultConditions = ['p.is_active' => 1];
        $conditions = array_merge($defaultConditions, $conditions);
        
        return $this->pageModel->getAllWithDetails(
            $langCode, 
            $conditions, 
            'p.order_number ASC'
        );
    }
    
    /**
     * Search pages by content
     * 
     * @param string $query Search query
     * @param int $limit Number of results
     * @return array Search results
     */
    public function searchPages($query, $limit = 10)
    {
        if (empty($query) || strlen($query) < 3) {
            return [];
        }
        
        $langCode = $this->language->getCurrentLanguage();
        
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':code', $langCode);
        $stmt->execute();
        $langId = $stmt->fetchColumn();
        
        if (!$langId) {
            return [];
        }
        
        // Search in titles and content
        $sql = "SELECT p.*, pd.title, pd.slug, pd.content, pd.meta_title, pd.meta_description
                FROM pages p
                JOIN page_details pd ON p.id = pd.page_id
                WHERE pd.language_id = :langId 
                AND p.is_active = 1
                AND (pd.title LIKE :query OR pd.content LIKE :query)
                ORDER BY 
                    CASE WHEN pd.title LIKE :query THEN 1 ELSE 2 END,
                    p.order_number ASC
                LIMIT :limit";
        
        $searchQuery = '%' . $query . '%';
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':langId', $langId, PDO::PARAM_INT);
        $stmt->bindParam(':query', $searchQuery);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get breadcrumb for page
     * 
     * @param array $page Current page
     * @return array Breadcrumb items
     */
    public function getBreadcrumb($page)
    {
        $langCode = $this->language->getCurrentLanguage();
        
        // Generate base URL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $baseUrl = $protocol . $host;
        
        $breadcrumb = [
            [
                'title' => __('home'),
                'url' => $baseUrl . '/' . $langCode
            ]
        ];
        
        // Add current page
        $breadcrumb[] = [
            'title' => $page['title'],
            'url' => $baseUrl . '/' . $langCode . '/page/' . $page['slug'],
            'active' => true
        ];
        
        return $breadcrumb;
    }
    
    /**
     * Get page by template for special pages
     * 
     * @param string $template Template name
     * @return array|false Page data
     */
    public function getPageByTemplate($template)
    {
        $langCode = $this->language->getCurrentLanguage();
        return $this->pageModel->getByTemplate($template, $langCode);
    }
}