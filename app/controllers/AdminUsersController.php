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
            $confirmPassword = $this->post('password_confirm');
            $email = $this->post('email');
            $firstName = $this->post('first_name');
            $lastName = $this->post('last_name');
            $role = $this->post('role');
            $isActive = $this->post('is_active', 0);
            
            // Validate inputs
            $errors = [];
            
            if (empty($username)) {
                $errors[] = __('username_required');
            } elseif ($userModel->usernameExists($username)) {
                $errors[] = __('username_exists');
            }
            
            if (empty($password)) {
                $errors[] = __('password_required');
            } elseif (strlen($password) < 6) {
                $errors[] = __('password_too_short');
            }
            
            if ($password !== $confirmPassword) {
                $errors[] = __('passwords_dont_match');
            }
            
            if (empty($email)) {
                $errors[] = __('email_required');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = __('invalid_email');
            } elseif ($userModel->emailExists($email)) {
                $errors[] = __('email_exists');
            }
            
            if (empty($firstName)) {
                $errors[] = __('first_name_required');
            }
            
            if (empty($lastName)) {
                $errors[] = __('last_name_required');
            }
            
            // Handle avatar upload
            $avatar = $this->file('avatar');
            $avatarName = null;
            
            if ($avatar && $avatar['error'] === UPLOAD_ERR_OK) {
                $avatarName = $this->uploadAvatar($avatar);
                
                if (!$avatarName) {
                    $errors[] = __('avatar_upload_failed');
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again
                $this->render('admin/users/create', [
                    'pageTitle' => __('add_user'),
                    'roles' => $roles
                ], 'admin');
                
                return;
            }
            
            // Prepare user data
            $userData = [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'role' => $role,
                'avatar' => $avatarName,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Add user
            $userId = $userModel->addUser($userData);
            
            if ($userId) {
                $this->session->setFlash('success', __('user_added'));
                $this->redirect('admin/users');
            } else {
                $this->session->setFlash('error', __('user_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/users/create', [
            'pageTitle' => __('add_user'),
            'roles' => $roles
        ], 'admin');
    }
    
    /**
     * Edit action - edit a user
     * 
     * @param int $id User ID
     */
    public function edit($id)
    {
        // Load user model
        $userModel = $this->loadModel('User');
        
        // Get user
        $user = $userModel->getById($id);
        
        // Check if user exists
        if (!$user) {
            $this->session->setFlash('error', __('user_not_found'));
            $this->redirect('admin/users');
        }
        
        // Get available roles
        $roles = $userModel->getRoles();
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $username = $this->post('username');
            $password = $this->post('password');
            $confirmPassword = $this->post('password_confirm');
            $email = $this->post('email');
            $firstName = $this->post('first_name');
            $lastName = $this->post('last_name');
            $role = $this->post('role');
            $isActive = $this->post('is_active', 0);
            
            // Validate inputs
            $errors = [];
            
            if (empty($username)) {
                $errors[] = __('username_required');
            } elseif ($userModel->usernameExists($username, $id)) {
                $errors[] = __('username_exists');
            }
            
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $errors[] = __('password_too_short');
                }
                
                if ($password !== $confirmPassword) {
                    $errors[] = __('passwords_dont_match');
                }
            }
            
            if (empty($email)) {
                $errors[] = __('email_required');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = __('invalid_email');
            } elseif ($userModel->emailExists($email, $id)) {
                $errors[] = __('email_exists');
            }
            
            if (empty($firstName)) {
                $errors[] = __('first_name_required');
            }
            
            if (empty($lastName)) {
                $errors[] = __('last_name_required');
            }
            
            // Don't allow deactivating own account
            if ($id == $this->session->get('user_id') && !$isActive) {
                $errors[] = __('cannot_deactivate_own_account');
            }
            
            // Don't allow changing own role
            if ($id == $this->session->get('user_id') && $role != $user['role']) {
                $errors[] = __('cannot_change_own_role');
            }
            
            // Handle avatar upload
            $avatar = $this->file('avatar');
            $avatarName = $user['avatar'];
            
            if ($avatar && $avatar['error'] === UPLOAD_ERR_OK) {
                $avatarName = $this->uploadAvatar($avatar);
                
                if (!$avatarName) {
                    $errors[] = __('avatar_upload_failed');
                } else {
                    // Delete old avatar if exists
                    $this->deleteAvatar($user['avatar']);
                }
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again
                $this->render('admin/users/edit', [
                    'pageTitle' => __('edit_user'),
                    'user' => $user,
                    'roles' => $roles
                ], 'admin');
                
                return;
            }
            
            // Prepare user data
            $userData = [
                'username' => $username,
                'email' => $email,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'avatar' => $avatarName,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Add password if provided
            if (!empty($password)) {
                $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            
            // Add role if not own account
            if ($id != $this->session->get('user_id')) {
                $userData['role'] = $role;
            }
            
            // Update user
            $result = $userModel->updateUser($id, $userData);
            
            if ($result) {
                $this->session->setFlash('success', __('user_updated'));
                $this->redirect('admin/users');
            } else {
                $this->session->setFlash('error', __('user_update_failed'));
            }
        }
        
        // Render view
        $this->render('admin/users/edit', [
            'pageTitle' => __('edit_user'),
            'user' => $user,
            'roles' => $roles
        ], 'admin');
    }
    
    /**
     * Delete action - delete a user
     * 
     * @param int $id User ID
     */
    public function delete($id)
    {
        // Load user model
        $userModel = $this->loadModel('User');
        
        // Get user
        $user = $userModel->getById($id);
        
        // Check if user exists
        if (!$user) {
            $this->session->setFlash('error', __('user_not_found'));
            $this->redirect('admin/users');
        }
        
        // Don't allow deleting own account
        if ($id == $this->session->get('user_id')) {
            $this->session->setFlash('error', __('cannot_delete_own_account'));
            $this->redirect('admin/users');
            return;
        }
        
        // Delete user
        $result = $userModel->deleteUser($id);
        
        if ($result) {
            // Delete user avatar if exists
            $this->deleteAvatar($user['avatar']);
            
            $this->session->setFlash('success', __('user_deleted'));
        } else {
            $this->session->setFlash('error', __('user_delete_failed'));
        }
        
        // Redirect to users list
        $this->redirect('admin/users');
    }
    
    /**
     * Toggle status action
     * 
     * @param int $id User ID
     */
    public function toggleStatus($id)
    {
        // Load user model
        $userModel = $this->loadModel('User');
        
        // Get user
        $user = $userModel->getById($id);
        
        // Check if user exists
        if (!$user) {
            $this->session->setFlash('error', __('user_not_found'));
            $this->redirect('admin/users');
        }
        
        // Don't allow deactivating own account
        if ($id == $this->session->get('user_id') && $user['is_active']) {
            $this->session->setFlash('error', __('cannot_deactivate_own_account'));
            $this->redirect('admin/users');
            return;
        }
        
        // Toggle status
        $newStatus = $user['is_active'] ? 0 : 1;
        $result = $userModel->updateStatus($id, $newStatus);
        
        if ($result) {
            $statusText = $newStatus ? __('activated') : __('deactivated');
            $this->session->setFlash('success', __('user') . ' ' . $statusText);
        } else {
            $this->session->setFlash('error', __('status_update_failed'));
        }
        
        // Redirect to users list
        $this->redirect('admin/users');
    }
    
    /**
     * Remove avatar action
     * 
     * @param int $id User ID
     */
    public function removeAvatar($id)
    {
        // Load user model
        $userModel = $this->loadModel('User');
        
        // Get user
        $user = $userModel->getById($id);
        
        // Check if user exists
        if (!$user) {
            $this->session->setFlash('error', __('user_not_found'));
            $this->redirect('admin/users');
        }
        
        // Check if user has avatar
        if (!$user['avatar']) {
            $this->session->setFlash('error', __('no_avatar'));
            $this->redirect('admin/users/edit/' . $id);
            return;
        }
        
        // Delete avatar file
        $this->deleteAvatar($user['avatar']);
        
        // Update user
        $result = $userModel->update(['avatar' => null], ['id' => $id]);
        
        if ($result) {
            $this->session->setFlash('success', __('avatar_removed'));
        } else {
            $this->session->setFlash('error', __('avatar_remove_failed'));
        }
        
        // Redirect to edit user page
        $this->redirect('admin/users/edit/' . $id);
    }
    
    /**
     * Upload avatar
     * 
     * @param array $file Uploaded file
     * @return string|false File name or false
     */
    private function uploadAvatar($file)
    {
        // Check if file exists
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        // Get file info
        $fileInfo = pathinfo($file['name']);
        $fileName = strtolower(str_replace(' ', '-', $fileInfo['filename']));
        $fileName = preg_replace('/[^a-z0-9\-]/', '', $fileName);
        $fileName = $fileName . '-' . uniqid() . '.' . strtolower($fileInfo['extension']);
        
        // Check file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (!in_array(strtolower($fileInfo['extension']), $allowedTypes)) {
            return false;
        }
        
        // Create upload directory if not exists
        $uploadDir = BASE_PATH . '/public/uploads/avatars/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Upload file
        if (move_uploaded_file($file['tmp_name'], $uploadDir . $fileName)) {
            return $fileName;
        }
        
        return false;
    }
    
    /**
     * Delete avatar
     * 
     * @param string $fileName File name
     * @return bool Success
     */
    private function deleteAvatar($fileName)
    {
        if (!$fileName) {
            return false;
        }
        
        $filePath = BASE_PATH . '/public/uploads/avatars/' . $fileName;
        
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        
        return false;
    }
}