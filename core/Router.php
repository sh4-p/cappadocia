<?php
/**
 * Router Class
 * 
 * Handles URL routing and mapping to controllers and actions
 */
class Router
{
    protected $routes = [];
    protected $adminPrefix = 'admin';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->initializeRoutes();
    }

    /**
     * Initialize default routes
     */
    private function initializeRoutes()
    {
        // Default route
        $this->addRoute('', 'Home', 'index');
        
        // Admin routes
        $this->addRoute($this->adminPrefix, 'Admin', 'index');
        $this->addRoute($this->adminPrefix . '/login', 'Admin', 'login');
        $this->addRoute($this->adminPrefix . '/logout', 'Admin', 'logout');
        $this->addRoute($this->adminPrefix . '/dashboard', 'Admin', 'dashboard');
        $this->addRoute($this->adminPrefix . '/profile', 'Admin', 'profile');
        
        // Tours routes
        $this->addRoute('tours', 'Tours', 'index');
        $this->addRoute('tours/([a-zA-Z0-9-]+)', 'Tours', 'detail', ['slug']);
        $this->addRoute('tours/search', 'Tours', 'search');
        $this->addRoute('tours/ajax-search', 'Tours', 'ajaxSearch');
        
        // Admin tours routes
        $this->addRoute($this->adminPrefix . '/tours', 'AdminTours', 'index');
        $this->addRoute($this->adminPrefix . '/tours/create', 'AdminTours', 'create');
        $this->addRoute($this->adminPrefix . '/tours/edit/([0-9]+)', 'AdminTours', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/tours/delete/([0-9]+)', 'AdminTours', 'delete', ['id']);
        $this->addRoute($this->adminPrefix . '/tours/toggle-status/([0-9]+)', 'AdminTours', 'toggleStatus', ['id']);
        $this->addRoute($this->adminPrefix . '/tours/toggle-featured/([0-9]+)', 'AdminTours', 'toggleFeatured', ['id']);
        
        // Categories routes
        $this->addRoute($this->adminPrefix . '/categories', 'AdminCategories', 'index');
        $this->addRoute($this->adminPrefix . '/categories/create', 'AdminCategories', 'create');
        $this->addRoute($this->adminPrefix . '/categories/edit/([0-9]+)', 'AdminCategories', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/categories/delete/([0-9]+)', 'AdminCategories', 'delete', ['id']);
        $this->addRoute($this->adminPrefix . '/categories/toggle-status/([0-9]+)', 'AdminCategories', 'toggleStatus', ['id']);
        $this->addRoute($this->adminPrefix . '/categories/update-order', 'AdminCategories', 'updateOrder');
        
        // Booking routes - UPDATED WITH TRACKING
        $this->addRoute('booking', 'Booking', 'index');
        $this->addRoute('booking/tour/([0-9]+)', 'Booking', 'tour', ['id']);
        $this->addRoute('booking/confirm', 'Booking', 'confirm');
        $this->addRoute('booking/thank-you', 'Booking', 'thankYou');
        $this->addRoute('booking/search', 'Booking', 'search');                                    // NEW: Search booking page
        $this->addRoute('booking/track/([A-Z0-9]{16})', 'Booking', 'track', ['token']);           // NEW: Track booking by token (must come before general track route)
        $this->addRoute('booking/track/?.*', 'Booking', 'trackRedirect');                         // NEW: Redirect to search if no token or invalid token

        // Newsletter subscription routes
        $this->addRoute('newsletter/subscribe', 'Newsletter', 'subscribe');
        $this->addRoute('newsletter/ajax-subscribe', 'Newsletter', 'ajaxSubscribe');
        $this->addRoute('newsletter/confirm/([a-zA-Z0-9]{64})', 'Newsletter', 'confirm', ['token']);
        $this->addRoute('newsletter/unsubscribe/([a-zA-Z0-9]{64})', 'Newsletter', 'unsubscribe', ['token']);

        // Admin newsletter routes
        $this->addRoute($this->adminPrefix . '/newsletter', 'AdminNewsletter', 'index');
        $this->addRoute($this->adminPrefix . '/newsletter/subscribers', 'AdminNewsletter', 'subscribers');
        $this->addRoute($this->adminPrefix . '/newsletter/add-subscriber', 'AdminNewsletter', 'addSubscriber');
        $this->addRoute($this->adminPrefix . '/newsletter/edit-subscriber/([0-9]+)', 'AdminNewsletter', 'editSubscriber', ['id']);
        $this->addRoute($this->adminPrefix . '/newsletter/delete-subscriber/([0-9]+)', 'AdminNewsletter', 'deleteSubscriber', ['id']);
        $this->addRoute($this->adminPrefix . '/newsletter/bulk-action', 'AdminNewsletter', 'bulkAction');
        $this->addRoute($this->adminPrefix . '/newsletter/export-subscribers', 'AdminNewsletter', 'exportSubscribers');
        $this->addRoute($this->adminPrefix . '/newsletter/import-subscribers', 'AdminNewsletter', 'importSubscribers');
        $this->addRoute($this->adminPrefix . '/newsletter/campaigns', 'AdminNewsletter', 'campaigns');
        $this->addRoute($this->adminPrefix . '/newsletter/create-campaign', 'AdminNewsletter', 'createCampaign');
        $this->addRoute($this->adminPrefix . '/newsletter/send-campaign/([0-9]+)', 'AdminNewsletter', 'sendCampaign', ['id']);
        $this->addRoute($this->adminPrefix . '/newsletter/clean-inactive', 'AdminNewsletter', 'cleanInactive');
        
        // Admin booking routes
        $this->addRoute($this->adminPrefix . '/bookings', 'AdminBookings', 'index');
        $this->addRoute($this->adminPrefix . '/bookings/create', 'AdminBookings', 'create');
        $this->addRoute($this->adminPrefix . '/bookings/view/([0-9]+)', 'AdminBookings', 'view', ['id']);
        $this->addRoute($this->adminPrefix . '/bookings/status/([0-9]+)/([a-z]+)', 'AdminBookings', 'status', ['id', 'status']);
        $this->addRoute($this->adminPrefix . '/bookings/list-by-customer/([^/]+)', 'AdminBookings', 'listByCustomer', ['email']);
        $this->addRoute($this->adminPrefix . '/bookings/get-tour-price/([0-9]+)', 'AdminBookings', 'getTourPrice', ['id']);
        $this->addRoute($this->adminPrefix . '/bookings/export', 'AdminBookings', 'export');
        
        // Gallery routes
        $this->addRoute('gallery', 'Gallery', 'index');
        $this->addRoute('gallery/tour/([0-9]+)', 'Gallery', 'tour', ['id']);
        $this->addRoute('gallery/ajax-load', 'Gallery', 'ajaxLoad');
        
        // Admin gallery routes
        $this->addRoute($this->adminPrefix . '/gallery', 'AdminGallery', 'index');
        $this->addRoute($this->adminPrefix . '/gallery/create', 'AdminGallery', 'create');
        $this->addRoute($this->adminPrefix . '/gallery/edit/([0-9]+)', 'AdminGallery', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/gallery/delete/([0-9]+)', 'AdminGallery', 'delete', ['id']);
        $this->addRoute($this->adminPrefix . '/gallery/toggle-status/([0-9]+)', 'AdminGallery', 'toggleStatus', ['id']);
        
        // Contact route
        $this->addRoute('contact', 'Contact', 'index');
        
        // About route
        $this->addRoute('about', 'About', 'index');
        
        // Settings routes
        $this->addRoute($this->adminPrefix . '/settings', 'AdminSettings', 'index');
        $this->addRoute($this->adminPrefix . '/settings/update', 'AdminSettings', 'update');
        $this->addRoute($this->adminPrefix . '/settings/test-email', 'AdminSettings', 'testEmail');
        
        // Language routes
        $this->addRoute($this->adminPrefix . '/languages', 'AdminLanguages', 'index');
        $this->addRoute($this->adminPrefix . '/languages/create', 'AdminLanguages', 'create');
        $this->addRoute($this->adminPrefix . '/languages/edit/([0-9]+)', 'AdminLanguages', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/languages/delete/([0-9]+)', 'AdminLanguages', 'delete', ['id']);
        $this->addRoute($this->adminPrefix . '/languages/set-default/([0-9]+)', 'AdminLanguages', 'setDefault', ['id']);
        $this->addRoute($this->adminPrefix . '/languages/toggle-status/([0-9]+)', 'AdminLanguages', 'toggleStatus', ['id']);
        
        // Translation routes
        $this->addRoute($this->adminPrefix . '/translations', 'AdminTranslations', 'index');
        $this->addRoute($this->adminPrefix . '/translations/edit/([a-z]+)', 'AdminTranslations', 'edit', ['lang']);
        $this->addRoute($this->adminPrefix . '/translations/add-key', 'AdminTranslations', 'addKey');
        $this->addRoute($this->adminPrefix . '/translations/delete-key/([0-9]+)', 'AdminTranslations', 'deleteKey', ['keyId']);
        $this->addRoute($this->adminPrefix . '/translations/import', 'AdminTranslations', 'import');
        $this->addRoute($this->adminPrefix . '/translations/export/([0-9]+)', 'AdminTranslations', 'export', ['languageId']);

        // Admin email templates routes
        $this->addRoute($this->adminPrefix . '/email-templates', 'AdminEmailTemplates', 'index');
        $this->addRoute($this->adminPrefix . '/email-templates/create', 'AdminEmailTemplates', 'create');
        $this->addRoute($this->adminPrefix . '/email-templates/edit/([0-9]+)', 'AdminEmailTemplates', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/email-templates/view/([0-9]+)', 'AdminEmailTemplates', 'view', ['id']);
        $this->addRoute($this->adminPrefix . '/email-templates/delete/([0-9]+)', 'AdminEmailTemplates', 'delete', ['id']);
        $this->addRoute($this->adminPrefix . '/email-templates/toggle-status/([0-9]+)', 'AdminEmailTemplates', 'toggleStatus', ['id']);
        $this->addRoute($this->adminPrefix . '/email-templates/preview/([0-9]+)', 'AdminEmailTemplates', 'preview', ['id']);
        $this->addRoute($this->adminPrefix . '/email-templates/test-send/([0-9]+)', 'AdminEmailTemplates', 'testSend', ['id']);
        
        // Pages routes
        $this->addRoute('page/([a-zA-Z0-9-]+)', 'Page', 'show', ['slug']);
        
        // Admin pages routes
        $this->addRoute($this->adminPrefix . '/pages', 'AdminPages', 'index');
        $this->addRoute($this->adminPrefix . '/pages/create', 'AdminPages', 'create');
        $this->addRoute($this->adminPrefix . '/pages/edit/([0-9]+)', 'AdminPages', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/pages/delete/([0-9]+)', 'AdminPages', 'delete', ['id']);
        $this->addRoute($this->adminPrefix . '/pages/toggle-status/([0-9]+)', 'AdminPages', 'toggleStatus', ['id']);
        $this->addRoute($this->adminPrefix . '/pages/update-order', 'AdminPages', 'updateOrder');

        // Admin testimonials routes
        $this->addRoute($this->adminPrefix . '/testimonials', 'AdminTestimonials', 'index');
        $this->addRoute($this->adminPrefix . '/testimonials/create', 'AdminTestimonials', 'create');
        $this->addRoute($this->adminPrefix . '/testimonials/edit/([0-9]+)', 'AdminTestimonials', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/testimonials/delete/([0-9]+)', 'AdminTestimonials', 'delete', ['id']);
        $this->addRoute($this->adminPrefix . '/testimonials/toggle-status/([0-9]+)', 'AdminTestimonials', 'toggleStatus', ['id']);
        
        // Admin users routes
        $this->addRoute($this->adminPrefix . '/users', 'AdminUsers', 'index');
        $this->addRoute($this->adminPrefix . '/users/create', 'AdminUsers', 'create');
        $this->addRoute($this->adminPrefix . '/users/edit/([0-9]+)', 'AdminUsers', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/users/delete/([0-9]+)', 'AdminUsers', 'delete', ['id']);
        $this->addRoute($this->adminPrefix . '/users/toggle-status/([0-9]+)', 'AdminUsers', 'toggleStatus', ['id']);
        $this->addRoute($this->adminPrefix . '/users/remove-avatar/([0-9]+)', 'AdminUsers', 'removeAvatar', ['id']);
        
        // Error routes
        $this->addRoute('error', 'Error', 'index');
        $this->addRoute('error/([0-9]+)', 'Error', 'index', ['code']);
        
        // Language switcher route
        $this->addRoute('language/([a-z]+)', 'Home', 'switchLanguage', ['lang']);
    }

    /**
     * Add a route
     * 
     * @param string $route Route pattern
     * @param string $controller Controller name
     * @param string $action Action name
     * @param array $params Parameter names
     */
    public function addRoute($route, $controller, $action, $params = [])
    {
        $this->routes[$route] = [
            'controller' => $controller,
            'action' => $action,
            'params' => $params
        ];
    }

    /**
     * Route the request
     * 
     * @param array $url URL parts
     * @return array Route information
     */
    public function route($url)
    {
        // Default route data
        $routeData = [
            'controller' => DEFAULT_CONTROLLER,
            'action' => DEFAULT_ACTION,
            'params' => []
        ];
        
        // If URL is empty, return default route
        if (empty($url)) {
            return $routeData;
        }
        
        // Convert URL parts to string for matching
        $urlPath = implode('/', $url);
        
        // Direct match
        if (isset($this->routes[$urlPath])) {
            return $this->routes[$urlPath];
        }
        
        // Pattern matching
        foreach ($this->routes as $pattern => $route) {
            // Skip empty patterns
            if (empty($pattern)) {
                continue;
            }
            
            // Prepare pattern for regex
            $pattern = str_replace('/', '\/', $pattern);
            
            // Match route pattern
            if (preg_match('/^' . $pattern . '$/', $urlPath, $matches)) {
                // Remove full match
                array_shift($matches);
                
                // Extract params
                $params = [];
                if (!empty($route['params'])) {
                    foreach ($route['params'] as $index => $name) {
                        $params[$name] = $matches[$index] ?? null;
                    }
                }
                
                // Set route data
                $routeData = [
                    'controller' => $route['controller'],
                    'action' => $route['action'],
                    'params' => $params
                ];
                
                return $routeData;
            }
        }
        
        // If no match found in specific routes, try to use first part as controller and second as action
        if (count($url) >= 2) {
            $routeData['controller'] = ucfirst($url[0]);
            $routeData['action'] = $url[1];
            $routeData['params'] = array_slice($url, 2);
        } else {
            $routeData['controller'] = ucfirst($url[0]);
        }
        
        return $routeData;
    }
}