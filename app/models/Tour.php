
<?php
/**
 * Tour Model
 */
class Tour extends Model
{
    protected $table = 'tours';
    protected $primaryKey = 'id';
    
    /**
     * Get all tours with details for a specific language
     * 
     * @param string $langCode Language code
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Tours
     */
    /**
 * Get all tours with details for a specific language
 * 
 * @param string $langCode Language code
 * @param array $conditions Where conditions
 * @param string $orderBy Order by clause
 * @param int $limit Limit
 * @param int $offset Offset
 * @return array Tours
 */
    public function getAllWithDetails($langCode, $conditions = [], $orderBy = 't.id DESC', $limit = null, $offset = null)
    {
        // Get language model and language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return [];
        }
        
        $langId = $language['id'];
        
        // Start building the SQL query
        $sql = "SELECT t.*, td.name, td.slug, td.short_description, td.description, 
                td.includes, td.excludes, td.itinerary, t.duration_type, t.duration_days, td.meta_title, td.meta_description,
                c.id as category_id, cd.name as category_name, cd.slug as category_slug
                FROM tours t
                JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN category_details cd ON c.id = cd.category_id AND cd.language_id = {$langId}
                WHERE td.language_id = {$langId}";
        
        // Add conditions
        if (!empty($conditions)) {
            // Handle t.is_active condition directly
            if (isset($conditions['t.is_active'])) {
                $isActive = (int)$conditions['t.is_active'];
                $sql .= " AND t.is_active = {$isActive}";
                unset($conditions['t.is_active']);
            }
            
            // Handle remaining conditions
            foreach ($conditions as $field => $value) {
                // Check if field contains a comparison operator (like !=)
                if (strpos($field, '!=') !== false) {
                    $fieldName = str_replace('!=', '', $field);
                    $fieldName = trim($fieldName);
                    $sql .= " AND {$fieldName} != " . (is_numeric($value) ? (int)$value : "'" . str_replace("'", "''", $value) . "'");
                } 
                else if (is_numeric($value)) {
                    $sql .= " AND {$field} = {$value}";
                } 
                else if ($value === null) {
                    $sql .= " AND {$field} IS NULL";
                }
                else {
                    $safeValue = str_replace("'", "''", $value);
                    $sql .= " AND {$field} = '{$safeValue}'";
                }
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
        
        // Execute the query
        return $this->query($sql);
    }
    
    /**
     * Get a single tour with details for a specific language
     * 
     * @param int|string $idOrSlug Tour ID or slug
     * @param string $langCode Language code
     * @return array|false Tour
     *///
    public function getWithDetails($idOrSlug, $langCode)
    {
        // Get language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return false;
        }
        
        $langId = $language['id'];
        
        // Determine if ID or slug
        $isId = is_numeric($idOrSlug);
        
        // Manually escape the slug to prevent SQL injection
        if (!$isId) {
            // Simple manual escaping - replace single quotes with two single quotes
            $safeSlug = str_replace("'", "''", $idOrSlug);
        }
        
        // Build and execute the query
        $sql = "SELECT t.*, td.name, td.slug, td.short_description, td.description, 
                td.includes, td.excludes, td.itinerary, td.meta_title, td.meta_description,
                c.id as category_id, cd.name as category_name, cd.slug as category_slug
                FROM tours t
                JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN category_details cd ON c.id = cd.category_id AND cd.language_id = {$langId}
                WHERE td.language_id = {$langId} AND ";
                
        // Add the condition based on ID or slug
        if ($isId) {
            $sql .= "t.id = " . (int)$idOrSlug;
        } else {
            $sql .= "td.slug = '{$safeSlug}'";
        }
        
        // Execute the query directly without parameter binding
        $result = $this->query($sql);
        
        // Return the first result or false if no result found
        return !empty($result) ? $result[0] : false;
    }
    
    /**
     * Get featured tours with details for a specific language
     * 
     * @param string $langCode Language code
     * @param int $limit Limit
     * @return array Tours
     */
    public function getFeatured($langCode, $limit = 6)
    {
        // Get language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return [];
        }
        
        // Use a custom query specifically for featured tours
        $sql = "SELECT t.*, td.name, td.slug, td.short_description, td.description, 
                td.includes, td.excludes, td.itinerary, td.meta_title, td.meta_description,
                c.id as category_id, cd.name as category_name, cd.slug as category_slug
                FROM tours t
                JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN category_details cd ON c.id = cd.category_id AND cd.language_id = ?
                WHERE td.language_id = ? AND t.is_featured = 1 AND t.is_active = 1
                ORDER BY t.id DESC
                LIMIT " . (int)$limit;
        
        // Use a direct query method rather than going through the generic getRows
        $this->db->query($sql);
        $this->db->bind(1, $language['id']);
        $this->db->bind(2, $language['id']);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get tours by category with details for a specific language
     * 
     * @param int|string $categoryIdOrSlug Category ID or slug
     * @param string $langCode Language code
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Tours
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
            $sql = "SELECT category_id FROM category_details WHERE slug = :slug AND language_id = :langId";
            $categoryId = $this->db->getValue($sql, ['slug' => $categoryIdOrSlug, 'langId' => $langId]);
            
            if (!$categoryId) {
                return [];
            }
        } else {
            $categoryId = $categoryIdOrSlug;
        }
        
        // Get tours
        return $this->getAllWithDetails($langCode, ['t.category_id' => $categoryId, 't.is_active' => 1], 't.id DESC', $limit, $offset);
    }
    
    /**
     * Search tours by keyword for a specific language
     * 
     * @param string $keyword Search keyword
     * @param string $langCode Language code
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Tours
     */
    public function search($keyword, $langCode, $limit = null, $offset = null)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return [];
        }
        
        // Build SQL query - Use different parameter names for each occurrence
        $sql = "SELECT t.*, td.name, td.slug, td.short_description, td.description, 
                    td.includes, td.excludes, td.itinerary, td.meta_title, td.meta_description,
                    c.id as category_id, cd.name as category_name, cd.slug as category_slug
                FROM tours t
                JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN category_details cd ON c.id = cd.category_id AND cd.language_id = :langId1
                WHERE td.language_id = :langId2
                AND t.is_active = 1
                AND (td.name LIKE :keyword1 OR td.short_description LIKE :keyword2 OR td.description LIKE :keyword3)";
        
        // Add order by
        $sql .= " ORDER BY t.is_featured DESC, t.id DESC";
        
        // Add limit and offset
        if ($limit) {
            $sql .= " LIMIT {$limit}";
            
            if ($offset) {
                $sql .= " OFFSET {$offset}";
            }
        }
        
        // Execute query with correct parameter binding
        return $this->db->getRows($sql, [
            'langId1' => $langId,
            'langId2' => $langId,
            'keyword1' => '%' . $keyword . '%',
            'keyword2' => '%' . $keyword . '%',
            'keyword3' => '%' . $keyword . '%'
        ]);
    }
    
    /**
     * Count tours by conditions
     * 
     * @param array $conditions Where conditions
     * @return int Count
     */
    public function countTours($conditions = [])
    {
        $sql = "SELECT COUNT(*) FROM tours";
        
        // Add conditions
        $params = [];
        
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
        
        // Execute query
        return $this->db->getValue($sql, $params);
    }
    
    /**
     * Get tour gallery
     * 
     * @param int $tourId Tour ID
     * @param string $langCode Language code
     * @return array Gallery images
     */
    public function getGallery($tourId, $langCode)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return [];
        }
        
        // Get gallery
        $sql = "SELECT g.*, gd.title, gd.description
                FROM gallery g
                LEFT JOIN gallery_details gd ON g.id = gd.gallery_id AND gd.language_id = :langId
                WHERE g.tour_id = :tourId AND g.is_active = 1
                ORDER BY g.order_number ASC, g.id ASC";
        
        return $this->db->getRows($sql, [
            'tourId' => $tourId,
            'langId' => $langId
        ]);
    }
    
    /**
     * Add a tour with details for all languages
     * 
     * @param array $tourData Tour data
     * @param array $detailsData Details data for each language
     * @return int|false Tour ID or false
     */
    public function addWithDetails($tourData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Insert tour
            $tourId = $this->insert($tourData);
            
            if (!$tourId) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Insert details for each language
            foreach ($detailsData as $langId => $details) {
                $details['tour_id'] = $tourId;
                $details['language_id'] = $langId;
                
                $result = $this->db->insert('tour_details', $details);
                
                if (!$result) {
                    $this->db->cancelTransaction();
                    return false;
                }
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            return $tourId;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update a tour with details for all languages
     * 
     * @param int $tourId Tour ID
     * @param array $tourData Tour data
     * @param array $detailsData Details data for each language
     * @return bool Success
     */
    public function updateWithDetails($tourId, $tourData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Update tour
            $result = $this->update($tourData, ['id' => $tourId]);
            
            if (!$result) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Update details for each language
            foreach ($detailsData as $langId => $details) {
                // Check if details exist
                $sql = "SELECT id FROM tour_details WHERE tour_id = :tourId AND language_id = :langId";
                $detailsId = $this->db->getValue($sql, [
                    'tourId' => $tourId,
                    'langId' => $langId
                ]);
                
                if ($detailsId) {
                    // Update details
                    $result = $this->db->update('tour_details', $details, ['id' => $detailsId]);
                } else {
                    // Insert details
                    $details['tour_id'] = $tourId;
                    $details['language_id'] = $langId;
                    
                    $result = $this->db->insert('tour_details', $details);
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
     * Delete a tour and its details
     * 
     * @param int $tourId Tour ID
     * @return bool Success
     */
    public function deleteWithDetails($tourId)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Delete tour details
            $this->db->delete('tour_details', ['tour_id' => $tourId]);
            
            // Delete tour gallery details
            $sql = "DELETE gd FROM gallery_details gd
                    JOIN gallery g ON gd.gallery_id = g.id
                    WHERE g.tour_id = :tourId";
            
            $this->db->executeQuery($sql, ['tourId' => $tourId]);
            
            // Delete tour gallery
            $this->db->delete('gallery', ['tour_id' => $tourId]);
            
            // Delete tour bookings
            $this->db->delete('bookings', ['tour_id' => $tourId]);
            
            // Delete tour
            $result = $this->delete(['id' => $tourId]);
            
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
     * Get active tours for admin booking form
     * 
     * @param string $langCode Language code
     * @return array Active tours
     */
    public function getActiveTours($langCode = DEFAULT_LANGUAGE)
    {
        $sql = "SELECT t.id, t.price, t.discount_price, td.name, td.slug
                FROM {$this->table} t
                LEFT JOIN tour_details td ON t.id = td.tour_id
                LEFT JOIN languages l ON td.language_id = l.id
                WHERE t.is_active = 1 AND l.code = :langCode
                ORDER BY td.name ASC";
        
        return $this->db->getRows($sql, [
            'langCode' => $langCode
        ]);
    }
}