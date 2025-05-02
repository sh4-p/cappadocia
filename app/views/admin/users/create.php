<?php
/**
 * Admin User Create View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('add_user'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/users" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_users'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo $adminUrl; ?>/users/create" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name" class="form-label"><?php _e('first_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name" class="form-label"><?php _e('last_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" class="form-control" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username" class="form-label"><?php _e('username'); ?> <span class="required">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label"><?php _e('email'); ?> <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="form-label"><?php _e('password'); ?> <span class="required">*</span></label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirm" class="form-label"><?php _e('confirm_password'); ?> <span class="required">*</span></label>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role" class="form-label"><?php _e('role'); ?> <span class="required">*</span></label>
                        <select id="role" name="role" class="form-select" required>
                            <?php foreach ($roles as $roleKey => $roleName): ?>
                                <option value="<?php echo $roleKey; ?>"><?php echo $roleName; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="avatar" class="form-label"><?php _e('avatar'); ?></label>
                        <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                    </div>
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
                    <?php _e('save_user'); ?>
                </button>
                <a href="<?php echo $adminUrl; ?>/users" class="btn btn-light">
                    <i class="material-icons">cancel</i>
                    <?php _e('cancel'); ?>
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.form-actions {
    margin-top: var(--spacing-lg);
    display: flex;
    gap: var(--spacing-md);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    
    form.addEventListener('submit', function(e) {
        if (password.value !== passwordConfirm.value) {
            e.preventDefault();
            alert("<?php _e('passwords_dont_match'); ?>");
            passwordConfirm.focus();
        }
    });
});
</script>