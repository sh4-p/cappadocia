<?php
/**
 * Admin Login View
 */
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang ?? 'tr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $settings['site_title']; ?> - <?php _e('admin_login'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo $imgUrl; ?>/favicon.ico" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #FF6B6B;
            --secondary-color: #4ECDC4;
            --dark-color: #2C3E50;
            --white-color: #ffffff;
            --border-radius-sm: 4px;
            --border-radius-md: 8px;
            --border-radius-lg: 12px;
            --spacing-sm: 0.5rem;
            --spacing-md: 1rem;
            --spacing-lg: 1.5rem;
            --spacing-xl: 2rem;
            --font-size-sm: 0.875rem;
            --font-size-md: 1rem;
            --font-size-lg: 1.125rem;
            --font-size-xl: 1.5rem;
            --font-weight-regular: 400;
            --font-weight-medium: 500;
            --font-weight-bold: 700;
            --transition-fast: 0.2s ease;
            --transition-medium: 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #2B2D42 0%, #3a3d5c 50%, #2B2D42 100%);
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 20% 20%, rgba(255, 107, 107, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(78, 205, 196, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 40% 70%, rgba(255, 230, 109, 0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        
        .login-card {
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            box-shadow: 
                0 8px 32px rgba(0, 0, 0, 0.3),
                0 2px 16px rgba(255, 107, 107, 0.1);
            border-radius: var(--border-radius-lg);
            padding: var(--spacing-xl);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
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
            font-weight: var(--font-weight-bold);
        }
        
        .alert {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: var(--border-radius-md);
            position: relative;
        }

        .alert-error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert .material-icons {
            font-size: 20px;
        }
        
        .login-form {
            margin-top: var(--spacing-xl);
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        .form-label {
            display: block;
            margin-bottom: var(--spacing-sm);
            font-weight: var(--font-weight-medium);
            color: var(--dark-color);
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
            z-index: 2;
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e5e9;
            border-radius: var(--border-radius-md);
            font-size: var(--font-size-md);
            transition: border-color var(--transition-fast);
            background-color: var(--white-color);
        }

        .input-with-icon .form-control {
            padding-left: 3rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(255, 107, 107, 0.1);
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            margin: 0;
            accent-color: var(--primary-color);
        }

        .form-check-label {
            font-size: var(--font-size-sm);
            color: var(--dark-color);
            cursor: pointer;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-sm);
            padding: 12px 24px;
            border: none;
            border-radius: var(--border-radius-md);
            font-size: var(--font-size-md);
            font-weight: var(--font-weight-medium);
            text-decoration: none;
            cursor: pointer;
            transition: all var(--transition-fast);
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white-color);
        }

        .btn-primary:hover {
            background-color: #ff5252;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 107, 0.3);
        }
        
        .btn-block {
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
            color: var(--dark-color);
            text-decoration: none;
            transition: color var(--transition-fast);
        }

        .back-to-site:hover {
            color: var(--primary-color);
        }

        /* Responsive */
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            
            .login-card {
                padding: var(--spacing-lg);
            }
        }
    </style>
</head>
<body>
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
</body>
</html>