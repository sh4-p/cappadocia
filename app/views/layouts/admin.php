<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' : ''; ?><?php echo $settings['site_title']; ?> - <?php _e('admin_panel'); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo $imgUrl; ?>/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="<?php echo $imgUrl; ?>/favicon.ico" type="image/x-icon">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo $cssUrl; ?>/admin.css">
    
    <!-- Add any additional CSS files -->
    <?php if (isset($additionalCss) && is_array($additionalCss)): ?>
        <?php foreach ($additionalCss as $css): ?>
            <link rel="stylesheet" href="<?php echo $cssUrl . '/' . $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Flash Messages - Add this to your admin layout or view files -->

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="material-icons">check_circle</i>
            <?php echo $_SESSION['flash_success']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="material-icons">error</i>
            <?php echo $_SESSION['flash_error']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_warning'])): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="material-icons">warning</i>
            <?php echo $_SESSION['flash_warning']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['flash_warning']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['flash_info'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="material-icons">info</i>
            <?php echo $_SESSION['flash_info']; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php unset($_SESSION['flash_info']); ?>
    <?php endif; ?>        
    <!-- Admin Wrapper -->
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo $adminUrl; ?>/dashboard">
                    <img src="<?php echo $imgUrl; ?>/logo-white.png" alt="<?php echo $settings['site_title']; ?>" class="sidebar-logo">
                </a>
            </div>
            <div class="sidebar-content">
                <nav class="sidebar-nav">
                    <ul>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/dashboard" class="<?php echo $controllerName == 'Admin' && $actionName == 'dashboard' ? 'active' : ''; ?>">
                                <i class="material-icons">dashboard</i>
                                <span><?php _e('dashboard'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/tours" class="<?php echo $controllerName == 'AdminTours' ? 'active' : ''; ?>">
                                <i class="material-icons">explore</i>
                                <span><?php _e('tours'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/extras" class="<?php echo $controllerName == 'AdminExtras' ? 'active' : ''; ?>">
                                <i class="material-icons">add_circle</i>
                                <span><?php _e('tour_extras'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/categories" class="<?php echo $controllerName == 'AdminCategories' ? 'active' : ''; ?>">
                                <i class="material-icons">category</i>
                                <span><?php _e('categories'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/bookings" class="<?php echo $controllerName == 'AdminBookings' ? 'active' : ''; ?>">
                                <i class="material-icons">book_online</i>
                                <span><?php _e('bookings'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/gallery" class="<?php echo $controllerName == 'AdminGallery' ? 'active' : ''; ?>">
                                <i class="material-icons">photo_library</i>
                                <span><?php _e('gallery'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/pages" class="<?php echo $controllerName == 'AdminPages' ? 'active' : ''; ?>">
                                <i class="material-icons">description</i>
                                <span><?php _e('pages'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/testimonials" class="<?php echo $controllerName == 'AdminTestimonials' ? 'active' : ''; ?>">
                                <i class="material-icons">format_quote</i>
                                <span><?php _e('testimonials'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/email-templates" class="<?php echo $controllerName == 'AdminEmailTemplates' ? 'active' : ''; ?>">
                                <i class="material-icons">email</i>
                                <span><?php _e('email_templates'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/newsletter" class="<?php echo $controllerName == 'AdminNewsletter' ? 'active' : ''; ?>">
                                <i class="material-icons">email</i>
                                <span><?php _e('newsletter'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/antibot" class="<?php echo $controllerName == 'AdminAntiBot' ? 'active' : ''; ?>">
                                <i class="material-icons">security</i>
                                <span><?php _e('anti_bot'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/languages" class="<?php echo $controllerName == 'AdminLanguages' ? 'active' : ''; ?>">
                                <i class="material-icons">language</i>
                                <span><?php _e('languages'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/translations" class="<?php echo $controllerName == 'AdminTranslations' ? 'active' : ''; ?>">
                                <i class="material-icons">translate</i>
                                <span><?php _e('translations'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/users" class="<?php echo $controllerName == 'AdminUsers' ? 'active' : ''; ?>">
                                <i class="material-icons">people</i>
                                <span><?php _e('users'); ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo $adminUrl; ?>/settings" class="<?php echo $controllerName == 'AdminSettings' ? 'active' : ''; ?>">
                                <i class="material-icons">settings</i>
                                <span><?php _e('settings'); ?></span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle-desktop">
                        <i class="material-icons">menu</i>
                    </button>
                    <button class="sidebar-toggle-mobile">
                        <i class="material-icons">menu</i>
                    </button>
                    <h1 class="page-title"><?php echo isset($pageTitle) ? $pageTitle : 'Dashboard'; ?></h1>
                </div>
                <div class="header-right">
                    <!-- Admin Quick Nav -->
                    <div class="header-actions">
                        <!-- Visit Site Button -->
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>" class="btn-visit-site" target="_blank">
                            <i class="material-icons">public</i>
                            <span><?php _e('visit_site'); ?></span>
                        </a>
                        
                        <!-- Language Switcher -->
                        <div class="dropdown">
                            <button class="dropdown-toggle">
                                <?php if (file_exists(BASE_PATH . '/public/uploads/flags/' . $currentLang . '.png')): ?>
                                    <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $currentLang; ?>.png" alt="<?php echo $currentLang; ?>">
                                <?php endif; ?>
                                <span><?php 
                                    // Safely display the language name
                                    $currentLangName = '';
                                    foreach ($languages as $lang) {
                                        if ($lang['code'] === $currentLang) {
                                            $currentLangName = $lang['name'];
                                            break;
                                        }
                                    }
                                    echo $currentLangName;
                                ?></span>
                                <i class="material-icons">arrow_drop_down</i>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($languages as $lang): ?>
                                    <?php if ($lang['code'] != $currentLang): ?>
                                        <li>
                                            <a href="<?php echo $adminUrl . '?lang=' . $lang['code']; ?>">
                                                <?php if (file_exists(BASE_PATH . '/public/uploads/flags/' . $lang['code'] . '.png')): ?>
                                                    <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $lang['code']; ?>.png" alt="<?php echo $lang['code']; ?>">
                                                <?php endif; ?>
                                                <span><?php echo $lang['name']; ?></span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        
                        <!-- User Dropdown -->
                        <div class="dropdown">
                            <button class="dropdown-toggle">
                                <div class="user-avatar">
                                    <i class="material-icons">person</i>
                                </div>
                                <span><?php echo $session->get('user_first_name'); ?></span>
                                <i class="material-icons">arrow_drop_down</i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="<?php echo $adminUrl; ?>/profile">
                                        <i class="material-icons">account_circle</i>
                                        <span><?php _e('profile'); ?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo $adminUrl; ?>/logout">
                                        <i class="material-icons">logout</i>
                                        <span><?php _e('logout'); ?></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="admin-content">
                <?php if ($session->hasFlash('success')): ?>
                    <div class="alert alert-success">
                        <i class="material-icons">check_circle</i>
                        <span><?php echo $session->getFlash('success'); ?></span>
                        <button type="button" class="alert-close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <?php if ($session->hasFlash('error')): ?>
                    <div class="alert alert-error">
                        <i class="material-icons">error</i>
                        <span><?php echo $session->getFlash('error'); ?></span>
                        <button type="button" class="alert-close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <?php if ($session->hasFlash('warning')): ?>
                    <div class="alert alert-warning">
                        <i class="material-icons">warning</i>
                        <span><?php echo $session->getFlash('warning'); ?></span>
                        <button type="button" class="alert-close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <?php if ($session->hasFlash('info')): ?>
                    <div class="alert alert-info">
                        <i class="material-icons">info</i>
                        <span><?php echo $session->getFlash('info'); ?></span>
                        <button type="button" class="alert-close">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <?php echo $content; ?>
            </div>
            
            <!-- Footer -->
            <footer class="admin-footer">
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo $settings['site_title']; ?>. <?php _e('all_rights_reserved'); ?></p>
                </div>
            </footer>
        </div>
    </div>
    
    <!-- JS Files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tiny.cloud/1/ufsmxd4yxjc0lp4ilt4e2s3865ezo6rpe06ln8dxgcuj0hms/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="<?php echo $jsUrl; ?>/admin.js"></script>
    
    <!-- Add any additional JS files -->
    <?php if (isset($additionalJs) && is_array($additionalJs)): ?>
        <?php foreach ($additionalJs as $js): ?>
            <script src="<?php echo $jsUrl . '/' . $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Add any additional scripts -->
    <?php if (isset($additionalScripts)): ?>
        <?php echo $additionalScripts; ?>
    <?php endif; ?>
</body>
<style>
.alert {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    margin-bottom: 20px;
    border: 1px solid transparent;
    border-radius: 6px;
    position: relative;
}

.alert-success {
    background-color: #d4edda;
    border-color: #c3e6cb;
    color: #155724;
}

.alert-danger {
    background-color: #f8d7da;
    border-color: #f5c6cb;
    color: #721c24;
}

.alert-warning {
    background-color: #fff3cd;
    border-color: #ffeaa7;
    color: #856404;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

.alert .material-icons {
    font-size: 20px;
}

.alert .close {
    position: absolute;
    top: 12px;
    right: 15px;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    opacity: 0.7;
}

.alert .close:hover {
    opacity: 1;
}

.alert-dismissible {
    padding-right: 50px;
}
</style>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.3s';
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 300);
            }
        }, 5000);
    });
});
</script>
</html>