<?php
/**
 * Category Model
 */
class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'id';
    
    /**
     * Get all categories with details for a specific language
     * 
     * @param string $langCode Language code
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Categories
     */
    public function getAllWithDetails($langCode, $conditions = [], $orderBy = 'c.order_number ASC', $limit = null, $offset = null)
    {
        // Get language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return [];
        }
        
        $sql = "SELECT c.*, cd.name, cd.slug, cd.description, cd.meta_title, cd.meta_description,
                (SELECT COUNT(*) FROM tours WHERE category_id = c.id AND is_active = 1) as tour_count
                FROM categories c
                JOIN category_details cd ON c.id = cd.category_id
                WHERE cd.language_id = :langId";
        
        $params = ['langId' => $language['id']];
        
        // Add conditions
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                // Handle special IS NULL/IS NOT NULL conditions
                if ($field === 'c.image IS NOT NULL') {
                    $sql .= " AND c.image IS NOT NULL";
                }
                // Handle IS NULL condition
                else if ($value === null) {
                    $sql .= " AND {$field} IS NULL";
                }
                // Handle regular conditions
                else {
                    // Create a safe parameter name (replace dots and other special chars)
                    $paramName = 'param_' . str_replace(['.', ' ', '-'], '_', $field);
                    $sql .= " AND {$field} = :{$paramName}";
                    $params[$paramName] = $value;
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
     * Get a single category with details for a specific language
     * 
     * @param int|string $idOrSlug Category ID or slug
     * @param string $langCode Language code
     * @return array|false Category
     */
    public function getWithDetails($idOrSlug, $langCode)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return false;
        }
        
        // Determine if ID or slug
        $isId = is_numeric($idOrSlug);
        
        // Build SQL query
        $sql = "SELECT c.*, cd.name, cd.slug, cd.description, cd.meta_title, cd.meta_description,
                       (SELECT COUNT(*) FROM tours WHERE category_id = c.id AND is_active = 1) as tour_count
                FROM {$this->table} c
                JOIN category_details cd ON c.id = cd.category_id
                WHERE cd.language_id = :langId
                AND " . ($isId ? "c.id = :id" : "cd.slug = :slug");
        
        // Execute query
        return $this->db->getRow($sql, [
            'langId' => $langId,
            ($isId ? 'id' : 'slug') => $idOrSlug
        ]);
    }
    
    /**
     * Get a category by slug
     * 
     * @param string $slug Category slug
     * @param string $langCode Language code
     * @return array|false Category
     */
    public function getBySlug($slug, $langCode)
    {
        return $this->getWithDetails($slug, $langCode);
    }
    
    /**
     * Get subcategories
     * 
     * @param int $parentId Parent category ID
     * @param string $langCode Language code
     * @return array Subcategories
     */
    public function getSubcategories($parentId, $langCode)
    {
        return $this->getAllWithDetails($langCode, [
            'c.parent_id' => $parentId,
            'c.is_active' => 1
        ], 'c.order_number ASC');
    }
    
    /**
     * Count categories
     * 
     * @param array $conditions Where conditions
     * @return int Count
     */
    public function countCategories($conditions = [])
    {
        return $this->count($conditions);
    }
    
    /**
     * Add a category with details for all languages
     * 
     * @param array $categoryData Category data
     * @param array $detailsData Details data for each language
     * @return int|false Category ID or false
     */
    public function addWithDetails($categoryData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Insert category
            $categoryId = $this->insert($categoryData);
            
            if (!$categoryId) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Insert details for each language
            foreach ($detailsData as $langId => $details) {
                $details['category_id'] = $categoryId;
                $details['language_id'] = $langId;
                
                $result = $this->db->insert('category_details', $details);
                
                if (!$result) {
                    $this->db->cancelTransaction();
                    return false;
                }
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            return $categoryId;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update a category with details for all languages
     * 
     * @param int $categoryId Category ID
     * @param array $categoryData Category data
     * @param array $detailsData Details data for each language
     * @return bool Success
     */
    public function updateWithDetails($categoryId, $categoryData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Update category
            $result = $this->update($categoryData, ['id' => $categoryId]);
            
            if (!$result) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Update details for each language
            foreach ($detailsData as $langId => $details) {
                // Check if details exist
                $sql = "SELECT id FROM category_details WHERE category_id = :categoryId AND language_id = :langId";
                $detailsId = $this->db->getValue($sql, [
                    'categoryId' => $categoryId,
                    'langId' => $langId
                ]);
                
                if ($detailsId) {
                    // Update details
                    $result = $this->db->update('category_details', $details, ['id' => $detailsId]);
                } else {
                    // Insert details
                    $details['category_id'] = $categoryId;
                    $details['language_id'] = $langId;
                    
                    $result = $this->db->insert('category_details', $details);
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
     * Delete a category and its details
     * 
     * @param int $categoryId Category ID
     * @return bool Success
     */
    public function deleteWithDetails($categoryId)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Check if category has subcategories
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE parent_id = :categoryId";
            $hasSubcategories = $this->db->getValue($sql, ['categoryId' => $categoryId]);
            
            if ($hasSubcategories) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Check if category has tours
            $sql = "SELECT COUNT(*) FROM tours WHERE category_id = :categoryId";
            $hasTours = $this->db->getValue($sql, ['categoryId' => $categoryId]);
            
            if ($hasTours) {
                // Update tours to have no category
                $this->db->update('tours', ['category_id' => null], ['category_id' => $categoryId]);
            }
            
            // Delete category details
            $this->db->delete('category_details', ['category_id' => $categoryId]);
            
            // Delete category
            $result = $this->delete(['id' => $categoryId]);
            
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
     * Check if slug exists
     * 
     * @param string $slug Slug
     * @param int $langId Language ID
     * @param int $categoryId Category ID (for update)
     * @return bool Exists
     */
    public function slugExists($slug, $langId, $categoryId = null)
    {
        $sql = "SELECT COUNT(*) FROM category_details WHERE slug = :slug AND language_id = :langId";
        $params = [
            'slug' => $slug,
            'langId' => $langId
        ];
        
        if ($categoryId) {
            $sql .= " AND category_id != :categoryId";
            $params['categoryId'] = $categoryId;
        }
        
        return (bool) $this->db->getValue($sql, $params);
    }
    
    /**
     * Generate a unique slug
     * 
     * @param string $name Name
     * @param int $langId Language ID
     * @param int $categoryId Category ID (for update)
     * @return string Unique slug
     */
    public function generateSlug($name, $langId, $categoryId = null)
    {
        // Generate base slug
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]/', '-', $name)));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug exists
        if (!$this->slugExists($slug, $langId, $categoryId)) {
            return $slug;
        }
        
        // If slug exists, add a number to make it unique
        $i = 1;
        $newSlug = $slug . '-' . $i;
        
        while ($this->slugExists($newSlug, $langId, $categoryId)) {
            $i++;
            $newSlug = $slug . '-' . $i;
        }
        
        return $newSlug;
    }
}