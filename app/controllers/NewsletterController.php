<?php
/**
 * Newsletter Controller - Updated with Anti-Bot Protection
 * 
 * Handles newsletter subscription, confirmation and unsubscription with bot protection
 */
class NewsletterController extends Controller
{
    private $newsletterModel;
    private $antiBot;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Load models
        $this->newsletterModel = $this->loadModel('Newsletter');
        
        // Initialize anti-bot protection
        $settingsModel = $this->loadModel('Settings');
        $settings = $settingsModel->getAllSettings();
        
        require_once BASE_PATH . '/core/AntiBot.php';
        $this->antiBot = new AntiBot($this->db, $settings, $this->session);
    }
    
    /**
     * Subscribe action - handle newsletter subscription with anti-bot protection
     */
    public function subscribe()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('');
        }
        
        // Anti-bot validation
        if ($this->antiBot->isEnabled()) {
            $antibotResult = $this->antiBot->validateSubmission('newsletter', $_POST);
            
            if (!$antibotResult['success']) {
                // Log the bot attempt
                error_log("Newsletter subscription bot attempt blocked: " . json_encode([
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'reason' => $antibotResult['blocked_reason'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                    'post_data' => array_keys($_POST)
                ]));
                
                $this->session->setFlash('newsletter_error', implode('<br>', $antibotResult['errors']));
                $this->redirectBack();
            }
        }
        
        // Get form data
        $email = trim($this->post('email', ''));
        $name = trim($this->post('name', ''));
        
        // Validate inputs
        $errors = [];
        
        if (empty($email)) {
            $errors[] = __('email_required');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = __('invalid_email');
        } elseif ($this->isDisposableEmail($email)) {
            $errors[] = __('disposable_email_not_allowed');
        }
        
        // Validate name if provided
        if (!empty($name)) {
            if (strlen($name) < 2) {
                $errors[] = __('name_too_short');
            } elseif (strlen($name) > 100) {
                $errors[] = __('name_too_long');
            } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s\-\'\.]+$/u', $name)) {
                $errors[] = __('invalid_name_format');
            }
        }
        
        // Check for spam patterns
        if ($this->isSpamEmail($email, $name)) {
            $errors[] = __('subscription_not_allowed');
        }
        
        // If there are errors, set error message and redirect back
        if (!empty($errors)) {
            $this->session->setFlash('newsletter_error', implode('<br>', $errors));
            $this->redirectBack();
        }
        
        // Check subscription frequency (prevent spam subscriptions)
        if (!$this->checkSubscriptionFrequency($email)) {
            $this->session->setFlash('newsletter_error', __('too_many_subscription_attempts'));
            $this->redirectBack();
        }
        
        // Subscribe email
        $subscriber = $this->newsletterModel->subscribe($email, $name);
        
        if ($subscriber) {
            // Log successful subscription
            $this->logNewsletterAction('subscribe', $email, $name, true);
            
            // Send confirmation email
            $this->sendConfirmationEmail($subscriber);
            
            // Set success message
            if ($subscriber['status'] === 'active') {
                $this->session->setFlash('newsletter_success', __('already_subscribed'));
            } else {
                $this->session->setFlash('newsletter_success', __('subscription_confirmation_sent'));
            }
        } else {
            // Log failed subscription
            $this->logNewsletterAction('subscribe', $email, $name, false);
            
            // Set error message
            $this->session->setFlash('newsletter_error', __('subscription_failed'));
        }
        
        $this->redirectBack();
    }
    
    /**
     * AJAX subscribe action with enhanced protection
     */
    public function ajaxSubscribe()
    {
        // Check if request is AJAX and POST
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        
        // Anti-bot validation
        if ($this->antiBot->isEnabled()) {
            $antibotResult = $this->antiBot->validateSubmission('newsletter', $_POST);
            
            if (!$antibotResult['success']) {
                $this->json([
                    'success' => false, 
                    'message' => implode(' ', $antibotResult['errors']),
                    'blocked_reason' => $antibotResult['blocked_reason']
                ]);
            }
        }
        
        // Get form data
        $email = trim($this->post('email', ''));
        $name = trim($this->post('name', ''));
        
        // Validate inputs
        if (empty($email)) {
            $this->json(['success' => false, 'message' => __('email_required')]);
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json(['success' => false, 'message' => __('invalid_email')]);
        }
        
        if ($this->isDisposableEmail($email)) {
            $this->json(['success' => false, 'message' => __('disposable_email_not_allowed')]);
        }
        
        // Check for spam patterns
        if ($this->isSpamEmail($email, $name)) {
            $this->json(['success' => false, 'message' => __('subscription_not_allowed')]);
        }
        
        // Check subscription frequency
        if (!$this->checkSubscriptionFrequency($email)) {
            $this->json(['success' => false, 'message' => __('too_many_subscription_attempts')]);
        }
        
        // Subscribe email
        $subscriber = $this->newsletterModel->subscribe($email, $name);
        
        if ($subscriber) {
            // Log successful subscription
            $this->logNewsletterAction('subscribe', $email, $name, true);
            
            // Send confirmation email
            $this->sendConfirmationEmail($subscriber);
            
            // Return success response
            if ($subscriber['status'] === 'active') {
                $this->json([
                    'success' => true,
                    'message' => __('already_subscribed')
                ]);
            } else {
                $this->json([
                    'success' => true,
                    'message' => __('subscription_confirmation_sent')
                ]);
            }
        } else {
            // Log failed subscription
            $this->logNewsletterAction('subscribe', $email, $name, false);
            
            // Return error response
            $this->json([
                'success' => false,
                'message' => __('subscription_failed')
            ]);
        }
    }
    
    /**
     * Check if email is from a disposable email service
     */
    private function isDisposableEmail($email)
    {
        $domain = strtolower(substr(strrchr($email, "@"), 1));
        
        $disposableDomains = [
            '10minutemail.com', '10minutemail.net', 'tempmail.org', 'guerrillamail.com',
            'mailinator.com', 'temp-mail.org', 'throwaway.email', 'fakeinbox.com',
            'getnada.com', 'sharklasers.com', 'guerrillamailblock.com', 'pokemail.net',
            'spam4.me', 'tempail.com', 'tempinbox.com', 'yopmail.com'
        ];
        
        return in_array($domain, $disposableDomains);
    }
    
    /**
     * Check for spam email patterns
     */
    private function isSpamEmail($email, $name)
    {
        $email = strtolower($email);
        $name = strtolower($name);
        
        // Check for suspicious patterns in email
        $suspiciousPatterns = [
            '/\+.*test/', '/\+.*spam/', '/\+.*fake/', '/\+.*bot/',
            '/test.*@/', '/spam.*@/', '/fake.*@/', '/bot.*@/',
            '/^\d+@/', '/^[a-z]{1,2}@/'
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $email)) {
                return true;
            }
        }
        
        // Check for suspicious names
        if (!empty($name)) {
            $suspiciousNames = ['test', 'spam', 'fake', 'bot', 'admin', 'user', 'guest'];
            
            foreach ($suspiciousNames as $suspiciousName) {
                if (stripos($name, $suspiciousName) !== false) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check subscription frequency to prevent spam
     */
    private function checkSubscriptionFrequency($email)
    {
        try {
            $ip = $_SERVER['REMOTE_ADDR'];
            
            // Check if same email tried to subscribe multiple times recently
            $sql = "SELECT COUNT(*) FROM newsletter_attempts 
                    WHERE (email = :email OR ip_address = :ip) 
                    AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
            
            $attempts = $this->db->getValue($sql, ['email' => $email, 'ip' => $ip]);
            
            return $attempts < 3; // Max 3 attempts per hour
        } catch (Exception $e) {
            // If table doesn't exist, allow subscription
            return true;
        }
    }
    
    /**
     * Log newsletter action
     */
    private function logNewsletterAction($action, $email, $name, $success)
    {
        try {
            $data = [
                'action' => $action,
                'email' => $email,
                'name' => $name,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'success' => $success ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('newsletter_attempts', $data);
        } catch (Exception $e) {
            // Log error but don't fail the request
            error_log("Failed to log newsletter action: " . $e->getMessage());
        }
    }
    
    /**
     * Confirm action - confirm newsletter subscription with additional validation
     * 
     * @param string $token Confirmation token
     */
    public function confirm($token)
    {
        // Validate token format
        if (empty($token) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            $this->session->setFlash('error', __('invalid_confirmation_link'));
            $this->redirect('');
        }
        
        // Get subscriber by token
        $subscriber = $this->newsletterModel->getByToken($token);
        
        if (!$subscriber) {
            // Log invalid token attempt
            error_log("Invalid newsletter confirmation token: " . $token . " from IP: " . $_SERVER['REMOTE_ADDR']);
            
            $this->session->setFlash('error', __('invalid_confirmation_link'));
            $this->redirect('');
        }
        
        $message = '';
        $type = 'info';
        
        if ($subscriber['status'] === 'active') {
            // Already confirmed
            $message = __('already_subscribed');
            $type = 'info';
        } elseif ($subscriber['status'] === 'unsubscribed') {
            // Unsubscribed
            $message = __('email_unsubscribed');
            $type = 'info';
        } else {
            // Confirm subscription
            $result = $this->newsletterModel->confirmSubscription($token);
            
            if ($result) {
                // Log successful confirmation
                $this->logNewsletterAction('confirm', $subscriber['email'], $subscriber['name'], true);
                
                // Send welcome email
                $this->sendWelcomeEmail($subscriber);
                
                $message = __('subscription_confirmed');
                $type = 'success';
            } else {
                // Log failed confirmation
                $this->logNewsletterAction('confirm', $subscriber['email'], $subscriber['name'], false);
                
                $message = __('confirmation_failed');
                $type = 'error';
            }
        }
        
        // Set page data
        $data = [
            'subscriber' => $subscriber,
            'message' => $message,
            'type' => $type,
            'pageTitle' => __('newsletter_confirmation'),
            'metaDescription' => __('newsletter_confirmation_description')
        ];
        
        // Render confirmation page
        $this->render('newsletter/confirm', $data);
    }
    
    /**
     * Unsubscribe action - unsubscribe from newsletter with validation
     * 
     * @param string $token Unsubscribe token
     */
    public function unsubscribe($token)
    {
        // Validate token format
        if (empty($token) || !preg_match('/^[a-f0-9]{64}$/', $token)) {
            $this->session->setFlash('error', __('invalid_unsubscribe_link'));
            $this->redirect('');
        }
        
        // Get subscriber by token
        $subscriber = $this->newsletterModel->getByToken($token);
        
        if (!$subscriber) {
            // Log invalid token attempt
            error_log("Invalid newsletter unsubscribe token: " . $token . " from IP: " . $_SERVER['REMOTE_ADDR']);
            
            $this->session->setFlash('error', __('invalid_unsubscribe_link'));
            $this->redirect('');
        }
        
        $message = '';
        $type = 'info';
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $confirm = $this->post('confirm');
            
            if ($confirm) {
                // Unsubscribe
                $result = $this->newsletterModel->unsubscribe($token);
                
                if ($result) {
                    // Log successful unsubscription
                    $this->logNewsletterAction('unsubscribe', $subscriber['email'], $subscriber['name'], true);
                    
                    // Send unsubscribe confirmation email
                    $this->sendUnsubscribeConfirmationEmail($subscriber);
                    
                    $message = __('unsubscribed_successfully');
                    $type = 'success';
                } else {
                    // Log failed unsubscription
                    $this->logNewsletterAction('unsubscribe', $subscriber['email'], $subscriber['name'], false);
                    
                    $message = __('unsubscribe_failed');
                    $type = 'error';
                }
            } else {
                // User cancelled
                $this->redirect('');
            }
        } else {
            // Show unsubscribe form
            if ($subscriber['status'] === 'unsubscribed') {
                $message = __('already_unsubscribed');
                $type = 'info';
            }
        }
        
        // Set page data
        $data = [
            'subscriber' => $subscriber,
            'message' => $message,
            'type' => $type,
            'pageTitle' => __('newsletter_unsubscribe'),
            'metaDescription' => __('newsletter_unsubscribe_description')
        ];
        
        // Render unsubscribe page
        $this->render('newsletter/unsubscribe', $data);
    }
    
    /**
     * Send confirmation email with enhanced security
     * 
     * @param array $subscriber Subscriber data
     * @return bool Success
     */
    private function sendConfirmationEmail($subscriber)
    {
        // Don't send if already active
        if ($subscriber['status'] === 'active') {
            return true;
        }
        
        try {
            // Load Email class
            require_once BASE_PATH . '/core/Email.php';
            $email = new Email();
            
            // Get current language
            $langCode = $this->language->getCurrentLanguage();
            
            // Generate confirmation link
            $confirmationLink = $this->generateConfirmationLink($subscriber['tracking_token']);
            
            // Prepare variables for email template
            $variables = [
                'email' => $subscriber['email'],
                'name' => $subscriber['name'] ?: $subscriber['email'],
                'confirmation_link' => $confirmationLink,
                'unsubscribe_link' => $this->generateUnsubscribeLink($subscriber['tracking_token']),
                'ip_address' => $_SERVER['REMOTE_ADDR'], // For security info
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            // Send email using template
            return $email->sendTemplate('newsletter_confirmation', $subscriber['email'], $variables);
            
        } catch (Exception $e) {
            error_log('Newsletter confirmation email error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send welcome email
     * 
     * @param array $subscriber Subscriber data
     * @return bool Success
     */
    private function sendWelcomeEmail($subscriber)
    {
        try {
            // Load Email class
            require_once BASE_PATH . '/core/Email.php';
            $email = new Email();
            
            // Prepare variables for email template
            $variables = [
                'email' => $subscriber['email'],
                'name' => $subscriber['name'] ?: $subscriber['email'],
                'unsubscribe_link' => $this->generateUnsubscribeLink($subscriber['tracking_token'])
            ];
            
            // Send email using template
            return $email->sendTemplate('newsletter_welcome', $subscriber['email'], $variables);
            
        } catch (Exception $e) {
            error_log('Newsletter welcome email error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send unsubscribe confirmation email
     * 
     * @param array $subscriber Subscriber data
     * @return bool Success
     */
    private function sendUnsubscribeConfirmationEmail($subscriber)
    {
        try {
            // Load Email class
            require_once BASE_PATH . '/core/Email.php';
            $email = new Email();
            
            // Prepare variables for email template
            $variables = [
                'email' => $subscriber['email'],
                'name' => $subscriber['name'] ?: $subscriber['email']
            ];
            
            // Send email using template
            return $email->sendTemplate('newsletter_unsubscribe_confirmation', $subscriber['email'], $variables);
            
        } catch (Exception $e) {
            error_log('Newsletter unsubscribe confirmation email error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate confirmation link
     * 
     * @param string $token Token
     * @return string Confirmation link
     */
    private function generateConfirmationLink($token)
    {
        $langCode = $this->language->getCurrentLanguage();
        return APP_URL . '/' . $langCode . '/newsletter/confirm/' . $token;
    }
    
    /**
     * Generate unsubscribe link
     * 
     * @param string $token Token
     * @return string Unsubscribe link
     */
    private function generateUnsubscribeLink($token)
    {
        $langCode = $this->language->getCurrentLanguage();
        return APP_URL . '/' . $langCode . '/newsletter/unsubscribe/' . $token;
    }
    
    /**
     * Redirect back to referring page
     */
    private function redirectBack()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        
        if ($referer && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {
            header('Location: ' . $referer);
        } else {
            $this->redirect('');
        }
        exit;
    }
}