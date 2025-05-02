
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
    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Admin Wrapper -->
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="<?php echo $adminUrl; ?>/dashboard">
                    <img src="<?php echo $imgUrl; ?>/logo-white.png" alt="<?php echo $settings['site_title']; ?>" class="sidebar-logo">
                </a>
                <button class="sidebar-toggle">
                    <i class="material-icons">menu</i>
                </button>
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
                                <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $currentLang; ?>.png" alt="<?php echo $currentLang; ?>">
                                <span><?php echo $languages[$currentLang]['name']; ?></span>
                                <i class="material-icons">arrow_drop_down</i>
                            </button>
                            <ul class="dropdown-menu">
                                <?php foreach ($languages as $lang): ?>
                                    <?php if ($lang['code'] != $currentLang): ?>
                                        <li>
                                            <a href="<?php echo $adminUrl . '?lang=' . $lang['code']; ?>">
                                                <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $lang['code']; ?>.png" alt="<?php echo $lang['code']; ?>">
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
</html>