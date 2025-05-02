<?php
/**
 * Page Model
 */
class Page extends Model
{
    protected $table = 'pages';
    protected $primaryKey = 'id';
    
    /**
     * Get all pages with details for a specific language
     * 
     * @param string $langCode Language code
     * @param array $conditions Where conditions
     * @param string $orderBy Order by clause
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Pages
     */
    public function getAllWithDetails($langCode, $conditions = [], $orderBy = 'p.order_number ASC', $limit = null, $offset = null)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return [];
        }
        
        // Build SQL query
        $sql = "SELECT p.*, pd.title, pd.slug, pd.content, pd.meta_title, pd.meta_description
                FROM {$this->table} p
                JOIN page_details pd ON p.id = pd.page_id
                WHERE pd.language_id = :langId";
        
        // Add conditions
        $params = ['langId' => $langId];
        
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                $sql .= " AND {$field} = :{$field}";
                $params[$field] = $value;
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
     * Get a single page with details for a specific language
     * 
     * @param int|string $idOrSlug Page ID or slug
     * @param string $langCode Language code
     * @return array|false Page
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
        $sql = "SELECT p.*, pd.title, pd.slug, pd.content, pd.meta_title, pd.meta_description
                FROM {$this->table} p
                JOIN page_details pd ON p.id = pd.page_id
                WHERE pd.language_id = :langId
                AND " . ($isId ? "p.id = :id" : "pd.slug = :slug");
        
        // Execute query
        return $this->db->getRow($sql, [
            'langId' => $langId,
            ($isId ? 'id' : 'slug') => $idOrSlug
        ]);
    }
    
    /**
     * Get page by slug
     * 
     * @param string $slug Page slug
     * @param string $langCode Language code
     * @return array|false Page
     */
    public function getBySlug($slug, $langCode)
    {
        return $this->getWithDetails($slug, $langCode);
    }
    
    /**
     * Get page by template
     * 
     * @param string $template Template name
     * @param string $langCode Language code
     * @return array|false Page
     */
    public function getByTemplate($template, $langCode)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return false;
        }
        
        // Build SQL query
        $sql = "SELECT p.*, pd.title, pd.slug, pd.content, pd.meta_title, pd.meta_description
                FROM {$this->table} p
                JOIN page_details pd ON p.id = pd.page_id
                WHERE pd.language_id = :langId AND p.template = :template";
        
        // Execute query
        return $this->db->getRow($sql, [
            'langId' => $langId,
            'template' => $template
        ]);
    }
    
    /**
     * Add a page with details for all languages
     * 
     * @param array $pageData Page data
     * @param array $detailsData Details data for each language
     * @return int|false Page ID or false
     */
    public function addWithDetails($pageData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Insert page
            $pageId = $this->insert($pageData);
            
            if (!$pageId) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Insert details for each language
            foreach ($detailsData as $langId => $details) {
                $details['page_id'] = $pageId;
                $details['language_id'] = $langId;
                
                $result = $this->db->insert('page_details', $details);
                
                if (!$result) {
                    $this->db->cancelTransaction();
                    return false;
                }
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            return $pageId;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update a page with details for all languages
     * 
     * @param int $pageId Page ID
     * @param array $pageData Page data
     * @param array $detailsData Details data for each language
     * @return bool Success
     */
    public function updateWithDetails($pageId, $pageData, $detailsData)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Update page
            $result = $this->update($pageData, ['id' => $pageId]);
            
            if (!$result) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Update details for each language
            foreach ($detailsData as $langId => $details) {
                // Check if details exist
                $sql = "SELECT id FROM page_details WHERE page_id = :pageId AND language_id = :langId";
                $detailsId = $this->db->getValue($sql, [
                    'pageId' => $pageId,
                    'langId' => $langId
                ]);
                
                if ($detailsId) {
                    // Update details
                    $result = $this->db->update('page_details', $details, ['id' => $detailsId]);
                } else {
                    // Insert details
                    $details['page_id'] = $pageId;
                    $details['language_id'] = $langId;
                    
                    $result = $this->db->insert('page_details', $details);
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
     * Delete a page and its details
     * 
     * @param int $pageId Page ID
     * @return bool Success
     */
    public function deleteWithDetails($pageId)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Delete page details
            $this->db->delete('page_details', ['page_id' => $pageId]);
            
            // Delete page
            $result = $this->delete(['id' => $pageId]);
            
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
     * @param int $pageId Page ID (for update)
     * @return bool Exists
     */
    public function slugExists($slug, $langId, $pageId = null)
    {
        $sql = "SELECT COUNT(*) FROM page_details WHERE slug = :slug AND language_id = :langId";
        $params = [
            'slug' => $slug,
            'langId' => $langId
        ];
        
        if ($pageId) {
            $sql .= " AND page_id != :pageId";
            $params['pageId'] = $pageId;
        }
        
        return (bool) $this->db->getValue($sql, $params);
    }
    
    /**
     * Generate a unique slug
     * 
     * @param string $title Title
     * @param int $langId Language ID
     * @param int $pageId Page ID (for update)
     * @return string Unique slug
     */
    public function generateSlug($title, $langId, $pageId = null)
    {
        // Generate base slug
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]/', '-', $title)));
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug exists
        if (!$this->slugExists($slug, $langId, $pageId)) {
            return $slug;
        }
        
        // If slug exists, add a number to make it unique
        $i = 1;
        $newSlug = $slug . '-' . $i;
        
        while ($this->slugExists($newSlug, $langId, $pageId)) {
            $i++;
            $newSlug = $slug . '-' . $i;
        }
        
        return $newSlug;
    }
    
    /**
     * Update page status
     * 
     * @param int $pageId Page ID
     * @param bool $isActive Is active
     * @return bool Success
     */
    public function updateStatus($pageId, $isActive)
    {
        return $this->update(['is_active' => $isActive ? 1 : 0], ['id' => $pageId]);
    }
    
    /**
     * Update page order
     * 
     * @param int $pageId Page ID
     * @param int $orderNumber Order number
     * @return bool Success
     */
    public function updateOrder($pageId, $orderNumber)
    {
        return $this->update(['order_number' => $orderNumber], ['id' => $pageId]);
    }
    
    /**
     * Get available templates
     * 
     * @return array Templates
     */
    public function getTemplates()
    {
        return [
            'default' => 'Default',
            'about' => 'About',
            'contact' => 'Contact',
            'full-width' => 'Full Width',
            'sidebar-left' => 'Sidebar Left',
            'sidebar-right' => 'Sidebar Right'
        ];
    }
}