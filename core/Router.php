
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
        
        // Tours routes
        $this->addRoute('tours', 'Tours', 'index');
        $this->addRoute('tours/([a-zA-Z0-9-]+)', 'Tours', 'detail', ['slug']);
        
        // Admin tours routes
        $this->addRoute($this->adminPrefix . '/tours', 'Admin/Tours', 'index');
        $this->addRoute($this->adminPrefix . '/tours/create', 'Admin/Tours', 'create');
        $this->addRoute($this->adminPrefix . '/tours/edit/([0-9]+)', 'Admin/Tours', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/tours/delete/([0-9]+)', 'Admin/Tours', 'delete', ['id']);
        
        // Categories routes
        $this->addRoute($this->adminPrefix . '/categories', 'Admin/Categories', 'index');
        $this->addRoute($this->adminPrefix . '/categories/create', 'Admin/Categories', 'create');
        $this->addRoute($this->adminPrefix . '/categories/edit/([0-9]+)', 'Admin/Categories', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/categories/delete/([0-9]+)', 'Admin/Categories', 'delete', ['id']);
        
        // Booking routes
        $this->addRoute('booking', 'Booking', 'index');
        $this->addRoute('booking/tour/([0-9]+)', 'Booking', 'tour', ['id']);
        $this->addRoute('booking/confirm', 'Booking', 'confirm');
        $this->addRoute('booking/thank-you', 'Booking', 'thankYou');
        
        // Admin booking routes
        $this->addRoute($this->adminPrefix . '/bookings', 'AdminBookings', 'index');
        $this->addRoute($this->adminPrefix . '/bookings/view/([0-9]+)', 'Admin/Bookings', 'view', ['id']);
        $this->addRoute($this->adminPrefix . '/bookings/status/([0-9]+)/([a-z]+)', 'Admin/Bookings', 'updateStatus', ['id', 'status']);
        
        // Gallery routes
        $this->addRoute('gallery', 'Gallery', 'index');
        
        // Admin gallery routes
        $this->addRoute($this->adminPrefix . '/gallery', 'Admin/Gallery', 'index');
        $this->addRoute($this->adminPrefix . '/gallery/create', 'Admin/Gallery', 'create');
        $this->addRoute($this->adminPrefix . '/gallery/edit/([0-9]+)', 'Admin/Gallery', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/gallery/delete/([0-9]+)', 'Admin/Gallery', 'delete', ['id']);
        
        // Contact route
        $this->addRoute('contact', 'Contact', 'index');
        
        // About route
        $this->addRoute('about', 'About', 'index');
        
        // Settings routes
        $this->addRoute($this->adminPrefix . '/settings', 'Admin/Settings', 'index');
        $this->addRoute($this->adminPrefix . '/settings/update', 'Admin/Settings', 'update');
        
        // Language routes
        $this->addRoute($this->adminPrefix . '/languages', 'Admin/Languages', 'index');
        $this->addRoute($this->adminPrefix . '/languages/create', 'Admin/Languages', 'create');
        $this->addRoute($this->adminPrefix . '/languages/edit/([0-9]+)', 'Admin/Languages', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/languages/delete/([0-9]+)', 'Admin/Languages', 'delete', ['id']);
        
        // Translation routes
        $this->addRoute($this->adminPrefix . '/translations', 'Admin/Translations', 'index');
        $this->addRoute($this->adminPrefix . '/translations/edit/([a-z]+)', 'Admin/Translations', 'edit', ['lang']);
        
        // Pages routes
        $this->addRoute('page/([a-zA-Z0-9-]+)', 'Page', 'show', ['slug']);
        
        // Admin pages routes
        $this->addRoute($this->adminPrefix . '/pages', 'Admin/Pages', 'index');
        $this->addRoute($this->adminPrefix . '/pages/create', 'Admin/Pages', 'create');
        $this->addRoute($this->adminPrefix . '/pages/edit/([0-9]+)', 'Admin/Pages', 'edit', ['id']);
        $this->addRoute($this->adminPrefix . '/pages/delete/([0-9]+)', 'Admin/Pages', 'delete', ['id']);
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