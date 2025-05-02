<?php
/**
 * Error Controller
 * 
 * Handles error pages
 */
class ErrorController extends Controller
{
    /**
     * Show error page
     * 
     * @param int $code Error code
     */
    public function index($code = 404)
    {
        // Set HTTP status code
        http_response_code($code);
        
        // Render error view
        $this->render('errors/' . $code, [
            'pageTitle' => __('error') . ' ' . $code
        ]);
    }
    
    /**
     * Get controller name
     * 
     * @return string Controller name
     */
    public function getControllerName()
    {
        return 'Error';
    }
    
    /**
     * Get action name
     * 
     * @return string Action name
     */
    public function getActionName()
    {
        return 'index';
    }
}