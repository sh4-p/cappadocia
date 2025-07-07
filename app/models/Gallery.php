<?php
/**
 * Gallery Model
 */
class Gallery extends Model
{
    protected $table = 'gallery';
    protected $primaryKey = 'id';
    
    /**
     * Get all gallery items with details for a specific language
     * 
     * @param string $langCode Language code
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Gallery items
     */
    public function getAllWithDetails($langCode, $conditions = [], $orderBy = 'g.id DESC', $limit = null, $offset = null)
    {
        // Get language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return [];
        }
        
        $sql = "SELECT g.*, gd.title, gd.description, 
                t.id as tour_id, td.name as tour_name, td.slug as tour_slug
                FROM gallery g
                LEFT JOIN gallery_details gd ON g.id = gd.gallery_id AND gd.language_id = :gdLangId
                LEFT JOIN tours t ON g.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id AND td.language_id = :tdLangId";
        
        $params = [
            'gdLangId' => $language['id'],
            'tdLangId' => $language['id']
        ];
        
        // Add where clause if conditions exist
        if (!empty($conditions)) {
            $sql .= " WHERE";
            $firstCondition = true;
            
            foreach ($conditions as $field => $value) {
                // Use AND for all conditions after the first one
                if (!$firstCondition) {
                    $sql .= " AND";
                }
                
                // Handle different condition types
                if ($value === null) {
                    $sql .= " {$field} IS NULL";
                } else if (is_int($value) || is_bool($value)) {
                    // For simple numeric values, add them directly
                    $sql .= " {$field} = " . (int)$value;
                } else {
                    // For string values, use parameterized query
                    $paramName = 'param_' . count($params);
                    $sql .= " {$field} = :{$paramName}";
                    $params[$paramName] = $value;
                }
                
                $firstCondition = false;
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
     * Get a gallery item with details for a specific language
     * 
     * @param int $id Gallery item ID
     * @param string $langCode Language code
     * @return array|false Gallery item
     */
    public function getWithDetails($id, $langCode)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return false;
        }
        
        // Build SQL query
        $sql = "SELECT g.*, gd.title, gd.description, 
                       t.id as tour_id, td.name as tour_name, td.slug as tour_slug
                FROM {$this->table} g
                LEFT JOIN gallery_details gd ON g.id = gd.gallery_id AND gd.language_id = :gdLangId
                LEFT JOIN tours t ON g.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id AND td.language_id = :tdLangId
                WHERE g.id = :id";
        
        // Execute query
        return $this->db->getRow($sql, [
            'id' => $id,
            'gdLangId' => $langId,
            'tdLangId' => $langId
        ]);
    }
    
    /**
     * Get gallery items by tour
     * 
     * @param int $tourId Tour ID
     * @param string $langCode Language code
     * @return array Gallery items
     */
    public function getByTour($tourId, $langCode)
    {
        return $this->getAllWithDetails($langCode, [
            'g.tour_id' => $tourId,
            'g.is_active' => 1
        ], 'g.order_number ASC, g.id ASC');
    }
    
    /**
     * Get gallery items by category
     * 
     * @param int|string $categoryIdOrSlug Category ID or slug
     * @param string $langCode Language code
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Gallery items
     */
    public function getByCategory($categoryIdOrSlug, $langCode, $limit = null, $offset = null)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return [];
        }
        
        // Determine if ID or slug
        $isId = is_numeric($categoryIdOrSlug);
        
        // Get category ID if slug
        if (!$isId) {
            $sql = "SELECT category_id FROM category_details WHERE slug = :slug AND language_id = :cdLangId";
            $categoryId = $this->db->getValue($sql, [
                'slug' => $categoryIdOrSlug,
                'cdLangId' => $langId
            ]);
            
            if (!$categoryId) {
                return [];
            }
        } else {
            $categoryId = $categoryIdOrSlug;
        }
        
        // Build SQL query
        $sql = "SELECT g.*, gd.title, gd.description, 
                       t.id as tour_id, td.name as tour_name, td.slug as tour_slug
                FROM {$this->table} g
                LEFT JOIN gallery_details gd ON g.id = gd.gallery_id AND gd.language_id = :gdLangId
                LEFT JOIN tours t ON g.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id AND td.language_id = :tdLangId
                WHERE g.is_active = 1 AND t.category_id = :categoryId";
        
        // Add order by
        $sql .= " ORDER BY g.order_number ASC, g.id DESC";
        
        // Add limit and offset
        if ($limit) {
            $sql .= " LIMIT {$limit}";
            
            if ($offset) {
                $sql .= " OFFSET {$offset}";
            }
        }
        
        // Execute query
        return $this->db->getRows($sql, [
            'gdLangId' => $langId,
            'tdLangId' => $langId,
            'categoryId' => $categoryId
        ]);
    }
    
    /**
     * Count gallery items by category
     * 
     * @param int|string $categoryIdOrSlug Category ID or slug
     * @param string $langCode Language code
     * @return int Count
     */
    public function countByCategory($categoryIdOrSlug, $langCode = DEFAULT_LANGUAGE)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return 0;
        }
        
        // Determine if ID or slug
        $isId = is_numeric($categoryIdOrSlug);
        
        // Get category ID if slug
        if (!$isId) {
            $sql = "SELECT category_id FROM category_details WHERE slug = :slug AND language_id = :cdLangId";
            $categoryId = $this->db->getValue($sql, [
                'slug' => $categoryIdOrSlug,
                'cdLangId' => $langId
            ]);
            
            if (!$categoryId) {
                return 0;
            }
        } else {
            $categoryId = $categoryIdOrSlug;
        }
        
        // Build SQL query
        $sql = "SELECT COUNT(*)
                FROM {$this->table} g
                LEFT JOIN tours t ON g.tour_id = t.id
                WHERE g.is_active = 1 AND t.category_id = :categoryId";
        
        // Execute query
        return $this->db->getValue($sql, [
            'categoryId' => $categoryId
        ]);
    }
    
    /**
     * Execute direct SQL query (safe for numeric values)
     * 
     * @param string $sql SQL query with numeric placeholders
     * @return array Results
     */
    private function executeDirectQuery($sql)
    {
        try {
            // Custom query using the query method in Database class
            $this->db->query($sql);
            // No parameters to bind
            return $this->db->resultSet();
        } catch (Exception $e) {
            writeLog('Gallery query error: ' . $e->getMessage(), 'gallery');
            return [];
        }
    }

    /**
     * Get gallery items for homepage
     * 
     * @param string $langCode Language code
     * @param int $limit Limit
     * @return array Gallery items
     */
    public function getHomeGallery($langCode, $limit = 8)
    {
        // Get language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return [];
        }
        
        $langId = (int)$language['id']; // Güvenli sayı dönüşümü
        $limit = (int)$limit; // Limit sayısını güvenli hale getir
        
        $sql = "SELECT g.*, gd.title, gd.description, 
                t.id as tour_id, td.name as tour_name, td.slug as tour_slug
                FROM gallery g
                LEFT JOIN gallery_details gd ON g.id = gd.gallery_id AND gd.language_id = {$langId}
                LEFT JOIN tours t ON g.tour_id = t.id
                LEFT JOIN tour_details td ON t.id = td.tour_id AND td.language_id = {$langId} 
                WHERE g.is_active = 1 
                ORDER BY RAND() 
                LIMIT {$limit}";
        
        return $this->executeDirectQuery($sql);
    }
    
    /**
     * Add a gallery item with details for all languages
     * 
     * @param array $galleryData Gallery data
     * @param array $detailsData Details data for each language
     * @return int|false Gallery item ID or false
     */
    public function addWithDetails($galleryData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Insert gallery item
            $galleryId = $this->insert($galleryData);
            
            if (!$galleryId) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Insert details for each language
            foreach ($detailsData as $langId => $details) {
                $details['gallery_id'] = $galleryId;
                $details['language_id'] = $langId;
                
                $result = $this->db->insert('gallery_details', $details);
                
                if (!$result) {
                    $this->db->cancelTransaction();
                    return false;
                }
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            return $galleryId;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update a gallery item with details for all languages
     * 
     * @param int $galleryId Gallery item ID
     * @param array $galleryData Gallery data
     * @param array $detailsData Details data for each language
     * @return bool Success
     */
    public function updateWithDetails($galleryId, $galleryData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Update gallery item
            $result = $this->update($galleryData, ['id' => $galleryId]);
            
            if (!$result) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Update details for each language
            foreach ($detailsData as $langId => $details) {
                // Check if details exist
                $sql = "SELECT id FROM gallery_details WHERE gallery_id = :galleryId AND language_id = :gdLangId";
                $detailsId = $this->db->getValue($sql, [
                    'galleryId' => $galleryId,
                    'gdLangId' => $langId
                ]);
                
                if ($detailsId) {
                    // Update details
                    $result = $this->db->update('gallery_details', $details, ['id' => $detailsId]);
                } else {
                    // Insert details
                    $details['gallery_id'] = $galleryId;
                    $details['language_id'] = $langId;
                    
                    $result = $this->db->insert('gallery_details', $details);
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
     * Delete a gallery item and its details
     * 
     * @param int $galleryId Gallery item ID
     * @return bool Success
     */
    public function deleteWithDetails($galleryId)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Delete gallery details
            $this->db->delete('gallery_details', ['gallery_id' => $galleryId]);
            
            // Delete gallery item
            $result = $this->delete(['id' => $galleryId]);
            
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
     * Update gallery item order
     * 
     * @param int $galleryId Gallery item ID
     * @param int $orderNumber Order number
     * @return bool Success
     */
    public function updateOrder($galleryId, $orderNumber)
    {
        return $this->update(['order_number' => $orderNumber], ['id' => $galleryId]);
    }
}