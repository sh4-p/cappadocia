<?php
/**
 * Contact Controller - Updated with Anti-Bot Protection
 * 
 * Handles the contact page and form submissions with bot protection
 */
class ContactController extends Controller
{
    private $antiBot;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Initialize anti-bot protection
        $settingsModel = $this->loadModel('Settings');
        $settings = $settingsModel->getAllSettings();
        
        require_once BASE_PATH . '/core/AntiBot.php';
        $this->antiBot = new AntiBot($this->db, $settings, $this->session);
    }
    
    /**
     * Index action - display contact page and process form submission
     */
    public function index()
    {
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processForm();
        }
        
        // Set page data
        $data = [
            'pageTitle' => __('contact_us'),
            'metaDescription' => __('contact_us_description'),
            'googleMapsApiKey' => $this->getGoogleMapsApiKey(),
            'mapCenterLat' => 38.642335, // Cappadocia coordinates
            'mapCenterLng' => 34.827335,
            'additionalCss' => [
                'contact.css'
            ],
            'additionalJs' => [
                'contact.js'
            ],
            // Anti-bot protection HTML
            'honeypotFields' => $this->antiBot->generateHoneypotFields(),
            'recaptchaV2Html' => $this->antiBot->getRecaptchaV2Html(),
            'recaptchaV3Html' => $this->antiBot->getRecaptchaV3Html('contact'),
            'turnstileHtml' => $this->antiBot->getTurnstileHtml(),
            'antibotEnabled' => $this->antiBot->isEnabled()
        ];
        
        // Render view
        $this->render('contact/index', $data);
    }
    
    /**
     * Process contact form submission with anti-bot protection
     */
    private function processForm()
    {
        // Anti-bot validation
        if ($this->antiBot->isEnabled()) {
            $antibotResult = $this->antiBot->validateSubmission('contact', $_POST);
            
            if (!$antibotResult['success']) {
                // Log the bot attempt
                error_log("Contact form bot attempt blocked: " . json_encode([
                    'ip' => $_SERVER['REMOTE_ADDR'],
                    'reason' => $antibotResult['blocked_reason'],
                    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                    'post_data' => array_keys($_POST)
                ]));
                
                // Set error message
                $this->session->setFlash('error', implode('<br>', $antibotResult['errors']));
                return;
            }
        }
        
        // Get form data
        $name = $this->post('name');
        $email = $this->post('email');
        $subject = $this->post('subject');
        $message = $this->post('message');
        
        // Validate inputs
        $errors = [];
        
        if (empty($name)) {
            $errors[] = __('name_required');
        } elseif (strlen($name) < 2) {
            $errors[] = __('name_too_short');
        } elseif (strlen($name) > 100) {
            $errors[] = __('name_too_long');
        }
        
        if (empty($email)) {
            $errors[] = __('email_required');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = __('invalid_email');
        }
        
        if (empty($subject)) {
            $errors[] = __('subject_required');
        } elseif (strlen($subject) < 5) {
            $errors[] = __('subject_too_short');
        } elseif (strlen($subject) > 200) {
            $errors[] = __('subject_too_long');
        }
        
        if (empty($message)) {
            $errors[] = __('message_required');
        } elseif (strlen($message) < 10) {
            $errors[] = __('message_too_short');
        } elseif (strlen($message) > 5000) {
            $errors[] = __('message_too_long');
        }
        
        // Check for spam content
        if ($this->containsSpam($name . ' ' . $email . ' ' . $subject . ' ' . $message)) {
            $errors[] = __('spam_content_detected');
        }
        
        // If there are errors, set error message and return
        if (!empty($errors)) {
            $this->session->setFlash('error', implode('<br>', $errors));
            return;
        }
        
        // Additional security checks
        if ($this->isLikelySuspicious($name, $email, $subject, $message)) {
            // Log suspicious attempt
            error_log("Suspicious contact form submission: " . json_encode([
                'ip' => $_SERVER['REMOTE_ADDR'],
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]));
            
            $this->session->setFlash('error', __('submission_under_review'));
            return;
        }
        
        // Send email
        $success = $this->sendEmail($name, $email, $subject, $message);
        
        if ($success) {
            // Log successful contact form submission
            $this->logContactSubmission($name, $email, $subject, true);
            
            // Set success message
            $this->session->setFlash('success', __('message_sent_success'));
            
            // Redirect to avoid form resubmission
            $this->redirect('contact');
        } else {
            // Log failed email sending
            $this->logContactSubmission($name, $email, $subject, false);
            
            // Set error message
            $this->session->setFlash('error', __('message_sent_error'));
        }
    }
    
    /**
     * Check if content contains spam keywords
     */
    private function containsSpam($content)
    {
        $content = strtolower($content);
        
        $spamKeywords = [
            'viagra', 'cialis', 'pharmacy', 'casino', 'poker', 'lottery',
            'bitcoin', 'cryptocurrency', 'investment', 'loan', 'credit',
            'seo service', 'web design service', 'marketing service',
            'cheap price', 'best price', 'guarantee', 'make money',
            'work from home', 'earn money', 'get rich', 'free money'
        ];
        
        foreach ($spamKeywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return true;
            }
        }
        
        // Check for excessive links
        $linkCount = preg_match_all('/https?:\/\//', $content);
        if ($linkCount > 2) {
            return true;
        }
        
        // Check for excessive capital letters
        $capitalRatio = strlen(preg_replace('/[^A-Z]/', '', $content)) / strlen($content);
        if ($capitalRatio > 0.3) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if submission is likely suspicious
     */
    private function isLikelySuspicious($name, $email, $subject, $message)
    {
        // Check for fake names
        $fakeName = preg_match('/^[a-zA-Z]{1,3}$/', $name) || 
                   preg_match('/test|admin|user|guest|demo/i', $name);
        
        // Check for suspicious email patterns
        $suspiciousEmail = preg_match('/\+.*@|\.ru$|\.tk$|temp|trash|fake/i', $email);
        
        // Check for very short or generic messages
        $genericMessage = strlen($message) < 20 || 
                         preg_match('/hello|hi|test|check|just checking/i', $message);
        
        // Check for duplicate content (same message multiple times)
        if ($this->isDuplicateMessage($message)) {
            return true;
        }
        
        return $fakeName || $suspiciousEmail || $genericMessage;
    }
    
    /**
     * Check if message is duplicate
     */
    private function isDuplicateMessage($message)
    {
        $messageHash = md5(trim(strtolower($message)));
        
        // Check if same message was sent in last 24 hours
        try {
            $sql = "SELECT COUNT(*) FROM contact_submissions 
                    WHERE message_hash = :hash 
                    AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
            
            $count = $this->db->getValue($sql, ['hash' => $messageHash]);
            return $count > 0;
        } catch (Exception $e) {
            // If table doesn't exist, skip check
            return false;
        }
    }
    
    /**
     * Log contact form submission
     */
    private function logContactSubmission($name, $email, $subject, $success)
    {
        try {
            $data = [
                'name' => $name,
                'email' => $email,
                'subject' => $subject,
                'message_hash' => md5(trim(strtolower($this->post('message')))),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'success' => $success ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->db->insert('contact_submissions', $data);
        } catch (Exception $e) {
            // Log error but don't fail the request
            error_log("Failed to log contact submission: " . $e->getMessage());
        }
    }
    
    /**
     * Send contact form email with enhanced template
     * 
     * @param string $name Sender name
     * @param string $email Sender email
     * @param string $subject Email subject
     * @param string $message Email message
     * @return bool Success
     */
    private function sendEmail($name, $email, $subject, $message)
    {
        try {
            // Load Email class
            require_once BASE_PATH . '/core/Email.php';
            $emailClass = new Email();
            
            // Get settings
            $settingsModel = $this->loadModel('Settings');
            $settings = $settingsModel->getAllSettings();
            
            // Prepare email data
            $emailData = [
                'name' => $name,
                'email' => $email,
                'phone' => $this->post('phone', ''),
                'subject' => $subject,
                'message' => $message,
                'timestamp' => date('Y-m-d H:i:s'),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ];
            
            // Try to send using email template first
            $templateSent = $emailClass->sendTemplate('contact_form', null, $emailData);
            
            if ($templateSent) {
                return true;
            }
            
            // Fallback to manual email
            $to = $settings['contact_email'] ?? $settings['email_from_address'];
            
            if (empty($to)) {
                error_log("Contact form: No recipient email configured");
                return false;
            }
            
            // Enhanced email content with security info
            $htmlMessage = $this->generateContactEmailHtml($emailData);
            
            // Send email
            return $emailClass->send($to, "Contact Form: " . $subject, $htmlMessage);
            
        } catch (Exception $e) {
            error_log("Contact form email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Generate HTML email content
     */
    private function generateContactEmailHtml($data)
    {
        $html = "
        <html>
        <head>
            <title>Contact Form Submission</title>
            <style>
                body { font-family: Arial, sans-serif; color: #333; }
                .header { background: #f8f9fa; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
                .content { padding: 20px; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #555; }
                .value { margin-top: 5px; padding: 10px; background: #f8f9fa; border-radius: 3px; }
                .security-info { font-size: 12px; color: #666; margin-top: 20px; padding-top: 20px; border-top: 1px solid #eee; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h2>New Contact Form Submission</h2>
                <p>Received: " . $data['timestamp'] . "</p>
            </div>
            
            <div class='content'>
                <div class='field'>
                    <div class='label'>Name:</div>
                    <div class='value'>" . htmlspecialchars($data['name']) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'>" . htmlspecialchars($data['email']) . "</div>
                </div>";
                
        if (!empty($data['phone'])) {
            $html .= "
                <div class='field'>
                    <div class='label'>Phone:</div>
                    <div class='value'>" . htmlspecialchars($data['phone']) . "</div>
                </div>";
        }
        
        $html .= "
                <div class='field'>
                    <div class='label'>Subject:</div>
                    <div class='value'>" . htmlspecialchars($data['subject']) . "</div>
                </div>
                
                <div class='field'>
                    <div class='label'>Message:</div>
                    <div class='value'>" . nl2br(htmlspecialchars($data['message'])) . "</div>
                </div>
                
                <div class='security-info'>
                    <strong>Security Information:</strong><br>
                    IP Address: " . htmlspecialchars($data['ip_address']) . "<br>
                    User Agent: " . htmlspecialchars($data['user_agent']) . "<br>
                    Timestamp: " . $data['timestamp'] . "
                </div>
            </div>
        </body>
        </html>";
        
        return $html;
    }
    
    /**
     * Get Google Maps API key
     * 
     * @return string API key
     */
    private function getGoogleMapsApiKey()
    {
        $settingsModel = $this->loadModel('Settings');
        $settings = $settingsModel->getAllSettings();
        
        return $settings['google_maps_api_key'] ?? '';
    }
    
    /**
     * AJAX endpoint for contact form submission
     */
    public function ajaxSubmit()
    {
        // Check if request is AJAX
        if (!$this->isAjax()) {
            $this->json(['success' => false, 'message' => 'Invalid request'], 400);
        }
        
        // Anti-bot validation
        if ($this->antiBot->isEnabled()) {
            $antibotResult = $this->antiBot->validateSubmission('contact', $_POST);
            
            if (!$antibotResult['success']) {
                $this->json([
                    'success' => false,
                    'message' => implode(' ', $antibotResult['errors']),
                    'blocked_reason' => $antibotResult['blocked_reason']
                ]);
            }
        }
        
        // Process form (same validation as regular form)
        $name = $this->post('name');
        $email = $this->post('email');
        $subject = $this->post('subject');
        $message = $this->post('message');
        
        // Validate inputs
        $errors = [];
        
        if (empty($name)) {
            $errors[] = __('name_required');
        }
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = __('invalid_email');
        }
        
        if (empty($subject)) {
            $errors[] = __('subject_required');
        }
        
        if (empty($message)) {
            $errors[] = __('message_required');
        }
        
        // Check for spam
        if ($this->containsSpam($name . ' ' . $email . ' ' . $subject . ' ' . $message)) {
            $errors[] = __('spam_content_detected');
        }
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'message' => implode(' ', $errors)]);
        }
        
        // Send email
        $success = $this->sendEmail($name, $email, $subject, $message);
        
        if ($success) {
            $this->logContactSubmission($name, $email, $subject, true);
            $this->json(['success' => true, 'message' => __('message_sent_success')]);
        } else {
            $this->logContactSubmission($name, $email, $subject, false);
            $this->json(['success' => false, 'message' => __('message_sent_error')]);
        }
    }
}