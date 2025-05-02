<?php
/**
 * Admin Gallery Edit View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('edit_image'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/gallery" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_gallery'); ?></span>
        </a>
    </div>
</div>

<form action="<?php echo $adminUrl; ?>/gallery/edit/<?php echo $gallery['id']; ?>" method="post" enctype="multipart/form-data" class="gallery-form">
    <input type="hidden" name="id" value="<?php echo $gallery['id']; ?>">
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('image_details'); ?></h3>
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
                            <?php $details = $galleryDetails[$lang['id']] ?? []; ?>
                            <div class="language-tab-content <?php echo $lang['code'] === $currentLang ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                <div class="form-group">
                                    <label for="title_<?php echo $lang['code']; ?>" class="form-label"><?php _e('title'); ?></label>
                                    <input type="text" id="title_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][title]" class="form-control" value="<?php echo htmlspecialchars($details['title'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label for="description_<?php echo $lang['code']; ?>" class="form-label"><?php _e('description'); ?></label>
                                    <textarea id="description_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][description]" class="form-control" rows="5"><?php echo htmlspecialchars($details['description'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Gallery Image -->
                    <div class="form-group">
                        <label for="image" class="form-label"><?php _e('image'); ?></label>
                        <div class="image-preview">
                            <img src="<?php echo $uploadsUrl . '/gallery/' . $gallery['image']; ?>" alt="<?php echo $gallery['title']; ?>" id="preview_image">
                        </div>
                        <div class="mt-3">
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                            <small class="form-text"><?php _e('image_change_help'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Image Options -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('image_options'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="tour_id" class="form-label"><?php _e('related_tour'); ?></label>
                        <select id="tour_id" name="tour_id" class="form-select">
                            <option value=""><?php _e('none'); ?></option>
                            <?php foreach ($tours as $tour): ?>
                                <option value="<?php echo $tour['id']; ?>" <?php echo $gallery['tour_id'] == $tour['id'] ? 'selected' : ''; ?>><?php echo $tour['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="order_number" class="form-label"><?php _e('order'); ?></label>
                        <input type="number" id="order_number" name="order_number" class="form-control" value="<?php echo $gallery['order_number']; ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" <?php echo $gallery['is_active'] ? 'checked' : ''; ?>>
                            <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Image Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('image_info'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <div class="info-label"><?php _e('file_name'); ?></div>
                        <div class="info-value"><?php echo $gallery['image']; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><?php _e('created_at'); ?></div>
                        <div class="info-value"><?php echo date('d M Y', strtotime($gallery['created_at'])); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><?php _e('updated_at'); ?></div>
                        <div class="info-value"><?php echo date('d M Y', strtotime($gallery['updated_at'])); ?></div>
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
        <a href="<?php echo $adminUrl; ?>/gallery" class="btn btn-light">
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
    height: 300px;
    border-radius: var(--border-radius-md);
    overflow: hidden;
    background-color: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: var(--spacing-md);
}

#preview_image {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.form-actions {
    margin-top: var(--spacing-lg);
    display: flex;
    gap: var(--spacing-md);
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--gray-200);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: var(--gray-600);
}

.info-value {
    font-weight: var(--font-weight-medium);
    word-break: break-all;
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
    const imageInput = document.getElementById('image');
    const previewImage = document.getElementById('preview_image');
    
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
});
</script>