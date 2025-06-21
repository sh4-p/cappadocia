<?php
/**
 * Email Helper Functions
 * 
 * Helper functions for email operations
 */

/**
 * Send email using template
 * 
 * @param string $templateKey Template key
 * @param string $toEmail Recipient email
 * @param array $variables Template variables
 * @return bool Success
 */
function sendEmail($templateKey, $toEmail, $variables = [])
{
    try {
        require_once BASE_PATH . '/core/Email.php';
        $email = new Email();
        
        return $email->sendTemplate($templateKey, $toEmail, $variables);
    } catch (Exception $e) {
        error_log("Email helper error: " . $e->getMessage());
        return false;
    }
}

/**
 * Send booking confirmation email
 * 
 * @param array $booking Booking data
 * @return bool Success
 */
function sendBookingConfirmation($booking)
{
    try {
        require_once BASE_PATH . '/core/Email.php';
        $email = new Email();
        
        return $email->sendBookingConfirmation($booking);
    } catch (Exception $e) {
        error_log("Booking confirmation email error: " . $e->getMessage());
        return false;
    }
}

/**
 * Send contact form email
 * 
 * @param array $contact Contact data
 * @return bool Success
 */
function sendContactEmail($contact)
{
    try {
        require_once BASE_PATH . '/core/Email.php';
        $email = new Email();
        
        return $email->sendContactEmail($contact);
    } catch (Exception $e) {
        error_log("Contact form email error: " . $e->getMessage());
        return false;
    }
}

/**
 * Send test email
 * 
 * @param string $toEmail Recipient email
 * @return bool Success
 */
function sendTestEmail($toEmail)
{
    try {
        require_once BASE_PATH . '/core/Email.php';
        $email = new Email();
        
        return $email->sendTestEmail($toEmail);
    } catch (Exception $e) {
        error_log("Test email error: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if email notifications are enabled for a specific type
 * 
 * @param string $type Notification type (booking, contact, etc.)
 * @return bool Enabled
 */
function isEmailNotificationEnabled($type)
{
    try {
        $db = new Database();
        $settingsModel = new Settings($db);
        
        $settingKey = 'email_notification_' . $type;
        return $settingsModel->getSetting($settingKey, '0') === '1';
    } catch (Exception $e) {
        error_log("Email notification check error: " . $e->getMessage());
        return false;
    }
}

/**
 * Get email settings
 * 
 * @return array Email settings
 */
function getEmailSettings()
{
    try {
        $db = new Database();
        $settingsModel = new Settings($db);
        
        return [
            'from_email' => $settingsModel->getSetting('email_from_address', ''),
            'from_name' => $settingsModel->getSetting('email_from_name', ''),
            'reply_to' => $settingsModel->getSetting('email_reply_to', ''),
            'admin_email' => $settingsModel->getSetting('email_admin', ''),
            'smtp_enabled' => $settingsModel->getSetting('smtp_enabled', '0') === '1',
            'smtp_host' => $settingsModel->getSetting('smtp_host', ''),
            'smtp_port' => $settingsModel->getSetting('smtp_port', '587'),
            'smtp_security' => $settingsModel->getSetting('smtp_security', 'tls'),
            'smtp_auth' => $settingsModel->getSetting('smtp_auth', '0') === '1',
            'smtp_username' => $settingsModel->getSetting('smtp_username', ''),
            'notifications' => [
                'booking' => $settingsModel->getSetting('email_notification_booking', '0') === '1',
                'booking_status' => $settingsModel->getSetting('email_notification_booking_status', '0') === '1',
                'contact' => $settingsModel->getSetting('email_notification_contact', '0') === '1',
                'registration' => $settingsModel->getSetting('email_notification_registration', '0') === '1',
                'newsletter' => $settingsModel->getSetting('email_notification_newsletter', '0') === '1'
            ]
        ];
    } catch (Exception $e) {
        error_log("Email settings error: " . $e->getMessage());
        return [];
    }
}

/**
 * Format email address with name
 * 
 * @param string $email Email address
 * @param string $name Name (optional)
 * @return string Formatted email
 */
function formatEmailAddress($email, $name = '')
{
    if (empty($name)) {
        return $email;
    }
    
    // Escape name for email header
    $name = str_replace(['"', '\\'], ['\"', '\\\\'], $name);
    
    return "\"{$name}\" <{$email}>";
}

/**
 * Validate email address
 * 
 * @param string $email Email address
 * @return bool Valid
 */
function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Get available email templates
 * 
 * @return array Email templates
 */
function getEmailTemplates()
{
    try {
        $db = new Database();
        $emailTemplateModel = new EmailTemplate($db);
        
        return $emailTemplateModel->getAll(['is_active' => 1], 'name ASC');
    } catch (Exception $e) {
        error_log("Email templates error: " . $e->getMessage());
        return [];
    }
}

/**
 * Get email template by key
 * 
 * @param string $templateKey Template key
 * @return array|false Template data
 */
function getEmailTemplate($templateKey)
{
    try {
        $db = new Database();
        $emailTemplateModel = new EmailTemplate($db);
        
        return $emailTemplateModel->getByKey($templateKey);
    } catch (Exception $e) {
        error_log("Email template get error: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if SMTP is configured
 * 
 * @return bool SMTP configured
 */
function isSMTPConfigured()
{
    try {
        $settings = getEmailSettings();
        
        return $settings['smtp_enabled'] && 
               !empty($settings['smtp_host']) && 
               !empty($settings['from_email']);
    } catch (Exception $e) {
        error_log("SMTP configuration check error: " . $e->getMessage());
        return false;
    }
}

/**
 * Log email activity
 * 
 * @param string $type Email type
 * @param string $recipient Recipient email
 * @param bool $success Success status
 * @param string $error Error message (if any)
 */
function logEmailActivity($type, $recipient, $success, $error = '')
{
    try {
        $logData = [
            'type' => $type,
            'recipient' => $recipient,
            'success' => $success,
            'error' => $error,
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ];
        
        $logEntry = json_encode($logData);
        
        // Write to email log
        $logFile = BASE_PATH . '/logs/email.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, date('Y-m-d H:i:s') . " " . $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);
        
    } catch (Exception $e) {
        error_log("Email logging error: " . $e->getMessage());
    }
}

/**
 * Queue email for later sending (if queue system exists)
 * 
 * @param string $templateKey Template key
 * @param string $toEmail Recipient email
 * @param array $variables Template variables
 * @param int $priority Priority (1-10, 1 highest)
 * @return bool Success
 */
function queueEmail($templateKey, $toEmail, $variables = [], $priority = 5)
{
    try {
        // Check if email_queue table exists
        $db = new Database();
        $sql = "SHOW TABLES LIKE 'email_queue'";
        $tableExists = $db->getValue($sql);
        
        if (!$tableExists) {
            // No queue table, send immediately
            return sendEmail($templateKey, $toEmail, $variables);
        }
        
        // Add to queue
        return $db->insert('email_queue', [
            'template_key' => $templateKey,
            'to_email' => $toEmail,
            'variables' => json_encode($variables),
            'priority' => $priority,
            'status' => 'pending',
            'attempts' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
    } catch (Exception $e) {
        error_log("Email queue error: " . $e->getMessage());
        // Fallback to immediate sending
        return sendEmail($templateKey, $toEmail, $variables);
    }
}