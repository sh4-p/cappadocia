<?php
/**
 * Tour Extra Model
 */
class TourExtra extends Model
{
    protected $table = 'tour_extras';
    protected $primaryKey = 'id';
    
    /**
     * Get all active extras
     * 
     * @return array Active extras
     */
    public function getActiveExtras()
    {
        return $this->getAll(['is_active' => 1], 'order_number ASC, name ASC');
    }
    
    /**
     * Get all extras with details for a specific language
     * 
     * @param string $langCode Language code
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @return array Extras with details
     */
    public function getAllWithDetails($langCode, $conditions = [], $orderBy = 'te.order_number ASC, ted.name ASC')
    {
        // Get language model and language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return [];
        }
        
        $langId = $language['id'];
        
        // Start building the SQL query
        $sql = "SELECT te.*, 
                       COALESCE(ted.name, te.name) as name, 
                       COALESCE(ted.description, te.description) as description,
                       COALESCE(te.pricing_type, 'fixed_group') as pricing_type, 
                       COALESCE(te.base_price, 0.00) as base_price, 
                       COALESCE(te.category, '') as category, 
                       COALESCE(te.order_number, 0) as order_number
                FROM {$this->table} te
                LEFT JOIN tour_extra_details ted ON te.id = ted.extra_id AND ted.language_id = {$langId}
                WHERE 1=1";
        
        // Add conditions
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                if (is_numeric($value)) {
                    $sql .= " AND {$field} = {$value}";
                } else if ($value === null) {
                    $sql .= " AND {$field} IS NULL";
                } else {
                    $safeValue = str_replace("'", "''", $value);
                    $sql .= " AND {$field} = '{$safeValue}'";
                }
            }
        }
        
        // Add order by
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        
        // Execute the query
        return $this->query($sql);
    }
    
    /**
     * Get a single extra with details for a specific language
     * 
     * @param int $id Extra ID
     * @param string $langCode Language code
     * @return array|false Extra with details
     */
    public function getWithDetails($id, $langCode)
    {
        // Get language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return false;
        }
        
        $langId = $language['id'];
        
        // Build and execute the query
        $sql = "SELECT te.*, 
                       COALESCE(ted.name, te.name) as name, 
                       COALESCE(ted.description, te.description) as description,
                       COALESCE(te.pricing_type, 'fixed_group') as pricing_type, 
                       COALESCE(te.base_price, 0.00) as base_price, 
                       COALESCE(te.category, '') as category, 
                       COALESCE(te.order_number, 0) as order_number
                FROM {$this->table} te
                LEFT JOIN tour_extra_details ted ON te.id = ted.extra_id AND ted.language_id = {$langId}
                WHERE te.id = " . (int)$id;
        
        // Execute the query directly
        $result = $this->query($sql);
        
        // Return the first result or false if no result found
        return !empty($result) ? $result[0] : false;
    }
    
    /**
     * Add an extra with details for all languages
     * 
     * @param array $extraData Extra data
     * @param array $detailsData Details data for each language
     * @return int|false Extra ID or false
     */
    public function addWithDetails($extraData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Insert extra
            $extraId = $this->insert($extraData);
            
            if (!$extraId) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Insert details for each language
            foreach ($detailsData as $langId => $details) {
                $details['extra_id'] = $extraId;
                $details['language_id'] = $langId;
                
                $result = $this->db->insert('tour_extra_details', $details);
                
                if (!$result) {
                    $this->db->cancelTransaction();
                    return false;
                }
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            return $extraId;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update an extra with details for all languages
     * 
     * @param int $extraId Extra ID
     * @param array $extraData Extra data
     * @param array $detailsData Details data for each language
     * @return bool Success
     */
    public function updateWithDetails($extraId, $extraData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Update extra
            $result = $this->update($extraData, ['id' => $extraId]);
            
            if (!$result) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Update details for each language
            foreach ($detailsData as $langId => $details) {
                // Check if details exist
                $sql = "SELECT id FROM tour_extra_details WHERE extra_id = :extraId AND language_id = :langId";
                $detailsId = $this->db->getValue($sql, [
                    'extraId' => $extraId,
                    'langId' => $langId
                ]);
                
                if ($detailsId) {
                    // Update details
                    $result = $this->db->update('tour_extra_details', $details, ['id' => $detailsId]);
                } else {
                    // Insert details
                    $details['extra_id'] = $extraId;
                    $details['language_id'] = $langId;
                    
                    $result = $this->db->insert('tour_extra_details', $details);
                }
                
                if (!$result) {
                    $this->db->cancelTransaction();
                    return false;
                }
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Delete an extra and its details
     * 
     * @param int $extraId Extra ID
     * @return bool Success
     */
    public function deleteWithDetails($extraId)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Delete extra details
            $this->db->delete('tour_extra_details', ['extra_id' => $extraId]);
            
            // Delete extra pricing
            $this->db->delete('tour_extra_pricing', ['extra_id' => $extraId]);
            
            // Delete tour associations
            $this->db->delete('tour_available_extras', ['extra_id' => $extraId]);
            
            // Delete extra
            $result = $this->delete(['id' => $extraId]);
            
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
     * Get extras available for a tour
     * 
     * @param int $tourId Tour ID
     * @return array Available extras with pricing
     */
    public function getAvailableExtrasForTour($tourId)
    {
        $sql = "SELECT te.*, tae.is_featured, tae.order_number as tour_order
                FROM {$this->table} te
                INNER JOIN tour_available_extras tae ON te.id = tae.extra_id
                WHERE tae.tour_id = :tourId AND te.is_active = 1
                ORDER BY tae.order_number ASC, te.order_number ASC";
        
        return $this->db->getRows($sql, ['tourId' => $tourId]);
    }
    
    /**
     * Get extra with pricing for person count
     * 
     * @param int $extraId Extra ID
     * @param int $personCount Number of persons
     * @return array|null Extra with pricing
     */
    public function getExtraWithPricing($extraId, $personCount = 1)
    {
        $extra = $this->getById($extraId);
        
        if (!$extra) {
            return null;
        }
        
        // Get pricing for this person count
        $pricingModel = new TourExtraPricing($this->db);
        $pricing = $pricingModel->calculateTotalPrice($extraId, $personCount);
        
        $extra['pricing'] = $pricing;
        
        return $extra;
    }
    
    /**
     * Save extra
     * 
     * @param array $data Extra data
     * @return int|false Extra ID or false
     */
    public function saveExtra($data)
    {
        // Prepare data
        $extraData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null,
            'is_active' => isset($data['is_active']) ? 1 : 0,
            'order_number' => (int)($data['order_number'] ?? 0)
        ];
        
        if (!empty($data['id'])) {
            // Update existing
            $result = $this->update($extraData, ['id' => $data['id']]);
            return $result ? $data['id'] : false;
        } else {
            // Create new
            return $this->insert($extraData);
        }
    }
    
    /**
     * Delete extra and related data
     * 
     * @param int $extraId Extra ID
     * @return bool Success
     */
    public function deleteExtra($extraId)
    {
        $this->db->beginTransaction();
        
        try {
            // Delete pricing
            $this->db->delete('tour_extra_pricing', ['extra_id' => $extraId]);
            
            // Delete tour associations
            $this->db->delete('tour_available_extras', ['extra_id' => $extraId]);
            
            // Delete extra details
            $this->db->delete('tour_extra_details', ['extra_id' => $extraId]);
            
            // Delete extra
            $this->delete(['id' => $extraId]);
            
            $this->db->endTransaction();
            return true;
            
        } catch (Exception $e) {
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Add extra to tour
     * 
     * @param int $tourId Tour ID
     * @param int $extraId Extra ID
     * @param bool $isFeatured Is featured
     * @param int $orderNumber Order number
     * @return bool Success
     */
    public function addExtraToTour($tourId, $extraId, $isFeatured = false, $orderNumber = 0)
    {
        $data = [
            'tour_id' => $tourId,
            'extra_id' => $extraId,
            'is_featured' => $isFeatured ? 1 : 0,
            'order_number' => $orderNumber
        ];
        
        // Check if already exists
        $existing = $this->db->getValue(
            "SELECT id FROM tour_available_extras WHERE tour_id = :tourId AND extra_id = :extraId",
            ['tourId' => $tourId, 'extraId' => $extraId]
        );
        
        if ($existing) {
            return $this->db->update('tour_available_extras', [
                'is_featured' => $data['is_featured'],
                'order_number' => $data['order_number']
            ], ['id' => $existing]);
        } else {
            return $this->db->insert('tour_available_extras', $data);
        }
    }
    
    /**
     * Remove extra from tour
     * 
     * @param int $tourId Tour ID
     * @param int $extraId Extra ID
     * @return bool Success
     */
    public function removeExtraFromTour($tourId, $extraId)
    {
        return $this->db->delete('tour_available_extras', [
            'tour_id' => $tourId,
            'extra_id' => $extraId
        ]);
    }
    
    /**
     * Get featured extras for a tour
     * 
     * @param int $tourId Tour ID
     * @param int $limit Limit
     * @return array Featured extras
     */
    public function getFeaturedExtrasForTour($tourId, $limit = 3)
    {
        $sql = "SELECT te.*, tae.is_featured, tae.order_number as tour_order
                FROM {$this->table} te
                INNER JOIN tour_available_extras tae ON te.id = tae.extra_id
                WHERE tae.tour_id = :tourId AND te.is_active = 1 AND tae.is_featured = 1
                ORDER BY tae.order_number ASC, te.order_number ASC
                LIMIT :limit";
        
        return $this->db->getRows($sql, [
            'tourId' => $tourId,
            'limit' => $limit
        ]);
    }
    
    /**
     * Search extras
     * 
     * @param string $search Search term
     * @param bool $onlyActive Only active extras
     * @return array Search results
     */
    public function searchExtras($search, $onlyActive = true)
    {
        $conditions = [];
        $params = [];
        
        if ($onlyActive) {
            $conditions[] = 'is_active = 1';
        }
        
        if (!empty($search)) {
            $conditions[] = '(name LIKE :search OR description LIKE :search)';
            $params['search'] = '%' . $search . '%';
        }
        
        $whereClause = !empty($conditions) ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT * FROM {$this->table} $whereClause ORDER BY order_number ASC, name ASC";
        
        return $this->db->getRows($sql, $params);
    }
}