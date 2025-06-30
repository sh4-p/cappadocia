<?php
/**
 * Admin Tour Create View - Günlük İtinerary ve Duration Seçimi ile
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('add_tour'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/tours" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_tours'); ?></span>
        </a>
    </div>
</div>

<form action="<?php echo $adminUrl; ?>/tours/create" method="post" enctype="multipart/form-data" class="tour-form">
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
                            <div class="language-tab-content <?php echo $lang['code'] === $currentLang ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                <div class="form-group">
                                    <label for="name_<?php echo $lang['code']; ?>" class="form-label"><?php _e('tour_name'); ?> <span class="required">*</span></label>
                                    <input type="text" id="name_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][name]" class="form-control" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="slug_<?php echo $lang['code']; ?>" class="form-label"><?php _e('slug'); ?></label>
                                    <input type="text" id="slug_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][slug]" class="form-control">
                                    <small class="form-text"><?php _e('slug_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="short_description_<?php echo $lang['code']; ?>" class="form-label"><?php _e('short_description'); ?> <span class="required">*</span></label>
                                    <textarea id="short_description_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][short_description]" class="form-control" rows="3" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description_<?php echo $lang['code']; ?>" class="form-label"><?php _e('description'); ?> <span class="required">*</span></label>
                                    <textarea id="description_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][description]" class="form-control editor" rows="10" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="includes_<?php echo $lang['code']; ?>" class="form-label"><?php _e('includes'); ?></label>
                                    <textarea id="includes_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][includes]" class="form-control" rows="5"></textarea>
                                    <small class="form-text"><?php _e('includes_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="excludes_<?php echo $lang['code']; ?>" class="form-label"><?php _e('excludes'); ?></label>
                                    <textarea id="excludes_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][excludes]" class="form-control" rows="5"></textarea>
                                    <small class="form-text"><?php _e('excludes_help'); ?></small>
                                </div>
                                
                                <!-- Günlük İtinerary Sistemi -->
                                <div class="form-group">
                                    <label class="form-label"><?php _e('daily_itinerary'); ?></label>
                                    <div class="itinerary-builder" data-lang="<?php echo $lang['code']; ?>">
                                        <div class="itinerary-days" id="itinerary-days-<?php echo $lang['code']; ?>">
                                            <!-- İlk gün otomatik eklenir -->
                                            <div class="itinerary-day" data-day="1">
                                                <div class="day-header">
                                                    <h5><?php _e('day'); ?> 1</h5>
                                                    <button type="button" class="btn btn-sm btn-danger remove-day" style="display: none;">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                </div>
                                                <div class="day-content">
                                                    <div class="form-group">
                                                        <label><?php _e('day_title'); ?></label>
                                                        <input type="text" name="details[<?php echo $lang['id']; ?>][itinerary][1][title]" class="form-control" placeholder="<?php _e('day_title_placeholder'); ?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <label><?php _e('day_description'); ?></label>
                                                        <textarea name="details[<?php echo $lang['id']; ?>][itinerary][1][description]" class="form-control" rows="4" placeholder="<?php _e('day_description_placeholder'); ?>"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-success add-day" data-lang="<?php echo $lang['code']; ?>">
                                            <i class="material-icons">add</i>
                                            <?php _e('add_day'); ?>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="meta_title_<?php echo $lang['code']; ?>" class="form-label"><?php _e('meta_title'); ?></label>
                                    <input type="text" id="meta_title_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][meta_title]" class="form-control">
                                </div>
                                
                                <div class="form-group">
                                    <label for="meta_description_<?php echo $lang['code']; ?>" class="form-label"><?php _e('meta_description'); ?></label>
                                    <textarea id="meta_description_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][meta_description]" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Tour Gallery Section -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('tour_gallery'); ?></h3>
                    <small class="text-muted"><?php _e('upload_multiple_images_description'); ?></small>
                </div>
                <div class="card-body">
                    <div class="gallery-upload-section">
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
                                <h5><?php _e('selected_images'); ?></h5>
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
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Duration Seçimi -->
                    <div class="form-group">
                        <label for="duration_type" class="form-label"><?php _e('duration'); ?> <span class="required">*</span></label>
                        <select id="duration_type" name="duration_type" class="form-select" required>
                            <option value=""><?php _e('select_duration'); ?></option>
                            <option value="half-day"><?php _e('half_day'); ?> (4-5 <?php _e('hours'); ?>)</option>
                            <option value="full-day"><?php _e('full_day'); ?> (8-10 <?php _e('hours'); ?>)</option>
                            <option value="2-days">2 <?php _e('days'); ?></option>
                            <option value="3-days">3 <?php _e('days'); ?></option>
                            <option value="4-days">4 <?php _e('days'); ?></option>
                            <option value="5-days">5 <?php _e('days'); ?></option>
                            <option value="6-days">6 <?php _e('days'); ?></option>
                            <option value="7-days">7 <?php _e('days'); ?></option>
                            <option value="custom"><?php _e('custom_duration'); ?></option>
                        </select>
                    </div>
                    
                    <!-- Custom Duration (sadece custom seçildiğinde görünür) -->
                    <div class="form-group" id="custom-duration-group" style="display: none;">
                        <label for="custom_duration" class="form-label"><?php _e('custom_duration_text'); ?></label>
                        <input type="text" id="custom_duration" name="custom_duration" class="form-control" placeholder="<?php _e('duration_placeholder'); ?>">
                    </div>
                    
                    <!-- Gün sayısı (duration ile senkronize) -->
                    <div class="form-group">
                        <label for="duration_days" class="form-label"><?php _e('number_of_days'); ?></label>
                        <input type="number" id="duration_days" name="duration_days" class="form-control" min="1" max="30" value="1" readonly>
                        <small class="form-text"><?php _e('days_auto_calculated'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="price" class="form-label"><?php _e('price'); ?> <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><?php echo $settings['currency_symbol']; ?></span>
                            <input type="number" id="price" name="price" class="form-control" min="0" step="0.01" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="discount_price" class="form-label"><?php _e('discount_price'); ?></label>
                        <div class="input-group">
                            <span class="input-group-text"><?php echo $settings['currency_symbol']; ?></span>
                            <input type="number" id="discount_price" name="discount_price" class="form-control" min="0" step="0.01" value="0">
                        </div>
                        <small class="form-text"><?php _e('discount_price_help'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" class="form-check-input">
                            <label for="is_featured" class="form-check-label"><?php _e('featured_tour'); ?></label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" checked>
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
                            <img src="<?php echo $imgUrl; ?>/no-image.jpg" alt="<?php _e('preview'); ?>" class="preview-image" id="featured-preview">
                        </div>
                        <div class="mt-3">
                            <input type="file" id="featured_image" name="featured_image" class="form-control" accept="image/*">
                            <small class="form-text"><?php _e('image_help'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="material-icons">save</i>
            <?php _e('save_tour'); ?>
        </button>
        <a href="<?php echo $adminUrl; ?>/tours" class="btn btn-light">
            <i class="material-icons">cancel</i>
            <?php _e('cancel'); ?>
        </a>
    </div>
</form>

<style>
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

@media (max-width: 768px) {
    .preview-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    }
    
    .gallery-dropzone {
        padding: 2rem 1rem;
    }
    
    .dropzone-content i {
        font-size: 3rem;
    }
    
    .itinerary-builder {
        padding: 1rem;
    }
    
    .day-content {
        padding: 1rem;
    }
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
    
    // Language Tabs
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
    
    // Duration Selection Logic
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
    
    // Itinerary Management
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
                <h5>Day ${dayNumber}</h5>
                <button type="button" class="btn btn-sm btn-danger remove-day">
                    <i class="material-icons">delete</i>
                </button>
            </div>
            <div class="day-content">
                <div class="form-group">
                    <label>Day Title</label>
                    <input type="text" name="details[${langId}][itinerary][${dayNumber}][title]" class="form-control" placeholder="e.g., Arrival & City Tour">
                </div>
                <div class="form-group">
                    <label>Day Description</label>
                    <textarea name="details[${langId}][itinerary][${dayNumber}][description]" class="form-control" rows="4" placeholder="Describe what happens on this day..."></textarea>
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
            day.querySelector('.day-header h5').textContent = `Day ${dayNumber}`;
            
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
    
    // Featured Image Preview
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
    
    // Gallery Upload System - FIXED VERSION
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
        selectImagesBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            galleryInput.click();
        });
        
        galleryDropzone.addEventListener('click', function(e) {
            if (e.target === galleryDropzone || e.target.closest('.dropzone-content')) {
                galleryInput.click();
            }
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
            if (e.dataTransfer.types.includes('Files')) {
                dragCounter++;
                if (dragCounter === 1) {
                    showDropOverlay();
                }
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
        
        galleryDropzone.addEventListener('dragenter', function(e) {
            e.preventDefault();
            galleryDropzone.classList.add('dragover');
        });
        
        galleryDropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            galleryDropzone.classList.add('dragover');
        });
        
        galleryDropzone.addEventListener('dragleave', function(e) {
            // Only remove dragover if we're actually leaving the dropzone
            if (!galleryDropzone.contains(e.relatedTarget)) {
                galleryDropzone.classList.remove('dragover');
            }
        });
        
        galleryDropzone.addEventListener('drop', function(e) {
            e.preventDefault();
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
                        <h3>Drop Images Here</h3>
                        <p>Release to upload images</p>
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
                previewSection.style.display = 'none';
                return;
            }
            
            previewSection.style.display = 'block';
            previewGrid.innerHTML = '';
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewItem = document.createElement('div');
                    previewItem.className = 'preview-item';
                    previewItem.innerHTML = `
                        <div class="preview-image-container">
                            <img src="${e.target.result}" alt="${file.name}">
                            <div class="preview-actions">
                                <button type="button" class="preview-action-btn delete-btn" onclick="removeFile(${index})">
                                    <i class="material-icons">delete</i>
                                </button>
                            </div>
                        </div>
                        <div class="preview-info">
                            <div class="preview-filename">${file.name}</div>
                            <div class="preview-filesize">${formatFileSize(file.size)}</div>
                        </div>
                    `;
                    previewGrid.appendChild(previewItem);
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
    
    // Initialize rich text editors
    const editors = document.querySelectorAll('.editor');
    
    if (editors.length > 0 && typeof ClassicEditor !== 'undefined') {
        editors.forEach(editor => {
            ClassicEditor.create(editor)
                .catch(error => {
                    console.error(error);
                });
        });
    }
});
</script>