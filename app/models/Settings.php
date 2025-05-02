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
        $setting = $this->getOne(['setting_key' => $key]);
       
        if ($setting) {
            // Update existing setting
            return $this->update(['setting_value' => $value], ['id' => $setting['id']]);
        } else {
            // Insert new setting
            return $this->insert([
                'setting_key' => $key,
                'setting_value' => $value
            ]);
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
        $this->db->beginTransaction();
       
        try {
            foreach ($settings as $key => $value) {
                $this->saveSetting($key, $value);
            }
           
            $this->db->endTransaction();
            return true;
        } catch (Exception $e) {
            $this->db->cancelTransaction();
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
}