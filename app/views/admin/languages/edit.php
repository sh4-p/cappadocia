<?php
/**
 * Admin Language Edit View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('edit_language'); ?>: <?php echo $language['name']; ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/languages" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_languages'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo $adminUrl; ?>/languages/edit/<?php echo $language['id']; ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code" class="form-label"><?php _e('language_code'); ?></label>
                        <input type="text" id="code" name="code" class="form-control" value="<?php echo $language['code']; ?>" readonly>
                        <small class="form-text"><?php _e('language_code_help_edit'); ?></small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label"><?php _e('language_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($language['name']); ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="flag" class="form-label"><?php _e('flag'); ?></label>
                <div class="flag-preview">
                    <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $language['flag']; ?>" alt="<?php echo $language['name']; ?>" id="flag_preview">
                </div>
                <div class="mt-3">
                    <input type="file" id="flag" name="flag" class="form-control" accept="image/*">
                    <small class="form-text"><?php _e('flag_help_edit'); ?></small>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="is_default" name="is_default" value="1" class="form-check-input" <?php echo $language['is_default'] ? 'checked' : ''; ?>>
                    <label for="is_default" class="form-check-label"><?php _e('default_language'); ?></label>
                    <small class="form-text"><?php _e('default_language_help'); ?></small>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" <?php echo $language['is_active'] ? 'checked' : ''; ?> <?php echo $language['is_default'] ? 'disabled' : ''; ?>>
                    <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                    <?php if ($language['is_default']): ?>
                        <small class="form-text"><?php _e('default_language_always_active'); ?></small>
                        <input type="hidden" name="is_active" value="1">
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="language-info">
                <div class="info-item">
                    <div class="info-label"><?php _e('created_at'); ?></div>
                    <div class="info-value"><?php echo date('d M Y, H:i', strtotime($language['created_at'])); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label"><?php _e('updated_at'); ?></div>
                    <div class="info-value"><?php echo date('d M Y, H:i', strtotime($language['updated_at'])); ?></div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="material-icons">save</i>
                    <?php _e('save_changes'); ?>
                </button>
                <a href="<?php echo $adminUrl; ?>/languages" class="btn btn-light">
                    <i class="material-icons">cancel</i>
                    <?php _e('cancel'); ?>
                </a>
                
                <?php if (!$language['is_default']): ?>
                    <a href="<?php echo $adminUrl; ?>/languages/delete/<?php echo $language['id']; ?>" class="btn btn-danger ms-auto delete-btn" data-confirm="<?php _e('delete_language_confirm'); ?>">
                        <i class="material-icons">delete</i>
                        <?php _e('delete_language'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<style>
.flag-preview {
    width: 200px;
    height: 120px;
    border-radius: var(--border-radius-md);
    overflow: hidden;
    background-color: var(--gray-100);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: var(--spacing-md);
    border: 1px solid var(--gray-300);
}

.flag-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.language-info {
    margin-top: var(--spacing-lg);
    background-color: var(--gray-100);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
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
    margin-top: var(--spacing-xl);
    display: flex;
    gap: var(--spacing-md);
}

.ms-auto {
    margin-left: auto;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Flag preview
    const flagInput = document.getElementById('flag');
    const flagPreview = document.getElementById('flag_preview');
    
    if (flagInput && flagPreview) {
        flagInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    flagPreview.src = e.target.result;
                }
                
                reader.readAsDataURL(this.files[0]);
            }
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