<?php
/**
 * Language Model
 */
class LanguageModel extends Model
{
    protected $table = 'languages';
    protected $primaryKey = 'id';
    
    /**
     * Get all languages
     * 
     * @param string $orderBy Order by clause
     * @return array Languages
     */
    public function getAllLanguages($orderBy = 'name ASC')
    {
        return $this->getAll([], $orderBy);
    }
    
    /**
     * Get active languages
     * 
     * @param string $orderBy Order by clause
     * @return array Active languages
     */
    public function getActiveLanguages($orderBy = 'name ASC')
    {
        $languages = $this->getAll(['is_active' => 1], $orderBy);
        $result = [];
        
        foreach ($languages as $language) {
            $result[$language['code']] = $language;
        }
        
        return $result;
    }
    
    /**
     * Get language by code
     * 
     * @param string $code Language code
     * @return array|false Language
     */
    public function getByCode($code)
    {
        return $this->getOne(['code' => $code]);
    }
    
    /**
     * Get default language
     * 
     * @return array|false Default language
     */
    public function getDefaultLanguage()
    {
        return $this->getOne(['is_default' => 1]);
    }
    
    /**
     * Add language
     * 
     * @param array $data Language data
     * @return int|false Language ID or false
     */
    public function addLanguage($data)
    {
        // Check if language code already exists
        if ($this->getByCode($data['code'])) {
            return false;
        }
        
        // Check if this is the first language
        $count = $this->count();
        
        if ($count === 0) {
            // First language is default
            $data['is_default'] = 1;
        } elseif (!empty($data['is_default'])) {
            // If new language is default, update old default
            $this->db->beginTransaction();
            
            try {
                // Set all languages as not default
                $this->db->executeQuery("UPDATE {$this->table} SET is_default = 0");
                
                // Insert new language
                $langId = $this->insert($data);
                
                $this->db->endTransaction();
                
                return $langId;
            } catch (Exception $e) {
                $this->db->cancelTransaction();
                return false;
            }
        }
        
        return $this->insert($data);
    }
    
    /**
     * Update language
     * 
     * @param int $id Language ID
     * @param array $data Language data
     * @return bool Success
     */
    public function updateLanguage($id, $data)
    {
        // Get language
        $language = $this->getById($id);
        
        if (!$language) {
            return false;
        }
        
        // Check if language code exists
        if (isset($data['code']) && $data['code'] !== $language['code']) {
            $existingLanguage = $this->getByCode($data['code']);
            
            if ($existingLanguage) {
                return false;
            }
        }
        
        // Check if default status changed
        if (isset($data['is_default']) && $data['is_default'] && !$language['is_default']) {
            // New default language
            $this->db->beginTransaction();
            
            try {
                // Set all languages as not default
                $this->db->executeQuery("UPDATE {$this->table} SET is_default = 0");
                
                // Update language
                $result = $this->update($data, ['id' => $id]);
                
                $this->db->endTransaction();
                
                return $result;
            } catch (Exception $e) {
                $this->db->cancelTransaction();
                return false;
            }
        }
        
        // If this is the default language, don't allow changing is_default to 0
        if (isset($data['is_default']) && !$data['is_default'] && $language['is_default']) {
            unset($data['is_default']);
        }
        
        return $this->update($data, ['id' => $id]);
    }
    
    /**
     * Delete language
     * 
     * @param int $id Language ID
     * @return bool Success
     */
    public function deleteLanguage($id)
    {
        // Get language
        $language = $this->getById($id);
        
        if (!$language) {
            return false;
        }
        
        // Don't allow deleting default language
        if ($language['is_default']) {
            return false;
        }
        
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Delete translations for this language
            $this->db->delete('translations', ['language_id' => $id]);
            
            // Delete category details for this language
            $this->db->delete('category_details', ['language_id' => $id]);
            
            // Delete tour details for this language
            $this->db->delete('tour_details', ['language_id' => $id]);
            
            // Delete page details for this language
            $this->db->delete('page_details', ['language_id' => $id]);
            
            // Delete testimonial details for this language
            $this->db->delete('testimonial_details', ['language_id' => $id]);
            
            // Delete gallery details for this language
            $this->db->delete('gallery_details', ['language_id' => $id]);
            
            // Delete language
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
     * Set language as default
     * 
     * @param int $id Language ID
     * @return bool Success
     */
    public function setAsDefault($id)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Set all languages as not default
            $this->db->executeQuery("UPDATE {$this->table} SET is_default = 0");
            
            // Set specific language as default
            $result = $this->update(['is_default' => 1], ['id' => $id]);
            
            // Commit transaction
            $this->db->endTransaction();
            
            return $result;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update language status
     * 
     * @param int $id Language ID
     * @param bool $isActive Is active
     * @return bool Success
     */
    public function updateStatus($id, $isActive)
    {
        // Get language
        $language = $this->getById($id);
        
        if (!$language) {
            return false;
        }
        
        // Don't allow deactivating default language
        if (!$isActive && $language['is_default']) {
            return false;
        }
        
        return $this->update(['is_active' => $isActive ? 1 : 0], ['id' => $id]);
    }
    
    /**
     * Get formatted language array for dropdown
     * 
     * @param bool $onlyActive Only active languages
     * @return array Formatted languages
     */
    public function getForDropdown($onlyActive = true)
    {
        $conditions = $onlyActive ? ['is_active' => 1] : [];
        $languages = $this->getAll($conditions, 'name ASC');
        $result = [];
        
        foreach ($languages as $language) {
            $result[$language['id']] = $language['name'] . ' (' . $language['code'] . ')';
        }
        
        return $result;
    }
}