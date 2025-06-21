<?php
/**
 * Email Class
 * 
 * Handles email sending with SMTP support and database templates
 */
class Email
{
    private $smtpConfig = [];
    private $fromEmail = '';
    private $fromName = '';
    private $replyTo = '';
    private $error = '';
    private $debug = false;
    private $emailTemplateModel = null;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        // Load settings for default configuration
        $this->loadDefaultSettings();
        
        // Load email template model
        try {
            $db = new Database();
            $this->emailTemplateModel = new EmailTemplate($db);
        } catch (Exception $e) {
            error_log('Email: Could not load EmailTemplate model: ' . $e->getMessage());
        }
    }
    
    /**
     * Load default settings from database
     */
    private function loadDefaultSettings()
    {
        try {
            $db = new Database();
            $settingsModel = new Settings($db);
            $settings = $settingsModel->getAllSettings();
            
            // Set default sender info
            $this->fromEmail = $settings['email_from_address'] ?? '';
            $this->fromName = $settings['email_from_name'] ?? '';
            $this->replyTo = $settings['email_reply_to'] ?? $this->fromEmail;
            
            // Configure SMTP if enabled
            if (!empty($settings['smtp_enabled']) && $settings['smtp_enabled'] == '1') {
                $this->configureSMTP([
                    'host' => $settings['smtp_host'] ?? '',
                    'port' => intval($settings['smtp_port'] ?? 587),
                    'security' => $settings['smtp_security'] ?? 'tls',
                    'auth' => !empty($settings['smtp_auth']) && $settings['smtp_auth'] == '1',
                    'username' => $settings['smtp_username'] ?? '',
                    'password' => $settings['smtp_password'] ?? '',
                    'timeout' => intval($settings['smtp_timeout'] ?? 30)
                ]);
            }
        } catch (Exception $e) {
            // Fallback if settings can't be loaded
            $this->error = 'Could not load email settings: ' . $e->getMessage();
        }
    }
    
    /**
     * Configure SMTP settings
     * 
     * @param array $config SMTP configuration
     */
    public function configureSMTP($config)
    {
        $this->smtpConfig = array_merge([
            'host' => '',
            'port' => 587,
            'security' => 'tls',
            'auth' => true,
            'username' => '',
            'password' => '',
            'timeout' => 30
        ], $config);
    }
    
    /**
     * Set sender information
     * 
     * @param string $email Sender email
     * @param string $name Sender name
     */
    public function setFrom($email, $name = '')
    {
        $this->fromEmail = $email;
        $this->fromName = $name;
    }
    
    /**
     * Set reply-to address
     * 
     * @param string $email Reply-to email
     */
    public function setReplyTo($email)
    {
        $this->replyTo = $email;
    }
    
    /**
     * Enable debug mode
     * 
     * @param bool $debug Debug mode
     */
    public function setDebug($debug = true)
    {
        $this->debug = $debug;
    }
    
    /**
     * Send email using template
     * 
     * @param string $templateKey Template key
     * @param string $toEmail Recipient email
     * @param array $variables Template variables
     * @return bool Success
     */
    public function sendTemplate($templateKey, $toEmail, $variables = [])
    {
        if (!$this->emailTemplateModel) {
            $this->error = 'Email template model not available';
            return false;
        }
        
        // Get template from database
        $template = $this->emailTemplateModel->getByKey($templateKey);
        
        if (!$template) {
            // Fallback to hardcoded templates
            return $this->sendLegacyTemplate($templateKey, $toEmail, $variables);
        }
        
        // Merge default variables
        $variables = array_merge([
            'from_email' => $this->fromEmail,
            'from_name' => $this->fromName,
            'timestamp' => date('Y-m-d H:i:s'),
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'localhost',
            'method' => !empty($this->smtpConfig['host']) ? 'SMTP' : 'PHP Mail'
        ], $variables);
        
        // Render template
        $subject = $this->emailTemplateModel->renderTemplate($template['subject'], $variables);
        $body = $this->emailTemplateModel->renderTemplate($template['body'], $variables);
        
        return $this->send($toEmail, $subject, $body);
    }
    
    /**
     * Send test email using template
     * 
     * @param string $toEmail Recipient email
     * @return bool Success
     */
    public function sendTestEmail($toEmail)
    {
        $variables = [
            'timestamp' => date('Y-m-d H:i:s'),
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'localhost',
            'from_email' => $this->fromEmail,
            'method' => !empty($this->smtpConfig['host']) ? 'SMTP' : 'PHP Mail'
        ];
        
        return $this->sendTemplate('test_email', $toEmail, $variables);
    }
    
    /**
     * Send booking confirmation email
     * 
     * @param array $booking Booking data
     * @return bool Success
     */
    public function sendBookingConfirmation($booking)
    {
        $variables = [
            'first_name' => $booking['first_name'] ?? '',
            'last_name' => $booking['last_name'] ?? '',
            'tour_name' => $booking['tour_name'] ?? '',
            'booking_date' => $booking['booking_date'] ?? '',
            'adults' => $booking['adults'] ?? '0',
            'children' => $booking['children'] ?? '0',
            'total_price' => number_format($booking['total_price'] ?? 0, 2),
            'special_requests' => $booking['special_requests'] ?? '',
            'from_name' => $this->fromName
        ];
        
        return $this->sendTemplate('booking_confirmation', $booking['email'], $variables);
    }
    
    /**
     * Send contact form email
     * 
     * @param array $contact Contact form data
     * @return bool Success
     */
    public function sendContactEmail($contact)
    {
        $variables = [
            'name' => $contact['name'] ?? '',
            'email' => $contact['email'] ?? '',
            'phone' => $contact['phone'] ?? '',
            'subject' => $contact['subject'] ?? '',
            'message' => $contact['message'] ?? '',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        // Send to admin
        $db = new Database();
        $settingsModel = new Settings($db);
        $adminEmail = $settingsModel->getSetting('email_admin', $this->fromEmail);
        
        return $this->sendTemplate('contact_form', $adminEmail, $variables);
    }
    
        /**
     * Send booking admin notification
     * 
     * @param array $booking Booking data
     * @return bool Success
     */
    public function sendBookingAdminNotification($booking)
    {
        $variables = [
            'first_name' => $booking['first_name'] ?? '',
            'last_name' => $booking['last_name'] ?? '',
            'tour_name' => $booking['tour_name'] ?? '',
            'booking_date' => $booking['booking_date'] ?? '',
            'adults' => $booking['adults'] ?? '0',
            'children' => $booking['children'] ?? '0',
            'total_price' => $booking['total_price_formatted'] ?? number_format($booking['total_price'] ?? 0, 2),
            'email' => $booking['email'] ?? '',
            'phone' => $booking['phone'] ?? '',
            'payment_method' => $booking['payment_method_formatted'] ?? $booking['payment_method'] ?? '',
            'special_requests' => $booking['special_requests'] ?? ''
        ];
        
        // Send to admin
        $db = new Database();
        $settingsModel = new Settings($db);
        $adminEmail = $settingsModel->getSetting('email_admin', $this->fromEmail);
        
        return $this->sendTemplate('booking_admin_notification', $adminEmail, $variables);
    }
    /**
     * Send booking status change email
     * 
     * @param string $status New status (confirmed, cancelled)
     * @param string $toEmail Recipient email
     * @param array $booking Booking data
     * @return bool Success
     */
    public function sendBookingStatusEmail($status, $toEmail, $booking)
    {
        $variables = [
            'first_name' => $booking['first_name'] ?? '',
            'last_name' => $booking['last_name'] ?? '',
            'tour_name' => $booking['tour_name'] ?? '',
            'booking_date' => $booking['booking_date'] ?? '',
            'adults' => $booking['adults'] ?? '0',
            'children' => $booking['children'] ?? '0',
            'total_price' => $booking['total_price'] ?? '0',
            'booking_id' => $booking['booking_id'] ?? $booking['id'] ?? '',
            'status' => ucfirst($status)
        ];
        
        $templateKey = 'booking_status_' . $status;
        return $this->sendTemplate($templateKey, $toEmail, $variables);
    }
    
    /**
     * Send welcome email to new user
     * 
     * @param array $user User data
     * @return bool Success
     */
    public function sendWelcomeEmail($user)
    {
        $variables = [
            'first_name' => $user['first_name'] ?? '',
            'last_name' => $user['last_name'] ?? '',
            'username' => $user['username'] ?? '',
            'email' => $user['email'] ?? '',
            'activation_link' => $user['activation_link'] ?? ''
        ];
        
        return $this->sendTemplate('welcome_email', $user['email'], $variables);
    }
    
    /**
     * Send password reset email
     * 
     * @param array $user User data with reset link
     * @return bool Success
     */
    public function sendPasswordReset($user)
    {
        $variables = [
            'first_name' => $user['first_name'] ?? '',
            'last_name' => $user['last_name'] ?? '',
            'reset_link' => $user['reset_link'] ?? '',
            'expiry_time' => $user['expiry_time'] ?? '24 hours'
        ];
        
        return $this->sendTemplate('password_reset', $user['email'], $variables);
    }
    
    /**
     * Send newsletter subscription confirmation
     * 
     * @param string $email Subscriber email
     * @param string $confirmationLink Confirmation link
     * @return bool Success
     */
    public function sendNewsletterConfirmation($email, $confirmationLink)
    {
        $variables = [
            'email' => $email,
            'confirmation_link' => $confirmationLink,
            'unsubscribe_link' => $confirmationLink // Can be different
        ];
        
        return $this->sendTemplate('newsletter_confirmation', $email, $variables);
    }
    
    /**
     * Send legacy template (fallback for missing database templates)
     * 
     * @param string $templateKey Template key
     * @param string $toEmail Recipient email
     * @param array $variables Template variables
     * @return bool Success
     */
    private function sendLegacyTemplate($templateKey, $toEmail, $variables)
    {
        switch ($templateKey) {
            case 'test_email':
                $subject = 'SMTP Test Email - ' . date('Y-m-d H:i:s');
                $message = $this->getTestEmailBody();
                break;
                
            case 'booking_confirmation':
                $subject = 'Booking Confirmation - ' . ($variables['tour_name'] ?? 'Tour');
                $message = $this->getBookingEmailBody($variables);
                break;
                
            case 'contact_form':
                $subject = 'New Contact Form Submission';
                $message = $this->getContactEmailBody($variables);
                break;
                
            default:
                $this->error = "Template '$templateKey' not found";
                return false;
        }
        
        return $this->send($toEmail, $subject, $message);
    }
    
    /**
     * Send email
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email message (HTML)
     * @param array $headers Additional headers
     * @return bool Success
     */
    public function send($to, $subject, $message, $headers = [])
    {
        $this->error = '';
        
        if (!$this->fromEmail) {
            $this->error = 'Sender email not configured';
            return false;
        }
        
        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            $this->error = 'Invalid recipient email address';
            return false;
        }
        
        if (!empty($this->smtpConfig['host'])) {
            return $this->sendSMTP($to, $subject, $message, $headers);
        } else {
            return $this->sendPHP($to, $subject, $message, $headers);
        }
    }
    
    /**
     * Send email via SMTP
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email message
     * @param array $headers Additional headers
     * @return bool Success
     */
    private function sendSMTP($to, $subject, $message, $headers = [])
    {
        try {
            $smtp = $this->connectSMTP();
            
            if (!$smtp) {
                return false;
            }
            
            // SMTP conversation
            $this->smtpCommand($smtp, "EHLO " . ($_SERVER['SERVER_NAME'] ?? 'localhost'));
            
            // Authentication
            if ($this->smtpConfig['auth'] && $this->smtpConfig['username']) {
                $this->smtpCommand($smtp, "AUTH LOGIN");
                $this->smtpCommand($smtp, base64_encode($this->smtpConfig['username']));
                $this->smtpCommand($smtp, base64_encode($this->smtpConfig['password']));
            }
            
            // Send email
            $this->smtpCommand($smtp, "MAIL FROM: <{$this->fromEmail}>");
            $this->smtpCommand($smtp, "RCPT TO: <{$to}>");
            $this->smtpCommand($smtp, "DATA");
            
            // Build and send email content
            $emailContent = $this->buildEmailContent($to, $subject, $message, $headers);
            fwrite($smtp, $emailContent . "\r\n.\r\n");
            
            $response = fgets($smtp);
            if (substr($response, 0, 3) !== '250') {
                throw new Exception("Email not accepted: $response");
            }
            
            $this->smtpCommand($smtp, "QUIT");
            fclose($smtp);
            
            return true;
            
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            if ($this->debug) {
                error_log("SMTP Error: " . $this->error);
            }
            return false;
        }
    }
    
    /**
     * Connect to SMTP server
     * 
     * @return resource|false SMTP connection
     */
    private function connectSMTP()
    {
        $host = $this->smtpConfig['host'];
        $port = $this->smtpConfig['port'];
        $security = $this->smtpConfig['security'];
        $timeout = $this->smtpConfig['timeout'];
        
        // Create context for SSL/TLS
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);
        
        // Add SSL prefix for port 465
        if ($security === 'ssl' || $port == 465) {
            $host = 'ssl://' . $host;
        }
        
        if ($this->debug) {
            error_log("Connecting to SMTP: $host:$port");
        }
        
        $smtp = stream_socket_client(
            "$host:$port", 
            $errno, 
            $errstr, 
            $timeout, 
            STREAM_CLIENT_CONNECT, 
            $context
        );
        
        if (!$smtp) {
            $this->error = "Could not connect to SMTP server $host:$port - $errstr ($errno)";
            return false;
        }
        
        // Set timeout for subsequent operations
        stream_set_timeout($smtp, $timeout);
        
        // Read server greeting
        $response = fgets($smtp, 515);
        if (!$response || substr($response, 0, 3) !== '220') {
            $this->error = "SMTP server not ready: " . ($response ?: 'No response');
            fclose($smtp);
            return false;
        }
        
        // Start TLS if required
        if ($security === 'tls' && $port != 465) {
            $this->smtpCommand($smtp, "STARTTLS");
            
            if (!stream_socket_enable_crypto($smtp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                $this->error = "Failed to enable TLS encryption";
                fclose($smtp);
                return false;
            }
        }
        
        return $smtp;
    }
    
    /**
     * Send SMTP command and check response
     * 
     * @param resource $smtp SMTP connection
     * @param string $command SMTP command
     * @return string Server response
     * @throws Exception On error
     */
    private function smtpCommand($smtp, $command)
    {
        if ($this->debug) {
            error_log("SMTP Command: $command");
        }
        
        fwrite($smtp, $command . "\r\n");
        $response = fgets($smtp, 515);
        
        if ($this->debug) {
            error_log("SMTP Response: $response");
        }
        
        if (!$response) {
            throw new Exception("No response from SMTP server");
        }
        
        $code = substr($response, 0, 3);
        
        // Check for success codes
        $successCodes = ['220', '221', '235', '250', '334', '354'];
        if (!in_array($code, $successCodes)) {
            throw new Exception("SMTP Error ($code): " . trim($response));
        }
        
        return $response;
    }
    
    /**
     * Send email via PHP mail() function
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email message
     * @param array $headers Additional headers
     * @return bool Success
     */
    private function sendPHP($to, $subject, $message, $headers = [])
    {
        $defaultHeaders = [
            'From' => $this->fromName ? "{$this->fromName} <{$this->fromEmail}>" : $this->fromEmail,
            'Reply-To' => $this->replyTo ?: $this->fromEmail,
            'Content-Type' => 'text/html; charset=UTF-8',
            'MIME-Version' => '1.0',
            'X-Mailer' => 'PHP/' . phpversion()
        ];
        
        $headers = array_merge($defaultHeaders, $headers);
        $headerString = '';
        
        foreach ($headers as $key => $value) {
            $headerString .= "$key: $value\r\n";
        }
        
        $result = mail($to, $subject, $message, trim($headerString));
        
        if (!$result) {
            $this->error = 'Failed to send email via PHP mail() function';
        }
        
        return $result;
    }
    
    /**
     * Build email content with headers
     * 
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $message Email message
     * @param array $headers Additional headers
     * @return string Email content
     */
    private function buildEmailContent($to, $subject, $message, $headers = [])
    {
        $email = "Date: " . date('r') . "\r\n";
        $email .= "To: $to\r\n";
        $email .= "Subject: " . $this->encodeHeader($subject) . "\r\n";
        $email .= "From: " . ($this->fromName ? $this->encodeHeader($this->fromName) . " <{$this->fromEmail}>" : $this->fromEmail) . "\r\n";
        $email .= "Reply-To: " . ($this->replyTo ?: $this->fromEmail) . "\r\n";
        $email .= "Content-Type: text/html; charset=UTF-8\r\n";
        $email .= "Content-Transfer-Encoding: 8bit\r\n";
        $email .= "MIME-Version: 1.0\r\n";
        $email .= "X-Mailer: Custom PHP Mailer\r\n";
        
        // Add custom headers
        foreach ($headers as $key => $value) {
            $email .= "$key: $value\r\n";
        }
        
        $email .= "\r\n" . $message;
        
        return $email;
    }
    
    /**
     * Encode email header for non-ASCII characters
     * 
     * @param string $text Header text
     * @return string Encoded header
     */
    private function encodeHeader($text)
    {
        if (mb_check_encoding($text, 'ASCII')) {
            return $text;
        }
        
        return '=?UTF-8?B?' . base64_encode($text) . '?=';
    }
    
    /**
     * Get test email body (legacy fallback)
     * 
     * @return string HTML email body
     */
    private function getTestEmailBody()
    {
        $serverName = $_SERVER['SERVER_NAME'] ?? 'localhost';
        $timestamp = date('Y-m-d H:i:s');
        
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>SMTP Test Email</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4;'>
            <div style='max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);'>
                <div style='background-color: #4361ee; color: white; padding: 20px; text-align: center;'>
                    <h1 style='margin: 0; font-size: 24px;'>✅ SMTP Test Successful</h1>
                </div>
                <div style='padding: 30px;'>
                    <p style='font-size: 16px; margin-bottom: 20px;'>Congratulations! Your SMTP configuration is working correctly.</p>
                    
                    <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                        <h3 style='margin-top: 0; color: #4361ee;'>Test Details:</h3>
                        <ul style='margin: 0; padding-left: 20px;'>
                            <li><strong>Server:</strong> $serverName</li>
                            <li><strong>Sent at:</strong> $timestamp</li>
                            <li><strong>From:</strong> {$this->fromEmail}</li>
                            <li><strong>Method:</strong> " . (!empty($this->smtpConfig['host']) ? 'SMTP' : 'PHP Mail') . "</li>
                        </ul>
                    </div>
                    
                    <p style='color: #28a745; font-weight: bold; font-size: 16px;'>
                        🎉 Your email system is ready to send notifications!
                    </p>
                    
                    <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666; text-align: center;'>
                        This is an automated test email from your website's admin panel.<br>
                        If you did not request this test, please contact your website administrator.
                    </div>
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Get booking confirmation email body (legacy fallback)
     * 
     * @param array $booking Booking data
     * @return string HTML email body
     */
    private function getBookingEmailBody($booking)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>Booking Confirmation</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #4361ee;'>Booking Confirmation</h2>
                <p>Dear {$booking['first_name']} {$booking['last_name']},</p>
                <p>Your booking has been confirmed! Here are the details:</p>
                
                <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <p><strong>Tour:</strong> " . ($booking['tour_name'] ?? 'N/A') . "</p>
                    <p><strong>Date:</strong> {$booking['booking_date']}</p>
                    <p><strong>Adults:</strong> {$booking['adults']}</p>
                    <p><strong>Children:</strong> {$booking['children']}</p>
                    <p><strong>Total Price:</strong> $" . number_format($booking['total_price'], 2) . "</p>
                </div>
                
                <p>We look forward to seeing you!</p>
                
                <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666;'>
                    Best regards,<br>
                    {$this->fromName}
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Get contact form email body (legacy fallback)
     * 
     * @param array $contact Contact form data
     * @return string HTML email body
     */
    private function getContactEmailBody($contact)
    {
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>New Contact Form Submission</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <h2 style='color: #4361ee;'>New Contact Form Submission</h2>
                
                <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0;'>
                    <p><strong>Name:</strong> " . ($contact['name'] ?? 'N/A') . "</p>
                    <p><strong>Email:</strong> " . ($contact['email'] ?? 'N/A') . "</p>
                    <p><strong>Phone:</strong> " . ($contact['phone'] ?? 'N/A') . "</p>
                    <p><strong>Subject:</strong> " . ($contact['subject'] ?? 'N/A') . "</p>
                </div>
                
                <div style='background-color: #ffffff; padding: 15px; border: 1px solid #ddd; border-radius: 5px;'>
                    <h4>Message:</h4>
                    <p>" . nl2br(htmlspecialchars($contact['message'] ?? '')) . "</p>
                </div>
                
                <div style='margin-top: 20px; font-size: 12px; color: #666;'>
                    Submitted at: " . date('Y-m-d H:i:s') . "
                </div>
            </div>
        </body>
        </html>";
    }
    
    /**
     * Get last error message
     * 
     * @return string Error message
     */
    public function getError()
    {
        return $this->error;
    }
    
    /**
     * Check if SMTP is configured
     * 
     * @return bool SMTP configured
     */
    public function isSMTPConfigured()
    {
        return !empty($this->smtpConfig['host']);
    }
    
    /**
     * Get SMTP configuration
     * 
     * @return array SMTP config
     */
    public function getSMTPConfig()
    {
        return $this->smtpConfig;
    }
}