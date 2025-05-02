<?php
/**
 * Admin Page Edit View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('edit_page'); ?>: <?php echo $page['title']; ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/pages" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_pages'); ?></span>
        </a>
        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/page/<?php echo $page['slug']; ?>" class="btn btn-light" target="_blank">
            <i class="material-icons">visibility</i>
            <span><?php _e('view_page'); ?></span>
        </a>
    </div>
</div>

<form action="<?php echo $adminUrl; ?>/pages/edit/<?php echo $page['id']; ?>" method="post" class="page-form">
    <input type="hidden" name="id" value="<?php echo $page['id']; ?>">
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('page_details'); ?></h3>
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
                            <?php $details = $pageDetails[$lang['id']] ?? []; ?>
                            <div class="language-tab-content <?php echo $lang['code'] === $currentLang ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                <div class="form-group">
                                    <label for="title_<?php echo $lang['code']; ?>" class="form-label"><?php _e('title'); ?> <span class="required">*</span></label>
                                    <input type="text" id="title_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][title]" class="form-control" value="<?php echo htmlspecialchars($details['title'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="slug_<?php echo $lang['code']; ?>" class="form-label"><?php _e('slug'); ?></label>
                                    <input type="text" id="slug_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][slug]" class="form-control" value="<?php echo htmlspecialchars($details['slug'] ?? ''); ?>">
                                    <small class="form-text"><?php _e('slug_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="content_<?php echo $lang['code']; ?>" class="form-label"><?php _e('content'); ?></label>
                                    <textarea id="content_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][content]" class="form-control editor" rows="15"><?php echo htmlspecialchars($details['content'] ?? ''); ?></textarea>
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
            <!-- Page Options -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('page_options'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="template" class="form-label"><?php _e('template'); ?></label>
                        <select id="template" name="template" class="form-select">
                            <?php foreach ($templates as $key => $name): ?>
                                <option value="<?php echo $key; ?>" <?php echo $page['template'] === $key ? 'selected' : ''; ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text"><?php _e('template_help'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="order_number" class="form-label"><?php _e('order'); ?></label>
                        <input type="number" id="order_number" name="order_number" class="form-control" value="<?php echo $page['order_number']; ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" <?php echo $page['is_active'] ? 'checked' : ''; ?>>
                            <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('page_info'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="page-info">
                        <div class="info-item">
                            <div class="info-label"><?php _e('id'); ?></div>
                            <div class="info-value"><?php echo $page['id']; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><?php _e('created_at'); ?></div>
                            <div class="info-value"><?php echo date('d M Y, H:i', strtotime($page['created_at'])); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><?php _e('updated_at'); ?></div>
                            <div class="info-value"><?php echo date('d M Y, H:i', strtotime($page['updated_at'])); ?></div>
                        </div>
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
        <a href="<?php echo $adminUrl; ?>/pages" class="btn btn-light">
            <i class="material-icons">cancel</i>
            <?php _e('cancel'); ?>
        </a>
        
        <a href="<?php echo $adminUrl; ?>/pages/delete/<?php echo $page['id']; ?>" class="btn btn-danger ms-auto delete-btn" data-confirm="<?php _e('delete_page_confirm'); ?>">
            <i class="material-icons">delete</i>
            <?php _e('delete_page'); ?>
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

.page-info {
    display: grid;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
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
}

.form-actions {
    margin-top: var(--spacing-lg);
    display: flex;
    gap: var(--spacing-md);
}

.ms-auto {
    margin-left: auto;
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
    
    // Initialize rich text editors
    const editors = document.querySelectorAll('.editor');
    
    if (editors.length > 0 && typeof ClassicEditor !== 'undefined') {
        editors.forEach(editor => {
            ClassicEditor.create(editor)
                .catch(error => {
                    console.error('Error initializing editor:', error);
                });
        });
    }
    
    // Delete confirmation
    const deleteBtn = document.querySelector('.delete-btn');
    
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmMessage = this.dataset.confirm || '<?php _e("delete_confirm"); ?>';
            
            if (confirm(confirmMessage)) {
                window.location.href = this.getAttribute('href');
            }
        });
    }
});
</script>