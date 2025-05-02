<?php
/**
 * Admin Language Create View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('add_language'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/languages" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_languages'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo $adminUrl; ?>/languages/create" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="code" class="form-label"><?php _e('language_code'); ?> <span class="required">*</span></label>
                        <input type="text" id="code" name="code" class="form-control" placeholder="en" maxlength="2" required>
                        <small class="form-text"><?php _e('language_code_help'); ?></small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name" class="form-label"><?php _e('language_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="English" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="flag" class="form-label"><?php _e('flag'); ?> <span class="required">*</span></label>
                <div class="flag-preview">
                    <img src="<?php echo $imgUrl; ?>/no-image.jpg" alt="<?php _e('flag_preview'); ?>" id="flag_preview">
                </div>
                <div class="mt-3">
                    <input type="file" id="flag" name="flag" class="form-control" accept="image/*" required>
                    <small class="form-text"><?php _e('flag_help'); ?></small>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="is_default" name="is_default" value="1" class="form-check-input">
                    <label for="is_default" class="form-check-label"><?php _e('default_language'); ?></label>
                    <small class="form-text"><?php _e('default_language_help'); ?></small>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" checked>
                    <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="material-icons">save</i>
                    <?php _e('save_language'); ?>
                </button>
                <a href="<?php echo $adminUrl; ?>/languages" class="btn btn-light">
                    <i class="material-icons">cancel</i>
                    <?php _e('cancel'); ?>
                </a>
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
}

.flag-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.form-actions {
    margin-top: var(--spacing-xl);
    display: flex;
    gap: var(--spacing-md);
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
    
    // Auto-uppercase language code
    const codeInput = document.getElementById('code');
    
    if (codeInput) {
        codeInput.addEventListener('input', function() {
            this.value = this.value.toLowerCase();
        });
    }
});
</script>