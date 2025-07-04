<?php
/**
 * Tours Search Results View
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
                    <li class="breadcrumbs-item"><a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours"><?php _e('tours'); ?></a></li>
                    <li class="breadcrumbs-item active"><?php _e('search_results'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Search Results Section -->
<section class="tours-section section">
    <div class="container">
        <!-- Search Info -->
        <div class="search-info">
            <div class="search-query">
                <h2><?php echo sprintf(__('search_results_for'), '<strong>"' . htmlspecialchars($query) . '"</strong>'); ?></h2>
                <p class="search-count">
                    <?php if ($totalTours > 0): ?>
                        <?php echo sprintf(__('found_x_tours'), $totalTours); ?>
                    <?php else: ?>
                        <?php _e('no_tours_found'); ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="search-actions">
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-outline">
                    <i class="material-icons">refresh</i>
                    <?php _e('clear_search'); ?>
                </a>
            </div>
        </div>

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
                
                <form action="<?php echo $appUrl . '/' . $currentLang; ?>/tours/search" method="get" data-auto-submit="true">
                    <!-- Keep current search query -->
                    <input type="hidden" name="q" value="<?php echo htmlspecialchars($query); ?>">
                    
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
                                            <input type="checkbox" name="category" value="<?php echo $cat['slug']; ?>" <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['slug']) ? 'checked' : ''; ?>>
                                            <span class="checkbox-custom"></span>
                                            <span class="checkbox-text"><?php echo $cat['name']; ?></span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="filter-group">
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
                                <input type="hidden" name="price_min" id="price_min" value="<?php echo isset($_GET['price_min']) ? $_GET['price_min'] : '0'; ?>">
                                <input type="hidden" name="price_max" id="price_max" value="<?php echo isset($_GET['price_max']) ? $_GET['price_max'] : '1000'; ?>">
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
                                        <input type="radio" name="duration" value="half-day" <?php echo (isset($_GET['duration']) && $_GET['duration'] == 'half-day') ? 'checked' : ''; ?>>
                                        <span class="radio-custom"></span>
                                        <span class="radio-text"><?php _e('half_day'); ?></span>
                                    </label>
                                </div>
                                <div class="filter-option">
                                    <label class="radio-label">
                                        <input type="radio" name="duration" value="full-day" <?php echo (isset($_GET['duration']) && $_GET['duration'] == 'full-day') ? 'checked' : ''; ?>>
                                        <span class="radio-custom"></span>
                                        <span class="radio-text"><?php _e('full_day'); ?></span>
                                    </label>
                                </div>
                                <div class="filter-option">
                                    <label class="radio-label">
                                        <input type="radio" name="duration" value="multi-day" <?php echo (isset($_GET['duration']) && $_GET['duration'] == 'multi-day') ? 'checked' : ''; ?>>
                                        <span class="radio-custom"></span>
                                        <span class="radio-text"><?php _e('multi_day'); ?></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filter Actions -->
                    <div class="filter-actions">
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours/search?q=<?php echo urlencode($query); ?>" class="btn btn-sm filter-reset">
                            <i class="material-icons">refresh</i>
                            <?php _e('reset_filters'); ?>
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="material-icons">search</i>
                            <?php _e('apply_filters'); ?>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Search Results Content -->
            <div class="tours-content">
                <!-- New Search Form -->
                <div class="tours-header">
                    <div class="tour-search">
                        <form action="<?php echo $appUrl . '/' . $currentLang; ?>/tours/search" method="get" class="tour-search-form">
                            <input type="text" name="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="<?php _e('search_tours'); ?>" class="tour-search-input">
                            <button type="submit">
                                <i class="material-icons">search</i>
                            </button>
                            <div class="tour-search-results"></div>
                        </form>
                    </div>
                </div>
                
                <?php if (empty($tours)): ?>
                    <!-- No Search Results -->
                    <div class="no-results">
                        <div class="no-results-icon">
                            <i class="material-icons">search_off</i>
                        </div>
                        <h3><?php _e('no_tours_found'); ?></h3>
                        <p><?php echo sprintf(__('no_tours_found_for_query'), '"' . htmlspecialchars($query) . '"'); ?></p>
                        <div class="no-results-actions">
                            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary">
                                <i class="material-icons">view_list</i>
                                <?php _e('view_all_tours'); ?>
                            </a>
                            <button class="btn btn-outline" onclick="document.querySelector('.tour-search-input').focus();">
                                <i class="material-icons">refresh</i>
                                <?php _e('try_different_search'); ?>
                            </button>
                        </div>
                        
                        <!-- Search Suggestions -->
                        <div class="search-suggestions">
                            <h4><?php _e('search_suggestions'); ?>:</h4>
                            <ul class="suggestions-list">
                                <li><?php _e('try_shorter_keywords'); ?></li>
                                <li><?php _e('check_spelling'); ?></li>
                                <li><?php _e('use_more_general_terms'); ?></li>
                                <li><?php _e('browse_categories_instead'); ?></li>
                            </ul>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Search Results Grid -->
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
                                            <?php 
                                            // Highlight search terms in title
                                            $highlightedTitle = preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', $tour['name']);
                                            echo $highlightedTitle; 
                                            ?>
                                        </a>
                                    </h3>
                                    <div class="tour-meta">
                                        <div class="tour-meta-item">
                                            <i class="material-icons">schedule</i>
                                            <span><?php echo $tour['duration']; ?></span>
                                        </div>
                                    </div>
                                    <p class="tour-description">
                                        <?php 
                                        // Highlight search terms in description
                                        $description = substr(strip_tags($tour['short_description']), 0, 120) . '...';
                                        $highlightedDescription = preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', $description);
                                        echo $highlightedDescription; 
                                        ?>
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
                                <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/search?q=' . urlencode($query) . '&page=' . ($currentPage - 1); ?>" class="pagination-item">
                                    <i class="material-icons">chevron_left</i>
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/search?q=' . urlencode($query) . '&page=' . $i; ?>" class="pagination-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/search?q=' . urlencode($query) . '&page=' . ($currentPage + 1); ?>" class="pagination-item">
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
/* ========== SEARCH SPECIFIC STYLES ========== */
.search-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #FF6B35;
}

.search-query h2 {
    margin-bottom: 5px;
    color: #264653;
    font-size: 24px;
}

.search-query strong {
    color: #FF6B35;
}

.search-count {
    margin: 0;
    color: #6c757d;
    font-size: 14px;
}

.search-actions {
    flex-shrink: 0;
}

/* Highlight search terms */
mark {
    background-color: #fff3cd;
    color: #856404;
    padding: 2px 4px;
    border-radius: 3px;
    font-weight: 500;
}

/* Enhanced No Results */
.no-results {
    text-align: center;
    padding: 60px 20px;
    background-color: #f8f9fa;
    border-radius: 15px;
    max-width: 600px;
    margin: 0 auto;
}

.no-results-icon {
    font-size: 80px;
    color: #ced4da;
    margin-bottom: 25px;
}

.no-results h3 {
    margin-bottom: 15px;
    color: #264653;
    font-size: 24px;
}

.no-results p {
    color: #6c757d;
    margin-bottom: 25px;
    font-size: 16px;
    line-height: 1.6;
}

.no-results-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.search-suggestions {
    text-align: left;
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
}

.search-suggestions h4 {
    margin-bottom: 15px;
    color: #264653;
    font-size: 16px;
}

.suggestions-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.suggestions-list li {
    padding: 8px 0;
    color: #495057;
    position: relative;
    padding-left: 20px;
}

.suggestions-list li::before {
    content: '💡';
    position: absolute;
    left: 0;
    top: 8px;
}

/* ========== IMPORT STYLES FROM INDEX ========== */
/* All the styles from tours/index.php */
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

.tour-meta {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
    color: #495057;
    font-size: 14px;
}

.tour-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.tour-meta-item i {
    font-size: 18px;
    color: #2A9D8F;
}

.tour-meta-item span {
    color: #495057;
    font-weight: 500;
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
    .search-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    .search-actions {
        width: 100%;
    }

    .search-actions .btn {
        width: 100%;
        justify-content: center;
    }

    .no-results-actions {
        flex-direction: column;
        align-items: center;
    }

    .no-results-actions .btn {
        width: 100%;
        max-width: 250px;
    }

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
}

@media (max-width: 480px) {
    .search-info {
        padding: 15px;
    }

    .search-query h2 {
        font-size: 20px;
    }

    .no-results {
        padding: 40px 15px;
    }

    .no-results-icon {
        font-size: 60px;
    }

    .no-results h3 {
        font-size: 20px;
    }
}
</style>