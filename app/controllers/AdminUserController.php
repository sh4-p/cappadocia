<?php
/**
 * Admin Users Controller
 * 
 * Handles users management in admin panel
 */
class AdminUsersController extends Controller
{
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Require login
        $this->requireLogin();
    }
    
    /**
     * Get controller name
     * 
     * @return string Controller name
     */
    public function getControllerName()
    {
        return 'AdminUsers';
    }
    
    /**
     * Get action name
     * 
     * @return string Action name
     */
    public function getActionName()
    {
        $action = '';
        
        if (isset($_GET['url'])) {
            $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            
            if (count($url) >= 2) {
                $action = $url[1];
            }
        }
        
        return $action ?: 'index';
    }
    
    /**
     * Index action - list all users
     */
    public function index()
    {
        // Load user model
        $userModel = $this->loadModel('User');
        
        // Get all users
        $users = $userModel->getAllUsers();
        
        // Render view
        $this->render('admin/users/index', [
            'pageTitle' => __('users'),
            'users' => $users
        ], 'admin');
    }
    
    /**
     * Create action - create a new user
     */
    public function create()
    {
        // Load user model
        $userModel = $this->loadModel('User');
        
        // Get available roles
        $roles = $userModel->getRoles();
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $username = $this->post('username');
            $password = $this->post('password');