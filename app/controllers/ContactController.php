<?php
/**
 * Contact Controller
 * 
 * Handles the contact page and form submissions
 */
class ContactController extends Controller
{
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
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
            ]
        ];
        
        // Render view
        $this->render('contact/index', $data);
    }
    
    /**
     * Process contact form submission
     */
    private function processForm()
    {
        // Get form data
        $name = $this->post('name');
        $email = $this->post('email');
        $subject = $this->post('subject');
        $message = $this->post('message');
        
        // Validate inputs
        $errors = [];
        
        if (empty($name)) {
            $errors[] = __('name_required');
        }
        
        if (empty($email)) {
            $errors[] = __('email_required');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = __('invalid_email');
        }
        
        if (empty($subject)) {
            $errors[] = __('subject_required');
        }
        
        if (empty($message)) {
            $errors[] = __('message_required');
        }
        
        // If there are errors, set error message and return
        if (!empty($errors)) {
            $this->session->setFlash('error', implode('<br>', $errors));
            return;
        }
        
        // Send email
        $success = $this->sendEmail($name, $email, $subject, $message);
        
        if ($success) {
            // Set success message
            $this->session->setFlash('success', __('message_sent_success'));
            
            // Redirect to avoid form resubmission
            $this->redirect('contact');
        } else {
            // Set error message
            $this->session->setFlash('error', __('message_sent_error'));
        }
    }
    
    /**
     * Send contact form email
     * 
     * @param string $name Sender name
     * @param string $email Sender email
     * @param string $subject Email subject
     * @param string $message Email message
     * @return bool Success
     */
    private function sendEmail($name, $email, $subject, $message)
    {
        // In a real application, this would send an actual email
        // For demonstration purposes, we'll just log the email data
        
        // Get settings
        $settingsModel = $this->loadModel('Settings');
        $settings = $settingsModel->getAllSettings();
        
        // Email recipient
        $to = $settings['contact_email'];
        
        // Email headers
        $headers = "From: $name <$email>" . "\r\n";
        $headers .= "Reply-To: $email" . "\r\n";
        $headers .= "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";
        
        // Email content
        $htmlMessage = "
        <html>
        <head>
            <title>Contact Form: $subject</title>
        </head>
        <body>
            <h1>Contact Form Submission</h1>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong></p>
            <p>" . nl2br(htmlspecialchars($message)) . "</p>
        </body>
        </html>
        ";
        
        // Log email data
        $logMessage = "Email to: $to, From: $name <$email>, Subject: $subject, Message: $message";
        $this->logMessage($logMessage);
        
        // Send email (in a real application)
        // return mail($to, "Contact Form: $subject", $htmlMessage, $headers);
        
        // For demonstration, just return true
        return true;
    }
    
    /**
     * Log a message
     * 
     * @param string $message Message to log
     */
    private function logMessage($message)
    {
        $logFile = BASE_PATH . '/logs/contact.log';
        $logDir = dirname($logFile);
        
        // Create logs directory if it doesn't exist
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        // Append message to log file
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $message" . PHP_EOL, FILE_APPEND);
    }
    
    /**
     * Get Google Maps API key
     * 
     * @return string API key
     */
    private function getGoogleMapsApiKey()
    {
        // In a real application, this would be from an environment variable or database
        // For demonstration purposes, return a placeholder
        return 'YOUR_GOOGLE_MAPS_API_KEY';
    }
}