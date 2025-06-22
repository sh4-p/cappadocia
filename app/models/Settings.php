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
                error_log("Updated setting: $key = $value, Result: " . ($result ? 'success' : 'failed'));
                return $result;
            } else {
                // Insert new setting
                $result = $this->insert([
                    'setting_key' => $key,
                    'setting_value' => $value
                ]);
                error_log("Inserted new setting: $key = $value, Result: " . ($result ? 'success' : 'failed'));
                return $result;
            }
        } catch (Exception $e) {
            error_log("Error saving setting $key: " . $e->getMessage());
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
                error_log("Successfully saved all $totalCount settings");
                return true;
            } else {
                $this->db->cancelTransaction();
                error_log("Failed to save all settings. Success: $successCount/$totalCount");
                return false;
            }
        } catch (Exception $e) {
            $this->db->cancelTransaction();
            error_log("Exception in saveMultipleSettings: " . $e->getMessage());
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
     *
     * @param string $key Setting key
     * @param string $defaultFileName Default filename
     * @return string Setting value or default
     */
    public function getFileSetting($key, $defaultFileName = '')
    {
        $value = $this->getSetting($key, $defaultFileName);
        return !empty($value) ? $value : $defaultFileName;
    }
}