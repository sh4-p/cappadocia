<?php
/**
 * Admin Profile View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('profile'); ?></h1>
</div>

<div class="row">
    <div class="col-md-4">
        <!-- Profile Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php _e('profile_info'); ?></h3>
            </div>
            <div class="card-body">
                <div class="profile-avatar">
                    <div class="avatar-circle">
                        <?php if ($user['avatar']): ?>
                            <img src="<?php echo $uploadsUrl . '/avatars/' . $user['avatar']; ?>" alt="<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>">
                        <?php else: ?>
                            <span class="avatar-initials"><?php echo substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1); ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="profile-details">
                    <div class="profile-name"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></div>
                    <div class="profile-role"><?php echo ucfirst($user['role']); ?></div>
                    <div class="profile-email"><?php echo $user['email']; ?></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Edit Profile -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php _e('edit_profile'); ?></h3>
            </div>
            <div class="card-body">
                <form action="<?php echo $adminUrl; ?>/profile" method="post" enctype="multipart/form-data" class="profile-form">
                    <div class="form-group">
                        <label for="first_name" class="form-label"><?php _e('first_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="last_name" class="form-label"><?php _e('last_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label"><?php _e('email'); ?> <span class="required">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="avatar" class="form-label"><?php _e('avatar'); ?></label>
                        <input type="file" id="avatar" name="avatar" class="form-control" accept="image/*">
                        <small class="form-text"><?php _e('avatar_help'); ?></small>
                    </div>
                    
                    <hr>
                    
                    <h4><?php _e('change_password'); ?></h4>
                    <p class="text-muted"><?php _e('change_password_help'); ?></p>
                    
                    <div class="form-group">
                        <label for="current_password" class="form-label"><?php _e('current_password'); ?></label>
                        <input type="password" id="current_password" name="current_password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password" class="form-label"><?php _e('new_password'); ?></label>
                        <input type="password" id="new_password" name="new_password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label"><?php _e('confirm_password'); ?></label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons">save</i>
                            <?php _e('save_changes'); ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-avatar {
    display: flex;
    justify-content: center;
    margin-bottom: var(--spacing-md);
}

.avatar-circle {
    width: 120px;
    height: 120px;
    border-radius: var(--border-radius-circle);
    overflow: hidden;
    background-color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white-color);
    font-size: 2.5rem;
    font-weight: var(--font-weight-bold);
}

.avatar-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-details {
    text-align: center;
}

.profile-name {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    margin-bottom: 0.25rem;
}

.profile-role {
    color: var(--primary-color);
    font-weight: var(--font-weight-medium);
    margin-bottom: 0.5rem;
}

.profile-email {
    color: var(--gray-600);
    font-size: var(--font-size-sm);
}

.form-actions {
    margin-top: var(--spacing-lg);
}
</style>