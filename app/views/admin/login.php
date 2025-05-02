<?php
/**
 * Admin Login View
 */

?>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <img src="<?php echo $imgUrl; ?>/logo.png" alt="<?php echo $settings['site_title']; ?>" class="login-logo">
            <h1 class="login-title"><?php _e('admin_login'); ?></h1>
        </div>
        
        <?php if ($session->hasFlash('error')): ?>
            <div class="alert alert-error">
                <i class="material-icons">error</i>
                <span><?php echo $session->getFlash('error'); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <i class="material-icons">check_circle</i>
                <span><?php echo $session->getFlash('success'); ?></span>
            </div>
        <?php endif; ?>
        
        <form method="post" action="<?php echo $adminUrl; ?>/login" class="login-form">
            <div class="form-group">
                <label for="username" class="form-label"><?php _e('username'); ?></label>
                <div class="input-with-icon">
                    <i class="material-icons">person</i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="<?php _e('enter_username'); ?>" required autofocus>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label"><?php _e('password'); ?></label>
                <div class="input-with-icon">
                    <i class="material-icons">lock</i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="<?php _e('enter_password'); ?>" required>
                </div>
            </div>
            
            <div class="form-group remember-me">
                <div class="form-check">
                    <input type="checkbox" id="remember_me" name="remember_me" class="form-check-input">
                    <label for="remember_me" class="form-check-label"><?php _e('remember_me'); ?></label>
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="material-icons">login</i>
                    <?php _e('login'); ?>
                </button>
            </div>
        </form>
        
        <div class="login-footer">
            <a href="<?php echo $appUrl; ?>" class="back-to-site">
                <i class="material-icons">arrow_back</i>
                <?php _e('back_to_site'); ?>
            </a>
        </div>
    </div>
</div>

<style>
    body {
        background-color: #f5f5f5;
        background-image: url('<?php echo $imgUrl; ?>/login-bg.jpg');
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .login-container {
        width: 100%;
        max-width: 400px;
    }
    
    .login-card {
        background-color: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-xl);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    
    .login-header {
        text-align: center;
        margin-bottom: var(--spacing-xl);
    }
    
    .login-logo {
        height: 60px;
        margin-bottom: var(--spacing-md);
    }
    
    .login-title {
        font-size: var(--font-size-xl);
        margin-bottom: 0;
        color: var(--primary-color);
    }
    
    .login-form {
        margin-top: var(--spacing-xl);
    }
    
    .input-with-icon {
        position: relative;
    }
    
    .input-with-icon i {
        position: absolute;
        top: 50%;
        left: 1rem;
        transform: translateY(-50%);
        color: #999;
    }
    
    .input-with-icon input {
        padding-left: 3rem;
    }
    
    .btn-block {
        display: block;
        width: 100%;
    }
    
    .remember-me {
        margin-bottom: var(--spacing-lg);
    }
    
    .login-footer {
        margin-top: var(--spacing-lg);
        text-align: center;
    }
    
    .back-to-site {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: var(--font-size-sm);
    }
    
    .alert {
        margin-bottom: var(--spacing-lg);
    }
</style>