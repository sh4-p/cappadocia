<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' | ' . $settings['site_title'] : $settings['site_title']; ?></title>
    <meta name="description" content="<?php echo isset($metaDescription) ? $metaDescription : $settings['site_description']; ?>">
    
    <!-- Favicon - Settings'den dinamik olarak -->
    <?php 
    $faviconFile = $settings['favicon'] ?? 'favicon.ico';
    $faviconPath = $imgUrl . '/' . $faviconFile;
    // Dosya var mı kontrol et
    $faviconExists = file_exists(BASE_PATH . '/public/img/' . $faviconFile);
    ?>
    <?php if ($faviconExists): ?>
        <link rel="icon" href="<?php echo $faviconPath; ?>" type="image/x-icon">
        <link rel="shortcut icon" href="<?php echo $faviconPath; ?>" type="image/x-icon">
    <?php else: ?>
        <!-- Varsayılan favicon yoksa hiçbir şey ekleme, 404 isteklerini önle -->
    <?php endif; ?>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="<?php echo $cssUrl; ?>/style.css">
    <link rel="stylesheet" href="<?php echo $cssUrl; ?>/header-glassmorphism.css">
    <link rel="stylesheet" href="<?php echo $cssUrl; ?>/glassmorphism-enhancements.css">
    <link rel="stylesheet" href="<?php echo $cssUrl; ?>/responsive.css">
    
    
    <!-- Add any additional CSS files -->
    <?php if (isset($additionalCss) && is_array($additionalCss)): ?>
        <?php foreach ($additionalCss as $css): ?>
            <link rel="stylesheet" href="<?php echo $cssUrl . '/' . $css; ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Google Analytics -->
    <?php if (!empty($settings['google_analytics'])): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $settings['google_analytics']; ?>"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '<?php echo $settings['google_analytics']; ?>');
    </script>
    <?php endif; ?>
</head>
<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="loader">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="header-wrapper">
                <!-- Logo - Settings'den dinamik olarak -->
                <div class="logo">
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>">
                        <?php 
                        $logoFile = $settings['logo'] ?? 'logo.png';
                        $logoPath = $imgUrl . '/' . $logoFile;
                        // Dosya var mı kontrol et
                        $logoExists = file_exists(BASE_PATH . '/public/img/' . $logoFile);
                        ?>
                        <?php if ($logoExists): ?>
                            <img src="<?php echo $logoPath; ?>" alt="<?php echo $settings['site_title']; ?>" class="main-logo">
                        <?php else: ?>
                            <!-- Varsayılan logo text -->
                            <span class="logo-text"><?php echo $settings['site_title']; ?></span>
                        <?php endif; ?>
                    </a>
                </div>
                
                <!-- Main Navigation -->
                <nav class="main-nav">
                    <ul>
                        <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a></li>
                        <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours"><?php _e('tours'); ?></a></li>
                        <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>/gallery"><?php _e('gallery'); ?></a></li>
                        <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>/about"><?php _e('about'); ?></a></li>
                        <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact"><?php _e('contact'); ?></a></li>
                    </ul>
                </nav>
                
                <!-- Header Actions -->
                <div class="header-actions">
                    <!-- Language Switcher -->
                    <div class="language-dropdown">
                        <button class="dropdown-toggle">
                            <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $currentLang; ?>.png" alt="<?php echo $currentLang; ?>">
                            <span><?php echo $languages[$currentLang]['name']; ?></span>
                            <i class="material-icons">arrow_drop_down</i>
                        </button>
                        <ul class="dropdown-menu">
                            <?php foreach ($languages as $code => $lang): ?>
                                <?php if ($code != $currentLang): ?>
                                    <li>
                                        <?php
                                        // Get current path
                                        $currentPath = $_SERVER['REQUEST_URI'];
                                        // Remove base path and current language
                                        $basePath = parse_url(APP_URL, PHP_URL_PATH) ?: '';
                                        $langPath = $basePath . '/' . $currentLang;
                                        
                                        // Remove language prefix if present
                                        if (strpos($currentPath, $langPath) === 0) {
                                            $path = substr($currentPath, strlen($langPath));
                                        } else {
                                            $path = $currentPath;
                                        }
                                        
                                        // Construct new URL with correct language code
                                        $newUrl = APP_URL . '/' . $code . $path;
                                        ?>
                                        <a href="<?php echo $newUrl; ?>">
                                            <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $code; ?>.png" alt="<?php echo $code; ?>">
                                            <span><?php echo $lang['name']; ?></span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <!-- Search Button -->
                    <button class="search-toggle">
                        <i class="material-icons">search</i>
                    </button>
                    
                    <!-- Mobile Menu Toggle -->
                    <button class="mobile-toggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Search Overlay -->
    <div class="search-overlay">
        <button class="search-close">
            <i class="material-icons">close</i>
        </button>
        <div class="search-content">
            <div class="container">
                <h2><?php _e('search_tours'); ?></h2>
                <form action="<?php echo $appUrl . '/' . $currentLang; ?>/tours/search" method="get">
                    <div class="search-field">
                        <input type="text" name="q" placeholder="<?php _e('search_placeholder'); ?>" autocomplete="off" id="search-input">
                        <button type="submit">
                            <i class="material-icons">search</i>
                        </button>
                    </div>
                </form>
                <div class="search-results" id="search-results"></div>
            </div>
        </div>
    </div>
    
    <!-- Main Content -->
    <main class="site-main">
        <?php echo $content; ?>
    </main>
    
    <!-- Footer -->
    <footer class="site-footer">
        <div class="footer-top">
            <div class="container">
                <div class="footer-widgets">
                    <!-- Widget: About -->
                    <div class="footer-widget">
                        <h3 class="widget-title"><?php _e('about_us'); ?></h3>
                        <div class="about-widget">
                            <?php 
                            // Footer logo - beyaz versiyonu tercih et
                            $footerLogoFile = $settings['logo'] ?? 'logo.png';
                            $footerLogoPath = $imgUrl . '/' . $footerLogoFile;
                            $footerLogoExists = file_exists(BASE_PATH . '/public/img/' . $footerLogoFile);
                            
                            // Beyaz versiyon var mı kontrol et
                            $whiteLogoFile = str_replace('.png', '-white.png', $footerLogoFile);
                            $whiteLogoFile = str_replace('.jpg', '-white.jpg', $whiteLogoFile);
                            $whiteLogoPath = $imgUrl . '/' . $whiteLogoFile;
                            $whiteLogoExists = file_exists(BASE_PATH . '/public/img/' . $whiteLogoFile);
                            ?>
                            
                            <?php if ($whiteLogoExists): ?>
                                <img src="<?php echo $whiteLogoPath; ?>" alt="<?php echo $settings['site_title']; ?>" class="footer-logo">
                            <?php elseif ($footerLogoExists): ?>
                                <img src="<?php echo $footerLogoPath; ?>" alt="<?php echo $settings['site_title']; ?>" class="footer-logo footer-logo-invert">
                            <?php else: ?>
                                <div class="footer-logo-text"><?php echo $settings['site_title']; ?></div>
                            <?php endif; ?>
                            
                            <p><?php echo $settings['site_description']; ?></p>
                            <div class="social-links">
                                <?php if (!empty($settings['facebook'])): ?>
                                    <a href="<?php echo $settings['facebook']; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($settings['instagram'])): ?>
                                    <a href="<?php echo $settings['instagram']; ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($settings['twitter'])): ?>
                                    <a href="<?php echo $settings['twitter']; ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                                <?php if (!empty($settings['youtube'])): ?>
                                    <a href="<?php echo $settings['youtube']; ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Widget: Quick Links -->
                    <div class="footer-widget">
                        <h3 class="widget-title"><?php _e('quick_links'); ?></h3>
                        <ul class="quick-links">
                            <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a></li>
                            <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours"><?php _e('tours'); ?></a></li>
                            <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>/gallery"><?php _e('gallery'); ?></a></li>
                            <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>/about"><?php _e('about'); ?></a></li>
                            <li><a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact"><?php _e('contact'); ?></a></li>
                        </ul>
                    </div>
                    
                    <!-- Widget: Recent Tours -->
                    <div class="footer-widget">
                        <h3 class="widget-title"><?php _e('top_tours'); ?></h3>
                        <ul class="recent-tours">
                            <?php 
                            // Bu kısım değiştirildi - Artık controller'dan gelen bilgileri kullanıyoruz
                            if (isset($featuredTours) && is_array($featuredTours)): 
                                foreach ($featuredTours as $tour): 
                            ?>
                                <li>
                                    <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>">
                                        <div class="tour-image">
                                            <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                                        </div>
                                        <div class="tour-info">
                                            <h4><?php echo $tour['name']; ?></h4>
                                            <span class="price">
                                                <?php if ($tour['discount_price']): ?>
                                                    <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                                                    <?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?>
                                                <?php else: ?>
                                                    <?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                    </a>
                                </li>
                            <?php 
                                endforeach; 
                            endif; 
                            ?>
                        </ul>
                    </div>
                    
                    <!-- Widget: Contact Info -->
                    <div class="footer-widget">
                        <h3 class="widget-title"><?php _e('contact_info'); ?></h3>
                        <ul class="contact-info">
                            <li>
                                <i class="material-icons">location_on</i>
                                <span><?php echo $settings['address']; ?></span>
                            </li>
                            <li>
                                <i class="material-icons">phone</i>
                                <span><?php echo $settings['contact_phone']; ?></span>
                            </li>
                            <li>
                                <i class="material-icons">email</i>
                                <span><?php echo $settings['contact_email']; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="copyright">
                    <p>&copy; <?php echo date('Y'); ?> <?php echo $settings['site_title']; ?>. <?php _e('all_rights_reserved'); ?></p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Back to Top Button -->
    <div class="back-to-top">
        <i class="material-icons">keyboard_arrow_up</i>
    </div>
    
    <!-- JS Files -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?php echo $jsUrl; ?>/main.js"></script>
    
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