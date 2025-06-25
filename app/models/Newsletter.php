<?php
/**
 * Newsletter Model
 */
class Newsletter extends Model
{
    protected $table = 'newsletter_subscribers';
    protected $primaryKey = 'id';
    
    /**
     * Subscribe email address
     * 
     * @param string $email Email address
     * @param string $name Name (optional)
     * @return array|false Subscriber data or false
     */
    public function subscribe($email, $name = null)
    {
        // Check if email already exists
        $existing = $this->getByEmail($email);
        
        if ($existing) {
            // If already active, return existing
            if ($existing['status'] === 'active') {
                return $existing;
            }
            
            // If unsubscribed or inactive, reactivate
            if (in_array($existing['status'], ['unsubscribed', 'inactive'])) {
                $token = $this->generateToken();
                $result = $this->update([
                    'status' => 'pending',
                    'token' => $token,
                    'name' => $name ?: $existing['name'],
                    'unsubscribed_at' => null
                ], ['id' => $existing['id']]);
                
                if ($result) {
                    return $this->getById($existing['id']);
                }
                return false;
            }
            
            // If pending, update name and return
            if ($existing['status'] === 'pending') {
                $this->update([
                    'name' => $name ?: $existing['name']
                ], ['id' => $existing['id']]);
                
                return $existing;
            }
        }
        
        // Create new subscription
        $token = $this->generateToken();
        $data = [
            'email' => $email,
            'name' => $name,
            'status' => 'pending',
            'token' => $token
        ];
        
        $id = $this->insert($data);
        
        if ($id) {
            return $this->getById($id);
        }
        
        return false;
    }
    
    /**
     * Confirm subscription by token
     * 
     * @param string $token Confirmation token
     * @return bool Success
     */
    public function confirmSubscription($token)
    {
        $subscriber = $this->getByToken($token);
        
        if (!$subscriber || $subscriber['status'] !== 'pending') {
            return false;
        }
        
        return $this->update([
            'status' => 'active',
            'subscribed_at' => date('Y-m-d H:i:s')
        ], ['id' => $subscriber['id']]);
    }
    
    /**
     * Unsubscribe by token
     * 
     * @param string $token Unsubscribe token
     * @return bool Success
     */
    public function unsubscribe($token)
    {
        $subscriber = $this->getByToken($token);
        
        if (!$subscriber || $subscriber['status'] === 'unsubscribed') {
            return false;
        }
        
        return $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => date('Y-m-d H:i:s')
        ], ['id' => $subscriber['id']]);
    }
    
    /**
     * Get subscriber by email
     * 
     * @param string $email Email address
     * @return array|false Subscriber
     */
    public function getByEmail($email)
    {
        return $this->getOne(['email' => $email]);
    }
    
    /**
     * Get subscriber by token
     * 
     * @param string $token Token
     * @return array|false Subscriber
     */
    public function getByToken($token)
    {
        return $this->getOne(['token' => $token]);
    }
    
    /**
     * Get all subscribers with filters
     * 
     * @param string $status Status filter
     * @param string $search Search term
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Subscribers
     */
    public function getSubscribers($status = null, $search = null, $limit = null, $offset = null)
    {
        $conditions = [];
        
        if ($status) {
            $conditions['status'] = $status;
        }
        
        if ($search) {
            // For search, we need custom SQL
            $sql = "SELECT * FROM {$this->table} WHERE 1=1";
            $params = [];
            
            if ($status) {
                $sql .= " AND status = :status";
                $params['status'] = $status;
            }
            
            $sql .= " AND (email LIKE :search OR name LIKE :search)";
            $params['search'] = '%' . $search . '%';
            
            $sql .= " ORDER BY created_at DESC";
            
            if ($limit) {
                $sql .= " LIMIT {$limit}";
                if ($offset) {
                    $sql .= " OFFSET {$offset}";
                }
            }
            
            return $this->query($sql, $params);
        }
        
        return $this->getAll($conditions, 'created_at DESC', $limit, $offset);
    }
    
    /**
     * Count subscribers
     * 
     * @param string $status Status filter
     * @param string $search Search term
     * @return int Count
     */
    public function countSubscribers($status = null, $search = null)
    {
        if ($search) {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE 1=1";
            $params = [];
            
            if ($status) {
                $sql .= " AND status = :status";
                $params['status'] = $status;
            }
            
            $sql .= " AND (email LIKE :search OR name LIKE :search)";
            $params['search'] = '%' . $search . '%';
            
            $result = $this->querySingle($sql, $params);
            return $result ? reset($result) : 0;
        }
        
        $conditions = [];
        if ($status) {
            $conditions['status'] = $status;
        }
        
        return $this->count($conditions);
    }
    
    /**
     * Get subscriber statistics
     * 
     * @return array Statistics
     */
    public function getStatistics()
    {
        $stats = [
            'total' => $this->count(),
            'active' => $this->count(['status' => 'active']),
            'pending' => $this->count(['status' => 'pending']),
            'unsubscribed' => $this->count(['status' => 'unsubscribed']),
            'inactive' => $this->count(['status' => 'inactive'])
        ];
        
        // Get recent subscriptions (last 30 days)
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
        $result = $this->querySingle($sql);
        $stats['recent'] = $result ? reset($result) : 0;
        
        return $stats;
    }
    
    /**
     * Get active subscribers for sending emails
     * 
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Active subscribers
     */
    public function getActiveSubscribers($limit = null, $offset = null)
    {
        return $this->getAll(['status' => 'active'], 'email ASC', $limit, $offset);
    }
    
    /**
     * Update subscriber status
     * 
     * @param int $id Subscriber ID
     * @param string $status New status
     * @return bool Success
     */
    public function updateStatus($id, $status)
    {
        $data = ['status' => $status];
        
        if ($status === 'active' && !$this->getById($id)['subscribed_at']) {
            $data['subscribed_at'] = date('Y-m-d H:i:s');
        } elseif ($status === 'unsubscribed') {
            $data['unsubscribed_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($data, ['id' => $id]);
    }
    
    /**
     * Delete subscriber
     * 
     * @param int $id Subscriber ID
     * @return bool Success
     */
    public function deleteSubscriber($id)
    {
        return $this->delete(['id' => $id]);
    }
    
    /**
     * Export subscribers
     * 
     * @param string $status Status filter
     * @return array Subscribers for export
     */
    public function exportSubscribers($status = null)
    {
        $conditions = [];
        if ($status) {
            $conditions['status'] = $status;
        }
        
        return $this->getAll($conditions, 'email ASC');
    }
    
    /**
     * Import subscribers from array
     * 
     * @param array $subscribers Array of subscriber data
     * @param bool $updateExisting Update existing subscribers
     * @return array Import results
     */
    public function importSubscribers($subscribers, $updateExisting = false)
    {
        $results = [
            'imported' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => []
        ];
        
        foreach ($subscribers as $index => $subscriber) {
            try {
                $email = trim($subscriber['email'] ?? '');
                $name = trim($subscriber['name'] ?? '');
                
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $results['errors'][] = "Row " . ($index + 1) . ": Invalid email address";
                    $results['skipped']++;
                    continue;
                }
                
                $existing = $this->getByEmail($email);
                
                if ($existing) {
                    if ($updateExisting && !empty($name)) {
                        $this->update(['name' => $name], ['id' => $existing['id']]);
                        $results['updated']++;
                    } else {
                        $results['skipped']++;
                    }
                } else {
                    $this->subscribe($email, $name);
                    $results['imported']++;
                }
            } catch (Exception $e) {
                $results['errors'][] = "Row " . ($index + 1) . ": " . $e->getMessage();
                $results['skipped']++;
            }
        }
        
        return $results;
    }
    
    /**
     * Generate unique token
     * 
     * @return string Token
     */
    private function generateToken()
    {
        do {
            $token = bin2hex(random_bytes(32));
            $existing = $this->getByToken($token);
        } while ($existing);
        
        return $token;
    }
    
    /**
     * Clean inactive subscribers
     * 
     * @param int $days Days to consider inactive
     * @return int Number of cleaned subscribers
     */
    public function cleanInactiveSubscribers($days = 365)
    {
        $sql = "DELETE FROM {$this->table} 
                WHERE status = 'pending' 
                AND created_at < DATE_SUB(NOW(), INTERVAL :days DAY)";
        
        $this->db->executeQuery($sql, ['days' => $days]);
        return $this->db->rowCount();
    }
}

/**
 * Newsletter Campaign Model
 */
class NewsletterCampaign extends Model
{
    protected $table = 'newsletter_campaigns';
    protected $primaryKey = 'id';
    
    /**
     * Get all campaigns with details
     * 
     * @param string $langCode Language code
     * @param array $conditions Conditions
     * @param string $orderBy Order by
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Campaigns
     */
    public function getAllWithDetails($langCode, $conditions = [], $orderBy = 'nc.id DESC', $limit = null, $offset = null)
    {
        // Get language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return [];
        }
        
        $sql = "SELECT nc.*, ncd.subject as localized_subject, ncd.content as localized_content,
                       u.first_name, u.last_name
                FROM {$this->table} nc
                LEFT JOIN newsletter_campaign_details ncd ON nc.id = ncd.campaign_id AND ncd.language_id = :langId
                LEFT JOIN users u ON nc.created_by = u.id";
        
        $params = ['langId' => $language['id']];
        
        // Add conditions
        if (!empty($conditions)) {
            $sql .= " WHERE";
            $i = 0;
            
            foreach ($conditions as $field => $value) {
                if ($i > 0) {
                    $sql .= " AND";
                }
                
                $sql .= " {$field} = :{$field}";
                $params[$field] = $value;
                
                $i++;
            }
        }
        
        // Add order by
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        // Add limit and offset
        if ($limit) {
            $sql .= " LIMIT {$limit}";
            
            if ($offset) {
                $sql .= " OFFSET {$offset}";
            }
        }
        
        return $this->db->getRows($sql, $params);
    }
    
    /**
     * Create campaign with details
     * 
     * @param array $campaignData Campaign data
     * @param array $detailsData Details for each language
     * @return int|false Campaign ID or false
     */
    public function createWithDetails($campaignData, $detailsData)
    {
        $this->db->beginTransaction();
        
        try {
            $campaignId = $this->insert($campaignData);
            
            if (!$campaignId) {
                $this->db->cancelTransaction();
                return false;
            }
            
            foreach ($detailsData as $langId => $details) {
                $details['campaign_id'] = $campaignId;
                $details['language_id'] = $langId;
                
                $result = $this->db->insert('newsletter_campaign_details', $details);
                
                if (!$result) {
                    $this->db->cancelTransaction();
                    return false;
                }
            }
            
            $this->db->endTransaction();
            return $campaignId;
        } catch (Exception $e) {
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update campaign status
     * 
     * @param int $id Campaign ID
     * @param string $status New status
     * @return bool Success
     */
    public function updateStatus($id, $status)
    {
        $data = ['status' => $status];
        
        if ($status === 'sent') {
            $data['sent_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($data, ['id' => $id]);
    }
    
    /**
     * Update send statistics
     * 
     * @param int $id Campaign ID
     * @param int $totalRecipients Total recipients
     * @param int $sentCount Sent count
     * @param int $failedCount Failed count
     * @return bool Success
     */
    public function updateSendStats($id, $totalRecipients, $sentCount, $failedCount)
    {
        return $this->update([
            'total_recipients' => $totalRecipients,
            'sent_count' => $sentCount,
            'failed_count' => $failedCount
        ], ['id' => $id]);
    }
}