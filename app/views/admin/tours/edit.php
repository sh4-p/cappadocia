<?php
/**
 * Admin Tour Edit View
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
                                
                                <div class="form-group">
                                    <label for="itinerary_<?php echo $lang['code']; ?>" class="form-label"><?php _e('itinerary'); ?></label>
                                    <textarea id="itinerary_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][itinerary]" class="form-control" rows="10"><?php echo htmlspecialchars($details['itinerary'] ?? ''); ?></textarea>
                                    <small class="form-text"><?php _e('itinerary_help'); ?></small>
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
                    <div class="gallery-grid">
                        <?php if (empty($gallery)): ?>
                            <div class="empty-gallery">
                                <p><?php _e('no_gallery_images'); ?></p>
                                <a href="<?php echo $adminUrl; ?>/gallery/create?tour_id=<?php echo $tour['id']; ?>" class="btn btn-primary btn-sm">
                                    <i class="material-icons">add_photo_alternate</i>
                                    <?php _e('add_images'); ?>
                                </a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($gallery as $image): ?>
                                <div class="gallery-item">
                                    <div class="gallery-image">
                                        <img src="<?php echo $uploadsUrl . '/gallery/' . $image['image']; ?>" alt="<?php echo $image['title']; ?>">
                                        <div class="gallery-actions">
                                            <a href="<?php echo $adminUrl; ?>/gallery/edit/<?php echo $image['id']; ?>" class="gallery-action-btn" title="<?php _e('edit'); ?>">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="<?php echo $adminUrl; ?>/gallery/delete/<?php echo $image['id']; ?>" class="gallery-action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_image_confirm'); ?>">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="gallery-title"><?php echo $image['title'] ?: __('no_title'); ?></div>
                                </div>
                            <?php endforeach; ?>
                            <div class="gallery-add">
                                <a href="<?php echo $adminUrl; ?>/gallery/create?tour_id=<?php echo $tour['id']; ?>" class="gallery-add-btn">
                                    <i class="material-icons">add_photo_alternate</i>
                                    <span><?php _e('add_more_images'); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
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
                    
                    <div class="form-group">
                        <label for="duration" class="form-label"><?php _e('duration'); ?> <span class="required">*</span></label>
                        <input type="text" id="duration" name="duration" class="form-control" value="<?php echo htmlspecialchars($tour['duration']); ?>" placeholder="<?php _e('duration_placeholder'); ?>" required>
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
            
            <!-- Tour Image -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('featured_image'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="image-preview">
                            <?php if ($tour['featured_image']): ?>
                                <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>" class="preview-image">
                            <?php else: ?>
                                <img src="<?php echo $imgUrl; ?>/no-image.jpg" alt="<?php _e('no_image'); ?>" class="preview-image">
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
                        <div class="stat-value"><?php echo number_format($stats['bookings']); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('revenue'); ?></div>
                        <div class="stat-value"><?php echo $settings['currency_symbol'] . number_format($stats['revenue'], 2); ?></div>
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

<style>
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

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--spacing-md);
}

.gallery-item {
    border-radius: var(--border-radius-md);
    overflow: hidden;
    background-color: var(--white-color);
    box-shadow: var(--shadow-sm);
}

.gallery-image {
    position: relative;
    height: 150px;
    overflow: hidden;
}

.gallery-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.gallery-actions {
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

.gallery-image:hover .gallery-actions {
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
}

.gallery-action-btn:hover {
    background-color: var(--primary-color);
    color: var(--white-color);
}

.gallery-title {
    padding: var(--spacing-sm);
    text-align: center;
    font-size: var(--font-size-sm);
    color: var(--gray-700);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.gallery-add {
    border: 2px dashed var(--gray-300);
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    height: 150px;
}

.gallery-add-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: var(--gray-500);
    transition: color var(--transition-fast);
}

.gallery-add-btn:hover {
    color: var(--primary-color);
}

.gallery-add-btn i {
    font-size: 2rem;
    margin-bottom: var(--spacing-xs);
}

.empty-gallery {
    text-align: center;
    padding: var(--spacing-lg) 0;
    color: var(--gray-500);
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

@media (max-width: 992px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Language Tabs
    const langTabBtns = document.querySelectorAll('.language-tab-btn');
    const langTabContents = document.querySelectorAll('.language-tab-content');
    
    langTabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const lang = this.dataset.lang;
            
            // Deactivate all tabs
            langTabBtns.forEach(btn => btn.classList.remove('active'));
            langTabContents.forEach(content => content.classList.remove('active'));
            
            // Activate selected tab
            this.classList.add('active');
            document.querySelector(`.language-tab-content[data-lang="${lang}"]`).classList.add('active');
        });
    });
    
    // Image Preview
    const imageInput = document.getElementById('featured_image');
    const previewImage = document.querySelector('.preview-image');
    
    if (imageInput && previewImage) {
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                }
                
                reader.readAsDataURL(this.files[0]);
            }
        });
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
    
    // Delete confirmation
    const deleteBtns = document.querySelectorAll('.delete-btn');
    
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmMessage = this.dataset.confirm || '<?php _e("delete_confirm"); ?>';
            
            if (confirm(confirmMessage)) {
                window.location.href = this.getAttribute('href');
            }
        });
    });
});
</script>