<?php
/**
 * Tour Group Pricing Model
 */
class TourGroupPricing extends Model
{
    protected $table = 'tour_group_pricing';
    protected $primaryKey = 'id';
    
    /**
     * Get pricing tiers for a tour
     * 
     * @param int $tourId Tour ID
     * @return array Pricing tiers
     */
    public function getPricingTiers($tourId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE tour_id = :tourId 
                ORDER BY min_persons ASC";
        
        return $this->db->getRows($sql, ['tourId' => $tourId]);
    }
    
    /**
     * Get price for specific person count
     * 
     * @param int $tourId Tour ID
     * @param int $personCount Number of persons
     * @return float|null Price per person or null if not found
     */
    public function getPriceForPersonCount($tourId, $personCount)
    {
        $sql = "SELECT price_per_person FROM {$this->table} 
                WHERE tour_id = :tourId 
                AND min_persons <= :personCount 
                AND (max_persons >= :personCount OR max_persons IS NULL)
                ORDER BY min_persons DESC
                LIMIT 1";
        
        return $this->db->getValue($sql, [
            'tourId' => $tourId,
            'personCount' => $personCount
        ]);
    }
    
    /**
     * Save pricing tiers for a tour
     * 
     * @param int $tourId Tour ID
     * @param array $tiers Pricing tiers
     * @return bool Success
     */
    public function savePricingTiers($tourId, $tiers)
    {
        $this->db->beginTransaction();
        
        try {
            // Delete existing tiers
            $this->db->delete($this->table, ['tour_id' => $tourId]);
            
            // Insert new tiers
            foreach ($tiers as $tier) {
                if (empty($tier['min_persons']) || empty($tier['price_per_person'])) {
                    continue;
                }
                
                $data = [
                    'tour_id' => $tourId,
                    'min_persons' => (int)$tier['min_persons'],
                    'max_persons' => !empty($tier['max_persons']) ? (int)$tier['max_persons'] : null,
                    'price_per_person' => (float)$tier['price_per_person']
                ];
                
                $this->db->insert($this->table, $data);
            }
            
            $this->db->endTransaction();
            return true;
            
        } catch (Exception $e) {
            $this->db->cancelTransaction();
            return false;
        }
    }
    
    /**
     * Delete all pricing tiers for a tour
     * 
     * @param int $tourId Tour ID
     * @return bool Success
     */
    public function deleteTiersForTour($tourId)
    {
        return $this->db->delete($this->table, ['tour_id' => $tourId]);
    }
    
    /**
     * Calculate total price for booking
     * 
     * @param int $tourId Tour ID
     * @param int $personCount Number of persons
     * @return array ['price_per_person' => float, 'total_price' => float, 'savings' => float]
     */
    public function calculateTotalPrice($tourId, $personCount)
    {
        $pricePerPerson = $this->getPriceForPersonCount($tourId, $personCount);
        
        if ($pricePerPerson === null) {
            // Fallback to tour's base price
            $tourModel = new Tour($this->db);
            $tour = $tourModel->getById($tourId);
            $pricePerPerson = $tour ? $tour['price'] : 0;
        }
        
        $totalPrice = $pricePerPerson * $personCount;
        
        // Calculate savings compared to single person price
        $singlePersonPrice = $this->getPriceForPersonCount($tourId, 1);
        $fullPrice = $singlePersonPrice ? ($singlePersonPrice * $personCount) : $totalPrice;
        $savings = $fullPrice - $totalPrice;
        
        return [
            'price_per_person' => $pricePerPerson,
            'total_price' => $totalPrice,
            'full_price' => $fullPrice,
            'savings' => $savings,
            'discount_percentage' => $fullPrice > 0 ? (($savings / $fullPrice) * 100) : 0
        ];
    }
    
    /**
     * Get all tours with group pricing enabled
     * 
     * @return array Tours with group pricing
     */
    public function getToursWithGroupPricing()
    {
        $sql = "SELECT DISTINCT t.* FROM tours t 
                INNER JOIN {$this->table} tgp ON t.id = tgp.tour_id 
                WHERE t.is_active = 1 
                ORDER BY t.id ASC";
        
        return $this->db->getRows($sql);
    }
    
    /**
     * Check if tour has group pricing
     * 
     * @param int $tourId Tour ID
     * @return bool Has group pricing
     */
    public function hasGroupPricing($tourId)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE tour_id = :tourId";
        $count = $this->db->getValue($sql, ['tourId' => $tourId]);
        
        return $count > 0;
    }
    
    /**
     * Get pricing breakdown for display
     * 
     * @param int $tourId Tour ID
     * @return array Formatted pricing breakdown
     */
    public function getPricingBreakdown($tourId)
    {
        $tiers = $this->getPricingTiers($tourId);
        $breakdown = [];
        
        foreach ($tiers as $tier) {
            $personText = '';
            
            if ($tier['max_persons']) {
                if ($tier['min_persons'] == $tier['max_persons']) {
                    $personText = $tier['min_persons'] . ' kişi';
                } else {
                    $personText = $tier['min_persons'] . '-' . $tier['max_persons'] . ' kişi';
                }
            } else {
                $personText = $tier['min_persons'] . '+ kişi';
            }
            
            $breakdown[] = [
                'persons' => $personText,
                'min_persons' => $tier['min_persons'],
                'max_persons' => $tier['max_persons'],
                'price' => $tier['price_per_person'],
                'formatted_price' => number_format($tier['price_per_person'], 2) . ' EUR'
            ];
        }
        
        return $breakdown;
    }
}