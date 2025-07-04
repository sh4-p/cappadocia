<?php
/**
 * Admin Controller
 * 
 * Handles admin panel authentication and dashboard
 */
class AdminController extends Controller
{
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Require login for all admin actions except login
        if ($this->getActionName() != 'login') {
            $this->requireLogin();
        }
    }
    
    /**
     * Index action - redirect to dashboard
     */
    public function index()
    {
        $this->redirect('admin/dashboard');
    }
    
    /**
     * Login action
     */
    public function login()
    {
        // Check if already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('admin/dashboard');
        }
        
        // Handle login form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->post('username');
            $password = $this->post('password');
            $rememberMe = $this->post('remember_me');
            
            // Validate inputs
            if (empty($username) || empty($password)) {
                $this->session->setFlash('error', __('username_password_required'));
            } else {
                // Get user from database
                $userModel = $this->loadModel('User');
                $user = $userModel->getByUsername($username);
                
                if ($user && password_verify($password, $user['password'])) {
                    // Check if user is active
                    if ($user['is_active']) {
                        // Set session
                        $this->session->set('user_id', $user['id']);
                        $this->session->set('user_username', $user['username']);
                        $this->session->set('user_email', $user['email']);
                        $this->session->set('user_first_name', $user['first_name']);
                        $this->session->set('user_last_name', $user['last_name']);
                        $this->session->set('user_role', $user['role']);
                        
                        // Set remember me cookie
                        if ($rememberMe) {
                            $token = bin2hex(random_bytes(32));
                            $expires = time() + (30 * 24 * 60 * 60); // 30 days
                            
                            // Save token in database
                            $userModel->saveRememberToken($user['id'], $token, $expires);
                            
                            // Set cookie
                            setcookie('remember_token', $token, $expires, '/', '', false, true);
                        }
                        
                        // Redirect to dashboard
                        $this->redirect('admin/dashboard');
                    } else {
                        // User is not active
                        $this->session->setFlash('error', __('account_inactive'));
                    }
                } else {
                    // Invalid username or password
                    $this->session->setFlash('error', __('invalid_credentials'));
                }
            }
        }
        
        // Render login view
        $this->render('admin/login', [
            'pageTitle' => __('login')
        ], 'admin-login');
    }
    
    /**
     * Logout action
     */
    public function logout()
    {
        // Clear session
        $this->session->remove('user_id');
        $this->session->remove('user_username');
        $this->session->remove('user_email');
        $this->session->remove('user_first_name');
        $this->session->remove('user_last_name');
        $this->session->remove('user_role');
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            // Remove token from database
            $userModel = $this->loadModel('User');
            $userModel->removeRememberToken($_COOKIE['remember_token']);
            
            // Remove cookie
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
        
        // Redirect to login page
        $this->redirect('admin/login');
    }
    
    /**
     * Dashboard action
     */
    public function dashboard()
    {
        // Load models
        $tourModel = $this->loadModel('Tour');
        $bookingModel = $this->loadModel('Booking');
        $categoryModel = $this->loadModel('Category');
        $userModel = $this->loadModel('User');
        
        // Get statistics
        $totalTours = $tourModel->countTours();
        $totalBookings = $bookingModel->countBookings();
        $totalCategories = $categoryModel->countCategories();
        $totalUsers = $userModel->countUsers();
        
        // Get recent bookings
        $recentBookings = $bookingModel->getRecentBookings(5);
        
        // Get sales data for chart
        $salesData = $bookingModel->getSalesData(30);
        
        // Get page data
        $data = [
            'pageTitle' => __('dashboard'),
            'totalTours' => $totalTours,
            'totalBookings' => $totalBookings,
            'totalCategories' => $totalCategories,
            'totalUsers' => $totalUsers,
            'recentBookings' => $recentBookings,
            'salesData' => $salesData
        ];
        
        // Render dashboard view
        $this->render('admin/dashboard', $data, 'admin');
    }
    
    /**
     * Profile action
     */
    public function profile()
    {
        // Load user model
        $userModel = $this->loadModel('User');
        
        // Get user data
        $userId = $this->session->get('user_id');
        $user = $userModel->getById($userId);
        
        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $firstName = $this->post('first_name');
            $lastName = $this->post('last_name');
            $email = $this->post('email');
            $currentPassword = $this->post('current_password');
            $newPassword = $this->post('new_password');
            $confirmPassword = $this->post('confirm_password');
            
            // Validate inputs
            $errors = [];
            
            if (empty($firstName)) {
                $errors[] = __('first_name_required');
            }
            
            if (empty($lastName)) {
                $errors[] = __('last_name_required');
            }
            
            if (empty($email)) {
                $errors[] = __('email_required');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = __('invalid_email');
            } elseif ($email !== $user['email'] && $userModel->emailExists($email)) {
                $errors[] = __('email_exists');
            }
            
            // Check if password is being changed
            if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
                if (empty($currentPassword)) {
                    $errors[] = __('current_password_required');
                } elseif (!password_verify($currentPassword, $user['password'])) {
                    $errors[] = __('current_password_incorrect');
                }
                
                if (empty($newPassword)) {
                    $errors[] = __('new_password_required');
                } elseif (strlen($newPassword) < 6) {
                    $errors[] = __('password_too_short');
                }
                
                if (empty($confirmPassword)) {
                    $errors[] = __('confirm_password_required');
                } elseif ($newPassword !== $confirmPassword) {
                    $errors[] = __('passwords_dont_match');
                }
            }
            
            // Update user if no errors
            if (empty($errors)) {
                $userData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email
                ];
                
                // Update password if being changed
                if (!empty($newPassword)) {
                    $userData['password'] = password_hash($newPassword, PASSWORD_DEFAULT);
                }
                
                // Update user
                $result = $userModel->update($userData, ['id' => $userId]);
                
                if ($result) {
                    // Update session data
                    $this->session->set('user_email', $email);
                    $this->session->set('user_first_name', $firstName);
                    $this->session->set('user_last_name', $lastName);
                    
                    // Set success message
                    $this->session->setFlash('success', __('profile_updated'));
                    
                    // Redirect to profile page
                    $this->redirect('admin/profile');
                } else {
                    $this->session->setFlash('error', __('profile_update_failed'));
                }
            } else {
                // Set error message
                $this->session->setFlash('error', implode('<br>', $errors));
            }
        }
        
        // Render profile view
        $this->render('admin/profile', [
            'pageTitle' => __('profile'),
            'user' => $user
        ], 'admin');
    }
    
    /**
     * Get controller name
     * 
     * @return string Controller name
     */
    public function getControllerName()
    {
        $class = get_class($this);
        return str_replace('Controller', '', $class);
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
}