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
                                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>" class="btn btn-outline btn-view-details">
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
<style>
    /* ========== IMPROVED TOUR CARDS ========== */
.tours-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 50px;
}

.tour-card {
    display: flex;
    flex-direction: column;
    height: 100%;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    background-color: #ffffff;
}

.tour-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.tour-image {
    position: relative;
    width: 100%;
    height: 240px;
    overflow: hidden;
}

.tour-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.tour-card:hover .tour-image img {
    transform: scale(1.1);
}

.tour-price {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: #FF6B35;
    color: #ffffff;
    padding: 8px 15px;
    border-radius: 10px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    z-index: 1;
    font-size: 14px;
}

.tour-price del {
    font-size: 13px;
    font-weight: 400;
    margin-right: 6px;
    opacity: 0.8;
}

.tour-category {
    position: absolute;
    bottom: 15px;
    left: 15px;
    background-color: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: #ffffff;
    padding: 6px 12px;
    border-radius: 10px;
    font-size: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    z-index: 1;
    font-weight: 500;
}

.tour-content {
    padding: 24px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.tour-title {
    font-size: 20px;
    margin-bottom: 10px;
    line-height: 1.3;
}

.tour-title a {
    color: #264653;
    transition: color 0.2s ease;
}

.tour-title a:hover {
    color: #FF6B35;
}

/* Fix for tour-meta text visibility */
.tour-meta {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
    color: #495057; /* Darker gray for better visibility */
    font-size: 14px;
}

.tour-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.tour-meta-item i {
    font-size: 18px;
    color: #2A9D8F; /* Teal color for the icons */
}

.tour-meta-item span {
    color: #495057; /* Explicitly set text color */
    font-weight: 500; /* Make it slightly bolder */
}

/* Enhanced Filter Styles */
.filter-group {
    margin-bottom: 20px;
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 15px;
    transition: all 0.3s ease;
}

.filter-group:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 15px;
    cursor: pointer;
}

.filter-header h4 {
    margin-bottom: 0;
    font-size: 16px;
    font-weight: 600;
}

.filter-toggle {
    color: #6c757d;
    transition: transform 0.3s ease;
}

.filter-group.open .filter-toggle {
    transform: rotate(180deg);
}

.filter-body {
    display: none;
    transition: all 0.3s ease;
}

.filter-group.open .filter-body {
    display: block;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.filter-option {
    display: flex;
    align-items: center;
}

.checkbox-label, .radio-label {
    display: flex;
    align-items: center;
    cursor: pointer;
    gap: 10px;
    width: 100%;
}

.checkbox-custom, .radio-custom {
    width: 20px;
    height: 20px;
    border: 2px solid #adb5bd;
    border-radius: 4px;
    position: relative;
    display: inline-block;
    transition: all 0.2s ease;
    flex-shrink: 0;
}

.radio-custom {
    border-radius: 50%;
}

.checkbox-label input, .radio-label input {
    display: none;
}

.checkbox-label input:checked + .checkbox-custom {
    background-color: #FF6B35;
    border-color: #FF6B35;
}

.radio-label input:checked + .radio-custom {
    border-color: #FF6B35;
}

.checkbox-label input:checked + .checkbox-custom::after {
    content: '';
    position: absolute;
    top: 3px;
    left: 6px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}

.radio-label input:checked + .radio-custom::after {
    content: '';
    position: absolute;
    top: 4px;
    left: 4px;
    width: 8px;
    height: 8px;
    background-color: #FF6B35;
    border-radius: 50%;
}

.checkbox-text, .radio-text {
    flex: 1;
    color: #495057; /* Darker text for better readability */
}

.checkbox-count {
    color: #6c757d;
    font-size: 14px;
    background-color: #f8f9fa;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 500;
}

/* Price Range Slider */
.price-range-slider {
    margin: 15px 10px;
    height: 6px;
    position: relative;
    background: #e9ecef;
    border-radius: 5px;
}

.price-range-slider .ui-slider-range {
    height: 6px;
    background: #FF6B35;
    position: absolute;
}

.price-range-slider .ui-slider-handle {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #FF6B35;
    position: absolute;
    top: -6px;
    margin-left: -9px;
    cursor: pointer;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    outline: none;
}

.price-range-values {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
    font-size: 14px;
    color: #495057;
}

.filter-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.filter-reset {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background-color: #f8f9fa;
    border: 1px solid #ced4da;
    color: #495057;
}

.filter-reset:hover {
    background-color: #e9ecef;
}

/* Improved Mobile Filters */
.filter-mobile-toggle {
    display: none;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background-color: #ffffff;
    color: #264653;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    margin-bottom: 20px;
    cursor: pointer;
}

@media (max-width: 768px) {
    .filter-mobile-toggle {
        display: flex;
    }
    
    .tour-filters {
        position: fixed;
        top: 0;
        left: -100%;
        width: 85%;
        height: 100vh;
        z-index: 1050;
        background-color: #ffffff;
        transition: left 0.3s ease;
        overflow-y: auto;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        padding: 20px;
    }
    
    .tour-filters.filters-open {
        left: 0;
    }
    
    .filters-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e9ecef;
    }
    
    .filter-close-mobile {
        display: block;
        font-size: 24px;
        color: #6c757d;
        background: none;
        border: none;
    }
}

.menu-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1040;
}

.menu-backdrop.active {
    display: block;
}

/* No Results Styling */
.no-results {
    text-align: center;
    padding: 50px 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
}

.no-results-icon {
    font-size: 60px;
    color: #ced4da;
    margin-bottom: 20px;
}

.no-results h3 {
    margin-bottom: 15px;
    color: #264653;
}

.no-results p {
    color: #6c757d;
    max-width: 500px;
    margin: 0 auto 20px;
}

.tour-description {
    margin-bottom: 20px;
    color: #495057;
    line-height: 1.6;
    flex-grow: 1;
}

.tour-footer {
    margin-top: auto;
    padding-top: 16px;
    border-top: 1px solid #f1f1f1;
}

.btn-view-details {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 20px;
    border-radius: 10px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.2s ease;
    background-color: transparent;
    border: 2px solid #FF6B35;
    color: #FF6B35;
}

.btn-view-details:hover {
    background-color: #FF6B35;
    color: #ffffff;
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

/* ========== RESPONSIVE STYLES ========== */
@media (max-width: 1200px) {
    .tours-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }
}

@media (max-width: 768px) {
    .tours-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .tours-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .tour-search {
        width: 100%;
    }
    
    .tours-wrapper {
        grid-template-columns: 1fr;
    }
    
    .tour-filters {
        position: fixed;
        top: 0;
        left: -100%;
        width: 85%;
        height: 100vh;
        z-index: 1050;
        background-color: #ffffff;
        transition: left 0.3s ease;
        overflow-y: auto;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }
    
    .tour-filters.filters-open {
        left: 0;
    }
    
    .filter-mobile-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        background-color: #ffffff;
        color: #264653;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        margin-bottom: 20px;
        cursor: pointer;
    }
    
    .filter-close-mobile {
        display: block;
        background: none;
        border: none;
        font-size: 24px;
        color: #6c757d;
    }
    
    .tour-card {
        max-width: 100%;
    }
    
    .tour-image {
        height: 220px;
    }
    
    .tour-content {
        padding: 20px;
    }
    
    .tour-title {
        font-size: 18px;
    }
    
    .tour-meta {
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }
    
    .tour-description {
        margin-bottom: 15px;
        font-size: 14px;
    }
    
    .tour-footer {
        padding-top: 12px;
    }
    
    .btn-view-details {
        width: 100%;
        padding: 12px 16px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .tour-image {
        height: 180px;
    }
    
    .tour-price {
        top: 10px;
        right: 10px;
        padding: 6px 12px;
        font-size: 13px;
    }
    
    .tour-category {
        bottom: 10px;
        left: 10px;
        padding: 4px 10px;
        font-size: 11px;
    }
    
    .tour-content {
        padding: 16px;
    }
    
    .tour-title {
        font-size: 16px;
    }
    
    .tour-meta-item i {
        font-size: 16px;
    }
    
    .tour-meta-item span {
        font-size: 12px;
    }
}

/* Menu backdrop for mobile filters */
.menu-backdrop {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1040;
}

.menu-backdrop.active {
    display: block;
}

/* Add this class to body when filters are open */
body.filters-opened {
    overflow: hidden;
}
</style>
