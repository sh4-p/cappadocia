<?php
/**
 * Admin User Edit View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('edit_user'); ?>: <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/users" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_users'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo $adminUrl; ?>/users/edit/<?php echo $user['id']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="first_name" class="form-label"><?php _e('first_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="last_name" class="form-label"><?php _e('last_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username" class="form-label"><?php _e('username'); ?> <span class="required">*</span></label>
                        <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email" class="form-label"><?php _e('email'); ?> <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password" class="form-label"><?php _e('password'); ?></label>
                        <input type="password" id="password" name="password" class="form-control">
                        <small class="form-text"><?php _e('password_edit_help'); ?></small>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirm" class="form-label"><?php _e('confirm_password'); ?></label>
                        <input type="password" id="password_confirm" name="password_confirm" class="form-control">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role" class="form-label"><?php _e('role'); ?> <span class="required">*</span></label>
                        <select id="role" name="role" class="form-select" required <?php echo $user['id'] == $session->get('user_id') ? 'disabled' : ''; ?>>
                            <?php foreach ($roles as $roleKey => $roleName): ?>
                                <option value="<?php echo $roleKey; ?>" <?php echo $user['role'] == $roleKey ? 'selected' : ''; ?>><?php echo $roleName; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php if ($user['id'] == $session->get('user_id')): ?>
                            <small class="form-text"><?php _e('cannot_change_own_role'); ?></small>
                            <input type="hidden" name="role" value="<?php echo $user['role']; ?>">
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="avatar" class="form-label"><?php _e('avatar'); ?></label>
                        <div class="avatar-preview">
                            <?php if ($user['avatar']): ?>
                                <img src="<?php echo $uploadsUrl . '/avatars/' . $user['avatar']; ?>" alt="<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>" id="avatar_preview">
                                <a href="<?php echo $adminUrl; ?>/users/remove-avatar/<?php echo $user['id']; ?>" class="remove-avatar" title="<?php _e('remove_avatar'); ?>">
                                    <i class="material-icons">close</i>
                                </a>
                            <?php else: ?>
                                <div class="avatar-placeholder" id="avatar_preview_placeholder">
                                    <span><?php echo substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1); ?></span>
                                </div>
                                <img src="" alt="" id="avatar_preview" style="display: none;">
                            <?php endif; ?>
                        </div>
                        <div class="mt-3">
                            <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" <?php echo $user['is_active'] ? 'checked' : ''; ?> <?php echo $user['id'] == $session->get('user_id') ? 'disabled' : ''; ?>>
                    <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                    <?php if ($user['id'] == $session->get('user_id')): ?>
                        <small class="form-text"><?php _e('cannot_deactivate_own_account'); ?></small>
                        <input type="hidden" name="is_active" value="1">
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="user-info-box">
                <div class="info-row">
                    <div class="info-label"><?php _e('created_at'); ?></div>
                    <div class="info-value"><?php echo date('d M Y, H:i', strtotime($user['created_at'])); ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label"><?php _e('last_login'); ?></div>
                    <div class="info-value"><?php echo $user['last_login'] ? date('d M Y, H:i', strtotime($user['last_login'])) : __('never'); ?></div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="material-icons">save</i>
                    <?php _e('save_changes'); ?>
                </button>
                <a href="<?php echo $adminUrl; ?>/users" class="btn btn-light">
                    <i class="material-icons">cancel</i>
                    <?php _e('cancel'); ?>
                </a>
                
                <?php if ($user['id'] != $session->get('user_id')): ?>
                    <a href="<?php echo $adminUrl; ?>/users/delete/<?php echo $user['id']; ?>" class="btn btn-danger delete-btn ms-auto" data-confirm="<?php _e('delete_user_confirm'); ?>">
                        <i class="material-icons">delete</i>
                        <?php _e('delete_user'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<style>
.avatar-preview {
    width: 100%;
    height: 150px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--gray-100);
    border-radius: var(--border-radius-md);
    overflow: hidden;
}

.avatar-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100px;
    height: 100px;
    border-radius: var(--border-radius-circle);
    background-color: var(--primary-color);
    color: var(--white-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: var(--font-weight-bold);
}

.remove-avatar {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 30px;
    height: 30px;
    border-radius: var(--border-radius-circle);
    background-color: rgba(0, 0, 0, 0.5);
    color: var(--white-color);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color var(--transition-fast);
}

.remove-avatar:hover {
    background-color: rgba(0, 0, 0, 0.7);
}

.user-info-box {
    background-color: var(--gray-100);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-200);
}

.info-row:last-child {
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
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const passwordConfirm = document.getElementById('password_confirm');
    
    form.addEventListener('submit', function(e) {
        if (password.value !== '' && password.value !== passwordConfirm.value) {
            e.preventDefault();
            alert("<?php _e('passwords_dont_match'); ?>");
            passwordConfirm.focus();
        }
    });
    
    // Avatar Preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar_preview');
    const avatarPlaceholder = document.getElementById('avatar_preview_placeholder');
    
    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    avatarPreview.src = e.target.result;
                    avatarPreview.style.display = 'block';
                    
                    if (avatarPlaceholder) {
                        avatarPlaceholder.style.display = 'none';
                    }
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