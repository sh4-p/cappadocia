<?php
/**
 * Admin Category Edit View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('edit_category'); ?>: <?php echo $category['name']; ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/categories" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_categories'); ?></span>
        </a>
        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours?category=<?php echo $category['slug']; ?>" class="btn btn-light" target="_blank">
            <i class="material-icons">visibility</i>
            <span><?php _e('view_category'); ?></span>
        </a>
    </div>
</div>

<form action="<?php echo $adminUrl; ?>/categories/edit/<?php echo $category['id']; ?>" method="post" enctype="multipart/form-data" class="category-form">
    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('category_details'); ?></h3>
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
                            <?php $details = $categoryDetails[$lang['id']] ?? []; ?>
                            <div class="language-tab-content <?php echo $lang['code'] === $currentLang ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                <div class="form-group">
                                    <label for="name_<?php echo $lang['code']; ?>" class="form-label"><?php _e('category_name'); ?> <span class="required">*</span></label>
                                    <input type="text" id="name_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][name]" class="form-control" value="<?php echo htmlspecialchars($details['name'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="slug_<?php echo $lang['code']; ?>" class="form-label"><?php _e('slug'); ?></label>
                                    <input type="text" id="slug_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][slug]" class="form-control" value="<?php echo htmlspecialchars($details['slug'] ?? ''); ?>">
                                    <small class="form-text"><?php _e('slug_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description_<?php echo $lang['code']; ?>" class="form-label"><?php _e('description'); ?></label>
                                    <textarea id="description_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][description]" class="form-control" rows="5"><?php echo htmlspecialchars($details['description'] ?? ''); ?></textarea>
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
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Category Options -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('category_options'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="parent_id" class="form-label"><?php _e('parent_category'); ?></label>
                        <select id="parent_id" name="parent_id" class="form-select">
                            <option value=""><?php _e('none'); ?></option>
                            <?php foreach ($parentCategories as $parentCategory): ?>
                                <?php if ($parentCategory['id'] != $category['id']): ?>
                                    <option value="<?php echo $parentCategory['id']; ?>" <?php echo $category['parent_id'] == $parentCategory['id'] ? 'selected' : ''; ?>><?php echo $parentCategory['name']; ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="order_number" class="form-label"><?php _e('order'); ?></label>
                        <input type="number" id="order_number" name="order_number" class="form-control" value="<?php echo $category['order_number']; ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" <?php echo $category['is_active'] ? 'checked' : ''; ?>>
                            <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Category Image -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('category_image'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="image-preview">
                            <?php if ($category['image']): ?>
                                <img src="<?php echo $uploadsUrl . '/categories/' . $category['image']; ?>" alt="<?php echo $category['name']; ?>" class="preview-image">
                            <?php else: ?>
                                <img src="<?php echo $imgUrl; ?>/no-image.jpg" alt="<?php _e('no_image'); ?>" class="preview-image">
                            <?php endif; ?>
                        </div>
                        <div class="mt-3">
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                            <small class="form-text"><?php _e('image_help'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Category Stats -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('category_stats'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('tours'); ?></div>
                        <div class="stat-value"><?php echo $category['tour_count']; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('created_at'); ?></div>
                        <div class="stat-value"><?php echo date('d M Y', strtotime($category['created_at'])); ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('updated_at'); ?></div>
                        <div class="stat-value"><?php echo date('d M Y', strtotime($category['updated_at'])); ?></div>
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
        <a href="<?php echo $adminUrl; ?>/categories" class="btn btn-light">
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
});
</script>