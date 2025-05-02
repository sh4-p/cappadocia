<?php
/**
 * Sidebar Right Page Template
 */
?>

<div class="page-banner">
    <div class="container">
        <h1 class="page-title"><?php echo $page['title']; ?></h1>
        <div class="breadcrumbs">
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a>
            <span class="separator">/</span>
            <span class="current"><?php echo $page['title']; ?></span>
        </div>
    </div>
</div>

<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="content-wrapper">
                    <?php echo $page['content']; ?>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Recent Tours -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php _e('recent_tours'); ?></h3>
                        <div class="recent-tours">
                            <?php
                            // Load tour model
                            $tourModel = new Tour();
                            
                            // Get recent tours
                            $recentTours = $tourModel->getAllWithDetails($currentLang, ['t.is_active' => 1], 't.id DESC', 5);
                            
                            foreach ($recentTours as $tour):
                            ?>
                                <div class="recent-tour-item">
                                    <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>" class="recent-tour-image">
                                        <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                                    </a>
                                    <div class="recent-tour-content">
                                        <h4 class="recent-tour-title">
                                            <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>"><?php echo $tour['name']; ?></a>
                                        </h4>
                                        <div class="recent-tour-price">
                                            <?php if ($tour['discount_price'] > 0): ?>
                                                <span class="old-price"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                                                <span class="price"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
                                            <?php else: ?>
                                                <span class="price"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php _e('contact_info'); ?></h3>
                        <div class="contact-info">
                            <div class="contact-info-item">
                                <i class="material-icons">phone</i>
                                <span><?php echo $settings['contact_phone'] ?? '+90 123 456 7890'; ?></span>
                            </div>
                            <div class="contact-info-item">
                                <i class="material-icons">email</i>
                                <span><?php echo $settings['contact_email'] ?? 'info@cappadocia-travel.com'; ?></span>
                            </div>
                            <div class="contact-info-item">
                                <i class="material-icons">location_on</i>
                                <span><?php echo $settings['address'] ?? 'Göreme, Nevşehir, Turkey'; ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php _e('follow_us'); ?></h3>
                        <div class="social-icons">
                            <?php if (!empty($settings['facebook'])): ?>
                                <a href="<?php echo $settings['facebook']; ?>" class="social-icon" target="_blank">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['instagram'])): ?>
                                <a href="<?php echo $settings['instagram']; ?>" class="social-icon" target="_blank">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['twitter'])): ?>
                                <a href="<?php echo $settings['twitter']; ?>" class="social-icon" target="_blank">
                                    <i class="fab fa-twitter"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['youtube'])): ?>
                                <a href="<?php echo $settings['youtube']; ?>" class="social-icon" target="_blank">
                                    <i class="fab fa-youtube"></i>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (!empty($settings['tripadvisor'])): ?>
                                <a href="<?php echo $settings['tripadvisor']; ?>" class="social-icon" target="_blank">
                                    <i class="fab fa-tripadvisor"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>