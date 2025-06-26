<?php
/**
 * Anti-Bot Model
 * 
 * Handles database operations for anti-bot system
 */
class AntiBotModel extends Model
{
    /**
     * Get overall statistics
     */
    public function getOverallStatistics()
    {
        $stats = [];
        
        // Total form attempts
        $sql = "SELECT COUNT(*) as total FROM form_attempts";
        $stats['total_attempts'] = $this->db->getValue($sql) ?: 0;
        
        // Total bot attempts
        $sql = "SELECT COUNT(*) as total FROM bot_attempts";
        $stats['total_bot_attempts'] = $this->db->getValue($sql) ?: 0;
        
        // Total blocked IPs
        $sql = "SELECT COUNT(*) as total FROM ip_blocks";
        $stats['total_blocked_ips'] = $this->db->getValue($sql) ?: 0;
        
        // Success rate
        if ($stats['total_attempts'] > 0) {
            $successful = $stats['total_attempts'] - $stats['total_bot_attempts'];
            $stats['success_rate'] = round(($successful / $stats['total_attempts']) * 100, 2);
        } else {
            $stats['success_rate'] = 100;
        }
        
        return $stats;
    }
    
    /**
     * Get statistics for a specific period
     */
    public function getStatistics($days = 7)
    {
        $stats = [];
        
        // Form attempts by type
        $sql = "SELECT form_type, COUNT(*) as total, SUM(success) as successful
                FROM form_attempts 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY form_type";
        
        $stats['form_attempts'] = $this->db->getRows($sql, ['days' => $days]);
        
        // Bot attempts by protection type
        $sql = "SELECT protection_type, COUNT(*) as total
                FROM bot_attempts 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY protection_type";
        
        $stats['bot_attempts'] = $this->db->getRows($sql, ['days' => $days]);
        
        // Recent IP blocks
        $sql = "SELECT COUNT(*) as total FROM ip_blocks 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)";
        $stats['recent_blocks'] = $this->db->getValue($sql, ['days' => $days]) ?: 0;
        
        // Top blocked IPs
        $sql = "SELECT ip_address, COUNT(*) as attempts
                FROM bot_attempts 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY ip_address
                ORDER BY attempts DESC
                LIMIT 10";
        $stats['top_blocked_ips'] = $this->db->getRows($sql, ['days' => $days]);
        
        return $stats;
    }
    
    /**
     * Get detailed statistics for charts
     */
    public function getDetailedStatistics($days = 30)
    {
        $stats = [];
        
        // Daily statistics
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as total_attempts,
                    SUM(CASE WHEN success = 1 THEN 1 ELSE 0 END) as successful_attempts
                FROM form_attempts 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY DATE(created_at)
                ORDER BY date";
        
        $stats['daily_attempts'] = $this->db->getRows($sql, ['days' => $days]);
        
        // Daily bot attempts
        $sql = "SELECT 
                    DATE(created_at) as date,
                    COUNT(*) as bot_attempts
                FROM bot_attempts 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY DATE(created_at)
                ORDER BY date";
        
        $stats['daily_bot_attempts'] = $this->db->getRows($sql, ['days' => $days]);
        
        // Protection type breakdown
        $sql = "SELECT protection_type, COUNT(*) as total
                FROM bot_attempts 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY protection_type
                ORDER BY total DESC";
        
        $stats['protection_breakdown'] = $this->db->getRows($sql, ['days' => $days]);
        
        // Form type breakdown
        $sql = "SELECT form_type, COUNT(*) as total
                FROM bot_attempts 
                WHERE created_at > DATE_SUB(NOW(), INTERVAL :days DAY)
                GROUP BY form_type
                ORDER BY total DESC";
        
        $stats['form_breakdown'] = $this->db->getRows($sql, ['days' => $days]);
        
        return $stats;
    }
    
    /**
     * Get chart data for visualization
     */
    public function getChartData($days = 30)
    {
        $chartData = [];
        
        // Daily attempts chart data - Fixed parameter issue
        $sql = "SELECT 
                    DATE(fa.created_at) as date,
                    COUNT(fa.id) as form_attempts,
                    COALESCE(ba.bot_attempts, 0) as bot_attempts
                FROM form_attempts fa
                LEFT JOIN (
                    SELECT DATE(created_at) as date, COUNT(*) as bot_attempts
                    FROM bot_attempts 
                    WHERE created_at > DATE_SUB(NOW(), INTERVAL :days1 DAY)
                    GROUP BY DATE(created_at)
                ) ba ON DATE(fa.created_at) = ba.date
                WHERE fa.created_at > DATE_SUB(NOW(), INTERVAL :days2 DAY)
                GROUP BY DATE(fa.created_at)
                ORDER BY date";
        
        $dailyData = $this->db->getRows($sql, ['days1' => $days, 'days2' => $days]);
        
        $chartData['daily'] = [
            'labels' => array_column($dailyData, 'date'),
            'form_attempts' => array_column($dailyData, 'form_attempts'),
            'bot_attempts' => array_column($dailyData, 'bot_attempts')
        ];
        
        return $chartData;
    }
    
    /**
     * Get recent bot attempts
     */
    public function getRecentBotAttempts($limit = 20)
    {
        $sql = "SELECT * FROM bot_attempts 
                ORDER BY created_at DESC 
                LIMIT :limit";
        
        return $this->db->getRows($sql, ['limit' => $limit]);
    }
    
    /**
     * Get bot attempts with filters
     */
    public function getBotAttempts($filters = [], $limit = 50, $offset = 0)
    {
        $sql = "SELECT * FROM bot_attempts WHERE 1=1";
        $params = [];
        
        if (!empty($filters['protection_type'])) {
            $sql .= " AND protection_type = :protection_type";
            $params['protection_type'] = $filters['protection_type'];
        }
        
        if (!empty($filters['form_type'])) {
            $sql .= " AND form_type = :form_type";
            $params['form_type'] = $filters['form_type'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(created_at) >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(created_at) <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;
        
        return $this->db->getRows($sql, $params);
    }
    
    /**
     * Count bot attempts with filters
     */
    public function countBotAttempts($filters = [])
    {
        $sql = "SELECT COUNT(*) FROM bot_attempts WHERE 1=1";
        $params = [];
        
        if (!empty($filters['protection_type'])) {
            $sql .= " AND protection_type = :protection_type";
            $params['protection_type'] = $filters['protection_type'];
        }
        
        if (!empty($filters['form_type'])) {
            $sql .= " AND form_type = :form_type";
            $params['form_type'] = $filters['form_type'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(created_at) >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(created_at) <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        return $this->db->getValue($sql, $params) ?: 0;
    }
    
    /**
     * Get protection types
     */
    public function getProtectionTypes()
    {
        $sql = "SELECT DISTINCT protection_type FROM bot_attempts ORDER BY protection_type";
        $results = $this->db->getRows($sql);
        
        return array_column($results, 'protection_type');
    }
    
    /**
     * Get form types
     */
    public function getFormTypes()
    {
        $sql = "SELECT DISTINCT form_type FROM bot_attempts ORDER BY form_type";
        $results = $this->db->getRows($sql);
        
        return array_column($results, 'form_type');
    }
    
    /**
     * Get blocked IPs
     */
    public function getBlockedIPs($limit = 50, $offset = 0)
    {
        $sql = "SELECT *, 
                CASE 
                    WHEN expires_at IS NULL THEN 'Permanent'
                    WHEN expires_at > NOW() THEN 'Active'
                    ELSE 'Expired'
                END as status
                FROM ip_blocks 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        return $this->db->getRows($sql, ['limit' => $limit, 'offset' => $offset]);
    }
    
    /**
     * Count blocked IPs
     */
    public function countIPBlocks()
    {
        $sql = "SELECT COUNT(*) FROM ip_blocks";
        return $this->db->getValue($sql) ?: 0;
    }
    
    /**
     * Remove IP block
     */
    public function removeIPBlock($id)
    {
        $sql = "DELETE FROM ip_blocks WHERE id = :id";
        return $this->db->executeQuery($sql, ['id' => $id]);
    }
    
    /**
     * Export bot attempts
     */
    public function exportBotAttempts($filters = [])
    {
        $sql = "SELECT * FROM bot_attempts WHERE 1=1";
        $params = [];
        
        if (!empty($filters['protection_type'])) {
            $sql .= " AND protection_type = :protection_type";
            $params['protection_type'] = $filters['protection_type'];
        }
        
        if (!empty($filters['form_type'])) {
            $sql .= " AND form_type = :form_type";
            $params['form_type'] = $filters['form_type'];
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(created_at) >= :date_from";
            $params['date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(created_at) <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        return $this->db->getRows($sql, $params);
    }
    
    /**
     * Create required tables if they don't exist
     */
    public function createTables()
    {
        // Create form_attempts table
        $sql = "CREATE TABLE IF NOT EXISTS `form_attempts` (
            `id` int NOT NULL AUTO_INCREMENT,
            `form_type` varchar(50) NOT NULL,
            `ip_address` varchar(45) NOT NULL,
            `user_agent` text,
            `success` tinyint(1) NOT NULL DEFAULT '0',
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `form_type` (`form_type`),
            KEY `ip_address` (`ip_address`),
            KEY `created_at` (`created_at`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->executeQuery($sql);
        
        // Create bot_attempts table
        $sql = "CREATE TABLE IF NOT EXISTS `bot_attempts` (
            `id` int NOT NULL AUTO_INCREMENT,
            `protection_type` varchar(50) NOT NULL,
            `ip_address` varchar(45) NOT NULL,
            `form_type` varchar(50) NOT NULL,
            `user_agent` text,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `protection_type` (`protection_type`),
            KEY `ip_address` (`ip_address`),
            KEY `form_type` (`form_type`),
            KEY `created_at` (`created_at`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->executeQuery($sql);
        
        // Create ip_blocks table
        $sql = "CREATE TABLE IF NOT EXISTS `ip_blocks` (
            `id` int NOT NULL AUTO_INCREMENT,
            `ip_address` varchar(45) NOT NULL,
            `reason` varchar(255) DEFAULT NULL,
            `expires_at` timestamp NULL DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `ip_address` (`ip_address`),
            KEY `expires_at` (`expires_at`),
            KEY `created_at` (`created_at`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        
        $this->db->executeQuery($sql);
        
        return true;
    }
}