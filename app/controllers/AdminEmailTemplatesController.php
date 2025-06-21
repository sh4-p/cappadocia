<?php
/**
 * Admin Email Templates Controller
 * 
 * Handles email templates management in admin panel
 */
class AdminEmailTemplatesController extends Controller
{
    /**
     * Constructor
     */
    public function __construct($db = null, $session = null, $language = null)
    {
        parent::__construct($db, $session, $language);
        
        // Require login
        $this->requireLogin();
    }
    
    /**
     * Get controller name
     * 
     * @return string Controller name
     */
    public function getControllerName()
    {
        return 'AdminEmailTemplates';
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
     * Index action - list all email templates
     */
    public function index()
    {
        // Load email template model
        $emailTemplateModel = $this->loadModel('EmailTemplate');
        
        // Get all email templates
        $templates = $emailTemplateModel->getAll([], 'name ASC');
        
        // Get available template keys
        $availableKeys = $emailTemplateModel->getAvailableKeys();
        
        // Render view
        $this->render('admin/email-templates/index', [
            'pageTitle' => __('email_templates'),
            'templates' => $templates,
            'availableKeys' => $availableKeys,
            'adminUrl' => $this->getAdminUrl()
        ], 'admin');
    }
    
    /**
     * Create action - create a new email template
     */
    public function create()
    {
        // Load email template model
        $emailTemplateModel = $this->loadModel('EmailTemplate');
        
        // Get available template keys
        $availableKeys = $emailTemplateModel->getAvailableKeys();
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $templateKey = $this->post('template_key');
            $name = $this->post('name');
            $subject = $this->post('subject');
            $body = $this->post('body');
            $description = $this->post('description');
            $isActive = $this->post('is_active', 0);
            
            // Validate inputs
            $errors = [];
            
            if (empty($templateKey)) {
                $errors[] = __('template_key_required');
            } elseif ($emailTemplateModel->keyExists($templateKey)) {
                $errors[] = __('template_key_exists');
            }
            
            if (empty($name)) {
                $errors[] = __('name_required');
            }
            
            if (empty($subject)) {
                $errors[] = __('subject_required');
            }
            
            if (empty($body)) {
                $errors[] = __('body_required');
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Render view again with data
                $this->render('admin/email-templates/create', [
                    'pageTitle' => __('add_email_template'),
                    'availableKeys' => $availableKeys,
                    'templateKey' => $templateKey,
                    'name' => $name,
                    'subject' => $subject,
                    'body' => $body,
                    'description' => $description,
                    'isActive' => $isActive,
                    'adminUrl' => $this->getAdminUrl()
                ], 'admin');
                
                return;
            }
            
            // Parse variables from template content
            $variables = $emailTemplateModel->parseVariables($body . ' ' . $subject);
            
            // Prepare template data
            $templateData = [
                'template_key' => $templateKey,
                'name' => $name,
                'subject' => $subject,
                'body' => $body,
                'variables' => json_encode($variables),
                'description' => $description,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Create template
            $templateId = $emailTemplateModel->createTemplate($templateData);
            
            if ($templateId) {
                $this->session->setFlash('success', __('email_template_added'));
                $this->redirect('admin/email-templates');
            } else {
                $this->session->setFlash('error', __('email_template_add_failed'));
            }
        }
        
        // Render view
        $this->render('admin/email-templates/create', [
            'pageTitle' => __('add_email_template'),
            'availableKeys' => $availableKeys,
            'adminUrl' => $this->getAdminUrl()
        ], 'admin');
    }
    
    /**
     * Edit action - edit an email template
     * 
     * @param int $id Template ID
     */
    public function edit($id)
    {
        // Load email template model
        $emailTemplateModel = $this->loadModel('EmailTemplate');
        
        // Get template
        $template = $emailTemplateModel->getById($id);
        
        // Check if template exists
        if (!$template) {
            $this->session->setFlash('error', __('email_template_not_found'));
            $this->redirect('admin/email-templates');
        }
        
        // Get available template keys
        $availableKeys = $emailTemplateModel->getAvailableKeys();
        
        // Parse variables from template
        $variables = json_decode($template['variables'], true) ?: [];
        
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $templateKey = $this->post('template_key');
            $name = $this->post('name');
            $subject = $this->post('subject');
            $body = $this->post('body');
            $description = $this->post('description');
            $isActive = $this->post('is_active', 0);
            
            // Validate inputs
            $errors = [];
            
            if (empty($templateKey)) {
                $errors[] = __('template_key_required');
            } elseif ($templateKey !== $template['template_key'] && $emailTemplateModel->keyExists($templateKey)) {
                $errors[] = __('template_key_exists');
            }
            
            if (empty($name)) {
                $errors[] = __('name_required');
            }
            
            if (empty($subject)) {
                $errors[] = __('subject_required');
            }
            
            if (empty($body)) {
                $errors[] = __('body_required');
            }
            
            // If there are errors, set error message and return
            if (!empty($errors)) {
                $this->session->setFlash('error', implode('<br>', $errors));
                
                // Update template with POST data for display
                $template['template_key'] = $templateKey;
                $template['name'] = $name;
                $template['subject'] = $subject;
                $template['body'] = $body;
                $template['description'] = $description;
                $template['is_active'] = $isActive;
                
                // Render view again
                $this->render('admin/email-templates/edit', [
                    'pageTitle' => __('edit_email_template'),
                    'template' => $template,
                    'variables' => $variables,
                    'availableKeys' => $availableKeys,
                    'adminUrl' => $this->getAdminUrl()
                ], 'admin');
                
                return;
            }
            
            // Parse variables from template content
            $newVariables = $emailTemplateModel->parseVariables($body . ' ' . $subject);
            
            // Prepare template data
            $templateData = [
                'template_key' => $templateKey,
                'name' => $name,
                'subject' => $subject,
                'body' => $body,
                'variables' => json_encode($newVariables),
                'description' => $description,
                'is_active' => $isActive ? 1 : 0
            ];
            
            // Update template
            $result = $emailTemplateModel->updateTemplate($id, $templateData);
            
            if ($result) {
                $this->session->setFlash('success', __('email_template_updated'));
                $this->redirect('admin/email-templates');
            } else {
                $this->session->setFlash('error', __('email_template_update_failed'));
            }
        }
        
        // Render view
        $this->render('admin/email-templates/edit', [
            'pageTitle' => __('edit_email_template'),
            'template' => $template,
            'variables' => $variables,
            'availableKeys' => $availableKeys,
            'adminUrl' => $this->getAdminUrl()
        ], 'admin');
    }
    
    /**
     * View action - view template details
     * 
     * @param int $id Template ID
     */
    public function view($id)
    {
        // Load email template model
        $emailTemplateModel = $this->loadModel('EmailTemplate');
        
        // Get template
        $template = $emailTemplateModel->getById($id);
        
        // Check if template exists
        if (!$template) {
            $this->session->setFlash('error', __('email_template_not_found'));
            $this->redirect('admin/email-templates');
        }
        
        // Parse variables from template
        $variables = json_decode($template['variables'], true) ?: [];
        
        // Render view
        $this->render('admin/email-templates/view', [
            'pageTitle' => __('view_email_template'),
            'template' => $template,
            'variables' => $variables,
            'adminUrl' => $this->getAdminUrl()
        ], 'admin');
    }
    
    /**
     * Delete action - delete an email template
     * 
     * @param int $id Template ID
     */
    public function delete($id)
    {
        // Load email template model
        $emailTemplateModel = $this->loadModel('EmailTemplate');
        
        // Get template
        $template = $emailTemplateModel->getById($id);
        
        // Check if template exists
        if (!$template) {
            $this->session->setFlash('error', __('email_template_not_found'));
            $this->redirect('admin/email-templates');
        }
        
        // Delete template
        $result = $emailTemplateModel->deleteTemplate($id);
        
        if ($result) {
            $this->session->setFlash('success', __('email_template_deleted'));
        } else {
            $this->session->setFlash('error', __('email_template_delete_failed'));
        }
        
        // Redirect to templates list
        $this->redirect('admin/email-templates');
    }
    
    /**
     * Toggle status action
     * 
     * @param int $id Template ID
     */
    public function toggleStatus($id)
    {
        // Load email template model
        $emailTemplateModel = $this->loadModel('EmailTemplate');
        
        // Get template
        $template = $emailTemplateModel->getById($id);
        
        // Check if template exists
        if (!$template) {
            $this->session->setFlash('error', __('email_template_not_found'));
            $this->redirect('admin/email-templates');
        }
        
        // Toggle status
        $newStatus = $template['is_active'] ? 0 : 1;
        $result = $emailTemplateModel->updateStatus($id, $newStatus);
        
        if ($result) {
            $statusText = $newStatus ? __('activated') : __('deactivated');
            $this->session->setFlash('success', __('email_template') . ' ' . $statusText);
        } else {
            $this->session->setFlash('error', __('status_update_failed'));
        }
        
        // Redirect to templates list
        $this->redirect('admin/email-templates');
    }
    
    /**
     * Preview action - preview template with sample data
     * 
     * @param int $id Template ID
     */
    public function preview($id)
    {
        // Load email template model
        $emailTemplateModel = $this->loadModel('EmailTemplate');
        
        // Get template
        $template = $emailTemplateModel->getById($id);
        
        // Check if template exists
        if (!$template) {
            $this->session->setFlash('error', __('email_template_not_found'));
            $this->redirect('admin/email-templates');
        }
        
        // Get sample data based on template key
        $sampleData = $this->getSampleData($template['template_key']);
        
        // Render template with sample data
        $renderedSubject = $emailTemplateModel->renderTemplate($template['subject'], $sampleData);
        $renderedBody = $emailTemplateModel->renderTemplate($template['body'], $sampleData);
        
        // Check if request is AJAX
        if ($this->isAjax()) {
            $this->json([
                'success' => true,
                'subject' => $renderedSubject,
                'body' => $renderedBody
            ]);
        }
        
        // Render preview page
        $this->render('admin/email-templates/preview', [
            'pageTitle' => __('preview_email_template'),
            'template' => $template,
            'renderedSubject' => $renderedSubject,
            'renderedBody' => $renderedBody,
            'sampleData' => $sampleData,
            'adminUrl' => $this->getAdminUrl()
        ], 'admin');
    }
    
    /**
     * Test send action - send test email
     * 
     * @param int $id Template ID
     */
    public function testSend($id)
    {
        try {
            // Check if request is POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->session->setFlash('error', 'Invalid request method');
                $this->redirect('admin/email-templates');
                return;
            }
            
            // Load email template model
            $emailTemplateModel = $this->loadModel('EmailTemplate');
            
            // Get template
            $template = $emailTemplateModel->getById($id);
            
            // Check if template exists
            if (!$template) {
                $this->session->setFlash('error', 'Email template not found');
                $this->redirect('admin/email-templates');
                return;
            }
            
            // Get test email address
            $testEmail = $this->post('test_email');
            
            if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
                $this->session->setFlash('error', 'Please provide a valid email address');
                $this->redirect('admin/email-templates/view/' . $id);
                return;
            }
            
            // Get sample data
            $sampleData = $this->getSampleData($template['template_key']);
            
            // Render template
            $subject = $emailTemplateModel->renderTemplate($template['subject'], $sampleData);
            $body = $emailTemplateModel->renderTemplate($template['body'], $sampleData);
            
            // Include Email class if not already loaded
            if (!class_exists('Email')) {
                require_once dirname(dirname(__DIR__)) . '/core/Email.php';
            }
            
            // Send email
            $email = new Email();
            $result = $email->send($testEmail, $subject, $body);
            
            if ($result) {
                $this->session->setFlash('success', 'Test email sent successfully to ' . $testEmail);
            } else {
                $this->session->setFlash('error', 'Failed to send test email: ' . $email->getError());
            }
            
        } catch (Exception $e) {
            // Log error for debugging
            error_log('Test email error: ' . $e->getMessage());
            $this->session->setFlash('error', 'An error occurred while sending test email: ' . $e->getMessage());
        }
        
        // Always redirect back to template view
        $this->redirect('admin/email-templates/view/' . $id);
    }
    
    /**
     * Get admin URL base
     * 
     * @return string Admin URL
     */
    private function getAdminUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];
        $scriptName = dirname($_SERVER['SCRIPT_NAME']);
        
        // Remove trailing slash
        $scriptName = rtrim($scriptName, '/');
        
        return $protocol . $host . $scriptName . '/admin';
    }
    
    /**
     * Get sample data for template preview
     * 
     * @param string $templateKey Template key
     * @return array Sample data
     */
    private function getSampleData($templateKey)
    {
        $sampleData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'server_name' => $_SERVER['SERVER_NAME'] ?? 'localhost',
            'from_email' => 'admin@example.com',
            'from_name' => 'Cappadocia Travel',
            'method' => 'SMTP'
        ];
        
        switch ($templateKey) {
            case 'booking_confirmation':
                $sampleData = array_merge($sampleData, [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'tour_name' => 'Cappadocia Hot Air Balloon Tour',
                    'booking_date' => '2025-07-15',
                    'adults' => '2',
                    'children' => '1',
                    'total_price' => '150.00',
                    'special_requests' => 'Vegetarian meal please',
                    'email' => 'john.doe@example.com',
                    'phone' => '+1 555 123 4567'
                ]);
                break;
                
            case 'contact_form':
                $sampleData = array_merge($sampleData, [
                    'name' => 'Jane Smith',
                    'email' => 'jane.smith@example.com',
                    'phone' => '+1 555 987 6543',
                    'subject' => 'Question about tours',
                    'message' => 'Hello, I would like to know more about your hot air balloon tours. What time do they usually start and what should I bring?'
                ]);
                break;
                
            case 'booking_admin_notification':
                $sampleData = array_merge($sampleData, [
                    'first_name' => 'Alice',
                    'last_name' => 'Johnson',
                    'tour_name' => 'Underground City Tour',
                    'booking_date' => '2025-07-20',
                    'adults' => '3',
                    'children' => '0',
                    'total_price' => '90.00',
                    'email' => 'alice.johnson@example.com',
                    'phone' => '+1 555 456 7890'
                ]);
                break;
                
            case 'password_reset':
                $sampleData = array_merge($sampleData, [
                    'first_name' => 'Bob',
                    'last_name' => 'Wilson',
                    'reset_link' => 'https://example.com/reset-password?token=abc123',
                    'expiry_time' => '24 hours'
                ]);
                break;
                
            case 'welcome_email':
                $sampleData = array_merge($sampleData, [
                    'first_name' => 'Sarah',
                    'last_name' => 'Brown',
                    'username' => 'sarahbrown',
                    'activation_link' => 'https://example.com/activate?token=xyz789'
                ]);
                break;
        }
        
        return $sampleData;
    }
}