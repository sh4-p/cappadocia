<?php
/**
 * Testimonial Model
 */
class Testimonial extends Model
{
    protected $table = 'testimonials';
    protected $primaryKey = 'id';
    
    /**
     * Get all testimonials with details for a specific language
     * 
     * @param string $langCode Language code
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Testimonials
     */
    public function getAllWithDetails($langCode, $conditions = [], $orderBy = 't.id DESC', $limit = null, $offset = null)
    {
        // Get language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return [];
        }
        
        $sql = "SELECT t.*, td.content
                FROM testimonials t
                JOIN testimonial_details td ON t.id = td.testimonial_id
                WHERE td.language_id = :langId";
        
        $params = ['langId' => $language['id']];
        
        // Add conditions - manually handle 't.is_active' condition
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                if ($field === 't.is_active') {
                    $sql .= " AND t.is_active = " . (int)$value; // Directly insert the value for simple boolean/integer
                } 
                else if (strpos($field, '.') !== false) {
                    // For any field with a dot, extract the parts and use them directly
                    list($table, $column) = explode('.', $field);
                    $sql .= " AND {$table}.{$column} = :{$table}_{$column}";
                    $params["{$table}_{$column}"] = $value;
                }
                else if ($value === null) {
                    $sql .= " AND {$field} IS NULL";
                }
                else {
                    $sql .= " AND {$field} = :{$field}";
                    $params[$field] = $value;
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
        
        return $this->db->getRows($sql, $params);
    }
    
    /**
     * Get a single testimonial with details for a specific language
     * 
     * @param int $id Testimonial ID
     * @param string $langCode Language code
     * @return array|false Testimonial
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
        $sql = "SELECT t.*, td.content
                FROM {$this->table} t
                JOIN testimonial_details td ON t.id = td.testimonial_id
                WHERE t.id = :id AND td.language_id = :langId";
        
        // Execute query
        return $this->db->getRow($sql, [
            'id' => $id,
            'langId' => $langId
        ]);
    }
    
    /**
     * Get random testimonials
     * 
     * @param string $langCode Language code
     * @param int $limit Limit
     * @return array Testimonials
     */
    public function getRandomTestimonials($langCode, $limit = 3)
    {
        return $this->getAllWithDetails($langCode, ['t.is_active' => 1], 'RAND()', $limit);
    }
    
    /**
     * Add a testimonial with details for all languages
     * 
     * @param array $testimonialData Testimonial data
     * @param array $detailsData Details data for each language
     * @return int|false Testimonial ID or false
     */
    public function addWithDetails($testimonialData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Insert testimonial
            $testimonialId = $this->insert($testimonialData);
            
            if (!$testimonialId) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Insert details for each language
            foreach ($detailsData as $langId => $details) {
                $details['testimonial_id'] = $testimonialId;
                $details['language_id'] = $langId;
                
                $result = $this->db->insert('testimonial_details', $details);
                
                if (!$result) {
                    $this->db->cancelTransaction();
                    return false;
                }
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            return $testimonialId;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update a testimonial with details for all languages
     * 
     * @param int $testimonialId Testimonial ID
     * @param array $testimonialData Testimonial data
     * @param array $detailsData Details data for each language
     * @return bool Success
     */
    public function updateWithDetails($testimonialId, $testimonialData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Update testimonial
            $result = $this->update($testimonialData, ['id' => $testimonialId]);
            
            if (!$result) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Update details for each language
            foreach ($detailsData as $langId => $details) {
                // Check if details exist
                $sql = "SELECT id FROM testimonial_details WHERE testimonial_id = :testimonialId AND language_id = :langId";
                $detailsId = $this->db->getValue($sql, [
                    'testimonialId' => $testimonialId,
                    'langId' => $langId
                ]);
                
                if ($detailsId) {
                    // Update details
                    $result = $this->db->update('testimonial_details', $details, ['id' => $detailsId]);
                } else {
                    // Insert details
                    $details['testimonial_id'] = $testimonialId;
                    $details['language_id'] = $langId;
                    
                    $result = $this->db->insert('testimonial_details', $details);
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
     * Delete a testimonial and its details
     * 
     * @param int $testimonialId Testimonial ID
     * @return bool Success
     */
    public function deleteWithDetails($testimonialId)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Delete testimonial details
            $this->db->delete('testimonial_details', ['testimonial_id' => $testimonialId]);
            
            // Delete testimonial
            $result = $this->delete(['id' => $testimonialId]);
            
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
     * Update testimonial status
     * 
     * @param int $testimonialId Testimonial ID
     * @param bool $isActive Is active
     * @return bool Success
     */
    public function updateStatus($testimonialId, $isActive)
    {
        return $this->update(['is_active' => $isActive ? 1 : 0], ['id' => $testimonialId]);
    }
}