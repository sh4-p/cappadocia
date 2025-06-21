<?php
/**
 * EmailTemplate Model
 */
class EmailTemplate extends Model
{
    protected $table = 'email_templates';
    protected $primaryKey = 'id';
    
    /**
     * Get template by key
     * 
     * @param string $templateKey Template key
     * @return array|false Template data
     */
    public function getByKey($templateKey)
    {
        return $this->getOne(['template_key' => $templateKey, 'is_active' => 1]);
    }
    
    /**
     * Get all active templates
     * 
     * @param string $orderBy Order by clause
     * @return array Templates
     */
    public function getAllActive($orderBy = 'name ASC')
    {
        return $this->getAll(['is_active' => 1], $orderBy);
    }
    
    /**
     * Check if template key exists
     * 
     * @param string $templateKey Template key
     * @param int $excludeId Exclude template ID (for update)
     * @return bool Exists
     */
    public function keyExists($templateKey, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE template_key = :templateKey";
        $params = ['templateKey' => $templateKey];
        
        if ($excludeId) {
            $sql .= " AND id != :excludeId";
            $params['excludeId'] = $excludeId;
        }
        
        return (bool) $this->db->getValue($sql, $params);
    }
    
    /**
     * Update template status
     * 
     * @param int $id Template ID
     * @param bool $isActive Is active
     * @return bool Success
     */
    public function updateStatus($id, $isActive)
    {
        return $this->update(['is_active' => $isActive ? 1 : 0], ['id' => $id]);
    }
    
    /**
     * Get available template keys
     * 
     * @return array Template keys with descriptions
     */
    public function getAvailableKeys()
    {
        return [
            'test_email' => [
                'name' => 'Test Email',
                'description' => 'Email template for SMTP testing',
                'variables' => ['timestamp', 'server_name', 'from_email', 'method']
            ],
            'booking_confirmation' => [
                'name' => 'Booking Confirmation',
                'description' => 'Email sent to customers when booking is confirmed',
                'variables' => ['first_name', 'last_name', 'tour_name', 'booking_date', 'adults', 'children', 'total_price', 'special_requests', 'from_name']
            ],
            'booking_status_confirmed' => [
                'name' => 'Booking Confirmed',
                'description' => 'Email sent when booking status is changed to confirmed',
                'variables' => ['first_name', 'last_name', 'tour_name', 'booking_date', 'adults', 'children', 'total_price', 'booking_id']
            ],
            'booking_status_cancelled' => [
                'name' => 'Booking Cancelled',
                'description' => 'Email sent when booking status is changed to cancelled',
                'variables' => ['first_name', 'last_name', 'tour_name', 'booking_date', 'adults', 'children', 'total_price', 'booking_id']
            ],
            'contact_form' => [
                'name' => 'Contact Form Submission',
                'description' => 'Email sent to admin when contact form is submitted',
                'variables' => ['name', 'email', 'phone', 'subject', 'message', 'timestamp']
            ],
            'contact_thank_you' => [
                'name' => 'Contact Form Thank You',
                'description' => 'Thank you email sent to contact form submitter',
                'variables' => ['name', 'subject', 'timestamp']
            ],
            'booking_admin_notification' => [
                'name' => 'New Booking Notification (Admin)',
                'description' => 'Email sent to admin when new booking is received',
                'variables' => ['first_name', 'last_name', 'tour_name', 'booking_date', 'adults', 'children', 'total_price', 'email', 'phone']
            ],
            'password_reset' => [
                'name' => 'Password Reset',
                'description' => 'Email sent to user when password reset is requested',
                'variables' => ['first_name', 'last_name', 'reset_link', 'expiry_time']
            ],
            'welcome_email' => [
                'name' => 'Welcome Email',
                'description' => 'Email sent to new users when they register',
                'variables' => ['first_name', 'last_name', 'username', 'activation_link']
            ],
            'newsletter_confirmation' => [
                'name' => 'Newsletter Subscription',
                'description' => 'Email sent when someone subscribes to newsletter',
                'variables' => ['email', 'confirmation_link', 'unsubscribe_link']
            ],
            'booking_reminder' => [
                'name' => 'Booking Reminder',
                'description' => 'Email sent as reminder before tour date',
                'variables' => ['first_name', 'last_name', 'tour_name', 'booking_date', 'total_price', 'special_requests']
            ]
        ];
    }
    
    /**
     * Parse template variables from content
     * 
     * @param string $content Template content
     * @return array Variables found in template
     */
    public function parseVariables($content)
    {
        $variables = [];
        
        // Match {{variable}} patterns
        if (preg_match_all('/\{\{([^}]+)\}\}/', $content, $matches)) {
            $variables = array_unique($matches[1]);
        }
        
        // Match {{#variable}} patterns (conditional blocks)
        if (preg_match_all('/\{\{#([^}]+)\}\}/', $content, $matches)) {
            $conditionalVars = array_unique($matches[1]);
            $variables = array_merge($variables, $conditionalVars);
        }
        
        return array_unique($variables);
    }
    
    /**
     * Render template with variables
     * 
     * @param string $content Template content
     * @param array $variables Variables to replace
     * @return string Rendered content
     */
    public function renderTemplate($content, $variables = [])
    {
        // Handle conditional blocks first {{#variable}}...{{/variable}}
        $content = preg_replace_callback('/\{\{#([^}]+)\}\}(.*?)\{\{\/\1\}\}/s', function($matches) use ($variables) {
            $varName = $matches[1];
            $blockContent = $matches[2];
            
            // If variable exists and is not empty, return the block content
            if (isset($variables[$varName]) && !empty($variables[$varName])) {
                return $blockContent;
            }
            
            return ''; // Remove the block if variable is empty or doesn't exist
        }, $content);
        
        // Replace simple variables {{variable}}
        foreach ($variables as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }
        
        // Clean up any remaining unreplaced variables
        $content = preg_replace('/\{\{[^}]+\}\}/', '', $content);
        
        return $content;
    }
    
    /**
     * Create new template
     * 
     * @param array $data Template data
     * @return int|false Template ID or false
     */
    public function createTemplate($data)
    {
        // Check if template key already exists
        if ($this->keyExists($data['template_key'])) {
            return false;
        }
        
        // Parse variables from content if not provided
        if (empty($data['variables']) && !empty($data['body'])) {
            $variables = $this->parseVariables($data['body'] . ' ' . $data['subject']);
            $data['variables'] = json_encode($variables);
        }
        
        // Set timestamps
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->insert($data);
    }
    
    /**
     * Update template
     * 
     * @param int $id Template ID
     * @param array $data Template data
     * @return bool Success
     */
    public function updateTemplate($id, $data)
    {
        // Check if template key already exists (excluding current template)
        if (isset($data['template_key']) && $this->keyExists($data['template_key'], $id)) {
            return false;
        }
        
        // Parse variables from content if not provided
        if (empty($data['variables']) && !empty($data['body'])) {
            $variables = $this->parseVariables($data['body'] . ' ' . $data['subject']);
            $data['variables'] = json_encode($variables);
        }
        
        // Set updated timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->update($data, ['id' => $id]);
    }
    
    /**
     * Delete template
     * 
     * @param int $id Template ID
     * @return bool Success
     */
    public function deleteTemplate($id)
    {
        return $this->delete(['id' => $id]);
    }
    
    /**
     * Get template usage statistics
     * 
     * @return array Usage statistics
     */
    public function getUsageStatistics()
    {
        // This can be extended to track email sends by template
        $sql = "SELECT template_key, COUNT(*) as count FROM {$this->table} GROUP BY template_key";
        return $this->db->getRows($sql);
    }
}