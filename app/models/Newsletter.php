<?php
/**
 * Newsletter Model - Updated with Tracking Token Support
 */
class Newsletter extends Model
{
    protected $table = 'newsletter_subscribers';
    protected $primaryKey = 'id';
    
    /**
     * Subscribe email address with tracking token
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
                // Ensure tracking token exists for existing active users
                if (empty($existing['tracking_token'])) {
                    $trackingToken = $this->generateTrackingToken();
                    $this->update([
                        'tracking_token' => $trackingToken
                    ], ['id' => $existing['id']]);
                    $existing['tracking_token'] = $trackingToken;
                }
                return $existing;
            }
            
            // If unsubscribed or inactive, reactivate
            if (in_array($existing['status'], ['unsubscribed', 'inactive'])) {
                $token = $this->generateConfirmationToken();
                $trackingToken = empty($existing['tracking_token']) ? $this->generateTrackingToken() : $existing['tracking_token'];
                
                $result = $this->update([
                    'status' => 'pending',
                    'token' => $token,
                    'tracking_token' => $trackingToken,
                    'name' => $name ?: $existing['name'],
                    'unsubscribed_at' => null,
                    'updated_at' => date('Y-m-d H:i:s')
                ], ['id' => $existing['id']]);
                
                if ($result) {
                    return $this->getById($existing['id']);
                }
                return false;
            }
            
            // If pending, update name and ensure tracking token exists
            if ($existing['status'] === 'pending') {
                $updateData = [
                    'name' => $name ?: $existing['name'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                // Add tracking token if missing
                if (empty($existing['tracking_token'])) {
                    $updateData['tracking_token'] = $this->generateTrackingToken();
                    $existing['tracking_token'] = $updateData['tracking_token'];
                }
                
                $this->update($updateData, ['id' => $existing['id']]);
                return $existing;
            }
        }
        
        // Create new subscription
        $token = $this->generateConfirmationToken();
        $trackingToken = $this->generateTrackingToken();
        
        $data = [
            'email' => $email,
            'name' => $name,
            'status' => 'pending',
            'token' => $token,
            'tracking_token' => $trackingToken,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $id = $this->insert($data);
        
        if ($id) {
            return $this->getById($id);
        }
        
        return false;
    }
    
    /**
     * Confirm subscription by token (supports both confirmation and tracking tokens)
     * 
     * @param string $token Confirmation token or tracking token
     * @return bool Success
     */
    public function confirmSubscription($token)
    {
        // Try confirmation token first (64 chars), then tracking token (32 chars)
        $subscriber = $this->getByToken($token);
        if (!$subscriber) {
            $subscriber = $this->getByTrackingToken($token);
        }
        
        if (!$subscriber || $subscriber['status'] !== 'pending') {
            return false;
        }
        
        return $this->update([
            'status' => 'active',
            'subscribed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $subscriber['id']]);
    }
    
    /**
     * Unsubscribe by token (supports both confirmation and tracking tokens)
     * 
     * @param string $token Unsubscribe token or tracking token
     * @return bool Success
     */
    public function unsubscribe($token)
    {
        // Try confirmation token first (64 chars), then tracking token (32 chars)
        $subscriber = $this->getByToken($token);
        if (!$subscriber) {
            $subscriber = $this->getByTrackingToken($token);
        }
        
        if (!$subscriber || $subscriber['status'] === 'unsubscribed') {
            return false;
        }
        
        return $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
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
     * Get subscriber by confirmation token (64 characters)
     * 
     * @param string $token Confirmation token
     * @return array|false Subscriber
     */
    public function getByToken($token)
    {
        return $this->getOne(['token' => $token]);
    }
    
    /**
     * Get subscriber by tracking token (32 characters)
     * 
     * @param string $token Tracking token
     * @return array|false Subscriber
     */
    public function getByTrackingToken($token)
    {
        return $this->getOne(['tracking_token' => $token]);
    }
    
    /**
     * Get subscriber by any token type (auto-detect)
     * 
     * @param string $token Token (either confirmation or tracking)
     * @return array|false Subscriber
     */
    public function getByAnyToken($token)
    {
        // Auto-detect token type by length
        if (strlen($token) === 64) {
            return $this->getByToken($token);
        } elseif (strlen($token) === 32) {
            return $this->getByTrackingToken($token);
        }
        
        return false;
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
        
        // Get subscribers without tracking tokens (for migration)
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE tracking_token IS NULL OR tracking_token = ''";
        $result = $this->querySingle($sql);
        $stats['missing_tracking_token'] = $result ? reset($result) : 0;
        
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
        $data = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
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
                        $updateData = ['name' => $name, 'updated_at' => date('Y-m-d H:i:s')];
                        
                        // Add tracking token if missing
                        if (empty($existing['tracking_token'])) {
                            $updateData['tracking_token'] = $this->generateTrackingToken();
                        }
                        
                        $this->update($updateData, ['id' => $existing['id']]);
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
     * Generate unique confirmation token (64 characters)
     * 
     * @return string Confirmation token
     */
    private function generateConfirmationToken()
    {
        do {
            $token = bin2hex(random_bytes(32)); // 64 characters
            $existing = $this->getByToken($token);
        } while ($existing);
        
        return $token;
    }
    
    /**
     * Generate unique tracking token (32 characters)
     * 
     * @return string Tracking token
     */
    private function generateTrackingToken()
    {
        do {
            $token = strtolower(substr(md5(time() . uniqid() . mt_rand()), 0, 32)); // 32 characters
            $existing = $this->getByTrackingToken($token);
        } while ($existing);
        
        return $token;
    }
    
    /**
     * Migrate existing subscribers to add tracking tokens
     * 
     * @return int Number of updated subscribers
     */
    public function migrateTrackingTokens()
    {
        $sql = "SELECT id FROM {$this->table} WHERE tracking_token IS NULL OR tracking_token = ''";
        $subscribers = $this->query($sql);
        
        $updated = 0;
        foreach ($subscribers as $subscriber) {
            $trackingToken = $this->generateTrackingToken();
            if ($this->update(['tracking_token' => $trackingToken], ['id' => $subscriber['id']])) {
                $updated++;
            }
        }
        
        return $updated;
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