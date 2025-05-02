
<?php
/**
 * View Class
 * 
 * Handles view rendering
 */
class View
{
    /**
     * Render a view
     * 
     * @param string $view View name
     * @param array $data Data to pass to the view
     * @param string $layout Layout name
     */
    public function render($view, $data = [], $layout = 'default')
    {
        // Extract data to make it available in view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include view file
        $viewFile = $this->getViewFile($view);
        
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            // View not found, show error message
            echo 'View file not found: ' . $view;
        }
        
        // Get view content
        $viewContent = ob_get_clean();
        
        // Load layout if specified
        if ($layout) {
            $layoutFile = $this->getLayoutFile($layout);
            
            if (file_exists($layoutFile)) {
                // Start output buffering for layout
                ob_start();
                
                // Make view content available in layout as $content
                $content = $viewContent;
                
                // Include layout file
                include $layoutFile;
                
                // Output final content
                echo ob_get_clean();
            } else {
                // Layout not found, show view content
                echo $viewContent;
            }
        } else {
            // No layout, show view content
            echo $viewContent;
        }
    }
    
    /**
     * Get view file path
     * 
     * @param string $view View name
     * @return string View file path
     */
    private function getViewFile($view)
    {
        // Split view name into controller and action
        $parts = explode('/', $view);
        
        // Handle admin views
        if (count($parts) >= 3 && $parts[0] == 'admin') {
            return BASE_PATH . '/app/views/admin/' . $parts[1] . '/' . $parts[2] . '.php';
        }
        
        // Handle regular views
        if (count($parts) == 2) {
            return BASE_PATH . '/app/views/' . $parts[0] . '/' . $parts[1] . '.php';
        }
        
        return BASE_PATH . '/app/views/' . $view . '.php';
    }
    
    /**
     * Get layout file path
     * 
     * @param string $layout Layout name
     * @return string Layout file path
     */
    private function getLayoutFile($layout)
    {
        return BASE_PATH . '/app/views/layouts/' . $layout . '.php';
    }
    
    /**
     * Render a partial view
     * 
     * @param string $partial Partial view name
     * @param array $data Data to pass to the partial
     */
    public function renderPartial($partial, $data = [])
    {
        // Extract data to make it available in partial
        extract($data);
        
        // Include partial file
        $partialFile = BASE_PATH . '/app/views/partials/' . $partial . '.php';
        
        if (file_exists($partialFile)) {
            include $partialFile;
        } else {
            // Partial not found, show error message
            echo 'Partial file not found: ' . $partial;
        }
    }
    
    /**
     * Translate text
     * 
     * @param string $key Translation key
     * @param array $data Replacement data
     * @return string Translated text
     */
    public function translate($key, $data = [])
    {
        // Get translation from data
        $translation = $GLOBALS['translations'][$key] ?? $key;
        
        // Replace placeholders with data
        if (!empty($data)) {
            foreach ($data as $placeholder => $value) {
                $translation = str_replace('{' . $placeholder . '}', $value, $translation);
            }
        }
        
        return $translation;
    }
}