<?php
/**
 * Sidebar Left Page Template
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
            <div class="col-lg-4">
                <div class="sidebar">
                    <!-- Categories -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php _e('categories'); ?></h3>
                        <div class="category-list">
                            <?php
                            // Load category model
                            $categoryModel = new Category();
                            
                            // Get categories
                            $categories = $categoryModel->getAllWithDetails($currentLang, ['c.is_active' => 1], 'c.order_number ASC');
                            
                            foreach ($categories as $category):
                            ?>
                                <div class="category-item">
                                    <a href="<?php echo $appUrl . '/' . $currentLang . '/tours?category=' . $category['slug']; ?>">
                                        <?php echo $category['name']; ?>
                                        <span class="count">(<?php echo $category['tour_count']; ?>)</span>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Featured Tours -->
                    <div class="sidebar-widget">
                        <h3 class="widget-title"><?php _e('featured_tours'); ?></h3>
                        <div class="featured-tours">
                            <?php
                            // Load tour model
                            $tourModel = new Tour();
                            
                            // Get featured tours
                            $featuredTours = $tourModel->getAllWithDetails($currentLang, ['t.is_active' => 1, 't.is_featured' => 1], 't.id DESC', 3);
                            
                            foreach ($featuredTours as $tour):
                            ?>
                                <div class="featured-tour-item">
                                    <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>" class="featured-tour-image">
                                        <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                                        <?php if ($tour['discount_price'] > 0): ?>
                                            <div class="discount-badge">
                                                <?php
                                                $discountPercent = round(($tour['price'] - $tour['discount_price']) / $tour['price'] * 100);
                                                echo "-{$discountPercent}%";
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </a>
                                    <div class="featured-tour-content">
                                        <h4 class="featured-tour-title">
                                            <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>"><?php echo $tour['name']; ?></a>
                                        </h4>
                                        <div class="featured-tour-meta">
                                            <span class="duration"><i class="material-icons">schedule</i> <?php echo $tour['duration']; ?></span>
                                        </div>
                                        <div class="featured-tour-price">
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
                    
                    <!-- Call to Action -->
                    <div class="sidebar-widget cta-widget">
                        <h3 class="widget-title"><?php _e('book_your_tour'); ?></h3>
                        <p><?php _e('cta_description'); ?></p>
                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours'; ?>" class="btn btn-primary btn-block">
                            <i class="material-icons">explore</i>
                            <?php _e('view_all_tours'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="content-wrapper">
                    <?php echo $page['content']; ?>
                </div>
            </div>
        </div>
    </div>
</div>