<?php
/**
 * Booking Model
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
}