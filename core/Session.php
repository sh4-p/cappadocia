<?php
/**
 * Session Class
 * 
 * Handles session management
 */
class Session
{
    private $prefix;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Set session prefix
        $this->prefix = SESSION_PREFIX;
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            // Set session parameters
            $params = session_get_cookie_params();
            session_set_cookie_params(
                SESSION_LIFETIME,
                $params['path'],
                $params['domain'],
                isset($_SERVER['HTTPS']),
                true
            );
            
            // Start session
            session_start();
        }
    }
    
    /**
     * Set session variable
     * 
     * @param string $key Session key
     * @param mixed $value Session value
     */
    public function set($key, $value)
    {
        $_SESSION[$this->prefix . $key] = $value;
    }
    
    /**
     * Get session variable
     * 
     * @param string $key Session key
     * @param mixed $default Default value if key not found
     * @return mixed Session value
     */
    public function get($key, $default = null)
    {
        return isset($_SESSION[$this->prefix . $key]) ? $_SESSION[$this->prefix . $key] : $default;
    }
    
    /**
     * Check if session variable exists
     * 
     * @param string $key Session key
     * @return bool Exists
     */
    public function has($key)
    {
        return isset($_SESSION[$this->prefix . $key]);
    }
    
    /**
     * Remove session variable
     * 
     * @param string $key Session key
     */
    public function remove($key)
    {
        if (isset($_SESSION[$this->prefix . $key])) {
            unset($_SESSION[$this->prefix . $key]);
        }
    }
    
    /**
     * Clear all session variables
     */
    public function clear()
    {
        // Only clear variables with our prefix
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, $this->prefix) === 0) {
                unset($_SESSION[$key]);
            }
        }
    }
    
    /**
     * Destroy session
     */
    public function destroy()
    {
        // Clear session data
        $this->clear();
        
        // Destroy session
        session_destroy();
        
        // Delete session cookie
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    
    /**
     * Regenerate session ID
     * 
     * @param bool $deleteOldSession Delete old session
     */
    public function regenerate($deleteOldSession = true)
    {
        session_regenerate_id($deleteOldSession);
    }
    
    /**
     * Set flash message
     * 
     * @param string $key Flash key
     * @param mixed $value Flash value
     */
    public function setFlash($key, $value)
    {
        $this->set('flash_' . $key, $value);
    }
    
    /**
     * Get flash message
     * 
     * @param string $key Flash key
     * @param mixed $default Default value if key not found
     * @return mixed Flash value
     */
    public function getFlash($key, $default = null)
    {
        $value = $this->get('flash_' . $key, $default);
        $this->remove('flash_' . $key);
        
        return $value;
    }
    
    /**
     * Check if flash message exists
     * 
     * @param string $key Flash key
     * @return bool Exists
     */
    public function hasFlash($key)
    {
        return $this->has('flash_' . $key);
    }
    
    /**
     * Get CSRF token
     * 
     * @return string CSRF token
     */
    public function getCsrfToken()
    {
        // Get existing token or create new one
        $token = $this->get('csrf_token');
        
        if (!$token) {
            $token = bin2hex(random_bytes(32));
            $this->set('csrf_token', $token);
        }
        
        return $token;
    }
    
    /**
     * Verify CSRF token
     * 
     * @param string $token CSRF token to verify
     * @return bool Is valid
     */
    public function verifyCsrfToken($token)
    {
        $sessionToken = $this->get('csrf_token');
        
        if ($sessionToken && $token && hash_equals($sessionToken, $token)) {
            // Regenerate token for next request
            $newToken = bin2hex(random_bytes(32));
            $this->set('csrf_token', $newToken);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Get session ID
     * 
     * @return string Session ID
     */
    public function getId()
    {
        return session_id();
    }
    
    /**
     * Set session ID
     * 
     * @param string $id Session ID
     * @return bool Success
     */
    public function setId($id)
    {
        return session_id($id);
    }
    
    /**
     * Get session name
     * 
     * @return string Session name
     */
    public function getName()
    {
        return session_name();
    }
    
    /**
     * Set session name
     * 
     * @param string $name Session name
     * @return string Old session name
     */
    public function setName($name)
    {
        return session_name($name);
    }
}