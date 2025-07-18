<?php
/**
 * Admin Tour Edit View - Günlük İtinerary ve Duration Seçimi ile
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('edit_tour'); ?>: <?php echo $tour['name']; ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/tours" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_tours'); ?></span>
        </a>
        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours/<?php echo $tour['slug']; ?>" class="btn btn-light" target="_blank">
            <i class="material-icons">visibility</i>
            <span><?php _e('view_tour'); ?></span>
        </a>
    </div>
</div>

<form action="<?php echo $adminUrl; ?>/tours/edit/<?php echo $tour['id']; ?>" method="post" enctype="multipart/form-data" class="tour-form">
    <input type="hidden" name="id" value="<?php echo $tour['id']; ?>">
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('tour_details'); ?></h3>
                </div>
                <div class="card-body">
                    <!-- Language Tabs -->
                    <div class="language-tabs">
                        <div class="language-tabs-nav">
                            <?php foreach ($languages as $lang): ?>
                                <button type="button" class="language-tab-btn <?php echo $lang['code'] === $currentLang ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                    <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $lang['code']; ?>.png" alt="<?php echo $lang['name']; ?>">
                                    <span><?php echo $lang['name']; ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Language Tab Contents -->
                        <?php foreach ($languages as $lang): ?>
                            <?php $details = $tourDetails[$lang['id']] ?? []; ?>
                            <div class="language-tab-content <?php echo $lang['code'] === $currentLang ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                <div class="form-group">
                                    <label for="name_<?php echo $lang['code']; ?>" class="form-label"><?php _e('tour_name'); ?> <span class="required">*</span></label>
                                    <input type="text" id="name_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][name]" class="form-control" value="<?php echo htmlspecialchars($details['name'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="slug_<?php echo $lang['code']; ?>" class="form-label"><?php _e('slug'); ?></label>
                                    <input type="text" id="slug_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][slug]" class="form-control" value="<?php echo htmlspecialchars($details['slug'] ?? ''); ?>">
                                    <small class="form-text"><?php _e('slug_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="short_description_<?php echo $lang['code']; ?>" class="form-label"><?php _e('short_description'); ?> <span class="required">*</span></label>
                                    <textarea id="short_description_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][short_description]" class="form-control" rows="3" required><?php echo htmlspecialchars($details['short_description'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description_<?php echo $lang['code']; ?>" class="form-label"><?php _e('description'); ?> <span class="required">*</span></label>
                                    <textarea id="description_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][description]" class="form-control editor" rows="10" required><?php echo htmlspecialchars($details['description'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="includes_<?php echo $lang['code']; ?>" class="form-label"><?php _e('includes'); ?></label>
                                    <textarea id="includes_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][includes]" class="form-control" rows="5"><?php echo htmlspecialchars($details['includes'] ?? ''); ?></textarea>
                                    <small class="form-text"><?php _e('includes_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="excludes_<?php echo $lang['code']; ?>" class="form-label"><?php _e('excludes'); ?></label>
                                    <textarea id="excludes_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][excludes]" class="form-control" rows="5"><?php echo htmlspecialchars($details['excludes'] ?? ''); ?></textarea>
                                    <small class="form-text"><?php _e('excludes_help'); ?></small>
                                </div>
                                
                                <!-- Günlük İtinerary Sistemi -->
                                <div class="form-group">
                                    <label class="form-label"><?php _e('daily_itinerary'); ?></label>
                                    <div class="itinerary-builder" data-lang="<?php echo $lang['code']; ?>">
                                        <div class="itinerary-days" id="itinerary-days-<?php echo $lang['code']; ?>">
                                            <?php
                                            // Parse existing itinerary
                                            $existingItinerary = [];
                                            if (!empty($details['itinerary'])) {
                                                // Try to parse as JSON first (new format)
                                                $jsonItinerary = json_decode($details['itinerary'], true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($jsonItinerary)) {
                                                    $existingItinerary = $jsonItinerary;
                                                } else {
                                                    // Parse old format (plain text with double newlines)
                                                    $days = explode("\n\n", $details['itinerary']);
                                                    foreach ($days as $index => $day) {
                                                        if (trim($day)) {
                                                            $lines = explode("\n", $day);
                                                            $existingItinerary[$index + 1] = [
                                                                'title' => $lines[0] ?? '',
                                                                'description' => implode("\n", array_slice($lines, 1))
                                                            ];
                                                        }
                                                    }
                                                }
                                            }
                                            
                                            // If no existing data, create at least one day
                                            if (empty($existingItinerary)) {
                                                $existingItinerary[1] = ['title' => '', 'description' => ''];
                                            }
                                            
                                            foreach ($existingItinerary as $dayNum => $dayData):
                                            ?>
                                                <div class="itinerary-day" data-day="<?php echo $dayNum; ?>">
                                                    <div class="day-header">
                                                        <h5><?php _e('day'); ?> <?php echo $dayNum; ?></h5>
                                                        <button type="button" class="btn btn-sm btn-danger remove-day" <?php echo count($existingItinerary) <= 1 ? 'style="display: none;"' : ''; ?>>
                                                            <i class="material-icons">delete</i>
                                                        </button>
                                                    </div>
                                                    <div class="day-content">
                                                        <div class="form-group">
                                                            <label><?php _e('day_title'); ?></label>
                                                            <input type="text" name="details[<?php echo $lang['id']; ?>][itinerary][<?php echo $dayNum; ?>][title]" class="form-control" placeholder="<?php _e('day_title_placeholder'); ?>" value="<?php echo htmlspecialchars($dayData['title'] ?? ''); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php _e('day_description'); ?></label>
                                                            <textarea name="details[<?php echo $lang['id']; ?>][itinerary][<?php echo $dayNum; ?>][description]" class="form-control" rows="4" placeholder="<?php _e('day_description_placeholder'); ?>"><?php echo htmlspecialchars($dayData['description'] ?? ''); ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button type="button" class="btn btn-success add-day" data-lang="<?php echo $lang['code']; ?>">
                                            <i class="material-icons">add</i>
                                            <?php _e('add_day'); ?>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="meta_title_<?php echo $lang['code']; ?>" class="form-label"><?php _e('meta_title'); ?></label>
                                    <input type="text" id="meta_title_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][meta_title]" class="form-control" value="<?php echo htmlspecialchars($details['meta_title'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="meta_description_<?php echo $lang['code']; ?>" class="form-label"><?php _e('meta_description'); ?></label>
                                    <textarea id="meta_description_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][meta_description]" class="form-control" rows="3"><?php echo htmlspecialchars($details['meta_description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
            <!-- Tour Gallery -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('tour_gallery'); ?></h3>
                </div>
                <div class="card-body">
                    <!-- Existing Gallery Images -->
                    <?php if (!empty($galleryItems)): ?>
                        <div class="existing-gallery">
                            <h5><?php _e('existing_images'); ?></h5>
                            <!-- Success/Error Messages Container -->
                            <div id="gallery-messages" style="display: none;"></div>
                            
                            <div class="existing-gallery-grid">
                                <?php foreach ($galleryItems as $image): ?>
                                    <div class="existing-gallery-item" data-image-id="<?php echo $image['id']; ?>">
                                        <div class="existing-gallery-image">
                                            <img src="<?php echo $uploadsUrl . '/gallery/' . $image['image']; ?>" alt="<?php echo htmlspecialchars($image['title'] ?: 'Gallery Image'); ?>">
                                            <div class="existing-gallery-actions">
                                                <!-- SADECE AJAX DELETE BUTTON - ESKİ LİNK KALDIRILDI -->
                                                <button type="button" 
                                                        class="gallery-action-btn gallery-delete-btn ajax-delete-btn" 
                                                        data-image-id="<?php echo $image['id']; ?>"
                                                        data-image-name="<?php echo htmlspecialchars($image['title'] ?: 'Untitled Image'); ?>"
                                                        title="<?php _e('delete'); ?>">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="existing-gallery-title"><?php echo htmlspecialchars($image['title'] ?: __('no_title')); ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <hr>
                    <?php endif; ?>

                    <!-- Add New Images -->
                    <div class="gallery-upload-section">
                        <h5><?php _e('add_new_images'); ?></h5>
                        
                        <!-- Drag & Drop Zone -->
                        <div class="gallery-dropzone" id="gallery-dropzone">
                            <div class="dropzone-content">
                                <i class="material-icons">cloud_upload</i>
                                <h4><?php _e('drag_drop_images_here'); ?></h4>
                                <p><?php _e('or_click_to_select'); ?></p>
                                <button type="button" class="btn btn-primary" id="select-images-btn">
                                    <i class="material-icons">add_photo_alternate</i>
                                    <?php _e('select_images'); ?>
                                </button>
                            </div>
                            <input type="file" id="gallery-images" name="gallery_images[]" multiple accept="image/*" style="display: none;">
                        </div>

                        <!-- Preview Grid -->
                        <div class="gallery-preview-grid" id="gallery-preview-grid" style="display: none;">
                            <div class="preview-grid-header">
                                <h6><?php _e('new_images_to_upload'); ?></h6>
                                <button type="button" class="btn btn-light btn-sm" id="clear-all-btn">
                                    <i class="material-icons">clear_all</i>
                                    <?php _e('clear_all'); ?>
                                </button>
                            </div>
                            <div class="preview-grid" id="preview-grid"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Tour Options -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('tour_options'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="category_id" class="form-label"><?php _e('category'); ?></label>
                        <select id="category_id" name="category_id" class="form-select">
                            <option value=""><?php _e('select_category'); ?></option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>" <?php echo $tour['category_id'] == $category['id'] ? 'selected' : ''; ?>><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Duration Seçimi -->
                    <div class="form-group">
                        <label for="duration_type" class="form-label"><?php _e('duration'); ?> <span class="required">*</span></label>
                        <select id="duration_type" name="duration_type" class="form-select" required>
                            <option value=""><?php _e('select_duration'); ?></option>
                            <option value="half-day" <?php echo ($tour['duration_type'] ?? '') == 'half-day' ? 'selected' : ''; ?>><?php _e('half_day'); ?> (4-5 <?php _e('hours'); ?>)</option>
                            <option value="full-day" <?php echo ($tour['duration_type'] ?? '') == 'full-day' ? 'selected' : ''; ?>><?php _e('full_day'); ?> (8-10 <?php _e('hours'); ?>)</option>
                            <option value="2-days" <?php echo ($tour['duration_type'] ?? '') == '2-days' ? 'selected' : ''; ?>>2 <?php _e('days'); ?></option>
                            <option value="3-days" <?php echo ($tour['duration_type'] ?? '') == '3-days' ? 'selected' : ''; ?>>3 <?php _e('days'); ?></option>
                            <option value="4-days" <?php echo ($tour['duration_type'] ?? '') == '4-days' ? 'selected' : ''; ?>>4 <?php _e('days'); ?></option>
                            <option value="5-days" <?php echo ($tour['duration_type'] ?? '') == '5-days' ? 'selected' : ''; ?>>5 <?php _e('days'); ?></option>
                            <option value="6-days" <?php echo ($tour['duration_type'] ?? '') == '6-days' ? 'selected' : ''; ?>>6 <?php _e('days'); ?></option>
                            <option value="7-days" <?php echo ($tour['duration_type'] ?? '') == '7-days' ? 'selected' : ''; ?>>7 <?php _e('days'); ?></option>
                            <option value="custom" <?php echo ($tour['duration_type'] ?? '') == 'custom' ? 'selected' : ''; ?>><?php _e('custom_duration'); ?></option>
                        </select>
                    </div>
                    
                    <!-- Custom Duration (sadece custom seçildiğinde görünür) -->
                    <div class="form-group" id="custom-duration-group" <?php echo ($tour['duration_type'] ?? '') !== 'custom' ? 'style="display: none;"' : ''; ?>>
                        <label for="custom_duration" class="form-label"><?php _e('custom_duration_text'); ?></label>
                        <input type="text" id="custom_duration" name="custom_duration" class="form-control" placeholder="<?php _e('duration_placeholder'); ?>" value="<?php echo htmlspecialchars($tour['duration'] ?? ''); ?>">
                    </div>
                    
                    <!-- Gün sayısı (duration ile senkronize) -->
                    <div class="form-group">
                        <label for="duration_days" class="form-label"><?php _e('number_of_days'); ?></label>
                        <input type="number" id="duration_days" name="duration_days" class="form-control" min="1" max="30" value="<?php echo $tour['duration_days'] ?? 1; ?>" <?php echo ($tour['duration_type'] ?? '') !== 'custom' ? 'readonly' : ''; ?>>
                        <small class="form-text"><?php _e('days_auto_calculated'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="price" class="form-label"><?php _e('price'); ?> <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><?php echo $settings['currency_symbol']; ?></span>
                            <input type="number" id="price" name="price" class="form-control" value="<?php echo $tour['price']; ?>" min="0" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="discount_price" class="form-label"><?php _e('discount_price'); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><?php echo $settings['currency_symbol']; ?></span>
                            <input type="number" id="discount_price" name="discount_price" class="form-control" value="<?php echo $tour['discount_price']; ?>" min="0" step="0.01">
                        </div>
                        <small class="form-text"><?php _e('discount_price_help'); ?></small>
                    </div>
                    
                    <!-- Group Pricing Section -->
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="group_pricing_enabled" name="group_pricing_enabled" value="1" class="form-check-input" <?php echo (!empty($tour['group_pricing_enabled']) ? 'checked' : ''); ?>>
                            <label for="group_pricing_enabled" class="form-check-label"><?php _e('enable_group_pricing'); ?></label>
                        </div>
                        <small class="form-text"><?php _e('group_pricing_help'); ?></small>
                    </div>
                    
                    <div id="group-pricing-section" class="group-pricing-section" <?php echo (empty($tour['group_pricing_enabled']) ? 'style="display: none;"' : ''); ?>>
                        <div class="form-group">
                            <label class="form-label"><?php _e('group_pricing_tiers'); ?></label>
                            <div id="pricing-tiers">
                                <?php
                                // Parse existing group pricing data
                                $groupPricing = [];
                                if (!empty($tour['group_pricing_tiers'])) {
                                    $groupPricing = json_decode($tour['group_pricing_tiers'], true);
                                    if (json_last_error() !== JSON_ERROR_NONE) {
                                        $groupPricing = [];
                                    }
                                }
                                
                                // Default pricing tiers structure
                                $defaultTiers = [
                                    ['min_persons' => 1, 'max_persons' => 1, 'price_per_person' => ''],
                                    ['min_persons' => 2, 'max_persons' => 2, 'price_per_person' => ''],
                                    ['min_persons' => 3, 'max_persons' => 3, 'price_per_person' => ''],
                                    ['min_persons' => 4, 'max_persons' => 4, 'price_per_person' => ''],
                                    ['min_persons' => 5, 'max_persons' => '', 'price_per_person' => '']
                                ];
                                
                                // Merge existing data with defaults
                                for ($i = 0; $i < 5; $i++) {
                                    $tierData = $groupPricing[$i] ?? $defaultTiers[$i];
                                    ?>
                                    <div class="pricing-tier">
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <input type="number" name="group_pricing_tiers[<?php echo $i; ?>][min_persons]" class="form-control" placeholder="Min" min="1" value="<?php echo htmlspecialchars($tierData['min_persons'] ?? $defaultTiers[$i]['min_persons']); ?>">
                                            </div>
                                            <div class="col-4">
                                                <?php if ($i === 4): // Last tier (5+ persons) ?>
                                                    <input type="text" class="form-control" placeholder="5+ kişi" readonly>
                                                    <input type="hidden" name="group_pricing_tiers[<?php echo $i; ?>][max_persons]" value="">
                                                <?php else: ?>
                                                    <input type="number" name="group_pricing_tiers[<?php echo $i; ?>][max_persons]" class="form-control" placeholder="Max" min="1" value="<?php echo htmlspecialchars($tierData['max_persons'] ?? $defaultTiers[$i]['max_persons']); ?>">
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-4">
                                                <div class="input-group">
                                                    <input type="number" name="group_pricing_tiers[<?php echo $i; ?>][price_per_person]" class="form-control" placeholder="Fiyat" min="0" step="0.01" value="<?php echo htmlspecialchars($tierData['price_per_person'] ?? ''); ?>">
                                                    <span class="input-group-text"><?php echo $settings['currency_symbol']; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <small class="form-text"><?php _e('group_pricing_tiers_help'); ?></small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" class="form-check-input" <?php echo $tour['is_featured'] ? 'checked' : ''; ?>>
                            <label for="is_featured" class="form-check-label"><?php _e('featured_tour'); ?></label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" <?php echo $tour['is_active'] ? 'checked' : ''; ?>>
                            <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Featured Image -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('featured_image'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="image-preview">
                            <?php if ($tour['featured_image']): ?>
                                <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>" class="preview-image" id="featured-preview">
                            <?php else: ?>
                                <img src="<?php echo $imgUrl; ?>/no-image.jpg" alt="<?php _e('no_image'); ?>" class="preview-image" id="featured-preview">
                            <?php endif; ?>
                        </div>
                        <div class="mt-3">
                            <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
                            <small class="form-text"><?php _e('image_help'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tour Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('tour_stats'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('bookings'); ?></div>
                        <div class="stat-value"><?php echo number_format($stats['bookings'] ?? 0); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('revenue'); ?></div>
                        <div class="stat-value"><?php echo $settings['currency_symbol'] . number_format($stats['revenue'] ?? 0, 2); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('created_at'); ?></div>
                        <div class="stat-value"><?php echo date('d M Y', strtotime($tour['created_at'])); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('updated_at'); ?></div>
                        <div class="stat-value"><?php echo date('d M Y', strtotime($tour['updated_at'])); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="material-icons">save</i>
            <?php _e('save_changes'); ?>
        </button>
        <a href="<?php echo $adminUrl; ?>/tours" class="btn btn-light">
            <i class="material-icons">cancel</i>
            <?php _e('cancel'); ?>
        </a>
    </div>
</form>

<!-- Success/Error Messages Container -->
<div id="gallery-messages" style="display: none;"></div>

<!-- Same styles as create.php -->
<style>
/* Gallery Delete Animation */
.existing-gallery-item.deleting {
    opacity: 0.5;
    pointer-events: none;
    position: relative;
}

.existing-gallery-item.deleting::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

.existing-gallery-item.deleting::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #fff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
    z-index: 11;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.existing-gallery-item.fade-out {
    animation: fadeOut 0.5s ease-out forwards;
}

@keyframes fadeOut {
    from {
        opacity: 1;
        transform: scale(1);
    }
    to {
        opacity: 0;
        transform: scale(0.8);
    }
}

/* Message Styles */
.gallery-message {
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius-md);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.gallery-message.success {
    background-color: rgba(40, 167, 69, 0.1);
    border: 1px solid rgba(40, 167, 69, 0.2);
    color: var(--success-color);
}

.gallery-message.error {
    background-color: rgba(220, 53, 69, 0.1);
    border: 1px solid rgba(220, 53, 69, 0.2);
    color: var(--danger-color);
}

.gallery-message i {
    font-size: 1.2rem;
}

/* Disable pointer events during deletion */
.ajax-delete-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* İtinerary Builder Styles */
.itinerary-builder {
    border: 1px solid #e0e0e0;
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    background: var(--gray-50);
}

.itinerary-days {
    margin-bottom: 1rem;
}

.itinerary-day {
    background: var(--white-color);
    border: 1px solid #e0e0e0;
    border-radius: var(--border-radius-md);
    margin-bottom: 1rem;
    overflow: hidden;
}

.day-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: var(--primary-color);
    color: var(--white-color);
}

.day-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
}

.remove-day {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.day-content {
    padding: 1.5rem;
}

.add-day {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
}

/* Custom Duration Group */
#custom-duration-group {
    transition: all 0.3s ease;
}

/* Duration Days Field */
input[readonly] {
    background-color: var(--gray-100);
    cursor: not-allowed;
}

/* Existing Gallery Styles */
.existing-gallery {
    margin-bottom: var(--spacing-lg);
}

.existing-gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}

.existing-gallery-item {
    border-radius: var(--border-radius-md);
    overflow: hidden;
    background-color: var(--white-color);
    box-shadow: var(--shadow-sm);
}

.existing-gallery-image {
    position: relative;
    height: 120px;
    overflow: hidden;
}

.existing-gallery-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.existing-gallery-actions {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity var(--transition-fast);
}

.existing-gallery-image:hover .existing-gallery-actions {
    opacity: 1;
}

.gallery-action-btn {
    width: 36px;
    height: 36px;
    border-radius: var(--border-radius-circle);
    background-color: var(--white-color);
    color: var(--dark-color);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color var(--transition-fast), color var(--transition-fast);
    text-decoration: none;
}

.gallery-action-btn:hover {
    background-color: var(--primary-color);
    color: var(--white-color);
}

.gallery-action-btn.delete-btn:hover {
    background-color: var(--danger-color);
}

.existing-gallery-title {
    padding: var(--spacing-sm);
    text-align: center;
    font-size: var(--font-size-sm);
    color: var(--gray-700);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Gallery Upload Styles */
.gallery-upload-section {
    margin-bottom: var(--spacing-lg);
}

.gallery-dropzone {
    border: 2px dashed var(--gray-300);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background-color: var(--gray-50);
}

.gallery-dropzone:hover,
.gallery-dropzone.dragover {
    border-color: var(--primary-color);
    background-color: rgba(67, 97, 238, 0.05);
}

.dropzone-content i {
    font-size: 4rem;
    color: var(--gray-400);
    margin-bottom: 1rem;
}

.dropzone-content h4 {
    color: var(--gray-700);
    margin-bottom: 0.5rem;
}

.dropzone-content p {
    color: var(--gray-500);
    margin-bottom: 1.5rem;
}

.gallery-preview-grid {
    margin-top: var(--spacing-lg);
}

.preview-grid-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: var(--spacing-md);
}

.preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: var(--spacing-md);
}

.preview-item {
    position: relative;
    background: var(--white-color);
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.preview-image-container {
    position: relative;
    height: 120px;
    background: var(--gray-100);
}

.preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-actions {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    display: flex;
    gap: 0.25rem;
}

.preview-action-btn {
    width: 28px;
    height: 28px;
    border-radius: var(--border-radius-circle);
    border: none;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.2s;
}

.preview-action-btn:hover {
    background: rgba(0, 0, 0, 0.9);
}

.preview-action-btn.delete-btn:hover {
    background: var(--danger-color);
}

.preview-info {
    padding: 0.75rem;
    font-size: var(--font-size-sm);
}

.preview-filename {
    font-weight: var(--font-weight-medium);
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.preview-filesize {
    color: var(--gray-500);
    font-size: var(--font-size-xs);
}

.drop-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(67, 97, 238, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    pointer-events: none;
}

.drop-overlay-content {
    text-align: center;
    color: white;
}

.drop-overlay-content i {
    font-size: 5rem;
    margin-bottom: 1rem;
}

.drop-overlay-content h3 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-200);
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    color: var(--gray-600);
}

.stat-value {
    font-weight: var(--font-weight-medium);
}

/* Language Tabs */
.language-tabs {
    margin-bottom: var(--spacing-lg);
}

.language-tabs-nav {
    display: flex;
    border-bottom: 1px solid var(--gray-300);
    margin-bottom: var(--spacing-md);
}

.language-tab-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    background: none;
    border-bottom: 2px solid transparent;
    cursor: pointer;
}

.language-tab-btn.active {
    border-bottom-color: var(--primary-color);
    color: var(--primary-color);
}

.language-tab-btn img {
    width: 20px;
    height: 15px;
    object-fit: cover;
}

.language-tab-content {
    display: none;
}

.language-tab-content.active {
    display: block;
}

.image-preview {
    width: 100%;
    height: 200px;
    border-radius: var(--border-radius-md);
    overflow: hidden;
    background-color: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.form-actions {
    margin-top: var(--spacing-lg);
    display: flex;
    gap: var(--spacing-md);
}

/* Group Pricing Styles */
.group-pricing-section {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
    background-color: var(--gray-50);
    margin-top: var(--spacing-sm);
}

.pricing-tier {
    margin-bottom: var(--spacing-sm);
    padding: var(--spacing-sm);
    background-color: var(--white-color);
    border-radius: var(--border-radius-sm);
    border: 1px solid var(--gray-200);
}

.pricing-tier:last-child {
    margin-bottom: 0;
}

.pricing-tier .row {
    align-items: center;
}

.pricing-tier input[readonly] {
    background-color: var(--gray-100);
    border-color: var(--gray-300);
}

@media (max-width: 768px) {
    .existing-gallery-grid,
    .preview-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
    
    .itinerary-builder {
        padding: 1rem;
    }
    
    .day-content {
        padding: 1rem;
    }
    
    .pricing-tier .row > .col-4 {
        margin-bottom: var(--spacing-xs);
    }
    
    .pricing-tier .row > .col-4:last-child {
        margin-bottom: 0;
    }
}

/* TinyMCE customizations */
.tox-tinymce {
    border-radius: var(--border-radius-md) !important;
    border-color: var(--gray-300) !important;
}

.tox-toolbar {
    background-color: var(--gray-50) !important;
    border-bottom-color: var(--gray-300) !important;
}

.tox-edit-area__iframe {
    border-radius: 0 0 var(--border-radius-md) var(--border-radius-md) !important;
}

.tox-statusbar {
    border-top-color: var(--gray-300) !important;
    background-color: var(--gray-50) !important;
    border-radius: 0 0 var(--border-radius-md) var(--border-radius-md) !important;
}

.tox-toolbar__group:not(:last-of-type) {
    border-right-color: var(--gray-300) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Language mappings - PHP'den alınacak
    const languageMappings = {
        <?php foreach ($languages as $lang): ?>
        '<?php echo $lang['code']; ?>': <?php echo $lang['id']; ?>,
        <?php endforeach; ?>
    };
    
    // =============================================================================
    // LANGUAGE TABS FUNCTIONALITY
    // =============================================================================
    const langTabBtns = document.querySelectorAll('.language-tab-btn');
    const langTabContents = document.querySelectorAll('.language-tab-content');
    
    langTabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const lang = this.dataset.lang;
            
            langTabBtns.forEach(btn => btn.classList.remove('active'));
            langTabContents.forEach(content => content.classList.remove('active'));
            
            this.classList.add('active');
            document.querySelector(`.language-tab-content[data-lang="${lang}"]`).classList.add('active');
        });
    });
    
    // =============================================================================
    // DURATION SELECTION LOGIC
    // =============================================================================
    const durationSelect = document.getElementById('duration_type');
    const customDurationGroup = document.getElementById('custom-duration-group');
    const durationDaysInput = document.getElementById('duration_days');
    
    if (durationSelect && durationDaysInput) {
        durationSelect.addEventListener('change', function() {
            const value = this.value;
            
            if (value === 'custom') {
                if (customDurationGroup) customDurationGroup.style.display = 'block';
                durationDaysInput.readOnly = false;
                durationDaysInput.value = '';
            } else {
                if (customDurationGroup) customDurationGroup.style.display = 'none';
                durationDaysInput.readOnly = true;
                
                switch(value) {
                    case 'half-day':
                    case 'full-day':
                        durationDaysInput.value = 1;
                        break;
                    case '2-days':
                        durationDaysInput.value = 2;
                        break;
                    case '3-days':
                        durationDaysInput.value = 3;
                        break;
                    case '4-days':
                        durationDaysInput.value = 4;
                        break;
                    case '5-days':
                        durationDaysInput.value = 5;
                        break;
                    case '6-days':
                        durationDaysInput.value = 6;
                        break;
                    case '7-days':
                        durationDaysInput.value = 7;
                        break;
                    default:
                        durationDaysInput.value = 1;
                }
                
                updateItineraryDays();
            }
        });
    }
    
    // =============================================================================
    // ITINERARY MANAGEMENT FUNCTIONS
    // =============================================================================
    function updateItineraryDays() {
        const days = parseInt(durationDaysInput.value) || 1;
        
        Object.keys(languageMappings).forEach(langCode => {
            const daysContainer = document.getElementById(`itinerary-days-${langCode}`);
            if (!daysContainer) return;
            
            const currentDays = daysContainer.querySelectorAll('.itinerary-day').length;
            
            if (days > currentDays) {
                for (let i = currentDays + 1; i <= days; i++) {
                    addItineraryDay(langCode, i);
                }
            } else if (days < currentDays) {
                for (let i = currentDays; i > days; i--) {
                    const dayElement = daysContainer.querySelector(`[data-day="${i}"]`);
                    if (dayElement) {
                        dayElement.remove();
                    }
                }
            }
            
            updateRemoveButtons(langCode);
        });
    }
    
    function addItineraryDay(langCode, dayNumber) {
        const daysContainer = document.getElementById(`itinerary-days-${langCode}`);
        if (!daysContainer) return;
        
        const langId = languageMappings[langCode];
        if (!langId) return;
        
        const dayElement = document.createElement('div');
        dayElement.className = 'itinerary-day';
        dayElement.setAttribute('data-day', dayNumber);
        
        dayElement.innerHTML = `
            <div class="day-header">
                <h5><?php _e('day'); ?> ${dayNumber}</h5>
                <button type="button" class="btn btn-sm btn-danger remove-day">
                    <i class="material-icons">delete</i>
                </button>
            </div>
            <div class="day-content">
                <div class="form-group">
                    <label><?php _e('day_title'); ?></label>
                    <input type="text" name="details[${langId}][itinerary][${dayNumber}][title]" class="form-control" placeholder="<?php _e('day_title_placeholder'); ?>">
                </div>
                <div class="form-group">
                    <label><?php _e('day_description'); ?></label>
                    <textarea name="details[${langId}][itinerary][${dayNumber}][description]" class="form-control" rows="4" placeholder="<?php _e('day_description_placeholder'); ?>"></textarea>
                </div>
            </div>
        `;
        
        daysContainer.appendChild(dayElement);
        
        // Add remove event listener
        const removeBtn = dayElement.querySelector('.remove-day');
        removeBtn.addEventListener('click', function() {
            dayElement.remove();
            updateRemoveButtons(langCode);
            renumberDays(langCode);
        });
    }
    
    function updateRemoveButtons(langCode) {
        const daysContainer = document.getElementById(`itinerary-days-${langCode}`);
        if (!daysContainer) return;
        
        const days = daysContainer.querySelectorAll('.itinerary-day');
        
        days.forEach((day, index) => {
            const removeBtn = day.querySelector('.remove-day');
            if (days.length > 1) {
                removeBtn.style.display = 'block';
            } else {
                removeBtn.style.display = 'none';
            }
        });
    }
    
    function renumberDays(langCode) {
        const daysContainer = document.getElementById(`itinerary-days-${langCode}`);
        if (!daysContainer) return;
        
        const days = daysContainer.querySelectorAll('.itinerary-day');
        const langId = languageMappings[langCode];
        
        days.forEach((day, index) => {
            const dayNumber = index + 1;
            day.setAttribute('data-day', dayNumber);
            day.querySelector('.day-header h5').textContent = `<?php _e('day'); ?> ${dayNumber}`;
            
            const titleInput = day.querySelector('input[type="text"]');
            const descInput = day.querySelector('textarea');
            
            if (titleInput) titleInput.name = `details[${langId}][itinerary][${dayNumber}][title]`;
            if (descInput) descInput.name = `details[${langId}][itinerary][${dayNumber}][description]`;
        });
    }
    
    // Add Day Button Events
    document.querySelectorAll('.add-day').forEach(btn => {
        btn.addEventListener('click', function() {
            const langCode = this.dataset.lang;
            const daysContainer = document.getElementById(`itinerary-days-${langCode}`);
            if (!daysContainer) return;
            
            const currentDays = daysContainer.querySelectorAll('.itinerary-day').length;
            const newDayNumber = currentDays + 1;
            
            addItineraryDay(langCode, newDayNumber);
            updateRemoveButtons(langCode);
            
            if (durationSelect && (durationSelect.value === 'custom' || !durationSelect.value)) {
                durationDaysInput.value = newDayNumber;
            }
        });
    });
    
    // Initialize existing remove buttons
    document.querySelectorAll('.remove-day').forEach(btn => {
        btn.addEventListener('click', function() {
            const day = this.closest('.itinerary-day');
            const daysContainer = day.closest('.itinerary-days');
            const langCode = daysContainer.id.replace('itinerary-days-', '');
            
            day.remove();
            updateRemoveButtons(langCode);
            renumberDays(langCode);
        });
    });
    
    // Initialize remove button visibility
    Object.keys(languageMappings).forEach(langCode => {
        updateRemoveButtons(langCode);
    });
    
    // =============================================================================
    // FEATURED IMAGE PREVIEW
    // =============================================================================
    const featuredImageInput = document.getElementById('featured_image');
    const featuredPreview = document.getElementById('featured-preview');
    
    if (featuredImageInput && featuredPreview) {
        featuredImageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    featuredPreview.src = e.target.result;
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
    }
    
    // =============================================================================
    // AJAX GALLERY DELETE FUNCTIONALITY
    // =============================================================================
    
    // Show message function
    function showGalleryMessage(message, type = 'success') {
        const messagesContainer = document.getElementById('gallery-messages');
        if (!messagesContainer) return;
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `gallery-message ${type}`;
        
        const icon = type === 'success' ? 'check_circle' : 'error';
        messageDiv.innerHTML = `
            <i class="material-icons">${icon}</i>
            <span>${message}</span>
        `;
        
        messagesContainer.innerHTML = '';
        messagesContainer.appendChild(messageDiv);
        messagesContainer.style.display = 'block';
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.style.opacity = '0';
                setTimeout(() => {
                    if (messageDiv.parentNode) {
                        messageDiv.remove();
                        if (messagesContainer.children.length === 0) {
                            messagesContainer.style.display = 'none';
                        }
                    }
                }, 300);
            }
        }, 3000);
    }
    
    // AJAX Delete Function
    function deleteGalleryImage(imageId, imageName, buttonElement) {
        const galleryItem = buttonElement.closest('.existing-gallery-item');
        if (!galleryItem) return;
        
        // Add deleting state
        galleryItem.classList.add('deleting');
        buttonElement.disabled = true;
        
        // Make AJAX request
        fetch(`<?php echo $adminUrl; ?>/tours/ajaxDeleteGalleryImage/${imageId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Remove deleting state and add fade out animation
                galleryItem.classList.remove('deleting');
                galleryItem.classList.add('fade-out');
                
                // Show success message
                showGalleryMessage(data.message || '<?php _e("gallery_item_deleted"); ?>', 'success');
                
                // Remove element after animation
                setTimeout(() => {
                    galleryItem.remove();
                    
                    // Check if no more images exist
                    const remainingImages = document.querySelectorAll('.existing-gallery-item');
                    if (remainingImages.length === 0) {
                        const existingGallery = document.querySelector('.existing-gallery');
                        if (existingGallery) {
                            existingGallery.style.display = 'none';
                            const hr = existingGallery.nextElementSibling;
                            if (hr && hr.tagName === 'HR') {
                                hr.style.display = 'none';
                            }
                        }
                    }
                }, 500);
            } else {
                // Remove deleting state
                galleryItem.classList.remove('deleting');
                buttonElement.disabled = false;
                
                // Show error message
                showGalleryMessage(data.message || '<?php _e("gallery_item_delete_failed"); ?>', 'error');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            
            // Remove deleting state
            galleryItem.classList.remove('deleting');
            buttonElement.disabled = false;
            
            // Show error message
            showGalleryMessage('<?php _e("delete_error_occurred"); ?>', 'error');
        });
    }
    
    // Add event listeners to delete buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.ajax-delete-btn')) {
            e.preventDefault();
            e.stopPropagation();
            
            const button = e.target.closest('.ajax-delete-btn');
            const imageId = button.dataset.imageId;
            const imageName = button.dataset.imageName || 'this image';
            
            // Confirm deletion
            const confirmMessage = `<?php _e("delete_image_confirm"); ?>: "${imageName}"?`;
            
            if (confirm(confirmMessage)) {
                deleteGalleryImage(imageId, imageName, button);
            }
        }
    });
    
    // Prevent clicking on image during deletion
    document.addEventListener('click', function(e) {
        if (e.target.closest('.existing-gallery-item.deleting')) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
    
    // =============================================================================
    // GALLERY UPLOAD SYSTEM (NEW IMAGES)
    // =============================================================================
    const galleryDropzone = document.getElementById('gallery-dropzone');
    const galleryInput = document.getElementById('gallery-images');
    const selectImagesBtn = document.getElementById('select-images-btn');
    const previewGrid = document.getElementById('preview-grid');
    const previewSection = document.getElementById('gallery-preview-grid');
    const clearAllBtn = document.getElementById('clear-all-btn');
    
    let selectedFiles = [];
    let dragCounter = 0;
    
    if (galleryDropzone && galleryInput && selectImagesBtn) {
        // Click to select images
        selectImagesBtn.addEventListener('click', function() {
            galleryInput.click();
        });
        
        galleryDropzone.addEventListener('click', function() {
            galleryInput.click();
        });
        
        // File input change
        galleryInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        // Drag and drop functionality
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            galleryDropzone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        // Global drag enter/leave for full page overlay
        document.addEventListener('dragenter', function(e) {
            dragCounter++;
            if (dragCounter === 1) {
                showDropOverlay();
            }
        });
        
        document.addEventListener('dragleave', function(e) {
            dragCounter--;
            if (dragCounter === 0) {
                hideDropOverlay();
            }
        });
        
        document.addEventListener('drop', function(e) {
            dragCounter = 0;
            hideDropOverlay();
        });
        
        galleryDropzone.addEventListener('dragover', function() {
            galleryDropzone.classList.add('dragover');
        });
        
        galleryDropzone.addEventListener('dragleave', function() {
            galleryDropzone.classList.remove('dragover');
        });
        
        galleryDropzone.addEventListener('drop', function(e) {
            galleryDropzone.classList.remove('dragover');
            const files = e.dataTransfer.files;
            handleFiles(files);
        });
        
        function showDropOverlay() {
            if (!document.querySelector('.drop-overlay')) {
                const overlay = document.createElement('div');
                overlay.className = 'drop-overlay';
                overlay.innerHTML = `
                    <div class="drop-overlay-content">
                        <i class="material-icons">cloud_upload</i>
                        <h3><?php _e('drop_images_here'); ?></h3>
                        <p><?php _e('release_to_upload'); ?></p>
                    </div>
                `;
                document.body.appendChild(overlay);
            }
        }
        
        function hideDropOverlay() {
            const overlay = document.querySelector('.drop-overlay');
            if (overlay) {
                overlay.remove();
            }
        }
        
        function handleFiles(files) {
            Array.from(files).forEach(file => {
                if (file.type.startsWith('image/')) {
                    // Check if file already exists
                    const exists = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                    if (!exists) {
                        selectedFiles.push(file);
                    }
                }
            });
            
            updateFileInput();
            renderPreview();
        }
        
        function updateFileInput() {
            const dt = new DataTransfer();
            selectedFiles.forEach(file => dt.items.add(file));
            galleryInput.files = dt.files;
        }
        
        function renderPreview() {
            if (selectedFiles.length === 0) {
                if (previewSection) previewSection.style.display = 'none';
                return;
            }
            
            if (previewSection) previewSection.style.display = 'block';
            if (previewGrid) previewGrid.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <div class="preview-image-container">
                            <img src="${e.target.result}" alt="${file.name}">
                            <div class="preview-actions">
                                <button type="button" class="preview-action-btn gallery-delete-btn" onclick="removeFile(${index})">
                                    <i class="material-icons">delete</i>
                                </button>
                            </div>
                        </div>
                        <div class="preview-info">
                            <div class="preview-filename">${file.name}</div>
                            <div class="preview-filesize">${formatFileSize(file.size)}</div>
                        </div>
                    `;
                    if (previewGrid) previewGrid.appendChild(previewItem);
                };
                reader.readAsDataURL(file);
            });
        }
        
        // Global function for removing files
        window.removeFile = function(index) {
            selectedFiles.splice(index, 1);
            updateFileInput();
            renderPreview();
        }
        
        // Clear all files
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', function() {
                selectedFiles = [];
                updateFileInput();
                renderPreview();
            });
        }
        
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    }
    
    // =============================================================================
    // GROUP PRICING TOGGLE FUNCTIONALITY
    // =============================================================================
    const groupPricingCheckbox = document.getElementById('group_pricing_enabled');
    const groupPricingSection = document.getElementById('group-pricing-section');
    const basicPriceField = document.getElementById('price');
    
    if (groupPricingCheckbox) {
        groupPricingCheckbox.addEventListener('change', function() {
            if (this.checked) {
                groupPricingSection.style.display = 'block';
                basicPriceField.required = false;
            } else {
                groupPricingSection.style.display = 'none';
                basicPriceField.required = true;
            }
        });
    }
    
    // =============================================================================
    // RICH TEXT EDITORS INITIALIZATION (TinyMCE)
    // =============================================================================
    // Initialize TinyMCE rich text editors
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '.editor',
            height: 400,
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | forecolor backcolor | ' +
                    'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | link image media table | code preview fullscreen help',
            content_style: 'body { font-family: "Poppins", sans-serif; font-size: 14px; }',
            paste_data_images: true,
            relative_urls: false,
            remove_script_host: false,
            document_base_url: window.location.origin + '/',
            branding: false,
            promotion: false
        });
    }
});
</script>