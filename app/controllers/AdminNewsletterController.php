<?php
/**
 * Admin Newsletter Controller
 * 
 * Handles newsletter management in admin panel
 */
class AdminNewsletterController extends Controller
{
    private $newsletterModel;
    private $campaignModel;
    
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Require login
        $this->requireLogin();
        
        // Load models
        $this->newsletterModel = $this->loadModel('Newsletter');
        $this->campaignModel = $this->loadModel('NewsletterCampaign');
    }
    
    /**
     * Get controller name
     * 
     * @return string Controller name
     */
    public function getControllerName()
    {
        return 'AdminNewsletter';
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
     * Index action - display newsletter dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = $this->newsletterModel->getStatistics();
        
        // Get recent subscribers
        $recentSubscribers = $this->newsletterModel->getSubscribers(null, null, 10);
        
        // Get recent campaigns
        $langCode = $this->language->getCurrentLanguage();
        $recentCampaigns = $this->campaignModel->getAllWithDetails($langCode, [], 'nc.id DESC', 5);
        
        // Render view
        $this->render('admin/newsletter/index', [
            'pageTitle' => __('newsletter'),
            'stats' => $stats,
            'recentSubscribers' => $recentSubscribers,
            'recentCampaigns' => $recentCampaigns
        ], 'admin');
    }
    
    /**
     * Subscribers action - manage subscribers
     */
    public function subscribers()
    {
        // Get filters
        $status = $this->get('status');
        $search = $this->get('search');
        $page = (int) $this->get('page', 1);
        
        // Set pagination
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        // Get subscribers
        $subscribers = $this->newsletterModel->getSubscribers($status, $search, $limit, $offset);
        $totalSubscribers = $this->newsletterModel->countSubscribers($status, $search);
        $totalPages = ceil($totalSubscribers / $limit);
        
        // Get statistics for filters
        $stats = $this->newsletterModel->getStatistics();
        
        // Render view
        $this->render('admin/newsletter/subscribers', [
            'pageTitle' => __('newsletter_subscribers'),
            'subscribers' => $subscribers,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalSubscribers' => $totalSubscribers,
            'status' => $status,
            'search' => $search
        ], 'admin');
    }
    
    /**
     * Add subscriber action
     */
    public function addSubscriber()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($this->post('email'));
            $name = trim($this->post('name'));
            $status = $this->post('status', 'active');
            
            // Validate inputs
            $errors = [];
            
            if (empty($email)) {
                $errors[] = __('email_required');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = __('invalid_email');
            } elseif ($this->newsletterModel->getByEmail($email)) {
                $errors[] = __('email_already_exists');
            }
            
            if (empty($errors)) {
                // Create subscriber directly
                $data = [
                    'email' => $email,
                    'name' => $name,
                    'status' => $status,
                    'token' => bin2hex(random_bytes(32))
                ];
                
                if ($status === 'active') {
                    $data['subscribed_at'] = date('Y-m-d H:i:s');
                }
                
                $id = $this->newsletterModel->insert($data);
                
                if ($id) {
                    $this->session->setFlash('success', __('subscriber_added'));
                    $this->redirect('admin/newsletter/subscribers');
                } else {
                    $this->session->setFlash('error', __('subscriber_add_failed'));
                }
            } else {
                $this->session->setFlash('error', implode('<br>', $errors));
            }
        }
        
        // Render form
        $this->render('admin/newsletter/add-subscriber', [
            'pageTitle' => __('add_subscriber')
        ], 'admin');
    }
    
    /**
     * Edit subscriber action
     * 
     * @param int $id Subscriber ID
     */
    public function editSubscriber($id)
    {
        $subscriber = $this->newsletterModel->getById($id);
        
        if (!$subscriber) {
            $this->session->setFlash('error', __('subscriber_not_found'));
            $this->redirect('admin/newsletter/subscribers');
        }
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($this->post('email'));
            $name = trim($this->post('name'));
            $status = $this->post('status');
            
            // Validate inputs
            $errors = [];
            
            if (empty($email)) {
                $errors[] = __('email_required');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = __('invalid_email');
            } else {
                $existing = $this->newsletterModel->getByEmail($email);
                if ($existing && $existing['id'] != $id) {
                    $errors[] = __('email_already_exists');
                }
            }
            
            if (empty($errors)) {
                $data = [
                    'email' => $email,
                    'name' => $name,
                    'status' => $status
                ];
                
                // Update timestamps based on status
                if ($status === 'active' && !$subscriber['subscribed_at']) {
                    $data['subscribed_at'] = date('Y-m-d H:i:s');
                } elseif ($status === 'unsubscribed' && !$subscriber['unsubscribed_at']) {
                    $data['unsubscribed_at'] = date('Y-m-d H:i:s');
                }
                
                $result = $this->newsletterModel->update($data, ['id' => $id]);
                
                if ($result) {
                    $this->session->setFlash('success', __('subscriber_updated'));
                    $this->redirect('admin/newsletter/subscribers');
                } else {
                    $this->session->setFlash('error', __('subscriber_update_failed'));
                }
            } else {
                $this->session->setFlash('error', implode('<br>', $errors));
            }
        }
        
        // Render form
        $this->render('admin/newsletter/edit-subscriber', [
            'pageTitle' => __('edit_subscriber'),
            'subscriber' => $subscriber
        ], 'admin');
    }
    
    /**
     * Delete subscriber action
     * 
     * @param int $id Subscriber ID
     */
    public function deleteSubscriber($id)
    {
        $subscriber = $this->newsletterModel->getById($id);
        
        if (!$subscriber) {
            $this->session->setFlash('error', __('subscriber_not_found'));
            $this->redirect('admin/newsletter/subscribers');
        }
        
        $result = $this->newsletterModel->deleteSubscriber($id);
        
        if ($result) {
            $this->session->setFlash('success', __('subscriber_deleted'));
        } else {
            $this->session->setFlash('error', __('subscriber_delete_failed'));
        }
        
        $this->redirect('admin/newsletter/subscribers');
    }
    
    /**
     * Bulk actions for subscribers
     */
    public function bulkAction()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('admin/newsletter/subscribers');
        }
        
        $action = $this->post('bulk_action');
        $selectedIds = $this->post('selected_ids', []);
        
        if (empty($selectedIds)) {
            $this->session->setFlash('error', __('no_subscribers_selected'));
            $this->redirect('admin/newsletter/subscribers');
        }
        
        $successCount = 0;
        
        foreach ($selectedIds as $id) {
            switch ($action) {
                case 'activate':
                    if ($this->newsletterModel->updateStatus($id, 'active')) {
                        $successCount++;
                    }
                    break;
                    
                case 'deactivate':
                    if ($this->newsletterModel->updateStatus($id, 'inactive')) {
                        $successCount++;
                    }
                    break;
                    
                case 'unsubscribe':
                    if ($this->newsletterModel->updateStatus($id, 'unsubscribed')) {
                        $successCount++;
                    }
                    break;
                    
                case 'delete':
                    if ($this->newsletterModel->deleteSubscriber($id)) {
                        $successCount++;
                    }
                    break;
            }
        }
        
        if ($successCount > 0) {
            $this->session->setFlash('success', sprintf(__('bulk_action_success'), $successCount));
        } else {
            $this->session->setFlash('error', __('bulk_action_failed'));
        }
        
        $this->redirect('admin/newsletter/subscribers');
    }
    
    /**
     * Export subscribers
     */
    public function exportSubscribers()
    {
        $status = $this->get('status');
        $subscribers = $this->newsletterModel->exportSubscribers($status);
        
        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="newsletter_subscribers_' . date('Y-m-d') . '.csv"');
        
        // Create output stream
        $output = fopen('php://output', 'w');
        
        // Add UTF-8 BOM
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Add headers
        fputcsv($output, [
            'Email',
            'Name',
            'Status',
            'Subscribed At',
            'Unsubscribed At',
            'Created At'
        ]);
        
        // Add subscribers
        foreach ($subscribers as $subscriber) {
            fputcsv($output, [
                $subscriber['email'],
                $subscriber['name'],
                $subscriber['status'],
                $subscriber['subscribed_at'],
                $subscriber['unsubscribed_at'],
                $subscriber['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    /**
     * Import subscribers
     */
    public function importSubscribers()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $file = $this->file('import_file');
            $updateExisting = $this->post('update_existing', false);
            
            if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
                $this->session->setFlash('error', __('file_upload_error'));
                $this->redirect('admin/newsletter/import-subscribers');
            }
            
            // Parse CSV file
            $handle = fopen($file['tmp_name'], 'r');
            $subscribers = [];
            $header = fgetcsv($handle); // Skip header
            
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) >= 1) {
                    $subscribers[] = [
                        'email' => $row[0],
                        'name' => isset($row[1]) ? $row[1] : ''
                    ];
                }
            }
            fclose($handle);
            
            // Import subscribers
            $results = $this->newsletterModel->importSubscribers($subscribers, $updateExisting);
            
            // Set flash messages
            $messages = [];
            if ($results['imported'] > 0) {
                $messages[] = sprintf(__('subscribers_imported'), $results['imported']);
            }
            if ($results['updated'] > 0) {
                $messages[] = sprintf(__('subscribers_updated'), $results['updated']);
            }
            if ($results['skipped'] > 0) {
                $messages[] = sprintf(__('subscribers_skipped'), $results['skipped']);
            }
            
            if (!empty($results['errors'])) {
                $this->session->setFlash('error', implode('<br>', $results['errors']));
            }
            
            if (!empty($messages)) {
                $this->session->setFlash('success', implode('<br>', $messages));
            }
            
            $this->redirect('admin/newsletter/subscribers');
        }
        
        // Render import form
        $this->render('admin/newsletter/import-subscribers', [
            'pageTitle' => __('import_subscribers')
        ], 'admin');
    }
    
    /**
     * Campaigns action - manage campaigns
     */
    public function campaigns()
    {
        $langCode = $this->language->getCurrentLanguage();
        
        // Get page
        $page = (int) $this->get('page', 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        // Get campaigns
        $campaigns = $this->campaignModel->getAllWithDetails($langCode, [], 'nc.id DESC', $limit, $offset);
        $totalCampaigns = $this->campaignModel->count();
        $totalPages = ceil($totalCampaigns / $limit);
        
        // Render view
        $this->render('admin/newsletter/campaigns', [
            'pageTitle' => __('newsletter_campaigns'),
            'campaigns' => $campaigns,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCampaigns' => $totalCampaigns
        ], 'admin');
    }
    
    /**
     * Create campaign action
     */
    public function createCampaign()
    {
        // Load language model
        $languageModel = $this->loadModel('LanguageModel');
        $languages = $languageModel->getActiveLanguages();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $subject = $this->post('subject');
            $content = $this->post('content');
            $details = $this->post('details', []);
            
            // Validate inputs
            $errors = [];
            
            if (empty($subject)) {
                $errors[] = __('subject_required');
            }
            
            if (empty($content)) {
                $errors[] = __('content_required');
            }
            
            if (empty($errors)) {
                $campaignData = [
                    'subject' => $subject,
                    'content' => $content,
                    'status' => 'draft',
                    'created_by' => $this->session->get('user_id')
                ];
                
                $campaignId = $this->campaignModel->createWithDetails($campaignData, $details);
                
                if ($campaignId) {
                    $this->session->setFlash('success', __('campaign_created'));
                    $this->redirect('admin/newsletter/campaigns');
                } else {
                    $this->session->setFlash('error', __('campaign_create_failed'));
                }
            } else {
                $this->session->setFlash('error', implode('<br>', $errors));
            }
        }
        
        // Render form
        $this->render('admin/newsletter/create-campaign', [
            'pageTitle' => __('create_campaign'),
            'languages' => $languages
        ], 'admin');
    }
    
    /**
     * Send campaign action
     * 
     * @param int $id Campaign ID
     */
    public function sendCampaign($id)
    {
        $campaign = $this->campaignModel->getById($id);
        
        if (!$campaign) {
            $this->session->setFlash('error', __('campaign_not_found'));
            $this->redirect('admin/newsletter/campaigns');
        }
        
        if ($campaign['status'] !== 'draft') {
            $this->session->setFlash('error', __('campaign_already_sent'));
            $this->redirect('admin/newsletter/campaigns');
        }
        
        // Get active subscribers
        $subscribers = $this->newsletterModel->getActiveSubscribers();
        
        if (empty($subscribers)) {
            $this->session->setFlash('error', __('no_active_subscribers'));
            $this->redirect('admin/newsletter/campaigns');
        }
        
        // Update campaign status to sending
        $this->campaignModel->updateStatus($id, 'sending');
        
        // Send emails in background or immediately
        $sent = $this->sendCampaignEmails($campaign, $subscribers);
        
        if ($sent > 0) {
            // Update campaign status and stats
            $this->campaignModel->updateStatus($id, 'sent');
            $this->campaignModel->updateSendStats($id, count($subscribers), $sent, count($subscribers) - $sent);
            
            $this->session->setFlash('success', sprintf(__('campaign_sent_success'), $sent));
        } else {
            $this->campaignModel->updateStatus($id, 'failed');
            $this->session->setFlash('error', __('campaign_send_failed'));
        }
        
        $this->redirect('admin/newsletter/campaigns');
    }
    
    /**
     * Send campaign emails
     * 
     * @param array $campaign Campaign data
     * @param array $subscribers Subscribers
     * @return int Number of emails sent
     */
    private function sendCampaignEmails($campaign, $subscribers)
    {
        try {
            // Load Email class
            require_once BASE_PATH . '/core/Email.php';
            $email = new Email();
            
            $sent = 0;
            
            foreach ($subscribers as $subscriber) {
                try {
                    // Prepare variables for email template
                    $variables = [
                        'email' => $subscriber['email'],
                        'name' => $subscriber['name'] ?: $subscriber['email'],
                        'unsubscribe_link' => $this->generateUnsubscribeLink($subscriber['token']),
                        'campaign_subject' => $campaign['subject'],
                        'campaign_content' => $campaign['content']
                    ];
                    
                    // Send email
                    if ($email->send($subscriber['email'], $campaign['subject'], $campaign['content'])) {
                        $sent++;
                        
                        // Log successful send
                        $this->logCampaignSend($campaign['id'], $subscriber, 'sent');
                    } else {
                        // Log failed send
                        $this->logCampaignSend($campaign['id'], $subscriber, 'failed', $email->getError());
                    }
                    
                } catch (Exception $e) {
                    // Log failed send
                    $this->logCampaignSend($campaign['id'], $subscriber, 'failed', $e->getMessage());
                }
                
                // Small delay to prevent overwhelming the email server
                usleep(100000); // 0.1 second
            }
            
            return $sent;
            
        } catch (Exception $e) {
            writeLog('Campaign email sending error: ' . $e->getMessage(), 'admin-newsletter');
            return 0;
        }
    }
    
    /**
     * Log campaign send
     * 
     * @param int $campaignId Campaign ID
     * @param array $subscriber Subscriber data
     * @param string $status Send status
     * @param string $errorMessage Error message
     */
    private function logCampaignSend($campaignId, $subscriber, $status, $errorMessage = null)
    {
        $data = [
            'campaign_id' => $campaignId,
            'subscriber_id' => $subscriber['id'],
            'email' => $subscriber['email'],
            'status' => $status,
            'error_message' => $errorMessage
        ];
        
        $this->db->insert('newsletter_send_log', $data);
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
     * Clean inactive subscribers
     */
    public function cleanInactive()
    {
        $days = (int) $this->post('days', 365);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cleaned = $this->newsletterModel->cleanInactiveSubscribers($days);
            
            $this->session->setFlash('success', sprintf(__('inactive_subscribers_cleaned'), $cleaned));
            $this->redirect('admin/newsletter/subscribers');
        }
        
        // Render confirmation form
        $this->render('admin/newsletter/clean-inactive', [
            'pageTitle' => __('clean_inactive_subscribers')
        ], 'admin');
    }
}