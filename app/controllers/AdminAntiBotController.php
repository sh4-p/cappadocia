<?php
/**
 * Admin Anti-Bot Controller
 * 
 * Handles anti-bot system management in admin panel
 */
class AdminAntiBotController extends Controller
{
    private $antiBotModel;
    private $settingsModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Require login
        $this->requireLogin();
        
        // Load models
        $this->antiBotModel = $this->loadModel('AntiBotModel');
        $this->settingsModel = $this->loadModel('Settings');
    }
    
    /**
     * Get controller name
     */
    public function getControllerName()
    {
        return 'AdminAntiBot';
    }
    
    /**
     * Get action name
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
     * Index action - Anti-bot dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = $this->antiBotModel->getStatistics(7); // Last 7 days
        $overallStats = $this->antiBotModel->getOverallStatistics();
        
        // Get recent bot attempts
        $recentAttempts = $this->antiBotModel->getRecentBotAttempts(20);
        
        // Get blocked IPs
        $blockedIps = $this->antiBotModel->getBlockedIPs();
        
        // Get settings
        $settings = $this->settingsModel->getAllSettings();
        
        // Check if anti-bot is enabled
        $isEnabled = isset($settings['antibot_enabled']) && $settings['antibot_enabled'] == '1';
        
        $data = [
            'pageTitle' => __('antibot_management'),
            'stats' => $stats,
            'overallStats' => $overallStats,
            'recentAttempts' => $recentAttempts,
            'blockedIps' => $blockedIps,
            'isEnabled' => $isEnabled,
            'settings' => $settings
        ];
        
        $this->render('admin/antibot/index', $data, 'admin');
    }
    
    /**
     * Settings action - Anti-bot configuration
     */
    public function settings()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateSettings();
        }
        
        // Get current settings
        $settings = $this->settingsModel->getAllSettings();
        
        $data = [
            'pageTitle' => __('antibot_settings'),
            'settings' => $settings
        ];
        
        $this->render('admin/antibot/settings', $data, 'admin');
    }
    
    /**
     * Update anti-bot settings
     */
    private function updateSettings()
    {
        $settings = [
            // General settings
            'antibot_enabled' => $this->post('antibot_enabled', '0'),
            
            // reCAPTCHA v2 settings
            'antibot_recaptcha_v2_enabled' => $this->post('antibot_recaptcha_v2_enabled', '0'),
            'recaptcha_v2_site_key' => trim($this->post('recaptcha_v2_site_key', '')),
            'recaptcha_v2_secret_key' => trim($this->post('recaptcha_v2_secret_key', '')),
            
            // reCAPTCHA v3 settings
            'antibot_recaptcha_v3_enabled' => $this->post('antibot_recaptcha_v3_enabled', '0'),
            'recaptcha_v3_site_key' => trim($this->post('recaptcha_v3_site_key', '')),
            'recaptcha_v3_secret_key' => trim($this->post('recaptcha_v3_secret_key', '')),
            'recaptcha_v3_min_score' => $this->post('recaptcha_v3_min_score', '0.5'),
            
            // Turnstile settings
            'antibot_turnstile_enabled' => $this->post('antibot_turnstile_enabled', '0'),
            'turnstile_site_key' => trim($this->post('turnstile_site_key', '')),
            'turnstile_secret_key' => trim($this->post('turnstile_secret_key', '')),
            
            // Honeypot settings
            'antibot_honeypot_enabled' => $this->post('antibot_honeypot_enabled', '0'),
            'honeypot_field_name' => trim($this->post('honeypot_field_name', 'website')),
            
            // Rate limiting settings
            'antibot_rate_limit_enabled' => $this->post('antibot_rate_limit_enabled', '0'),
            'antibot_rate_limit_contact' => (int) $this->post('antibot_rate_limit_contact', '3'),
            'antibot_rate_limit_newsletter' => (int) $this->post('antibot_rate_limit_newsletter', '5'),
            'antibot_rate_limit_booking' => (int) $this->post('antibot_rate_limit_booking', '10'),
            
            // Auto-blocking settings
            'antibot_auto_block_threshold' => (int) $this->post('antibot_auto_block_threshold', '5')
        ];
        
        // Update each setting
        $updateCount = 0;
        foreach ($settings as $key => $value) {
            if ($this->settingsModel->updateSetting($key, $value)) {
                $updateCount++;
            }
        }
        
        if ($updateCount > 0) {
            $this->session->setFlash('success', __('antibot_settings_updated'));
        } else {
            $this->session->setFlash('error', __('antibot_settings_update_failed'));
        }
        
        $this->redirect('admin/antibot/settings');
    }
    
    /**
     * Bot attempts log
     */
    public function attempts()
    {
        $page = (int) $this->get('page', 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $filters = [
            'protection_type' => $this->get('protection_type'),
            'form_type' => $this->get('form_type'),
            'date_from' => $this->get('date_from'),
            'date_to' => $this->get('date_to')
        ];
        
        // Get bot attempts
        $attempts = $this->antiBotModel->getBotAttempts($filters, $limit, $offset);
        $totalAttempts = $this->antiBotModel->countBotAttempts($filters);
        $totalPages = ceil($totalAttempts / $limit);
        
        // Get filter options
        $protectionTypes = $this->antiBotModel->getProtectionTypes();
        $formTypes = $this->antiBotModel->getFormTypes();
        
        $data = [
            'pageTitle' => __('bot_attempts_log'),
            'attempts' => $attempts,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalAttempts' => $totalAttempts,
            'filters' => $filters,
            'protectionTypes' => $protectionTypes,
            'formTypes' => $formTypes
        ];
        
        $this->render('admin/antibot/attempts', $data, 'admin');
    }
    
    /**
     * IP blocks management
     */
    public function blocks()
    {
        $page = (int) $this->get('page', 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        // Get blocked IPs
        $blocks = $this->antiBotModel->getIPBlocks($limit, $offset);
        $totalBlocks = $this->antiBotModel->countIPBlocks();
        $totalPages = ceil($totalBlocks / $limit);
        
        $data = [
            'pageTitle' => __('ip_blocks_management'),
            'blocks' => $blocks,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalBlocks' => $totalBlocks
        ];
        
        $this->render('admin/antibot/blocks', $data, 'admin');
    }
    
    /**
     * Add IP block
     */
    public function addBlock()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ipAddress = trim($this->post('ip_address'));
            $reason = trim($this->post('reason'));
            $hours = (int) $this->post('hours', 0);
            
            // Validate IP address
            if (!filter_var($ipAddress, FILTER_VALIDATE_IP)) {
                $this->session->setFlash('error', __('invalid_ip_address'));
                $this->redirect('admin/antibot/blocks');
            }
            
            // Initialize AntiBot
            require_once BASE_PATH . '/core/AntiBot.php';
            $settings = $this->settingsModel->getAllSettings();
            $antiBot = new AntiBot($this->db, $settings, $this->session);
            
            // Block IP
            $antiBot->blockIp($ipAddress, $reason, $hours > 0 ? $hours : null);
            
            $this->session->setFlash('success', __('ip_blocked_successfully'));
        }
        
        $this->redirect('admin/antibot/blocks');
    }
    
    /**
     * Remove IP block
     */
    public function removeBlock($id)
    {
        $result = $this->antiBotModel->removeIPBlock($id);
        
        if ($result) {
            $this->session->setFlash('success', __('ip_block_removed'));
        } else {
            $this->session->setFlash('error', __('ip_block_remove_failed'));
        }
        
        $this->redirect('admin/antibot/blocks');
    }
    
    /**
     * Statistics page
     */
    public function statistics()
    {
        $days = (int) $this->get('days', 30);
        
        // Get statistics
        $stats = $this->antiBotModel->getDetailedStatistics($days);
        $chartData = $this->antiBotModel->getChartData($days);
        
        $data = [
            'pageTitle' => __('antibot_statistics'),
            'stats' => $stats,
            'chartData' => $chartData,
            'days' => $days
        ];
        
        $this->render('admin/antibot/statistics', $data, 'admin');
    }
    
    /**
     * Clean old records
     */
    public function clean()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Initialize AntiBot
            require_once BASE_PATH . '/core/AntiBot.php';
            $settings = $this->settingsModel->getAllSettings();
            $antiBot = new AntiBot($this->db, $settings, $this->session);
            
            // Clean old records
            $antiBot->cleanOldRecords();
            
            $this->session->setFlash('success', __('old_records_cleaned'));
        }
        
        $this->redirect('admin/antibot');
    }
    
    /**
     * Test anti-bot system
     */
    public function test()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $testType = $this->post('test_type');
            
            // Initialize AntiBot
            require_once BASE_PATH . '/core/AntiBot.php';
            $settings = $this->settingsModel->getAllSettings();
            $antiBot = new AntiBot($this->db, $settings, $this->session);
            
            $result = ['success' => false, 'message' => ''];
            
            switch ($testType) {
                case 'recaptcha_v2':
                    if ($antiBot->isRecaptchaV2Enabled()) {
                        $result['success'] = true;
                        $result['message'] = __('recaptcha_v2_configured');
                    } else {
                        $result['message'] = __('recaptcha_v2_not_configured');
                    }
                    break;
                    
                case 'recaptcha_v3':
                    if ($antiBot->isRecaptchaV3Enabled()) {
                        $result['success'] = true;
                        $result['message'] = __('recaptcha_v3_configured');
                    } else {
                        $result['message'] = __('recaptcha_v3_not_configured');
                    }
                    break;
                    
                case 'turnstile':
                    if ($antiBot->isTurnstileEnabled()) {
                        $result['success'] = true;
                        $result['message'] = __('turnstile_configured');
                    } else {
                        $result['message'] = __('turnstile_not_configured');
                    }
                    break;
                    
                case 'honeypot':
                    if ($antiBot->isHoneypotEnabled()) {
                        $result['success'] = true;
                        $result['message'] = __('honeypot_enabled');
                    } else {
                        $result['message'] = __('honeypot_disabled');
                    }
                    break;
                    
                case 'rate_limit':
                    if ($antiBot->isRateLimitEnabled()) {
                        $result['success'] = true;
                        $result['message'] = __('rate_limit_enabled');
                    } else {
                        $result['message'] = __('rate_limit_disabled');
                    }
                    break;
            }
            
            $this->json($result);
        }
        
        $this->redirect('admin/antibot');
    }
    
    /**
     * Export bot attempts log
     */
    public function export()
    {
        $filters = [
            'protection_type' => $this->get('protection_type'),
            'form_type' => $this->get('form_type'),
            'date_from' => $this->get('date_from'),
            'date_to' => $this->get('date_to')
        ];
        
        $attempts = $this->antiBotModel->exportBotAttempts($filters);
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="bot_attempts_' . date('Y-m-d') . '.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add headers
        fputcsv($output, [
            'ID',
            'Protection Type',
            'IP Address',
            'Form Type',
            'User Agent',
            'Created At'
        ]);
        
        // Add attempts
        foreach ($attempts as $attempt) {
            fputcsv($output, [
                $attempt['id'],
                $attempt['protection_type'],
                $attempt['ip_address'],
                $attempt['form_type'],
                $attempt['user_agent'],
                $attempt['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
}