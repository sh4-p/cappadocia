<?php
/**
 * Newsletter Controller
 * 
 * Handles newsletter subscription, confirmation and unsubscription
 */
class NewsletterController extends Controller
{
    private $newsletterModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Load models
        $this->newsletterModel = $this->loadModel('Newsletter');
    }
    
    /**
     * Subscribe action - handle newsletter subscription
     */
    public function subscribe()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('');
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
        }
        
        // If there are errors, set error message and redirect back
        if (!empty($errors)) {
            $this->session->setFlash('newsletter_error', implode('<br>', $errors));
            $this->redirectBack();
        }
        
        // Subscribe email
        $subscriber = $this->newsletterModel->subscribe($email, $name);
        
        if ($subscriber) {
            // Send confirmation email
            $this->sendConfirmationEmail($subscriber);
            
            // Set success message
            if ($subscriber['status'] === 'active') {
                $this->session->setFlash('newsletter_success', __('already_subscribed'));
            } else {
                $this->session->setFlash('newsletter_success', __('subscription_confirmation_sent'));
            }
        } else {
            // Set error message
            $this->session->setFlash('newsletter_error', __('subscription_failed'));
        }
        
        $this->redirectBack();
    }
    
    /**
     * Confirm action - confirm newsletter subscription
     * 
     * @param string $token Confirmation token
     */
    public function confirm($token)
    {
        // Get subscriber by token
        $subscriber = $this->newsletterModel->getByToken($token);
        
        if (!$subscriber) {
            // Set error message
            $this->session->setFlash('error', __('invalid_confirmation_link'));
            $this->redirect('');
        }
        
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
                // Send welcome email
                $this->sendWelcomeEmail($subscriber);
                
                $message = __('subscription_confirmed');
                $type = 'success';
            } else {
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
     * Unsubscribe action - unsubscribe from newsletter
     * 
     * @param string $token Unsubscribe token
     */
    public function unsubscribe($token)
    {
        // Get subscriber by token
        $subscriber = $this->newsletterModel->getByToken($token);
        
        if (!$subscriber) {
            // Set error message
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
                    // Send unsubscribe confirmation email
                    $this->sendUnsubscribeConfirmationEmail($subscriber);
                    
                    $message = __('unsubscribed_successfully');
                    $type = 'success';
                } else {
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
     * AJAX subscribe action
     */
    public function ajaxSubscribe()
    {
        // Check if request is AJAX and POST
        if (!$this->isAjax() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
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
        
        // Subscribe email
        $subscriber = $this->newsletterModel->subscribe($email, $name);
        
        if ($subscriber) {
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
            // Return error response
            $this->json([
                'success' => false,
                'message' => __('subscription_failed')
            ]);
        }
    }
    
    /**
     * Send confirmation email
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
            $confirmationLink = $this->generateConfirmationLink($subscriber['token']);
            
            // Prepare variables for email template
            $variables = [
                'email' => $subscriber['email'],
                'name' => $subscriber['name'] ?: $subscriber['email'],
                'confirmation_link' => $confirmationLink,
                'unsubscribe_link' => $this->generateUnsubscribeLink($subscriber['token'])
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
                'unsubscribe_link' => $this->generateUnsubscribeLink($subscriber['token'])
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