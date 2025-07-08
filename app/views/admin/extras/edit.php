<?php
/**
 * Admin Extras Edit View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('edit_extra'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/extras" class="btn btn-secondary">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_list'); ?></span>
        </a>
    </div>
</div>

<form action="<?php echo $adminUrl; ?>/extras/edit/<?php echo $extra['id']; ?>" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('extra_details'); ?></h3>
                </div>
                <div class="card-body">
                    <!-- Language Tabs -->
                    <div class="language-tabs">
                        <div class="language-tabs-nav">
                            <?php foreach ($languages as $index => $lang): ?>
                                <button type="button" class="language-tab-btn <?php echo $index === 0 ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                    <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $lang['code']; ?>.png" alt="<?php echo $lang['name']; ?>">
                                    <span><?php echo $lang['name']; ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Language Content -->
                        <?php foreach ($languages as $index => $lang): ?>
                            <div class="language-tab-content <?php echo $index === 0 ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                <div class="form-group">
                                    <label for="name_<?php echo $lang['id']; ?>"><?php _e('name'); ?> *</label>
                                    <input type="text" id="name_<?php echo $lang['id']; ?>" name="details[<?php echo $lang['id']; ?>][name]" 
                                           class="form-control" required 
                                           value="<?php echo isset($extraDetails[$lang['id']]['name']) ? htmlspecialchars($extraDetails[$lang['id']]['name']) : ''; ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="description_<?php echo $lang['id']; ?>"><?php _e('description'); ?></label>
                                    <textarea id="description_<?php echo $lang['id']; ?>" name="details[<?php echo $lang['id']; ?>][description]" 
                                              class="form-control" rows="3"><?php echo isset($extraDetails[$lang['id']]['description']) ? htmlspecialchars($extraDetails[$lang['id']]['description']) : ''; ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Pricing Configuration -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('pricing_configuration'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="pricing_type"><?php _e('pricing_type'); ?></label>
                        <select id="pricing_type" name="pricing_type" class="form-control" onchange="togglePricingFields()">
                            <option value="fixed_group" <?php echo ($extra['pricing_type'] ?? 'fixed_group') === 'fixed_group' ? 'selected' : ''; ?>><?php _e('fixed_group_price'); ?></option>
                            <option value="per_person" <?php echo ($extra['pricing_type'] ?? '') === 'per_person' ? 'selected' : ''; ?>><?php _e('per_person_price'); ?></option>
                            <option value="tiered" <?php echo ($extra['pricing_type'] ?? '') === 'tiered' ? 'selected' : ''; ?>><?php _e('tiered_pricing'); ?></option>
                        </select>
                        <small class="form-text text-muted"><?php _e('pricing_type_help'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="base_price"><?php _e('base_price'); ?> *</label>
                        <div class="input-group">
                            <span class="input-group-text"><?php echo $settings['currency_symbol']; ?></span>
                            <input type="number" id="base_price" name="base_price" class="form-control" 
                                   step="0.01" min="0" required 
                                   value="<?php echo isset($extra['base_price']) ? $extra['base_price'] : ''; ?>">
                        </div>
                        <small class="form-text text-muted" id="base-price-help"><?php _e('base_price_help_fixed'); ?></small>
                    </div>
                    
                    <!-- Tiered Pricing Section -->
                    <div id="tiered-pricing-section" style="display: <?php echo ($extra['pricing_type'] ?? '') === 'tiered' ? 'block' : 'none'; ?>;">
                        <h4><?php _e('pricing_tiers'); ?></h4>
                        <div class="pricing-tiers">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="tier-group">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="tier_<?php echo $i; ?>_persons"><?php _e('persons'); ?> (<?php _e('tier'); ?> <?php echo $i; ?>)</label>
                                            <input type="number" id="tier_<?php echo $i; ?>_persons" name="pricing_tiers[<?php echo $i; ?>][persons]" 
                                                   class="form-control" min="1" 
                                                   value="<?php echo isset($pricingTiers[$i]['persons']) ? $pricingTiers[$i]['persons'] : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="tier_<?php echo $i; ?>_price"><?php _e('price_per_person'); ?></label>
                                            <div class="input-group">
                                                <span class="input-group-text"><?php echo $settings['currency_symbol']; ?></span>
                                                <input type="number" id="tier_<?php echo $i; ?>_price" name="pricing_tiers[<?php echo $i; ?>][price_per_person]" 
                                                       class="form-control" step="0.01" min="0"
                                                       value="<?php echo isset($pricingTiers[$i]['price_per_person']) ? $pricingTiers[$i]['price_per_person'] : ''; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <small class="form-text text-muted"><?php _e('tiered_pricing_help'); ?></small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('extra_settings'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="category"><?php _e('category'); ?></label>
                        <select id="category" name="category" class="form-control">
                            <option value=""><?php _e('select_category'); ?></option>
                            <option value="transportation" <?php echo ($extra['category'] ?? '') === 'transportation' ? 'selected' : ''; ?>><?php _e('transportation'); ?></option>
                            <option value="meals" <?php echo ($extra['category'] ?? '') === 'meals' ? 'selected' : ''; ?>><?php _e('meals'); ?></option>
                            <option value="activities" <?php echo ($extra['category'] ?? '') === 'activities' ? 'selected' : ''; ?>><?php _e('activities'); ?></option>
                            <option value="accommodation" <?php echo ($extra['category'] ?? '') === 'accommodation' ? 'selected' : ''; ?>><?php _e('accommodation'); ?></option>
                            <option value="other" <?php echo ($extra['category'] ?? '') === 'other' ? 'selected' : ''; ?>><?php _e('other'); ?></option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="order_number"><?php _e('order'); ?></label>
                        <input type="number" id="order_number" name="order_number" class="form-control" 
                               min="0" value="<?php echo isset($extra['order_number']) ? $extra['order_number'] : '0'; ?>">
                        <small class="form-text text-muted"><?php _e('order_help'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" 
                                   <?php echo ($extra['is_active'] ?? true) ? 'checked' : ''; ?>>
                            <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="material-icons">save</i>
                            <?php _e('update_extra'); ?>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Extra Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('extra_info'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="info-row">
                        <span class="info-label"><?php _e('id'); ?>:</span>
                        <span class="info-value">#<?php echo $extra['id']; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><?php _e('created'); ?>:</span>
                        <span class="info-value"><?php echo isset($extra['created_at']) ? date('d/m/Y H:i', strtotime($extra['created_at'])) : '-'; ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label"><?php _e('updated'); ?>:</span>
                        <span class="info-value"><?php echo isset($extra['updated_at']) ? date('d/m/Y H:i', strtotime($extra['updated_at'])) : '-'; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.language-tabs-nav {
    display: flex;
    border-bottom: 1px solid var(--gray-300);
    margin-bottom: 1.5rem;
}

.language-tab-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    color: var(--gray-600);
    cursor: pointer;
    transition: all 0.2s ease;
}

.language-tab-btn.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.language-tab-btn img {
    width: 20px;
    height: 15px;
    object-fit: cover;
    border-radius: 2px;
}

.language-tab-content {
    display: none;
}

.language-tab-content.active {
    display: block;
}

.tier-group {
    padding: 1rem;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    margin-bottom: 1rem;
    background-color: var(--gray-50);
}

.tier-group:last-child {
    margin-bottom: 0;
}

.pricing-tiers {
    margin-top: 1rem;
}

.input-group-text {
    background-color: var(--gray-100);
    border-color: var(--gray-300);
    color: var(--gray-600);
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-200);
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: var(--gray-600);
}

.info-value {
    color: var(--dark-color);
}
</style>

<script>
function togglePricingFields() {
    const pricingType = document.getElementById('pricing_type').value;
    const tieredSection = document.getElementById('tiered-pricing-section');
    const basePriceHelp = document.getElementById('base-price-help');
    
    if (pricingType === 'tiered') {
        tieredSection.style.display = 'block';
        basePriceHelp.textContent = '<?php _e("base_price_help_tiered"); ?>';
    } else {
        tieredSection.style.display = 'none';
        
        if (pricingType === 'per_person') {
            basePriceHelp.textContent = '<?php _e("base_price_help_per_person"); ?>';
        } else {
            basePriceHelp.textContent = '<?php _e("base_price_help_fixed"); ?>';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Language tab switching
    const tabBtns = document.querySelectorAll('.language-tab-btn');
    const tabContents = document.querySelectorAll('.language-tab-content');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetLang = this.getAttribute('data-lang');
            
            // Remove active class from all buttons and contents
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show corresponding content
            const targetContent = document.querySelector(`.language-tab-content[data-lang="${targetLang}"]`);
            if (targetContent) {
                targetContent.classList.add('active');
            }
        });
    });
    
    // Pricing type change handler
    const pricingTypeSelect = document.getElementById('pricing_type');
    const tieredPricingSection = document.getElementById('tiered-pricing-section');
    
    if (pricingTypeSelect && tieredPricingSection) {
        pricingTypeSelect.addEventListener('change', function() {
            if (this.value === 'tiered') {
                tieredPricingSection.style.display = 'block';
            } else {
                tieredPricingSection.style.display = 'none';
            }
        });
        
        // Initialize on page load
        if (pricingTypeSelect.value === 'tiered') {
            tieredPricingSection.style.display = 'block';
        }
    }
});
</script>