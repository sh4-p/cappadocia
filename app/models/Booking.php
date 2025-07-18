<?php
/**
 * Booking Model - Updated with Tracking System
 */
class Booking extends Model
{
    protected $table = 'bookings';
    protected $primaryKey = 'id';
    
    /**
     * Get all bookings with tour details
     * 
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Bookings
     */
    public function getAllWithTourDetails($conditions = [], $orderBy = 'b.id DESC', $limit = null, $offset = null)
    {
        $sql = "SELECT b.*, t.id as tour_id, td.name as tour_name
                FROM {$this->table} b
                LEFT JOIN tours t ON b.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN languages l ON td.language_id = l.id
                WHERE l.code = :langCode";
        
        // Add conditions
        $params = ['langCode' => DEFAULT_LANGUAGE];
        
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                $sql .= " AND {$field} = :{$field}_param";
                $params[$field . '_param'] = $value;
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
        return $this->db->getRows($sql, $params);
    }
    
    /**
     * Get a single booking with tour details
     * 
     * @param int $id Booking ID
     * @param string $langCode Language code
     * @return array|false Booking
     */
    public function getWithTourDetails($id, $langCode = DEFAULT_LANGUAGE)
    {
        $sql = "SELECT b.*, 
                       t.id as tour_id, 
                       t.price, 
                       t.discount_price, 
                       t.featured_image,
                       td.name as tour_name, 
                       td.slug as tour_slug
                FROM {$this->table} b
                LEFT JOIN tours t ON b.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN languages l ON td.language_id = l.id
                WHERE b.id = :id AND l.code = :langCode";
        
        return $this->db->getRow($sql, [
            'id' => $id,
            'langCode' => $langCode
        ]);
    }
    
    /**
     * Get booking by tracking token
     * 
     * @param string $token Tracking token
     * @param string $langCode Language code
     * @return array|false Booking
     */
    public function getByTrackingToken($token, $langCode = DEFAULT_LANGUAGE)
    {
        $sql = "SELECT b.*, 
                       t.id as tour_id, 
                       t.price, 
                       t.discount_price, 
                       t.featured_image,
                       td.name as tour_name, 
                       td.slug as tour_slug,
                       td.description as tour_description
                FROM {$this->table} b
                LEFT JOIN tours t ON b.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN languages l ON td.language_id = l.id
                WHERE b.tracking_token = :token AND l.code = :langCode";
        
        return $this->db->getRow($sql, [
            'token' => $token,
            'langCode' => $langCode
        ]);
    }
    
    /**
     * Search bookings by email and booking reference
     * 
     * @param string $email Customer email
     * @param string $reference Booking reference (ID)
     * @param string $langCode Language code
     * @return array|false Booking
     */
    public function searchByEmailAndReference($email, $reference, $langCode = DEFAULT_LANGUAGE)
    {
        // Clean reference - remove # and leading zeros
        $bookingId = ltrim(str_replace('#', '', $reference), '0');
        
        $sql = "SELECT b.*, 
                       t.id as tour_id, 
                       t.price, 
                       t.discount_price, 
                       t.featured_image,
                       td.name as tour_name, 
                       td.slug as tour_slug,
                       td.description as tour_description
                FROM {$this->table} b
                LEFT JOIN tours t ON b.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN languages l ON td.language_id = l.id
                WHERE b.email = :email AND b.id = :booking_id AND l.code = :langCode";
        
        return $this->db->getRow($sql, [
            'email' => $email,
            'booking_id' => $bookingId,
            'langCode' => $langCode
        ]);
    }
    
    /**
     * Get recent bookings
     * 
     * @param int $limit Limit
     * @return array Bookings
     */
    public function getRecentBookings($limit = 5)
    {
        $sql = "SELECT b.*, t.id as tour_id, td.name as tour_name
                FROM {$this->table} b
                LEFT JOIN tours t ON b.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN languages l ON td.language_id = l.id
                WHERE l.code = :langCode
                ORDER BY b.created_at DESC
                LIMIT {$limit}";
        
        return $this->db->getRows($sql, [
            'langCode' => DEFAULT_LANGUAGE
        ]);
    }
    
    /**
     * Get bookings by user email
     * 
     * @param string $email User email
     * @param string $langCode Language code
     * @return array Bookings
     */
    public function getByEmail($email, $langCode = DEFAULT_LANGUAGE)
    {
        $sql = "SELECT b.*, t.id as tour_id, td.name as tour_name, td.slug as tour_slug
                FROM {$this->table} b
                LEFT JOIN tours t ON b.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN languages l ON td.language_id = l.id
                WHERE b.email = :email AND l.code = :langCode
                ORDER BY b.booking_date DESC";
        
        return $this->db->getRows($sql, [
            'email' => $email,
            'langCode' => $langCode
        ]);
    }
    
    /**
     * Get bookings by tour
     * 
     * @param int $tourId Tour ID
     * @return array Bookings
     */
    public function getByTour($tourId)
    {
        return $this->getAll(['tour_id' => $tourId], 'booking_date DESC');
    }
    
    /**
     * Count bookings
     * 
     * @param array $conditions Where conditions
     * @return int Count
     */
    public function countBookings($conditions = [])
    {
        return $this->count($conditions);
    }
    
    /**
     * Update booking status
     * 
     * @param int $id Booking ID
     * @param string $status Status
     * @return bool Success
     */
    public function updateStatus($id, $status)
    {
        return $this->update(['status' => $status], ['id' => $id]);
    }
    
    /**
     * Get booking status history
     * 
     * @param int $bookingId Booking ID
     * @return array Status history
     */
    public function getStatusHistory($bookingId)
    {
        // This would require a separate status_history table
        // For now, we'll return a simple array based on current status
        $booking = $this->getById($bookingId);
        if (!$booking) {
            return [];
        }
        
        $history = [];
        
        // Always starts with pending
        $history[] = [
            'status' => 'pending',
            'created_at' => $booking['created_at'],
            'message' => __('booking_created')
        ];
        
        // If status is not pending, add current status
        if ($booking['status'] !== 'pending') {
            $history[] = [
                'status' => $booking['status'],
                'created_at' => $booking['updated_at'] ?: $booking['created_at'],
                'message' => __('status_changed_to_' . $booking['status'])
            ];
        }
        
        return $history;
    }
    
    /**
     * Generate unique tracking token
     * 
     * @return string Tracking token
     */
    public function generateTrackingToken()
    {
        do {
            // Generate a unique token: timestamp + random string
            $token = strtoupper(substr(md5(time() . uniqid() . mt_rand()), 0, 16));
            
            // Check if token already exists
            $existing = $this->db->getRow(
                "SELECT id FROM {$this->table} WHERE tracking_token = :token",
                ['token' => $token]
            );
        } while ($existing);
        
        return $token;
    }
    
    /**
     * Get sales data for a specific period
     * 
     * @param int $days Number of days
     * @return array Sales data
     */
    public function getSalesData($days = 30)
    {
        $data = [];
        
        // Get start date (days ago)
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        
        // Get sales data
        $sql = "SELECT DATE(created_at) as date, SUM(total_price) as total
                FROM {$this->table}
                WHERE created_at >= :startDate AND status != 'cancelled'
                GROUP BY DATE(created_at)
                ORDER BY date ASC";
        
        $salesData = $this->db->getRows($sql, [
            'startDate' => $startDate
        ]);
        
        // Format data
        $currentDate = strtotime($startDate);
        $endDate = strtotime(date('Y-m-d'));
        
        // Create an array with all dates
        while ($currentDate <= $endDate) {
            $date = date('Y-m-d', $currentDate);
            $data[] = [
                'date' => $date,
                'total' => 0
            ];
            $currentDate = strtotime('+1 day', $currentDate);
        }
        
        // Fill in sales data
        foreach ($salesData as $sale) {
            foreach ($data as &$item) {
                if ($item['date'] === $sale['date']) {
                    $item['total'] = (float) $sale['total'];
                    break;
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Get monthly sales stats
     * 
     * @param int $year Year
     * @return array Monthly sales
     */
    public function getMonthlySales($year = null)
    {
        // If year is not provided, use current year
        if (!$year) {
            $year = date('Y');
        }
        
        $data = [];
        
        // Get monthly sales
        $sql = "SELECT MONTH(created_at) as month, SUM(total_price) as total, COUNT(*) as count
                FROM {$this->table}
                WHERE YEAR(created_at) = :year AND status != 'cancelled'
                GROUP BY MONTH(created_at)
                ORDER BY month ASC";
        
        $monthlySales = $this->db->getRows($sql, [
            'year' => $year
        ]);
        
        // Create an array with all months
        for ($month = 1; $month <= 12; $month++) {
            $data[$month] = [
                'month' => $month,
                'total' => 0,
                'count' => 0
            ];
        }
        
        // Fill in sales data
        foreach ($monthlySales as $sale) {
            $month = (int) $sale['month'];
            $data[$month] = [
                'month' => $month,
                'total' => (float) $sale['total'],
                'count' => (int) $sale['count']
            ];
        }
        
        return array_values($data);
    }
    
    /**
     * Get payment method statistics
     * 
     * @param int $days Number of days (optional)
     * @return array Payment method statistics
     */
    public function getPaymentMethodStats($days = null)
    {
        $sql = "SELECT payment_method, COUNT(*) as count, SUM(total_price) as total
                FROM {$this->table}
                WHERE status != 'cancelled'";
        
        $params = [];
        
        if ($days) {
            $sql .= " AND created_at >= :startDate";
            $params['startDate'] = date('Y-m-d', strtotime("-{$days} days"));
        }
        
        $sql .= " GROUP BY payment_method
                  ORDER BY count DESC";
        
        return $this->db->getRows($sql, $params);
    }
    
    /**
     * Insert booking with validation and tracking token
     * 
     * @param array $data Booking data
     * @return int|false Booking ID or false
     */
    public function insert($data)
    {
        // Validate required fields
        $requiredFields = ['tour_id', 'first_name', 'last_name', 'email', 'phone', 'booking_date', 'adults', 'total_price'];
        
        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }
        
        // Set defaults
        $data['children'] = isset($data['children']) ? (int)$data['children'] : 0;
        $data['status'] = isset($data['status']) ? $data['status'] : 'pending';
        $data['payment_method'] = isset($data['payment_method']) ? $data['payment_method'] : 'card';
        $data['notes'] = isset($data['notes']) ? $data['notes'] : '';
        $data['created_by_admin'] = isset($data['created_by_admin']) ? (int)$data['created_by_admin'] : 0;
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        // Generate tracking token if not provided
        if (!isset($data['tracking_token']) || empty($data['tracking_token'])) {
            $data['tracking_token'] = $this->generateTrackingToken();
        }
        
        // Insert booking
        return parent::insert($data);
    }
    
    /**
     * Update booking with validation
     * 
     * @param array $data Booking data
     * @param array $conditions Where conditions
     * @return bool Success
     */
    public function update($data, $conditions)
    {
        // Set updated timestamp
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return parent::update($data, $conditions);
    }
    
    /**
     * Get formatted booking reference
     * 
     * @param int $bookingId Booking ID
     * @return string Formatted reference
     */
    public static function formatReference($bookingId)
    {
        return '#' . str_pad($bookingId, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Get booking status label
     * 
     * @param string $status Status
     * @return string Status label
     */
    public static function getStatusLabel($status)
    {
        $labels = [
            'pending' => __('awaiting_confirmation'),
            'confirmed' => __('confirmed'),
            'cancelled' => __('cancelled'),
            'completed' => __('completed')
        ];
        
        return isset($labels[$status]) ? $labels[$status] : ucfirst($status);
    }
    
    /**
     * Get booking status color
     * 
     * @param string $status Status
     * @return string Status color class
     */
    public static function getStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'success',
            'cancelled' => 'danger',
            'completed' => 'primary'
        ];
        
        return isset($colors[$status]) ? $colors[$status] : 'secondary';
    }
}