<?php
/**
 * Admin Tour Create View
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
                                
                                <div class="form-group">
                                    <label for="itinerary_<?php echo $lang['code']; ?>" class="form-label"><?php _e('itinerary'); ?></label>
                                    <textarea id="itinerary_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][itinerary]" class="form-control" rows="10"></textarea>
                                    <small class="form-text"><?php _e('itinerary_help'); ?></small>
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
                    
                    <div class="form-group">
                        <label for="duration" class="form-label"><?php _e('duration'); ?> <span class="required">*</span></label>
                        <input type="text" id="duration" name="duration" class="form-control" placeholder="<?php _e('duration_placeholder'); ?>" required>
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
            
            <!-- Tour Image -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('featured_image'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="image-preview">
                            <img src="<?php echo $imgUrl; ?>/no-image.jpg" alt="<?php _e('preview'); ?>" class="preview-image">
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
});
</script>