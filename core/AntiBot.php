<?php
/**
 * Anti-Bot Protection System
 * 
 * Provides multiple layers of protection against bots:
 * - Google reCAPTCHA v2/v3
 * - Cloudflare Turnstile
 * - Honeypot fields
 * - Rate limiting
 * - IP-based blocking
 */
class AntiBot
{
    private $db;
    private $settings;
    private $session;
    
    // Protection types
    const TYPE_RECAPTCHA_V2 = 'recaptcha_v2';
    const TYPE_RECAPTCHA_V3 = 'recaptcha_v3';
    const TYPE_TURNSTILE = 'turnstile';
    const TYPE_HONEYPOT = 'honeypot';
    const TYPE_RATE_LIMIT = 'rate_limit';
    
    // Rate limit thresholds
    const RATE_LIMIT_CONTACT = 3; // 3 attempts per hour
    const RATE_LIMIT_NEWSLETTER = 5; // 5 attempts per hour
    const RATE_LIMIT_BOOKING = 10; // 10 attempts per hour
    
    public function __construct($db, $settings, $session)
    {
        $this->db = $db;
        $this->settings = $settings;
        $this->session = $session;
    }
    
    /**
     * Validate form submission against all enabled protections
     * 
     * @param string $formType Form type (contact, newsletter, booking)
     * @param array $postData POST data
     * @return array Validation result
     */
    public function validateSubmission($formType, $postData)
    {
        $result = [
            'success' => true,
            'errors' => [],
            'blocked_reason' => null
        ];
        
        // Check if anti-bot is enabled
        if (!$this->isEnabled()) {
            return $result;
        }
        
        // Check IP blocking
        if ($this->isIpBlocked()) {
            $result['success'] = false;
            $result['blocked_reason'] = 'ip_blocked';
            $result['errors'][] = __('your_ip_is_blocked');
            return $result;
        }
        
        // Check rate limiting
        $rateLimitResult = $this->checkRateLimit($formType);
        if (!$rateLimitResult['success']) {
            $result['success'] = false;
            $result['blocked_reason'] = 'rate_limit';
            $result['errors'][] = $rateLimitResult['message'];
            return $result;
        }
        
        // Check honeypot
        if ($this->isHoneypotEnabled()) {
            $honeypotResult = $this->validateHoneypot($postData);
            if (!$honeypotResult['success']) {
                $result['success'] = false;
                $result['blocked_reason'] = 'honeypot';
                $result['errors'][] = __('spam_detected');
                $this->logBotAttempt('honeypot', $_SERVER['REMOTE_ADDR'], $formType);
                return $result;
            }
        }
        
        // Check reCAPTCHA v2
        if ($this->isRecaptchaV2Enabled()) {
            $recaptchaResult = $this->validateRecaptchaV2($postData);
            if (!$recaptchaResult['success']) {
                $result['success'] = false;
                $result['blocked_reason'] = 'recaptcha_v2';
                $result['errors'][] = $recaptchaResult['message'];
                return $result;
            }
        }
        
        // Check reCAPTCHA v3
        if ($this->isRecaptchaV3Enabled()) {
            $recaptchaResult = $this->validateRecaptchaV3($postData);
            if (!$recaptchaResult['success']) {
                $result['success'] = false;
                $result['blocked_reason'] = 'recaptcha_v3';
                $result['errors'][] = $recaptchaResult['message'];
                return $result;
            }
        }
        
        // Check Cloudflare Turnstile
        if ($this->isTurnstileEnabled()) {
            $turnstileResult = $this->validateTurnstile($postData);
            if (!$turnstileResult['success']) {
                $result['success'] = false;
                $result['blocked_reason'] = 'turnstile';
                $result['errors'][] = $turnstileResult['message'];
                return $result;
            }
        }
        
        // Log successful attempt
        $this->logAttempt($formType, $_SERVER['REMOTE_ADDR'], true);
        
        return $result;
    }
    
    /**
     * Check if anti-bot protection is enabled
     */
    public function isEnabled()
    {
        return isset($this->settings['antibot_enabled']) && $this->settings['antibot_enabled'] == '1';
    }
    
    /**
     * Check if IP is blocked
     */
    public function isIpBlocked()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        
        // Check permanent IP blocks
        $sql = "SELECT id FROM ip_blocks WHERE ip_address = :ip AND (expires_at IS NULL OR expires_at > NOW())";
        return (bool) $this->db->getValue($sql, ['ip' => $ip]);
    }
    
    /**
     * Check rate limiting for form type
     */
    public function checkRateLimit($formType)
    {
        if (!$this->isRateLimitEnabled()) {
            return ['success' => true];
        }
        
        $ip = $_SERVER['REMOTE_ADDR'];
        $threshold = $this->getRateLimitThreshold($formType);
        
        // Check attempts in the last hour
        $sql = "SELECT COUNT(*) FROM form_attempts 
                WHERE ip_address = :ip AND form_type = :form_type 
                AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)";
        
        $attempts = $this->db->getValue($sql, [
            'ip' => $ip,
            'form_type' => $formType
        ]);
        
        if ($attempts >= $threshold) {
            return [
                'success' => false,
                'message' => sprintf(__('rate_limit_exceeded'), $threshold)
            ];
        }
        
        return ['success' => true];
    }
    
    /**
     * Validate honeypot field
     */
    public function validateHoneypot($postData)
    {
        $honeypotField = $this->getHoneypotFieldName();
        
        // If honeypot field is filled, it's a bot
        if (!empty($postData[$honeypotField])) {
            return [
                'success' => false,
                'message' => __('spam_detected')
            ];
        }
        
        // Check time-based validation
        $timeField = $honeypotField . '_time';
        if (isset($postData[$timeField])) {
            $submitTime = time();
            $startTime = (int) $postData[$timeField];
            $timeDiff = $submitTime - $startTime;
            
            // If form was submitted too quickly (less than 3 seconds), likely a bot
            if ($timeDiff < 3) {
                return [
                    'success' => false,
                    'message' => __('form_submitted_too_quickly')
                ];
            }
        }
        
        return ['success' => true];
    }
    
    /**
     * Validate Google reCAPTCHA v2
     */
    public function validateRecaptchaV2($postData)
    {
        if (empty($postData['g-recaptcha-response'])) {
            return [
                'success' => false,
                'message' => __('please_complete_captcha')
            ];
        }
        
        $secretKey = $this->settings['recaptcha_v2_secret_key'] ?? '';
        $response = $postData['g-recaptcha-response'];
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        
        $result = $this->makeHttpRequest($url, $data);
        
        if ($result && isset($result['success']) && $result['success']) {
            return ['success' => true];
        }
        
        return [
            'success' => false,
            'message' => __('captcha_verification_failed')
        ];
    }
    
    /**
     * Validate Google reCAPTCHA v3
     */
    public function validateRecaptchaV3($postData)
    {
        if (empty($postData['g-recaptcha-response'])) {
            return [
                'success' => false,
                'message' => __('captcha_verification_required')
            ];
        }
        
        $secretKey = $this->settings['recaptcha_v3_secret_key'] ?? '';
        $response = $postData['g-recaptcha-response'];
        $minScore = (float) ($this->settings['recaptcha_v3_min_score'] ?? 0.5);
        
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        
        $result = $this->makeHttpRequest($url, $data);
        
        if ($result && isset($result['success']) && $result['success']) {
            $score = $result['score'] ?? 0;
            
            if ($score >= $minScore) {
                return ['success' => true];
            } else {
                return [
                    'success' => false,
                    'message' => __('suspicious_activity_detected')
                ];
            }
        }
        
        return [
            'success' => false,
            'message' => __('captcha_verification_failed')
        ];
    }
    
    /**
     * Validate Cloudflare Turnstile
     */
    public function validateTurnstile($postData)
    {
        if (empty($postData['cf-turnstile-response'])) {
            return [
                'success' => false,
                'message' => __('please_complete_verification')
            ];
        }
        
        $secretKey = $this->settings['turnstile_secret_key'] ?? '';
        $response = $postData['cf-turnstile-response'];
        
        $url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ];
        
        $result = $this->makeHttpRequest($url, $data);
        
        if ($result && isset($result['success']) && $result['success']) {
            return ['success' => true];
        }
        
        return [
            'success' => false,
            'message' => __('verification_failed')
        ];
    }
    
    /**
     * Get honeypot field name
     */
    public function getHoneypotFieldName()
    {
        return $this->settings['honeypot_field_name'] ?? 'website';
    }
    
    /**
     * Generate honeypot HTML fields
     */
    public function generateHoneypotFields()
    {
        if (!$this->isHoneypotEnabled()) {
            return '';
        }
        
        $fieldName = $this->getHoneypotFieldName();
        $timeField = $fieldName . '_time';
        $currentTime = time();
        
        return '
        <div style="position: absolute; left: -5000px; opacity: 0;" aria-hidden="true">
            <input type="text" name="' . $fieldName . '" value="" tabindex="-1" autocomplete="off">
            <input type="hidden" name="' . $timeField . '" value="' . $currentTime . '">
        </div>';
    }
    
    /**
     * Get reCAPTCHA v2 HTML
     */
    public function getRecaptchaV2Html()
    {
        if (!$this->isRecaptchaV2Enabled()) {
            return '';
        }
        
        $siteKey = $this->settings['recaptcha_v2_site_key'] ?? '';
        
        return '
        <div class="g-recaptcha" data-sitekey="' . htmlspecialchars($siteKey) . '"></div>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>';
    }
    
    /**
     * Get reCAPTCHA v3 HTML
     */
    public function getRecaptchaV3Html($action)
    {
        if (!$this->isRecaptchaV3Enabled()) {
            return '';
        }
        
        $siteKey = $this->settings['recaptcha_v3_site_key'] ?? '';
        
        return '
        <script src="https://www.google.com/recaptcha/api.js?render=' . $siteKey . '"></script>
        <script>
            grecaptcha.ready(function() {
                grecaptcha.execute("' . $siteKey . '", {action: "' . $action . '"}).then(function(token) {
                    var recaptchaInput = document.createElement("input");
                    recaptchaInput.type = "hidden";
                    recaptchaInput.name = "g-recaptcha-response";
                    recaptchaInput.value = token;
                    document.querySelector("form").appendChild(recaptchaInput);
                });
            });
        </script>';
    }
    
    /**
     * Get Turnstile HTML
     */
    public function getTurnstileHtml()
    {
        if (!$this->isTurnstileEnabled()) {
            return '';
        }
        
        $siteKey = $this->settings['turnstile_site_key'] ?? '';
        
        return '
        <div class="cf-turnstile" data-sitekey="' . htmlspecialchars($siteKey) . '"></div>
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>';
    }
    
    /**
     * Check if specific protection is enabled
     */
    public function isRecaptchaV2Enabled()
    {
        return isset($this->settings['antibot_recaptcha_v2_enabled']) && 
               $this->settings['antibot_recaptcha_v2_enabled'] == '1' &&
               !empty($this->settings['recaptcha_v2_site_key']) &&
               !empty($this->settings['recaptcha_v2_secret_key']);
    }
    
    public function isRecaptchaV3Enabled()
    {
        return isset($this->settings['antibot_recaptcha_v3_enabled']) && 
               $this->settings['antibot_recaptcha_v3_enabled'] == '1' &&
               !empty($this->settings['recaptcha_v3_site_key']) &&
               !empty($this->settings['recaptcha_v3_secret_key']);
    }
    
    public function isTurnstileEnabled()
    {
        return isset($this->settings['antibot_turnstile_enabled']) && 
               $this->settings['antibot_turnstile_enabled'] == '1' &&
               !empty($this->settings['turnstile_site_key']) &&
               !empty($this->settings['turnstile_secret_key']);
    }
    
    public function isHoneypotEnabled()
    {
        return isset($this->settings['antibot_honeypot_enabled']) && 
               $this->settings['antibot_honeypot_enabled'] == '1';
    }
    
    public function isRateLimitEnabled()
    {
        return isset($this->settings['antibot_rate_limit_enabled']) && 
               $this->settings['antibot_rate_limit_enabled'] == '1';
    }
    
    /**
     * Get rate limit threshold for form type
     */
    private function getRateLimitThreshold($formType)
    {
        switch ($formType) {
            case 'contact':
                return (int) ($this->settings['antibot_rate_limit_contact'] ?? self::RATE_LIMIT_CONTACT);
            case 'newsletter':
                return (int) ($this->settings['antibot_rate_limit_newsletter'] ?? self::RATE_LIMIT_NEWSLETTER);
            case 'booking':
                return (int) ($this->settings['antibot_rate_limit_booking'] ?? self::RATE_LIMIT_BOOKING);
            default:
                return 5;
        }
    }
    
    /**
     * Log form attempt
     */
    public function logAttempt($formType, $ip, $success = true)
    {
        if (!$this->isRateLimitEnabled()) {
            return;
        }
        
        $data = [
            'form_type' => $formType,
            'ip_address' => $ip,
            'success' => $success ? 1 : 0,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('form_attempts', $data);
    }
    
    /**
     * Log bot attempt
     */
    public function logBotAttempt($protectionType, $ip, $formType)
    {
        $data = [
            'protection_type' => $protectionType,
            'ip_address' => $ip,
            'form_type' => $formType,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->insert('bot_attempts', $data);
        
        // Auto-block IP if too many bot attempts
        $this->checkAutoBlock($ip);
    }
    
    /**
     * Check if IP should be auto-blocked
     */
    private function checkAutoBlock($ip)
    {
        $threshold = (int) ($this->settings['antibot_auto_block_threshold'] ?? 5);
        
        if ($threshold <= 0) {
            return;
        }
        
        // Check bot attempts in last 24 hours
        $sql = "SELECT COUNT(*) FROM bot_attempts 
                WHERE ip_address = :ip AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)";
        
        $attempts = $this->db->getValue($sql, ['ip' => $ip]);
        
        if ($attempts >= $threshold) {
            $this->blockIp($ip, 'auto_block', 24); // Block for 24 hours
        }
    }
    
    /**
     * Block IP address
     */
    public function blockIp($ip, $reason, $hours = null)
    {
        $data = [
            'ip_address' => $ip,
            'reason' => $reason,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($hours) {
            $data['expires_at'] = date('Y-m-d H:i:s', strtotime("+{$hours} hours"));
        }
        
        // Check if IP is already blocked
        $existing = $this->db->getValue("SELECT id FROM ip_blocks WHERE ip_address = :ip", ['ip' => $ip]);
        
        if ($existing) {
            $this->db->update('ip_blocks', $data, ['id' => $existing]);
        } else {
            $this->db->insert('ip_blocks', $data);
        }
    }
    
    /**
     * Make HTTP request for captcha verification
     */
    private function makeHttpRequest($url, $data)
    {
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
                'timeout' => 10
            ]
        ];
        
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        
        if ($result === false) {
            return null;
        }
        
        return json_decode($result, true);
    }
    
    /**
     * Clean old records
     */
    public function cleanOldRecords()
    {
        // Clean old form attempts (older than 7 days)
        $this->db->executeQuery("DELETE FROM form_attempts WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)");
        
        // Clean old bot attempts (older than 30 days)
        $this->db->executeQuery("DELETE FROM bot_attempts WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        
        // Clean expired IP blocks
        $this->db->executeQuery("DELETE FROM ip_blocks WHERE expires_at IS NOT NULL AND expires_at < NOW()");
    }
    
    /**
     * Get protection statistics
     */
    public function getStatistics($days = 7)
    {
        $stats = [];
        
        // Form attempts
        $sql = "SELECT form_type, COUNT(*) as total, SUM(success) as successful
                FROM form_attempts 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY form_type";
        
        $stats['form_attempts'] = $this->db->getRows($sql, ['days' => $days]);
        
        // Bot attempts
        $sql = "SELECT protection_type, COUNT(*) as total
                FROM bot_attempts 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY protection_type";
        
        $stats['bot_attempts'] = $this->db->getRows($sql, ['days' => $days]);
        
        // IP blocks
        $sql = "SELECT COUNT(*) as total FROM ip_blocks WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)";
        $stats['ip_blocks'] = $this->db->getValue($sql, ['days' => $days]);
        
        return $stats;
    }
}