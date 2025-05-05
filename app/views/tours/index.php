<?php
/**
 * Tours Index View
 */
?>

<!-- Page Header -->
<div class="page-header" style="background-image: url('<?php echo $imgUrl; ?>/tours-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="page-title"><?php echo $pageTitle; ?></h1>
            
            <!-- Breadcrumbs -->
            <div class="breadcrumbs">
                <ul class="breadcrumbs-list">
                    <li class="breadcrumbs-item"><a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a></li>
                    <li class="breadcrumbs-item active"><?php _e('tours'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Tours Section -->
<section class="tours-section section">
    <div class="container">
        <div class="tours-wrapper">
            <!-- Filters Mobile Toggle -->
            <div class="filter-mobile-toggle">
                <i class="material-icons">filter_list</i>
                <span><?php _e('filters'); ?></span>
            </div>
            
            <!-- Tour Filters -->
            <div class="tour-filters">
                <div class="filters-header">
                    <h3><?php _e('filters'); ?></h3>
                    <button class="filter-close-mobile">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                
                <form action="<?php echo $appUrl . '/' . $currentLang; ?>/tours" method="get" data-auto-submit="true">
                    <!-- Category Filter -->
                    <div class="filter-group open">
                        <div class="filter-header">
                            <h4><?php _e('categories'); ?></h4>
                            <button class="filter-toggle">
                                <i class="material-icons">expand_more</i>
                            </button>
                        </div>
                        <div class="filter-body">
                            <div class="filter-options">
                                <?php foreach ($categories as $cat): ?>
                                    <div class="filter-option">
                                        <label class="checkbox-label">
                                            <input type="checkbox" name="category" value="<?php echo $cat['slug']; ?>" <?php echo ($category && $category['id'] == $cat['id']) ? 'checked' : ''; ?>>
                                            <span class="checkbox-custom"></span>
                                            <span class="checkbox-text"><?php echo $cat['name']; ?></span>
                                            <span class="checkbox-count">(<?php echo $cat['tour_count']; ?>)</span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="filter-group open">
                        <div class="filter-header">
                            <h4><?php _e('price_range'); ?></h4>
                            <button class="filter-toggle">
                                <i class="material-icons">expand_more</i>
                            </button>
                        </div>
                        <div class="filter-body">
                            <div class="price-range">
                                <div class="price-range-slider" data-min="0" data-max="1000" data-currency="<?php echo $settings['currency_symbol']; ?>"></div>
                                <div class="price-range-values">
                                    <span class="price-min-display"><?php echo $settings['currency_symbol']; ?>0</span>
                                    <span class="price-max-display"><?php echo $settings['currency_symbol']; ?>1000</span>
                                </div>
                                <input type="hidden" name="price_min" id="price_min" value="0">
                                <input type="hidden" name="price_max" id="price_max" value="1000">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Duration Filter -->
                    <div class="filter-group">
                        <div class="filter-header">
                            <h4><?php _e('duration'); ?></h4>
                            <button class="filter-toggle">
                                <i class="material-icons">expand_more</i>
                            </button>
                        </div>
                        <div class="filter-body">
                            <div class="filter-options">
                                <div class="filter-option">
                                    <label class="radio-label">
                                        <input type="radio" name="duration" value="half-day">
                                        <span class="radio-custom"></span>
                                        <span class="radio-text"><?php _e('half_day'); ?></span>
                                    </label>
                                </div>
                                <div class="filter-option">
                                    <label class="radio-label">
                                        <input type="radio" name="duration" value="full-day">
                                        <span class="radio-custom"></span>
                                        <span class="radio-text"><?php _e('full_day'); ?></span>
                                    </label>
                                </div>
                                <div class="filter-option">
                                    <label class="radio-label">
                                        <input type="radio" name="duration" value="multi-day">
                                        <span class="radio-custom"></span>
                                        <span class="radio-text"><?php _e('multi_day'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Actions -->
                    <div class="filter-actions">
                        <button type="reset" class="btn btn-sm filter-reset">
                            <i class="material-icons">refresh</i>
                            <?php _e('reset_filters'); ?>
                        </button>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="material-icons">search</i>
                            <?php _e('apply_filters'); ?>
                        </button>
                    </div>
                </form>
            </div>
            <!-- Tours Content -->
            <div class="tours-content">
                <!-- Tours Header -->
                <div class="tours-header">
                    <?php if ($category): ?>
                        <h2 class="category-title"><?php echo $category['name']; ?></h2>
                        <p class="category-description"><?php echo $category['description']; ?></p>
                    <?php else: ?>
                        <div class="tours-count">
                            <span><?php echo sprintf(__('showing_tours'), $totalTours); ?></span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Search Form -->
                    <div class="tour-search">
                        <form action="<?php echo $appUrl . '/' . $currentLang; ?>/tours/search" method="get" class="tour-search-form">
                            <input type="text" name="q" placeholder="<?php _e('search_tours'); ?>" class="tour-search-input">
                            <button type="submit">
                                <i class="material-icons">search</i>
                            </button>
                            <div class="tour-search-results"></div>
                        </form>
                    </div>
                </div>
                
                <?php if (empty($tours)): ?>
                    <!-- No Tours Found -->
                    <div class="no-results">
                        <div class="no-results-icon">
                            <i class="material-icons">search_off</i>
                        </div>
                        <h3><?php _e('no_tours_found'); ?></h3>
                        <p><?php _e('no_tours_found_description'); ?></p>
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary">
                            <i class="material-icons">refresh</i>
                            <?php _e('clear_filters'); ?>
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Tours Grid -->
                    <div class="tours-grid">
                        <?php foreach ($tours as $tour): ?>
                            <div class="tour-card" data-aos="fade-up">
                                <div class="tour-image">
                                    <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                                    <div class="tour-price">
                                        <?php if ($tour['discount_price']): ?>
                                            <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                                            <?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?>
                                        <?php else: ?>
                                            <?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($tour['category_name']): ?>
                                        <div class="tour-category"><?php echo $tour['category_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="tour-content">
                                    <h3 class="tour-title">
                                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>">
                                            <?php echo $tour['name']; ?>
                                        </a>
                                    </h3>
                                    <div class="tour-meta">
                                        <div class="tour-meta-item">
                                            <i class="material-icons">schedule</i>
                                            <span><?php echo $tour['duration']; ?></span>
                                        </div>
                                    </div>
                                    <p class="tour-description">
                                        <?php echo substr(strip_tags($tour['short_description']), 0, 120) . '...'; ?>
                                    </p>
                                    <div class="tour-footer">
                                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>" class="btn btn-outline btn-sm">
                                            <?php _e('view_details'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="<?php echo $appUrl . '/' . $currentLang . '/tours?page=' . ($currentPage - 1) . ($category ? '&category=' . $category['slug'] : ''); ?>" class="pagination-item">
                                    <i class="material-icons">chevron_left</i>
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="<?php echo $appUrl . '/' . $currentLang . '/tours?page=' . $i . ($category ? '&category=' . $category['slug'] : ''); ?>" class="pagination-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="<?php echo $appUrl . '/' . $currentLang . '/tours?page=' . ($currentPage + 1) . ($category ? '&category=' . $category['slug'] : ''); ?>" class="pagination-item">
                                    <i class="material-icons">chevron_right</i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" style="background-image: url('<?php echo $imgUrl; ?>/cta-bg.jpg');">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title"><?php _e('ready_for_adventure'); ?></h2>
            <p class="cta-text"><?php _e('ready_for_adventure_text'); ?></p>
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-primary btn-lg">
                <i class="material-icons">chat</i>
                <?php _e('contact_us'); ?>
            </a>
        </div>
    </div>
</section>