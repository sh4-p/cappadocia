<?php
/**
 * Newsletter Campaign Model
 */
class NewsletterCampaign extends Model
{
    protected $table = 'newsletter_campaigns';
    protected $primaryKey = 'id';
    
    /**
     * Get all campaigns with details
     * 
     * @param string $langCode Language code
     * @param array $conditions Conditions
     * @param string $orderBy Order by
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array Campaigns
     */
    public function getAllWithDetails($langCode, $conditions = [], $orderBy = 'nc.id DESC', $limit = null, $offset = null)
    {
        // Get language ID
        $languageModel = new LanguageModel($this->db);
        $language = $languageModel->getByCode($langCode);
        
        if (!$language) {
            return [];
        }
        
        $sql = "SELECT nc.*, ncd.subject as localized_subject, ncd.content as localized_content,
                       u.first_name, u.last_name
                FROM {$this->table} nc
                LEFT JOIN newsletter_campaign_details ncd ON nc.id = ncd.campaign_id AND ncd.language_id = :langId
                LEFT JOIN users u ON nc.created_by = u.id";
        
        $params = ['langId' => $language['id']];
        
        // Add conditions
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
     * Create campaign with details
     * 
     * @param array $campaignData Campaign data
     * @param array $detailsData Details for each language
     * @return int|false Campaign ID or false
     */
    public function createWithDetails($campaignData, $detailsData)
    {
        $this->db->beginTransaction();
        
        try {
            $campaignId = $this->insert($campaignData);
            
            if (!$campaignId) {
                $this->db->cancelTransaction();
                return false;
            }
            
            foreach ($detailsData as $langId => $details) {
                $details['campaign_id'] = $campaignId;
                $details['language_id'] = $langId;
                
                $result = $this->db->insert('newsletter_campaign_details', $details);
                
                if (!$result) {
                    $this->db->cancelTransaction();
                    return false;
                }
            }
            
            $this->db->endTransaction();
            return $campaignId;
        } catch (Exception $e) {
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Update campaign status
     * 
     * @param int $id Campaign ID
     * @param string $status New status
     * @return bool Success
     */
    public function updateStatus($id, $status)
    {
        $data = ['status' => $status];
        
        if ($status === 'sent') {
            $data['sent_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->update($data, ['id' => $id]);
    }
    
    /**
     * Update send statistics
     * 
     * @param int $id Campaign ID
     * @param int $totalRecipients Total recipients
     * @param int $sentCount Sent count
     * @param int $failedCount Failed count
     * @return bool Success
     */
    public function updateSendStats($id, $totalRecipients, $sentCount, $failedCount)
    {
        return $this->update([
            'total_recipients' => $totalRecipients,
            'sent_count' => $sentCount,
            'failed_count' => $failedCount
        ], ['id' => $id]);
    }
}