<?php
/**
 * Translation Model
 */
class Translation extends Model
{
    protected $table = 'translations';
    protected $primaryKey = 'id';
    
    /**
     * Get all translations for a specific language
     * 
     * @param string $langCode Language code
     * @return array Translations
     */
    public function getTranslationsForLanguage($langCode)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return [];
        }
        
        // Get translations
        $sql = "SELECT k.key_name, t.value 
                FROM translation_keys k
                LEFT JOIN translations t ON k.id = t.key_id AND t.language_id = :langId
                ORDER BY k.key_name ASC";
        
        $translations = $this->db->getRows($sql, ['langId' => $langId]);
        
        // Format translations
        $result = [];
        
        foreach ($translations as $translation) {
            $result[$translation['key_name']] = $translation['value'];
        }
        
        return $result;
    }
    
    /**
     * Get all translation keys with values for all languages
     * 
     * @return array Translation keys with values
     */
    public function getAllTranslations()
    {
        // Get languages
        $languageModel = new Language($this->db);
        $languages = $languageModel->getAllLanguages();
        
        // Get translation keys
        $sql = "SELECT k.id, k.key_name FROM translation_keys k ORDER BY k.key_name ASC";
        $keys = $this->db->getRows($sql);
        
        // Get translations for each key and language
        $result = [];
        
        foreach ($keys as $key) {
            $result[$key['id']] = [
                'key_name' => $key['key_name'],
                'translations' => []
            ];
            
            foreach ($languages as $language) {
                $sql = "SELECT value FROM translations WHERE key_id = :keyId AND language_id = :langId";
                $value = $this->db->getValue($sql, [
                    'keyId' => $key['id'],
                    'langId' => $language['id']
                ]);
                
                $result[$key['id']]['translations'][$language['id']] = $value;
            }
        }
        
        return $result;
    }
    
    /**
     * Get translations by language
     * 
     * @param int $langId Language ID
     * @return array Translations
     */
    public function getByLanguage($langId)
    {
        // Get translation keys
        $sql = "SELECT k.id, k.key_name FROM translation_keys k ORDER BY k.key_name ASC";
        $keys = $this->db->getRows($sql);
        
        // Get translations for each key
        $result = [];
        
        foreach ($keys as $key) {
            $sql = "SELECT value FROM translations WHERE key_id = :keyId AND language_id = :langId";
            $value = $this->db->getValue($sql, [
                'keyId' => $key['id'],
                'langId' => $langId
            ]);
            
            $result[$key['id']] = [
                'key_name' => $key['key_name'],
                'value' => $value
            ];
        }
        
        return $result;
    }
    
    /**
     * Add translation key
     * 
     * @param string $keyName Key name
     * @return int|false Key ID or false
     */
    public function addKey($keyName)
    {
        // Check if key already exists
        $sql = "SELECT id FROM translation_keys WHERE key_name = :keyName";
        $existingKey = $this->db->getValue($sql, ['keyName' => $keyName]);
        
        if ($existingKey) {
            return $existingKey;
        }
        
        // Insert key
        return $this->db->insert('translation_keys', ['key_name' => $keyName]);
    }
    
    /**
     * Update or add translation
     * 
     * @param int $keyId Key ID
     * @param int $langId Language ID
     * @param string $value Translation value
     * @return bool Success
     */
    public function updateTranslation($keyId, $langId, $value)
    {
        // Check if translation exists
        $sql = "SELECT id FROM translations WHERE key_id = :keyId AND language_id = :langId";
        $translationId = $this->db->getValue($sql, [
            'keyId' => $keyId,
            'langId' => $langId
        ]);
        
        if ($translationId) {
            // Update translation
            return $this->db->update('translations', ['value' => $value], ['id' => $translationId]);
        } else {
            // Insert translation
            return $this->db->insert('translations', [
                'key_id' => $keyId,
                'language_id' => $langId,
                'value' => $value
            ]);
        }
    }
    
    /**
     * Delete translation key and all its translations
     * 
     * @param int $keyId Key ID
     * @return bool Success
     */
    public function deleteKey($keyId)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Delete translations
            $this->db->delete('translations', ['key_id' => $keyId]);
            
            // Delete key
            $result = $this->db->delete('translation_keys', ['id' => $keyId]);
            
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
     * Import translations from array
     * 
     * @param array $translations Translations array
     * @param int $langId Language ID
     * @return bool Success
     */
    public function importTranslations($translations, $langId)
    {
        if (empty($translations) || !is_array($translations)) {
            return false;
        }
        
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            foreach ($translations as $keyName => $value) {
                // Add or get key
                $keyId = $this->addKey($keyName);
                
                if (!$keyId) {
                    $this->db->cancelTransaction();
                    return false;
                }
                
                // Update translation
                $result = $this->updateTranslation($keyId, $langId, $value);
                
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
     * Export translations to array
     * 
     * @param int $langId Language ID
     * @return array Translations
     */
    public function exportTranslations($langId)
    {
        $translations = $this->getByLanguage($langId);
        $result = [];
        
        foreach ($translations as $translation) {
            $result[$translation['key_name']] = $translation['value'];
        }
        
        return $result;
    }
    
    /**
     * Copy translations from one language to another
     * 
     * @param int $fromLangId Source language ID
     * @param int $toLangId Target language ID
     * @return bool Success
     */
    public function copyTranslations($fromLangId, $toLangId)
    {
        // Get source translations
        $translations = $this->exportTranslations($fromLangId);
        
        // Import to target language
        return $this->importTranslations($translations, $toLangId);
    }
}