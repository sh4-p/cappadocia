<?php
/**
 * Tour Extra Pricing Model
 */
class TourExtraPricing extends Model
{
    protected $table = 'tour_extra_pricing';
    protected $primaryKey = 'id';
    
    /**
     * Get pricing tiers for an extra
     * 
     * @param int $extraId Extra ID
     * @return array Pricing tiers
     */
    public function getPricingTiers($extraId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE extra_id = :extraId 
                ORDER BY min_persons ASC";
        
        return $this->db->getRows($sql, ['extraId' => $extraId]);
    }
    
    /**
     * Get price for specific person count
     * 
     * @param int $extraId Extra ID
     * @param int $personCount Number of persons
     * @return float|null Price per person or null if not found
     */
    public function getPriceForPersonCount($extraId, $personCount)
    {
        $sql = "SELECT price_per_person FROM {$this->table} 
                WHERE extra_id = :extraId 
                AND min_persons <= :personCount 
                AND (max_persons >= :personCount OR max_persons IS NULL)
                ORDER BY min_persons DESC
                LIMIT 1";
        
        return $this->db->getValue($sql, [
            'extraId' => $extraId,
            'personCount' => $personCount
        ]);
    }
    
    /**
     * Save pricing tiers for an extra
     * 
     * @param int $extraId Extra ID
     * @param array $tiers Pricing tiers
     * @return bool Success
     */
    public function savePricingTiers($extraId, $tiers)
    {
        $this->db->beginTransaction();
        
        try {
            // Delete existing tiers
            $this->db->delete($this->table, ['extra_id' => $extraId]);
            
            // Insert new tiers
            foreach ($tiers as $tier) {
                if (empty($tier['min_persons']) || empty($tier['price_per_person'])) {
                    continue;
                }
                
                $data = [
                    'extra_id' => $extraId,
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
     * Calculate total price for extra
     * 
     * @param int $extraId Extra ID
     * @param int $personCount Number of persons
     * @return array ['price_per_person' => float, 'total_price' => float, 'savings' => float]
     */
    public function calculateTotalPrice($extraId, $personCount)
    {
        $pricePerPerson = $this->getPriceForPersonCount($extraId, $personCount);
        
        if ($pricePerPerson === null) {
            // No pricing configured, return zero
            return [
                'price_per_person' => 0,
                'total_price' => 0,
                'full_price' => 0,
                'savings' => 0,
                'discount_percentage' => 0
            ];
        }
        
        $totalPrice = $pricePerPerson * $personCount;
        
        // Calculate savings compared to single person price
        $singlePersonPrice = $this->getPriceForPersonCount($extraId, 1);
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
     * Get pricing breakdown for display
     * 
     * @param int $extraId Extra ID
     * @return array Formatted pricing breakdown
     */
    public function getPricingBreakdown($extraId)
    {
        $tiers = $this->getPricingTiers($extraId);
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
    
    /**
     * Delete pricing tiers for an extra
     * 
     * @param int $extraId Extra ID
     * @return bool Success
     */
    public function deleteTiersForExtra($extraId)
    {
        return $this->db->delete($this->table, ['extra_id' => $extraId]);
    }
}