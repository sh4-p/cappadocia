<?php
/**
 * Maintenance Mode Page
 * 
 * This page is shown when maintenance mode is enabled
 */
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $pageTitle; ?></title>
    <meta name="description" content="<?php echo $metaDescription; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $imgUrl; ?>/favicon.ico">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #7209b7;
            --warning-color: #ff6b35;
            --text-dark: #212529;
            --text-light: #6c757d;
            --bg-light: #f8f9fa;
            --white: #ffffff;
            --border-radius: 12px;
            --shadow: 0 10px 30px rgba(67, 97, 238, 0.1);
            --shadow-lg: 0 20px 60px rgba(67, 97, 238, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, var(--bg-light) 0%, #e3f2fd 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-dark);
            line-height: 1.6;
        }

        .maintenance-container {
            background: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            padding: 60px 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
            position: relative;
            overflow: hidden;
            word-wrap: break-word;
        }

        .maintenance-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
        }

        .maintenance-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, var(--warning-color), #ff8c42);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .maintenance-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 20px;
            letter-spacing: -0.02em;
        }

        .maintenance-subtitle {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 30px;
            font-weight: 500;
        }

        .maintenance-message {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 20px;
            line-height: 1.7;
        }

        .maintenance-features {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 1px solid var(--primary-color);
            border-radius: var(--border-radius);
            padding: 30px;
            margin: 40px 0;
            text-align: left;
            width: 100%;
            box-sizing: border-box;
        }

        .maintenance-features h3 {
            color: var(--text-dark);
            margin-bottom: 20px;
            font-size: 1.25rem;
            text-align: center;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .feature-list li {
            padding: 12px 0;
            display: flex;
            align-items: flex-start;
            color: var(--text-light);
            word-wrap: break-word;
            line-height: 1.5;
        }

        .feature-list li .material-icons {
            color: var(--primary-color);
            margin-right: 12px;
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .feature-list li span {
            flex: 1;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .admin-login-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--white);
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .admin-login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
            text-decoration: none;
            color: var(--white);
        }

        .social-links {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e9ecef;
        }

        .social-links h4 {
            color: var(--text-dark);
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        .social-icon {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--bg-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: var(--primary-color);
            color: var(--white);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .maintenance-container {
                padding: 40px 30px;
            }

            .maintenance-title {
                font-size: 2rem;
            }

            .maintenance-subtitle {
                font-size: 1.1rem;
            }

            .maintenance-message {
                font-size: 1rem;
            }

            .maintenance-features {
                padding: 25px 20px;
                margin: 30px 0;
            }

            .feature-list li {
                padding: 10px 0;
            }

            .feature-list li .material-icons {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .maintenance-container {
                padding: 30px 20px;
            }

            .maintenance-title {
                font-size: 1.75rem;
            }

            .maintenance-icon {
                width: 80px;
                height: 80px;
                font-size: 36px;
            }

            .maintenance-features {
                padding: 20px 15px;
            }

            .feature-list li {
                padding: 8px 0;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <i class="material-icons" style="color: white; font-size: 48px;">build</i>
        </div>
        
        <h1 class="maintenance-title"><?php echo __('maintenance_mode_title'); ?></h1>
        <p class="maintenance-subtitle"><?php echo __('maintenance_mode_subtitle'); ?></p>
        
        <p class="maintenance-message">
            <?php echo __('maintenance_mode_message'); ?>
        </p>
        
        <p class="maintenance-message">
            <?php echo __('maintenance_mode_back_soon'); ?>
        </p>

        <div class="maintenance-features">
            <h3><?php echo __('maintenance_what_we_doing'); ?></h3>
            <ul class="feature-list">
                <li>
                    <i class="material-icons">speed</i>
                    <span><?php echo __('maintenance_feature_performance'); ?></span>
                </li>
                <li>
                    <i class="material-icons">security</i>
                    <span><?php echo __('maintenance_feature_security'); ?></span>
                </li>
                <li>
                    <i class="material-icons">auto_awesome</i>
                    <span><?php echo __('maintenance_feature_new_features'); ?></span>
                </li>
                <li>
                    <i class="material-icons">bug_report</i>
                    <span><?php echo __('maintenance_feature_bug_fixes'); ?></span>
                </li>
            </ul>
        </div>

        <?php if (!empty($settings['facebook']) || !empty($settings['instagram']) || !empty($settings['twitter'])): ?>
        <div class="social-links">
            <h4><?php echo __('maintenance_follow_us'); ?></h4>
            <div class="social-icons">
                <?php if (!empty($settings['facebook'])): ?>
                <a href="<?php echo $settings['facebook']; ?>" target="_blank" class="social-icon">
                    <i class="material-icons">facebook</i>
                </a>
                <?php endif; ?>
                
                <?php if (!empty($settings['instagram'])): ?>
                <a href="<?php echo $settings['instagram']; ?>" target="_blank" class="social-icon">
                    <i class="material-icons">camera_alt</i>
                </a>
                <?php endif; ?>
                
                <?php if (!empty($settings['twitter'])): ?>
                <a href="<?php echo $settings['twitter']; ?>" target="_blank" class="social-icon">
                    <i class="material-icons">alternate_email</i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html>