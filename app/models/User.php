<?php
/**
 * User Model
 */
class User extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    
    /**
     * Get all users
     * 
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Users
     */
    public function getAllUsers($conditions = [], $orderBy = 'id DESC', $limit = null, $offset = null)
    {
        // Build SQL query
        $sql = "SELECT id, username, email, first_name, last_name, role, is_active, created_at, updated_at
                FROM {$this->table}";
        
        // Add conditions
        if (!empty($conditions)) {
            $sql .= " WHERE";
            $i = 0;
            
            foreach ($conditions as $field => $value) {
                if ($i > 0) {
                    $sql .= " AND";
                }
                
                $sql .= " {$field} = :{$field}";
                
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
        
        // Execute query
        return $this->db->getRows($sql, $conditions);
    }
    
    /**
     * Get user by ID
     * 
     * @param int $id User ID
     * @return array|false User
     */
    public function getById($id)
    {
        // Build SQL query
        $sql = "SELECT id, username, email, first_name, last_name, role, is_active, created_at, updated_at
                FROM {$this->table}
                WHERE id = :id";
        
        // Execute query
        return $this->db->getRow($sql, ['id' => $id]);
    }
    
    /**
     * Get user by username
     * 
     * @param string $username Username
     * @return array|false User
     */
    public function getByUsername($username)
    {
        // Build SQL query
        $sql = "SELECT *
                FROM {$this->table}
                WHERE username = :username";
        
        // Execute query
        return $this->db->getRow($sql, ['username' => $username]);
    }
    
    /**
     * Get user by email
     * 
     * @param string $email Email
     * @return array|false User
     */
    public function getByEmail($email)
    {
        // Build SQL query
        $sql = "SELECT *
                FROM {$this->table}
                WHERE email = :email";
        
        // Execute query
        return $this->db->getRow($sql, ['email' => $email]);
    }
    
    /**
     * Get user by remember token
     * 
     * @param string $token Remember token
     * @return array|false User
     */
    public function getByRememberToken($token)
    {
        // Build SQL query
        $sql = "SELECT u.*
                FROM {$this->table} u
                JOIN user_remember_tokens t ON u.id = t.user_id
                WHERE t.token = :token AND t.expires > NOW()";
        
        // Execute query
        return $this->db->getRow($sql, ['token' => $token]);
    }
    
    /**
     * Add user
     * 
     * @param array $data User data
     * @return int|false User ID or false
     */
    public function addUser($data)
    {
        // Check if username or email already exists
        if ($this->usernameExists($data['username']) || $this->emailExists($data['email'])) {
            return false;
        }
        
        // Hash password if provided
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Insert user
        return $this->insert($data);
    }
    
    /**
     * Update user
     * 
     * @param int $id User ID
     * @param array $data User data
     * @return bool Success
     */
    public function updateUser($id, $data)
    {
        // Check if username or email already exists
        if (isset($data['username']) && $this->usernameExists($data['username'], $id)) {
            return false;
        }
        
        if (isset($data['email']) && $this->emailExists($data['email'], $id)) {
            return false;
        }
        
        // Hash password if provided
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Update user
        return $this->update($data, ['id' => $id]);
    }
    
    /**
     * Delete user
     * 
     * @param int $id User ID
     * @return bool Success
     */
    public function deleteUser($id)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Delete user remember tokens
            $this->db->delete('user_remember_tokens', ['user_id' => $id]);
            
            // Delete user
            $result = $this->delete(['id' => $id]);
            
            // Commit transaction
            $this->db->endTransaction();
            
            return (bool) $result;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Check if username exists
     * 
     * @param string $username Username
     * @param int $excludeId Exclude user ID (for update)
     * @return bool Exists
     */
    public function usernameExists($username, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = :username";
        $params = ['username' => $username];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        return (bool) $this->db->getValue($sql, $params);
    }
    
    /**
     * Check if email exists
     * 
     * @param string $email Email
     * @param int $excludeId Exclude user ID (for update)
     * @return bool Exists
     */
    public function emailExists($email, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = :email";
        $params = ['email' => $email];
        
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        
        return (bool) $this->db->getValue($sql, $params);
    }
    
    /**
     * Count users
     * 
     * @param array $conditions Where conditions
     * @return int Count
     */
    public function countUsers($conditions = [])
    {
        return $this->count($conditions);
    }
    
    /**
     * Update user status
     * 
     * @param int $id User ID
     * @param bool $isActive Is active
     * @return bool Success
     */
    public function updateStatus($id, $isActive)
    {
        return $this->update(['is_active' => $isActive ? 1 : 0], ['id' => $id]);
    }
    
    /**
     * Save remember token
     * 
     * @param int $userId User ID
     * @param string $token Remember token
     * @param int $expires Expiration timestamp
     * @return bool Success
     */
    public function saveRememberToken($userId, $token, $expires)
    {
        // Delete old tokens for this user
        $this->db->delete('user_remember_tokens', ['user_id' => $userId]);
        
        // Insert new token
        return $this->db->insert('user_remember_tokens', [
            'user_id' => $userId,
            'token' => $token,
            'expires' => date('Y-m-d H:i:s', $expires)
        ]);
    }
    
    /**
     * Remove remember token
     * 
     * @param string $token Remember token
     * @return bool Success
     */
    public function removeRememberToken($token)
    {
        return $this->db->delete('user_remember_tokens', ['token' => $token]);
    }
    
    /**
     * Change password
     * 
     * @param int $userId User ID
     * @param string $newPassword New password
     * @return bool Success
     */
    public function changePassword($userId, $newPassword)
    {
        // Hash password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Update password
        return $this->update(['password' => $hashedPassword], ['id' => $userId]);
    }
    
    /**
     * Verify password
     * 
     * @param int $userId User ID
     * @param string $password Password to verify
     * @return bool Is valid
     */
    public function verifyPassword($userId, $password)
    {
        // Get user
        $user = $this->getById($userId);
        
        if (!$user) {
            return false;
        }
        
        // Verify password
        return password_verify($password, $user['password']);
    }
    
    /**
     * Get available roles
     * 
     * @return array Roles
     */
    public function getRoles()
    {
        return [
            'admin' => 'Administrator',
            'editor' => 'Editor'
        ];
    }
}