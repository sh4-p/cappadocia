<?php
/**
 * Settings Model
 *
 * Handles application settings from the database
 */
class Settings extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
   
    /**
     * Get all settings as key-value pairs
     *
     * @return array Settings as key-value pairs
     */
    public function getAllSettings()
    {
        $settings = parent::getAll();
        $result = [];
       
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
       
        return $result;
    }
    
    /**
     * Override getAll to return settings as key-value pairs
     *
     * @param array $conditions Query conditions
     * @param string $orderBy Order by clause
     * @param int $limit Result limit
     * @param int $offset Result offset
     * @return array Settings
     */
    public function getAll($conditions = [], $orderBy = null, $limit = null, $offset = null)
    {
        // Call the parent getAll method to get settings with specified conditions
        $settings = parent::getAll($conditions, $orderBy, $limit, $offset);
        $result = [];
       
        // Convert to key-value pairs
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $setting['setting_value'];
        }
       
        return $result;
    }
   
    /**
     * Get setting by key
     *
     * @param string $key Setting key
     * @param mixed $default Default value if not found
     * @return mixed Setting value
     */
    public function getSetting($key, $default = null)
    {
        $setting = $this->getOne(['setting_key' => $key]);
        return $setting ? $setting['setting_value'] : $default;
    }
   
    /**
     * Save setting
     *
     * @param string $key Setting key
     * @param mixed $value Setting value
     * @return bool Success
     */
    public function saveSetting($key, $value)
    {
        try {
            $setting = $this->getOne(['setting_key' => $key]);
           
            if ($setting) {
                // Update existing setting
                $result = $this->update(['setting_value' => $value], ['id' => $setting['id']]);
                writeLog("Updated setting: $key = $value, Result: " . ($result ? 'success' : 'failed'), 'settings');
                return $result;
            } else {
                // Insert new setting
                $result = $this->insert([
                    'setting_key' => $key,
                    'setting_value' => $value
                ]);
                writeLog("Inserted new setting: $key = $value, Result: " . ($result ? 'success' : 'failed'), 'settings');
                return $result;
            }
        } catch (Exception $e) {
            writeLog("Error saving setting $key: " . $e->getMessage(), 'settings');
            return false;
        }
    }
   
    /**
     * Delete setting
     *
     * @param string $key Setting key
     * @return bool Success
     */
    public function deleteSetting($key)
    {
        return $this->delete(['setting_key' => $key]);
    }
   
    /**
     * Save multiple settings at once
     *
     * @param array $settings Associative array of setting keys and values
     * @return bool Success
     */
    public function saveMultipleSettings($settings)
    {
        try {
            $this->db->beginTransaction();
            
            $successCount = 0;
            $totalCount = count($settings);
            
            foreach ($settings as $key => $value) {
                if ($this->saveSetting($key, $value)) {
                    $successCount++;
                }
            }
           
            if ($successCount === $totalCount) {
                $this->db->endTransaction();
                writeLog("Successfully saved all $totalCount settings", 'settings');
                return true;
            } else {
                $this->db->cancelTransaction();
                writeLog("Failed to save all settings. Success: $successCount/$totalCount", 'settings');
                return false;
            }
        } catch (Exception $e) {
            $this->db->cancelTransaction();
            writeLog("Exception in saveMultipleSettings: " . $e->getMessage(), 'settings');
            return false;
        }
    }
   
    /**
     * Get all settings for a specific group
     *
     * @param string $prefix Group prefix (e.g., 'social_' for social media settings)
     * @return array Settings in the group
     */
    public function getSettingsByPrefix($prefix)
    {
        $sql = "SELECT * FROM {$this->table} WHERE setting_key LIKE :prefix";
        $rows = $this->db->getRows($sql, ['prefix' => $prefix . '%']);
       
        $result = [];
        foreach ($rows as $row) {
            $result[$row['setting_key']] = $row['setting_value'];
        }
       
        return $result;
    }
    
    /**
     * Get setting with default value for file paths
     * Checks if file exists before returning the value
     *
     * @param string $key Setting key
     * @param string $defaultFileName Default filename
     * @return string Setting value or default
     */
    public function getFileSetting($key, $defaultFileName = '')
    {
        $value = $this->getSetting($key, $defaultFileName);
        
        // If we have a value, check if the file exists
        if (!empty($value) && $value !== $defaultFileName) {
            $filePath = BASE_PATH . '/public/img/' . $value;
            if (file_exists($filePath)) {
                return $value;
            } else {
                writeLog("File not found for setting $key: $filePath", 'settings');
                // File doesn't exist, return empty string to prevent 404s
                return '';
            }
        }
        
        return $value;
    }
    
    /**
     * Get logo setting with fallback
     *
     * @return string Logo filename or empty string
     */
    public function getLogoSetting()
    {
        return $this->getFileSetting('logo', '');
    }
    
    /**
     * Get favicon setting with fallback
     *
     * @return string Favicon filename or empty string
     */
    public function getFaviconSetting()
    {
        return $this->getFileSetting('favicon', '');
    }
    
    /**
     * Check if a file setting exists and is valid
     *
     * @param string $key Setting key
     * @return bool True if file exists
     */
    public function fileSettingExists($key)
    {
        $value = $this->getSetting($key);
        if (empty($value)) {
            return false;
        }
        
        $filePath = BASE_PATH . '/public/img/' . $value;
        return file_exists($filePath);
    }
    
    /**
     * Clean up orphaned file settings
     * Removes settings for files that no longer exist
     */
    public function cleanupFileSettings()
    {
        $fileSettings = ['logo', 'favicon', 'hero_bg', 'about_bg'];
        
        foreach ($fileSettings as $key) {
            if (!$this->fileSettingExists($key)) {
                $this->saveSetting($key, '');
                writeLog("Cleaned up orphaned file setting: $key", 'settings');
            }
        }
    }
}