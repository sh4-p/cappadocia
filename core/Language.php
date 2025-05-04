
<?php
/**
 * Language Class
 * 
 * Handles multi-language support
 */
class Language
{
    private $currentLanguage;
    private $translations = [];
    private $db;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = new Database();
        $this->setLanguage(DEFAULT_LANGUAGE);
    }
    
    /**
     * Set current language
     * 
     * @param string $langCode Language code
     * @return bool Success
     */
    public function setLanguage($langCode)
    {
        $availableLanguages = json_decode(AVAILABLE_LANGUAGES, true);
        
        if (isset($availableLanguages[$langCode])) {
            $this->currentLanguage = $langCode;
            $this->loadTranslations($langCode);
            return true;
        }
        
        // If language not found, set default
        $this->currentLanguage = DEFAULT_LANGUAGE;
        $this->loadTranslations(DEFAULT_LANGUAGE);
        return false;
    }
    
    /**
     * Get current language code
     * 
     * @return string Language code
     */
    public function getCurrentLanguage()
    {
        return $this->currentLanguage;
    }
    public function translate($key, $params = [])
    {
        $translation = isset($this->translations[$key]) ? $this->translations[$key] : $key;
        
        // Replace any parameters in the translation
        if (!empty($params)) {
            foreach ($params as $param => $value) {
                $translation = str_replace(':' . $param, $value, $translation);
            }
        }
        
        return $translation;
    }
    
    /**
     * Load translations for language
     * 
     * @param string $langCode Language code
     */
    private function loadTranslations($langCode)
    {
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            // If language not found in database, use default language
            $langCode = DEFAULT_LANGUAGE;
            $sql = "SELECT id FROM languages WHERE code = :code";
            $langId = $this->db->getValue($sql, ['code' => $langCode]);
            
            if (!$langId) {
                return; // Still no language ID, nothing to load
            }
        }
        
        // Get translations
        $sql = "SELECT k.key_name, t.value 
                FROM translations t 
                JOIN translation_keys k ON t.key_id = k.id 
                WHERE t.language_id = :langId";
        
        $translations = $this->db->getRows($sql, ['langId' => $langId]);
        
        // Format translations
        $this->translations = [];
        foreach ($translations as $translation) {
            $this->translations[$translation['key_name']] = $translation['value'];
        }
        
        // Debug - log loaded translations count
        error_log("Loaded " . count($this->translations) . " translations for language $langCode (ID: $langId)");
    }
    
    /**
     * Get translation
     * 
     * @param string $key Translation key
     * @param string $default Default value if key not found
     * @return string Translated text
     */
    public function get($key, $default = null)
    {
        // Debug
        error_log("Requesting translation for key: {$key}, has translation: " . 
            (isset($this->translations[$key]) ? 'Yes' : 'No'));
            
        return $this->translations[$key] ?? ($default ?? $key);
    }
    
    /**
     * Get all translations
     * 
     * @return array Translations
     */
    public function getAll()
    {
        return $this->translations;
    }
    
    /**
     * Get available languages
     * 
     * @return array Available languages
     */
    public function getAvailableLanguages()
    {
        return json_decode(AVAILABLE_LANGUAGES, true);
    }
    
    /**
     * Add translation key
     * 
     * @param string $key Translation key
     * @param array $translations Translations for each language
     * @return bool Success
     */
    public function addTranslation($key, $translations)
    {
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Add key
            $keyId = $this->db->insert('translation_keys', [
                'key_name' => $key
            ]);
            
            if (!$keyId) {
                $this->db->cancelTransaction();
                return false;
            }
            
            // Add translations for each language
            foreach ($translations as $langCode => $value) {
                // Get language ID
                $sql = "SELECT id FROM languages WHERE code = :code";
                $langId = $this->db->getValue($sql, ['code' => $langCode]);
                
                if (!$langId) {
                    continue;
                }
                
                // Add translation
                $this->db->insert('translations', [
                    'key_id' => $keyId,
                    'language_id' => $langId,
                    'value' => $value
                ]);
            }
            
            // Commit transaction
            $this->db->endTransaction();
            
            // Reload translations for current language
            $this->loadTranslations($this->currentLanguage);
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update translation
     * 
     * @param string $key Translation key
     * @param string $langCode Language code
     * @param string $value Translation value
     * @return bool Success
     */
    public function updateTranslation($key, $langCode, $value)
    {
        // Get key ID
        $sql = "SELECT id FROM translation_keys WHERE key_name = :key";
        $keyId = $this->db->getValue($sql, ['key' => $key]);
        
        if (!$keyId) {
            return false;
        }
        
        // Get language ID
        $sql = "SELECT id FROM languages WHERE code = :code";
        $langId = $this->db->getValue($sql, ['code' => $langCode]);
        
        if (!$langId) {
            return false;
        }
        
        // Check if translation exists
        $sql = "SELECT id FROM translations WHERE key_id = :keyId AND language_id = :langId";
        $translationId = $this->db->getValue($sql, [
            'keyId' => $keyId,
            'langId' => $langId
        ]);
        
        if ($translationId) {
            // Update existing translation
            $result = $this->db->update('translations', [
                'value' => $value
            ], [
                'id' => $translationId
            ]);
        } else {
            // Add new translation
            $result = $this->db->insert('translations', [
                'key_id' => $keyId,
                'language_id' => $langId,
                'value' => $value
            ]);
        }
        
        // Reload translations if current language updated
        if ($langCode == $this->currentLanguage) {
            $this->loadTranslations($this->currentLanguage);
        }
        
        return (bool) $result;
    }
    
    /**
     * Delete translation key
     * 
     * @param string $key Translation key
     * @return bool Success
     */
    public function deleteTranslation($key)
    {
        // Get key ID
        $sql = "SELECT id FROM translation_keys WHERE key_name = :key";
        $keyId = $this->db->getValue($sql, ['key' => $key]);
        
        if (!$keyId) {
            return false;
        }
        
        // Begin transaction
        $this->db->beginTransaction();
        
        try {
            // Delete translations
            $this->db->delete('translations', [
                'key_id' => $keyId
            ]);
            
            // Delete key
            $this->db->delete('translation_keys', [
                'id' => $keyId
            ]);
            
            // Commit transaction
            $this->db->endTransaction();
            
            // Reload translations
            $this->loadTranslations($this->currentLanguage);
            
            return true;
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->cancelTransaction();
            return false;
        }
    }
}