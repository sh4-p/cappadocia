<?php
/**
 * Tour Detail View - Complete Redesign
 * Enhanced user experience with a focus on conversions
 */
?>

<!-- Immersive Hero Section -->
<section class="tour-hero">
    <div class="tour-hero-image" style="background-image: url('<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>');">
        <div class="tour-hero-overlay"></div>
        
        <div class="container">
            <div class="tour-hero-content">
                <!-- Tour Title and Rating -->
                <div class="tour-hero-header">
                    <?php if (isset($tour['is_popular']) && $tour['is_popular']): ?>
                        <div class="tour-badge popular">
                            <i class="material-icons">star</i> <?php _e('most_popular'); ?>
                        </div>
                    <?php elseif (isset($tour['is_new']) && $tour['is_new']): ?>
                        <div class="tour-badge new">
                            <i class="material-icons">new_releases</i> <?php _e('new'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <h1 class="tour-title"><?php echo $tour['name']; ?></h1>
                    
                    <div class="tour-rating-wrapper">
                        <div class="tour-rating">
                            <?php 
                            $rating = isset($tour['rating']) ? $tour['rating'] : 5;
                            for ($i = 1; $i <= 5; $i++): ?>
                                <i class="material-icons"><?php echo $i <= $rating ? 'star' : 'star_border'; ?></i>
                            <?php endfor; ?>
                            <span class="rating-value"><?php echo number_format($rating, 1); ?></span>
                            <span class="rating-count">(<?php echo isset($tour['reviews_count']) ? $tour['reviews_count'] : rand(15, 50); ?> <?php _e('reviews'); ?>)</span>
                        </div>
                    </div>
                </div>
                
                <!-- Tour Highlights -->
                <div class="tour-highlights-banner">
                    <div class="tour-highlight">
                        <i class="material-icons">schedule</i>
                        <div class="highlight-info">
                            <span class="highlight-label"><?php _e('duration'); ?></span>
                            <span class="highlight-value"><?php echo $tour['duration']; ?></span>
                        </div>
                    </div>
                    
                    <div class="tour-highlight">
                        <i class="material-icons">group</i>
                        <div class="highlight-info">
                            <span class="highlight-label"><?php _e('group_size'); ?></span>
                            <span class="highlight-value"><?php _e('max'); ?> 15</span>
                        </div>
                    </div>
                    
                    <div class="tour-highlight">
                        <i class="material-icons">language</i>
                        <div class="highlight-info">
                            <span class="highlight-label"><?php _e('languages'); ?></span>
                            <span class="highlight-value">English, Türkçe</span>
                        </div>
                    </div>
                    
                    <div class="tour-highlight">
                        <i class="material-icons">verified_user</i>
                        <div class="highlight-info">
                            <span class="highlight-label"><?php _e('instant_confirmation'); ?></span>
                            <span class="highlight-value"><?php _e('guaranteed'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tour Content Section -->
    <section class="tour-content-section">
        <div class="container">
            <div class="tour-content-wrapper">
                <!-- Main Content Area -->
                <div class="tour-main-content">
                    <!-- Gallery Slider - Redesigned -->
                    <div class="tour-gallery" data-aos="fade-up">
                        <div class="gallery-main">
                            <?php if (isset($gallery) && !empty($gallery)): ?>
                                <?php foreach ($gallery as $index => $item): ?>
                                    <div class="gallery-main-slide <?php echo ($index === 0) ? 'active' : ''; ?>">
                                        <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" alt="<?php echo isset($item['title']) ? $item['title'] : $tour['name'] . ' ' . ($index + 1); ?>">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="gallery-main-slide active">
                                    <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                                </div>
                            <?php endif; ?>
                            
                            <button class="gallery-nav prev"><i class="material-icons">chevron_left</i></button>
                            <button class="gallery-nav next"><i class="material-icons">chevron_right</i></button>
                        </div>
                        
                        <?php if (isset($gallery) && count($gallery) > 1): ?>
                            <div class="gallery-thumbs">
                                <?php foreach ($gallery as $index => $item): ?>
                                    <div class="gallery-thumb <?php echo ($index === 0) ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                        <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" alt="<?php echo isset($item['title']) ? $item['title'] : $tour['name'] . ' thumbnail ' . ($index + 1); ?>">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Redesigned Price Display for Mobile -->
                    <div class="tour-price-mobile">
                        <div class="price-details">
                            <?php if (isset($tour['discount_price']) && $tour['discount_price']): ?>
                                <div class="price-wrapper">
                                    <del class="old-price"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                                    <div class="current-price"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></div>
                                    <div class="price-per"><?php _e('per_person'); ?></div>
                                </div>
                                <div class="discount-badge">
                                    <?php echo round((($tour['price'] - $tour['discount_price']) / $tour['price']) * 100); ?>% <?php _e('off'); ?>
                                </div>
                            <?php else: ?>
                                <div class="price-wrapper">
                                    <div class="current-price"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></div>
                                    <div class="price-per"><?php _e('per_person'); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <a href="#booking-form" class="btn btn-primary btn-lg book-now-btn">
                            <i class="material-icons">shopping_cart</i>
                            <?php _e('book_now'); ?>
                        </a>
                    </div>
                    
                    <!-- Tour Tabs Redesigned -->
                    <div class="tour-tabs" data-aos="fade-up">
                        <div class="tabs-nav">
                            <button class="tab-button active" data-target="overview">
                                <i class="material-icons">info</i>
                                <span><?php _e('overview'); ?></span>
                            </button>
                            <button class="tab-button" data-target="itinerary">
                                <i class="material-icons">map</i>
                                <span><?php _e('itinerary'); ?></span>
                            </button>
                            <button class="tab-button" data-target="includes">
                                <i class="material-icons">check_circle</i>
                                <span><?php _e('included'); ?></span>
                            </button>
                            <button class="tab-button" data-target="location">
                                <i class="material-icons">place</i>
                                <span><?php _e('location'); ?></span>
                            </button>
                            <button class="tab-button" data-target="reviews">
                                <i class="material-icons">star</i>
                                <span><?php _e('reviews'); ?></span>
                            </button>
                        </div>
                        
                        <!-- Tab Contents -->
                        <div class="tab-contents">
                            <!-- Overview Tab -->
                            <div class="tab-content active" id="overview">
                                <div class="content-box">
                                    <div class="content-header">
                                        <h2><?php _e('tour_overview'); ?></h2>
                                    </div>
                                    
                                    <div class="content-body">
                                        <?php if (isset($tour['short_description']) && !empty($tour['short_description'])): ?>
                                            <div class="tour-summary">
                                                <?php echo $tour['short_description']; ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="tour-description">
                                            <?php echo $tour['description']; ?>
                                        </div>
                                        
                                        <!-- Experience Highlights -->
                                        <div class="experience-highlights">
                                            <h3><?php _e('experience_highlights'); ?></h3>
                                            <div class="highlights-grid">
                                                <?php 
                                                // Use highlights if available, otherwise generate from includes
                                                $highlights = isset($tour['highlights']) ? explode("\n", $tour['highlights']) : explode("\n", $tour['includes']);
                                                $highlightIcons = ['explore', 'photo_camera', 'history_edu', 'restaurant', 'directions_bus', 'hotel'];
                                                
                                                foreach (array_slice($highlights, 0, 6) as $index => $highlight): 
                                                    if (trim($highlight)):
                                                ?>
                                                    <div class="highlight-card">
                                                        <div class="highlight-icon">
                                                            <i class="material-icons"><?php echo isset($highlightIcons[$index]) ? $highlightIcons[$index] : 'check_circle'; ?></i>
                                                        </div>
                                                        <h4><?php echo trim($highlight); ?></h4>
                                                    </div>
                                                <?php 
                                                    endif;
                                                endforeach; 
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Itinerary Tab -->
                            <div class="tab-content" id="itinerary">
                                <div class="content-box">
                                    <div class="content-header">
                                        <h2><?php _e('tour_itinerary'); ?></h2>
                                    </div>
                                    
                                    <div class="content-body">
                                        <div class="itinerary-timeline">
                                            <?php
                                            if (isset($tour['itinerary']) && !empty($tour['itinerary'])):
                                                // Parse itinerary as structured content
                                                $itinerary = explode("\n", $tour['itinerary']);
                                                $day = 1;
                                                $inItem = false;
                                                
                                                foreach ($itinerary as $line):
                                                    $line = trim($line);
                                                    if (empty($line)) continue;
                                                    
                                                    if (preg_match('/^Day\s+(\d+)/i', $line, $matches) || preg_match('/^(\d+)\.\s/i', $line, $matches)):
                                                        // Close previous item if open
                                                        if ($inItem):
                                                            echo '</div></div>';
                                                            $inItem = false;
                                                        endif;
                                                        
                                                        // Start new item
                                                        echo '<div class="itinerary-item">';
                                                        $inItem = true;
                                                        
                                                        if (isset($matches[1])):
                                                            $day = $matches[1];
                                                        endif;
                                                        
                                                        echo '<div class="itinerary-day">
                                                                <div class="day-marker">
                                                                    <span>' . $day . '</span>
                                                                </div>
                                                              </div>';
                                                        
                                                        // Extract title (after Day X:)
                                                        $title = preg_replace('/^Day\s+\d+[:\.\-\s]*/i', '', $line);
                                                        $title = preg_replace('/^\d+\.\s*/i', '', $title);
                                                        
                                                        echo '<div class="itinerary-content">';
                                                        if (!empty($title)):
                                                            echo '<h3 class="itinerary-title">' . $title . '</h3>';
                                                        endif;
                                                        
                                                        echo '<div class="itinerary-details">';
                                                    else:
                                                        // Regular content line
                                                        if (!$inItem):
                                                            // Start new item if not already in one
                                                            echo '<div class="itinerary-item">';
                                                            echo '<div class="itinerary-day">
                                                                    <div class="day-marker">
                                                                        <span>' . $day . '</span>
                                                                    </div>
                                                                  </div>';
                                                            echo '<div class="itinerary-content">';
                                                            echo '<h3 class="itinerary-title">' . __('activities') . '</h3>';
                                                            echo '<div class="itinerary-details">';
                                                            $inItem = true;
                                                        endif;
                                                        
                                                        echo '<p>' . $line . '</p>';
                                                    endif;
                                                    
                                                    $day++;
                                                endforeach;
                                                
                                                // Close last item if open
                                                if ($inItem):
                                                    echo '</div></div></div>';
                                                endif;
                                            else:
                                                echo '<div class="no-content-message">';
                                                echo '<i class="material-icons">info</i>';
                                                echo '<p>' . __('no_itinerary_available') . '</p>';
                                                echo '</div>';
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Includes/Excludes Tab -->
                            <div class="tab-content" id="includes">
                                <div class="content-box">
                                    <div class="content-header">
                                        <h2><?php _e('whats_included'); ?></h2>
                                    </div>
                                    
                                    <div class="content-body">
                                        <div class="includes-excludes">
                                            <div class="includes-section">
                                                <h3><?php _e('tour_includes'); ?></h3>
                                                <ul class="includes-list">
                                                    <?php
                                                    // Parse includes as list
                                                    $includes = explode("\n", $tour['includes']);
                                                    foreach ($includes as $include):
                                                        if (trim($include)):
                                                    ?>
                                                        <li>
                                                            <i class="material-icons">check_circle</i>
                                                            <span><?php echo trim($include); ?></span>
                                                        </li>
                                                    <?php
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                </ul>
                                            </div>
                                            
                                            <div class="excludes-section">
                                                <h3><?php _e('tour_excludes'); ?></h3>
                                                <ul class="excludes-list">
                                                    <?php
                                                    // Parse excludes as list
                                                    if (isset($tour['excludes']) && !empty($tour['excludes'])):
                                                        $excludes = explode("\n", $tour['excludes']);
                                                        foreach ($excludes as $exclude):
                                                            if (trim($exclude)):
                                                    ?>
                                                        <li>
                                                            <i class="material-icons">remove_circle</i>
                                                            <span><?php echo trim($exclude); ?></span>
                                                        </li>
                                                    <?php
                                                            endif;
                                                        endforeach;
                                                    else:
                                                    ?>
                                                        <li class="no-excludes">
                                                            <i class="material-icons">info</i>
                                                            <span><?php _e('no_excludes_specified'); ?></span>
                                                        </li>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        <!-- Additional Info -->
                                        <div class="additional-info">
                                            <h3><?php _e('additional_info'); ?></h3>
                                            <div class="info-cards">
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="material-icons">directions_walk</i>
                                                    </div>
                                                    <h4><?php _e('activity_level'); ?></h4>
                                                    <p><?php _e('moderate'); ?></p>
                                                </div>
                                                
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="material-icons">schedule</i>
                                                    </div>
                                                    <h4><?php _e('tour_starts'); ?></h4>
                                                    <p>08:00 AM</p>
                                                </div>
                                                
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="material-icons">event_available</i>
                                                    </div>
                                                    <h4><?php _e('availability'); ?></h4>
                                                    <p><?php _e('daily'); ?></p>
                                                </div>
                                                
                                                <div class="info-card">
                                                    <div class="info-icon">
                                                        <i class="material-icons">update</i>
                                                    </div>
                                                    <h4><?php _e('cancellation'); ?></h4>
                                                    <p><?php _e('free_cancellation_24h'); ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Location Tab -->
                            <div class="tab-content" id="location">
                                <div class="content-box">
                                    <div class="content-header">
                                        <h2><?php _e('tour_location'); ?></h2>
                                    </div>
                                    
                                    <div class="content-body">
                                        <div class="location-map-container">
                                            <div id="tour_map" class="location-map" data-lat="38.642335" data-lng="34.827335" data-zoom="13" data-title="<?php echo $tour['name']; ?>"></div>
                                        </div>
                                        
                                        <div class="meeting-point">
                                            <h3><?php _e('meeting_point'); ?></h3>
                                            <div class="meeting-info">
                                                <div class="meeting-icon">
                                                    <i class="material-icons">location_on</i>
                                                </div>
                                                <div class="meeting-details">
                                                    <p><?php _e('meeting_point_description'); ?></p>
                                                    <a href="#" class="btn-link get-directions-btn">
                                                        <i class="material-icons">directions</i>
                                                        <?php _e('get_directions'); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Reviews Tab -->
                            <div class="tab-content" id="reviews">
                                <div class="content-box">
                                    <div class="content-header">
                                        <h2><?php _e('customer_reviews'); ?></h2>
                                    </div>
                                    
                                    <div class="content-body">
                                        <div class="reviews-summary">
                                            <div class="overall-rating">
                                                <div class="rating-number"><?php echo number_format($rating, 1); ?></div>
                                                <div class="rating-stars">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="material-icons"><?php echo $i <= $rating ? 'star' : 'star_border'; ?></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <div class="rating-count">
                                                    <?php echo isset($tour['reviews_count']) ? $tour['reviews_count'] : rand(15, 50); ?> <?php _e('reviews'); ?>
                                                </div>
                                            </div>
                                            
                                            <div class="rating-breakdown">
                                                <?php 
                                                $categories = [
                                                    'value' => [__('value_for_money'), 4.8],
                                                    'guide' => [__('guide'), 4.9],
                                                    'experience' => [__('experience'), 4.7],
                                                    'safety' => [__('safety'), 5.0]
                                                ];
                                                
                                                foreach ($categories as $key => $category): 
                                                ?>
                                                    <div class="rating-category">
                                                        <div class="category-name"><?php echo $category[0]; ?></div>
                                                        <div class="category-bar">
                                                            <div class="category-value" style="width: <?php echo ($category[1] / 5) * 100; ?>%;"></div>
                                                        </div>
                                                        <div class="category-score"><?php echo number_format($category[1], 1); ?></div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Reviews List -->
                                        <div class="reviews-list">
                                            <?php 
                                            // Sample reviews
                                            $reviews = [
                                                [
                                                    'name' => 'John Smith',
                                                    'date' => '2023-10-15',
                                                    'rating' => 5,
                                                    'content' => 'Amazing experience! The hot air balloon ride was absolutely breathtaking. Our guide was knowledgeable and friendly. Would highly recommend this tour to anyone visiting Cappadocia.',
                                                    'image' => 'avatar-1.jpg'
                                                ],
                                                [
                                                    'name' => 'Maria Garcia',
                                                    'date' => '2023-09-22',
                                                    'rating' => 4,
                                                    'content' => 'Great tour overall. Beautiful landscapes and interesting history. The only downside was that it was a bit rushed in some areas. Still definitely worth it!',
                                                    'image' => 'avatar-2.jpg'
                                                ],
                                                [
                                                    'name' => 'Hiroshi Tanaka',
                                                    'date' => '2023-08-30',
                                                    'rating' => 5,
                                                    'content' => 'Perfect day trip! The underground city was fascinating and the views from the valley were spectacular. Our guide spoke excellent English and was very informative.',
                                                    'image' => 'avatar-3.jpg'
                                                ]
                                            ];
                                            
                                            foreach ($reviews as $review): 
                                            ?>
                                                <div class="review-item">
                                                    <div class="review-header">
                                                        <div class="reviewer">
                                                            <div class="reviewer-avatar">
                                                                <img src="<?php echo $imgUrl; ?>/<?php echo $review['image']; ?>" alt="<?php echo $review['name']; ?>">
                                                            </div>
                                                            <div class="reviewer-info">
                                                                <h4><?php echo $review['name']; ?></h4>
                                                                <div class="review-date"><?php echo date('F j, Y', strtotime($review['date'])); ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="review-rating">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <i class="material-icons"><?php echo $i <= $review['rating'] ? 'star' : 'star_border'; ?></i>
                                                            <?php endfor; ?>
                                                        </div>
                                                    </div>
                                                    <div class="review-content">
                                                        <p><?php echo $review['content']; ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                            
                                            <div class="reviews-actions">
                                                <button class="btn btn-outline load-more-reviews">
                                                    <i class="material-icons">refresh</i>
                                                    <?php _e('load_more_reviews'); ?>
                                                </button>
                                                <button class="btn btn-outline write-review">
                                                    <i class="material-icons">rate_review</i>
                                                    <?php _e('write_review'); ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Share Section -->
                    <div class="share-section" data-aos="fade-up">
                        <h3><?php _e('share_this_tour'); ?></h3>
                        <div class="share-buttons">
                            <a href="#" class="share-button facebook" data-type="facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="share-button twitter" data-type="twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="share-button whatsapp" data-type="whatsapp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="#" class="share-button pinterest" data-type="pinterest" data-image="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>">
                                <i class="fab fa-pinterest-p"></i>
                            </a>
                            <a href="#" class="share-button email" data-type="email">
                                <i class="fas fa-envelope"></i>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Related Tours -->
                    <?php if (isset($relatedTours) && !empty($relatedTours)): ?>
                    <div class="related-tours" data-aos="fade-up">
                        <h2><?php _e('you_might_also_like'); ?></h2>
                        <div class="related-tours-slider">
                            <?php foreach ($relatedTours as $relatedTour): ?>
                                <div class="related-tour-card">
                                    <div class="related-tour-image">
                                        <img src="<?php echo $uploadsUrl . '/tours/' . $relatedTour['featured_image']; ?>" alt="<?php echo $relatedTour['name']; ?>">
                                        <div class="related-tour-price">
                                            <?php if (isset($relatedTour['discount_price']) && $relatedTour['discount_price']): ?>
                                                <del><?php echo $settings['currency_symbol'] . number_format($relatedTour['price'], 2); ?></del>
                                                <?php echo $settings['currency_symbol'] . number_format($relatedTour['discount_price'], 2); ?>
                                            <?php else: ?>
                                                <?php echo $settings['currency_symbol'] . number_format($relatedTour['price'], 2); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="related-tour-content">
                                        <h3 class="related-tour-title">
                                            <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $relatedTour['slug']; ?>">
                                                <?php echo $relatedTour['name']; ?>
                                            </a>
                                        </h3>
                                        <div class="related-tour-meta">
                                            <div class="tour-meta-item">
                                                <i class="material-icons">schedule</i>
                                                <span><?php echo $relatedTour['duration']; ?></span>
                                            </div>
                                            <div class="tour-rating">
                                                <?php 
                                                $relatedRating = isset($relatedTour['rating']) ? $relatedTour['rating'] : 5;
                                                for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="material-icons"><?php echo $i <= $relatedRating ? 'star' : 'star_border'; ?></i>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Booking Sidebar -->
                <div class="tour-sidebar">
                    <!-- Booking Form Card -->
                    <div class="booking-form-card" id="booking-form" data-aos="fade-left">
                        <div class="booking-form-header">
                            <h3><?php _e('book_this_tour'); ?></h3>
                        </div>
                        
                        <!-- Price Display -->
                        <div class="booking-price-display">
                            <?php if (isset($tour['discount_price']) && $tour['discount_price']): ?>
                                <div class="booking-price-info">
                                    <div class="old-price"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></div>
                                    <div class="current-price"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></div>
                                    <div class="price-per-person"><?php _e('per_person'); ?></div>
                                </div>
                                <div class="discount-badge">
                                    <?php echo round((($tour['price'] - $tour['discount_price']) / $tour['price']) * 100); ?>% <?php _e('off'); ?>
                                </div>
                            <?php else: ?>
                                <div class="booking-price-info">
                                    <div class="current-price"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></div>
                                    <div class="price-per-person"><?php _e('per_person'); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Limited Time Offer -->
                        <div class="limited-offer">
                            <i class="material-icons">timelapse</i>
                            <div class="offer-text">
                                <span class="offer-title"><?php _e('special_offer'); ?></span>
                                <span class="offer-desc"><?php _e('limited_time_offer'); ?></span>
                            </div>
                        </div>
                        
                        <!-- Booking Form -->
                        <form action="<?php echo $appUrl . '/' . $currentLang; ?>/booking/tour/<?php echo $tour['id']; ?>" method="get" class="booking-form">
                            <div class="form-group">
                                <label for="booking_date"><?php _e('select_date'); ?></label>
                                <div class="input-with-icon">
                                    <i class="material-icons">event</i>
                                    <input type="text" id="booking_date" name="date" class="form-control" placeholder="<?php _e('choose_date'); ?>" required>
                                </div>
                                
                                <!-- Date Availability -->
                                <div class="date-availability">
                                    <div class="availability-label"><?php _e('availability'); ?>:</div>
                                    <div class="availability-items">
                                        <div class="availability-item available">
                                            <div class="availability-date">Today</div>
                                            <div class="availability-status"><?php _e('available'); ?></div>
                                        </div>
                                        <div class="availability-item available">
                                            <div class="availability-date">Tomorrow</div>
                                            <div class="availability-status"><?php _e('available'); ?></div>
                                        </div>
                                        <div class="availability-item limited">
                                            <div class="availability-date"><?php echo date('M d', strtotime('+2 days')); ?></div>
                                            <div class="availability-status"><?php _e('few_spots'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label><?php _e('participants'); ?></label>
                                <div class="guests-selector">
                                    <div class="guest-type">
                                        <div class="guest-info">
                                            <span class="guest-label"><?php _e('adults'); ?></span>
                                            <span class="guest-age"><?php _e('age_12_plus'); ?></span>
                                        </div>
                                        <div class="guest-counter">
                                            <button type="button" class="counter-btn decrease">
                                                <i class="material-icons">remove</i>
                                            </button>
                                            <input type="number" name="adults" id="booking_adults" value="2" min="1" max="10">
                                            <button type="button" class="counter-btn increase">
                                                <i class="material-icons">add</i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div class="guest-type">
                                        <div class="guest-info">
                                            <span class="guest-label"><?php _e('children'); ?></span>
                                            <span class="guest-age"><?php _e('age_2_11'); ?></span>
                                        </div>
                                        <div class="guest-counter">
                                            <button type="button" class="counter-btn decrease">
                                                <i class="material-icons">remove</i>
                                            </button>
                                            <input type="number" name="children" id="booking_children" value="0" min="0" max="10">
                                            <button type="button" class="counter-btn increase">
                                                <i class="material-icons">add</i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Booking Summary -->
                            <div class="booking-summary">
                                <div class="summary-row">
                                    <div class="summary-label"><?php _e('adults'); ?></div>
                                    <div class="summary-value">
                                        <span id="summary_adults">2</span> × 
                                        <span class="summary-price">
                                            <?php echo $settings['currency_symbol'] . number_format(isset($tour['discount_price']) && $tour['discount_price'] ? $tour['discount_price'] : $tour['price'], 2); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="summary-row" id="children_row" style="display: none;">
                                    <div class="summary-label"><?php _e('children'); ?></div>
                                    <div class="summary-value">
                                        <span id="summary_children">0</span> × 
                                        <span class="summary-price">
                                            <?php echo $settings['currency_symbol'] . number_format((isset($tour['discount_price']) && $tour['discount_price'] ? $tour['discount_price'] : $tour['price']) * 0.5, 2); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="summary-row total">
                                    <div class="summary-label"><?php _e('total'); ?></div>
                                    <div class="summary-value" id="summary_total">
                                        <?php 
                                        $price = isset($tour['discount_price']) && $tour['discount_price'] ? $tour['discount_price'] : $tour['price'];
                                        echo $settings['currency_symbol'] . number_format($price * 2, 2); 
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" id="booking_base_price" value="<?php echo $tour['price']; ?>">
                            <input type="hidden" id="booking_discount_price" value="<?php echo isset($tour['discount_price']) ? $tour['discount_price'] : 0; ?>">
                            <input type="hidden" id="currency_symbol" value="<?php echo $settings['currency_symbol']; ?>">
                            
                            <!-- Book Now Button -->
                            <button type="submit" class="btn btn-primary btn-lg btn-block book-now-btn">
                                <i class="material-icons">shopping_cart</i>
                                <?php _e('book_now'); ?>
                            </button>
                            
                            <!-- Instant Confirmation Badge -->
                            <div class="instant-confirmation">
                                <i class="material-icons">verified</i>
                                <span><?php _e('instant_confirmation'); ?></span>
                            </div>
                        </form>
                        
                        <!-- Trust Badges -->
                        <div class="trust-badges">
                            <div class="trust-badge">
                                <i class="material-icons">verified_user</i>
                                <span><?php _e('secure_payments'); ?></span>
                            </div>
                            <div class="trust-badge">
                                <i class="material-icons">update</i>
                                <span><?php _e('free_cancellation'); ?></span>
                            </div>
                            <div class="trust-badge">
                                <i class="material-icons">support_agent</i>
                                <span><?php _e('24_7_support'); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Why Choose Us Card -->
                    <div class="why-choose-card" data-aos="fade-left">
                        <h3><?php _e('why_choose_us'); ?></h3>
                        <div class="benefits-list">
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="material-icons">language</i>
                                </div>
                                <div class="benefit-content">
                                    <h4><?php _e('multilingual_guides'); ?></h4>
                                    <p><?php _e('multilingual_guides_desc'); ?></p>
                                </div>
                            </div>
                            
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="material-icons">stars</i>
                                </div>
                                <div class="benefit-content">
                                    <h4><?php _e('experienced_team'); ?></h4>
                                    <p><?php _e('experienced_team_desc'); ?></p>
                                </div>
                            </div>
                            
                            <div class="benefit-item">
                                <div class="benefit-icon">
                                    <i class="material-icons">favorite</i>
                                </div>
                                <div class="benefit-content">
                                    <h4><?php _e('personalized_service'); ?></h4>
                                    <p><?php _e('personalized_service_desc'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Help Card -->
                    <div class="help-card" data-aos="fade-left">
                        <h3><?php _e('need_help'); ?></h3>
                        <div class="help-contact">
                            <div class="contact-item">
                                <i class="material-icons">phone</i>
                                <div class="contact-info">
                                    <div class="contact-label"><?php _e('call_us'); ?></div>
                                    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" class="contact-value"><?php echo $settings['contact_phone']; ?></a>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="material-icons">email</i>
                                <div class="contact-info">
                                    <div class="contact-label"><?php _e('email_us'); ?></div>
                                    <a href="mailto:<?php echo $settings['contact_email']; ?>" class="contact-value"><?php echo $settings['contact_email']; ?></a>
                                </div>
                            </div>
                            
                            <div class="contact-item">
                                <i class="fab fa-whatsapp"></i>
                                <div class="contact-info">
                                    <div class="contact-label"><?php _e('whatsapp'); ?></div>
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" class="contact-value" target="_blank"><?php _e('chat_with_us'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fixed Booking Bar for Mobile -->
    <div class="mobile-booking-bar">
        <div class="mobile-price">
            <?php if (isset($tour['discount_price']) && $tour['discount_price']): ?>
                <div class="price-wrapper">
                    <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                    <span class="current-price"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
                </div>
            <?php else: ?>
                <span class="current-price"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
            <?php endif; ?>
        </div>
        <a href="#booking-form" class="btn btn-primary book-now-btn-mobile">
            <?php _e('book_now'); ?>
        </a>
    </div>

    <!-- Custom CSS Styles -->
    <style>
    /* Tour Hero Styles */
    .tour-hero {
        position: relative;
        width: 100%;
        height: auto;
    }
    
    .tour-hero-image {
        position: relative;
        height: 600px;
        background-size: cover;
        background-position: center;
        color: var(--white-color);
    }
    
    .tour-hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.7));
    }
    
    .tour-hero-content {
        position: relative;
        z-index: 2;
        padding: 3rem 0;
        max-width: 800px;
    }
    
    .tour-hero-header {
        margin-bottom: 2rem;
    }
    
    .tour-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: var(--primary-color);
        color: var(--white-color);
        font-size: 0.875rem;
        font-weight: 600;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .tour-badge.popular {
        background-color: #f5a623;
    }
    
    .tour-badge.new {
        background-color: #00bcd4;
    }
    
    .tour-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--white-color);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }
    
    .tour-rating-wrapper {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .tour-rating {
        display: flex;
        align-items: center;
        color: #FFD700;
    }
    
    .tour-rating .material-icons {
        font-size: 1.25rem;
    }
    
    .rating-value {
        margin-left: 0.5rem;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--white-color);
    }
    
    .rating-count {
        margin-left: 0.5rem;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.875rem;
    }
    
    .tour-highlights-banner {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        padding: 1.5rem;
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }
    
    .tour-highlight {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .tour-highlight i {
        font-size: 1.5rem;
        color: var(--primary-color);
    }
    
    .highlight-info {
        display: flex;
        flex-direction: column;
    }
    
    .highlight-label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
    }
    
    .highlight-value {
        font-weight: 600;
        color: var(--white-color);
    }
    
    /* Tour Content Section */
    .tour-content-section {
        padding: 4rem 0;
        background-color: #f8f9fa;
    }
    
    .tour-content-wrapper {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }
    
    /* Gallery Styles */
    .tour-gallery {
        margin-bottom: 2rem;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .gallery-main {
        position: relative;
        height: 500px;
        overflow: hidden;
    }
    
    .gallery-main-slide {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.5s ease;
        display: none;
    }
    
    .gallery-main-slide.active {
        opacity: 1;
        display: block;
    }
    
    .gallery-main-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .gallery-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        z-index: 2;
    }
    
    .gallery-nav:hover {
        background-color: var(--white-color);
        transform: translateY(-50%) scale(1.1);
    }
    
    .gallery-nav.prev {
        left: 20px;
    }
    
    .gallery-nav.next {
        right: 20px;
    }
    
    .gallery-thumbs {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 0.5rem;
        padding: 0.5rem;
        background-color: var(--white-color);
    }
    
    .gallery-thumb {
        height: 80px;
        border-radius: 5px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        opacity: 0.7;
    }
    
    .gallery-thumb:hover, .gallery-thumb.active {
        opacity: 1;
        transform: translateY(-3px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }
    
    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Mobile Price Display */
    .tour-price-mobile {
        display: none;
        margin-bottom: 2rem;
        padding: 1rem;
        border-radius: 10px;
        background-color: var(--white-color);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .price-details {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    
    .price-wrapper {
        display: flex;
        flex-direction: column;
    }
    
    .old-price {
        font-size: 0.875rem;
        color: var(--gray-600);
        text-decoration: line-through;
    }
    
    .current-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .price-per {
        font-size: 0.75rem;
        color: var(--gray-600);
    }
    
    .discount-badge {
        background-color: var(--primary-color);
        color: var(--white-color);
        padding: 0.25rem 0.75rem;
        border-radius: 5px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    /* Tour Tabs */
    .tour-tabs {
        margin-bottom: 2rem;
        background-color: var(--white-color);
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .tabs-nav {
        display: flex;
        overflow-x: auto;
        background-color: var(--white-color);
        border-bottom: 1px solid #eaeaea;
    }
    
    .tab-button {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: var(--gray-700);
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        white-space: nowrap;
        position: relative;
    }
    
    .tab-button:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 3px;
        background-color: var(--primary-color);
        transition: width 0.3s ease;
    }
    
    .tab-button.active {
        color: var(--primary-color);
    }
    
    .tab-button.active:after {
        width: 100%;
    }
    
    .tab-contents {
        padding: 0;
    }
    
    .tab-content {
        display: none;
    }
    
    .tab-content.active {
        display: block;
    }
    
    .content-box {
        padding: 0;
    }
    
    .content-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eaeaea;
    }
    
    .content-header h2 {
        margin: 0;
        font-size: 1.5rem;
        color: var(--dark-color);
    }
    
    .content-body {
        padding: 1.5rem;
    }
    
    /* Tour Overview */
    .tour-summary {
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-left: 4px solid var(--primary-color);
        border-radius: 0 5px 5px 0;
        margin-bottom: 1.5rem;
    }
    
    .tour-description {
        margin-bottom: 2rem;
        line-height: 1.7;
    }
    
    .experience-highlights h3 {
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        color: var(--dark-color);
    }
    
    .highlights-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .highlight-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .highlight-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transform: translateY(-3px);
    }
    
    .highlight-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .highlight-card h4 {
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-color);
    }
    
    /* Itinerary Styles */
    .itinerary-timeline {
        position: relative;
    }
    
    .itinerary-item {
        display: flex;
        margin-bottom: 2rem;
    }
    
    .itinerary-day {
        position: relative;
        min-width: 60px;
        display: flex;
        justify-content: center;
    }
    
    .day-marker {
        position: relative;
        z-index: 2;
        width: 50px;
        height: 50px;
        background-color: var(--primary-color);
        color: var(--white-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
    }
    
    .itinerary-item:not(:last-child) .itinerary-day:after {
        content: '';
        position: absolute;
        top: 50px;
        left: 50%;
        transform: translateX(-50%);
        width: 2px;
        height: calc(100% - 25px);
        background-color: #e0e0e0;
        z-index: 1;
    }
    
    .itinerary-content {
        flex: 1;
        padding-left: 1.5rem;
    }
    
    .itinerary-title {
        margin-top: 0;
        margin-bottom: 1rem;
        font-size: 1.25rem;
        color: var(--dark-color);
    }
    
    .itinerary-details {
        line-height: 1.7;
        color: var(--gray-700);
    }
    
    .no-content-message {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        padding: 2rem;
        text-align: center;
        color: var(--gray-600);
    }
    
    .no-content-message i {
        font-size: 3rem;
        color: var(--gray-400);
    }
    
    /* Includes/Excludes Styles */
    .includes-excludes {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .includes-section h3,
    .excludes-section h3 {
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        color: var(--dark-color);
    }
    
    .includes-list,
    .excludes-list {
        display: grid;
        gap: 1rem;
    }
    
    .includes-list li,
    .excludes-list li {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .includes-list li i {
        color: var(--success-color);
        font-size: 1.25rem;
    }
    
    .excludes-list li i {
        color: var(--danger-color);
        font-size: 1.25rem;
    }
    
    .additional-info h3 {
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        color: var(--dark-color);
    }
    
    .info-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.5rem;
    }
    
    .info-card {
        text-align: center;
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .info-card:hover {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transform: translateY(-3px);
    }
    
    .info-icon {
        width: 60px;
        height: 60px;
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
    }
    
    .info-card h4 {
        margin-bottom: 0.5rem;
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .info-card p {
        color: var(--gray-600);
        margin: 0;
    }
    
    /* Location Styles */
    .location-map-container {
        margin-bottom: 2rem;
    }
    
    .location-map {
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .meeting-point {
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .meeting-point h3 {
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        color: var(--dark-color);
    }
    
    .meeting-info {
        display: flex;
        gap: 1.5rem;
    }
    
    .meeting-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .meeting-details p {
        margin-bottom: 1rem;
        color: var(--gray-700);
    }
    
    .btn-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .btn-link:hover {
        text-decoration: underline;
    }
    
    /* Reviews Styles */
    .reviews-summary {
        display: flex;
        gap: 2rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .overall-rating {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-right: 2rem;
        border-right: 1px solid #e0e0e0;
    }
    
    .rating-number {
        font-size: 3rem;
        font-weight: 700;
        color: var(--dark-color);
        line-height: 1;
    }
    
    .rating-stars {
        margin: 0.5rem 0;
        color: #FFD700;
    }
    
    .rating-count {
        font-size: 0.875rem;
        color: var(--gray-600);
    }
    
    .rating-breakdown {
        flex: 1;
    }
    
    .rating-category {
        display: grid;
        grid-template-columns: 120px 1fr 40px;
        gap: 1rem;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .category-name {
        font-size: 0.875rem;
        color: var(--gray-700);
    }
    
    .category-bar {
        height: 8px;
        background-color: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .category-value {
        height: 100%;
        background-color: var(--primary-color);
        border-radius: 4px;
    }
    
    .category-score {
        font-weight: 600;
        color: var(--dark-color);
        text-align: right;
    }
    
    .reviews-list {
        margin-bottom: 2rem;
    }
    
    .review-item {
        padding: 1.5rem 0;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .review-item:last-child {
        border-bottom: none;
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }
    
    .reviewer {
        display: flex;
        gap: 1rem;
    }
    
    .reviewer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        overflow: hidden;
    }
    
    .reviewer-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .reviewer-info h4 {
        margin: 0 0 0.25rem;
        font-size: 1rem;
        color: var(--dark-color);
    }
    
    .review-date {
        font-size: 0.875rem;
        color: var(--gray-600);
    }
    
    .review-rating {
        color: #FFD700;
    }
    
    .review-content p {
        margin: 0;
        color: var(--gray-700);
        line-height: 1.7;
    }
    
    .reviews-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    
    /* Share Section */
    .share-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: var(--white-color);
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        text-align: center;
    }
    
    .share-section h3 {
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        color: var(--dark-color);
    }
    
    .share-buttons {
        display: flex;
        justify-content: center;
        gap: 1rem;
    }
    
    .share-button {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white-color);
        font-size: 1.25rem;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }
    
    .share-button:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .share-button.facebook {
        background-color: #3b5998;
    }
    
    .share-button.twitter {
        background-color: #1da1f2;
    }
    
    .share-button.whatsapp {
        background-color: #25d366;
    }
    
    .share-button.pinterest {
        background-color: #bd081c;
    }
    
    .share-button.email {
        background-color: #dd4b39;
    }
    
    /* Related Tours */
    .related-tours {
        margin-bottom: 2rem;
    }
    
    .related-tours h2 {
        margin-bottom: 1.5rem;
        font-size: 1.5rem;
        color: var(--dark-color);
    }
    
    .related-tours-slider {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    
    .related-tour-card {
        background-color: var(--white-color);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .related-tour-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .related-tour-image {
        position: relative;
        height: 180px;
    }
    
    .related-tour-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .related-tour-price {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: var(--primary-color);
        color: var(--white-color);
        padding: 0.5rem 0.75rem;
        border-radius: 5px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .related-tour-price del {
        font-size: 0.75rem;
        opacity: 0.8;
        margin-right: 0.25rem;
    }
    
    .related-tour-content {
        padding: 1rem;
    }
    
    .related-tour-title {
        margin: 0 0 0.5rem;
        font-size: 1rem;
    }
    
    .related-tour-title a {
        color: var(--dark-color);
        transition: color 0.3s ease;
    }
    
    .related-tour-title a:hover {
        color: var(--primary-color);
    }
    
    .related-tour-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* Booking Sidebar */
    .tour-sidebar {
        position: relative;
    }
    
    .booking-form-card {
        background-color: var(--white-color);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
        position: sticky;
        top: 2rem;
    }
    
    .booking-form-header {
        background-color: var(--primary-color);
        color: var(--white-color);
        padding: 1.5rem;
        text-align: center;
    }
    
    .booking-form-header h3 {
        margin: 0;
        font-size: 1.5rem;
        color: var(--white-color);
    }
    
    .booking-price-display {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #eaeaea;
    }
    
    .booking-price-info {
        display: flex;
        flex-direction: column;
    }
    
    .old-price {
        font-size: 0.875rem;
        color: var(--gray-600);
        text-decoration: line-through;
    }
    
    .current-price {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary-color);
    }
    
    .price-per-person {
        font-size: 0.75rem;
        color: var(--gray-600);
    }
    
    .limited-offer {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.5rem;
        background-color: #fff8e1;
        border-bottom: 1px solid #eaeaea;
    }
    
    .limited-offer i {
        color: #f57c00;
        font-size: 1.5rem;
    }
    
    .offer-text {
        display: flex;
        flex-direction: column;
    }
    
    .offer-title {
        font-weight: 600;
        color: #f57c00;
    }
    
    .offer-desc {
        font-size: 0.875rem;
        color: var(--gray-700);
    }
    
    .booking-form {
        padding: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .input-with-icon {
        position: relative;
    }
    
    .input-with-icon i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-600);
    }
    
    .input-with-icon input {
        padding-left: 3rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.1);
        outline: none;
    }
    
    .date-availability {
        margin-top: 1rem;
    }
    
    .availability-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }
    
    .availability-items {
        display: flex;
        gap: 0.5rem;
    }
    
    .availability-item {
        flex: 1;
        padding: 0.5rem;
        border-radius: 5px;
        text-align: center;
        font-size: 0.75rem;
    }
    
    .availability-item.available {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border: 1px solid rgba(40, 167, 69, 0.2);
    }
    
    .availability-item.limited {
        background-color: rgba(255, 193, 7, 0.1);
        color: #ffc107;
        border: 1px solid rgba(255, 193, 7, 0.2);
    }
    
    .availability-date {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .guests-selector {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
    }
    
    .guest-type {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #eaeaea;
    }
    
    .guest-type:last-child {
        border-bottom: none;
    }
    
    .guest-info {
        display: flex;
        flex-direction: column;
    }
    
    .guest-label {
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .guest-age {
        font-size: 0.75rem;
        color: var(--gray-600);
    }
    
    .guest-counter {
        display: flex;
        align-items: center;
    }
    
    .counter-btn {
        width: 36px;
        height: 36px;
        border-radius: 5px;
        background-color: var(--white-color);
        border: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .counter-btn:hover {
        background-color: var(--primary-color);
        color: var(--white-color);
        border-color: var(--primary-color);
    }
    
    .counter-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .guest-counter input {
        width: 40px;
        height: 36px;
        text-align: center;
        border: 1px solid #e0e0e0;
        margin: 0 0.5rem;
    }
    
    .booking-summary {
        margin-top: 1.5rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }
    
    .summary-row.total {
        margin-top: 0.5rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e0e0e0;
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .book-now-btn {
        margin-top: 1.5rem;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        width: 100%;
    }
    
    .instant-confirmation {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1rem;
        padding: 0.75rem;
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    .trust-badges {
        border-top: 1px solid #eaeaea;
        margin-top: 1.5rem;
        padding: 1.5rem;
    }
    
    .trust-badge {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .trust-badge:last-child {
        margin-bottom: 0;
    }
    
    .trust-badge i {
        color: #28a745;
    }
    
    .why-choose-card,
    .help-card {
        background-color: var(--white-color);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .why-choose-card h3,
    .help-card h3 {
        margin-bottom: 1.5rem;
        font-size: 1.25rem;
        color: var(--dark-color);
        text-align: center;
    }
    
    .benefits-list {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .benefit-item {
        display: flex;
        gap: 1rem;
    }
    
    .benefit-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .benefit-content h4 {
        margin: 0 0 0.5rem;
        font-size: 1rem;
        color: var(--dark-color);
    }
    
    .benefit-content p {
        margin: 0;
        font-size: 0.875rem;
        color: var(--gray-600);
    }
    
    .help-contact {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .contact-item i {
        width: 40px;
        height: 40px;
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .contact-info {
        flex: 1;
    }
    
    .contact-label {
        font-size: 0.75rem;
        color: var(--gray-600);
    }
    
    .contact-value {
        font-weight: 600;
        color: var(--dark-color);
    }
    
    /* Mobile Booking Bar */
    .mobile-booking-bar {
        display: none;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: var(--white-color);
        padding: 1rem;
        box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
        z-index: 999;
    }
    
    .mobile-price {
        display: flex;
        flex-direction: column;
    }
    
    .price-wrapper del {
        font-size: 0.75rem;
        color: var(--gray-600);
    }
    
    .book-now-btn-mobile {
        display: block;
        width: 100%;
        padding: 0.75rem;
        text-align: center;
        background-color: var(--primary-color);
        color: var(--white-color);
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    
    .book-now-btn-mobile:hover {
        background-color: var(--primary-dark);
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .tour-hero-image {
            height: 400px;
        }
        
        .tour-highlights-banner {
            flex-wrap: wrap;
        }
        
        .tour-content-wrapper {
            grid-template-columns: 1fr;
        }
        
        .tour-sidebar {
            order: -1;
            margin-bottom: 2rem;
        }
        
        .booking-form-card {
            position: static;
        }
        
        .tour-price-mobile {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .includes-excludes {
            grid-template-columns: 1fr;
            gap: 2rem;
        }
        
        .info-cards {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .related-tours-slider {
            grid-template-columns: repeat(2, 1fr);
            overflow-x: auto;
            padding-bottom: 1rem;
        }
    }
    
    @media (max-width: 768px) {
        .tour-title {
            font-size: 2rem;
        }
        
        .tour-hero-image {
            height: 300px;
        }
        
        .tour-highlights-banner {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }
        
        .gallery-main {
            height: 300px;
        }
        
        .gallery-thumbs {
            grid-template-columns: repeat(4, 1fr);
        }
        
        .tabs-nav {
            overflow-x: auto;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
        }
        
        .tab-button {
            padding: 0.75rem 1rem;
        }
        
        .highlights-grid {
            grid-template-columns: 1fr;
        }
        
        .info-cards {
            grid-template-columns: 1fr;
        }
        
        .reviews-summary {
            flex-direction: column;
            gap: 2rem;
        }
        
        .overall-rating {
            padding-right: 0;
            padding-bottom: 2rem;
            border-right: none;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .review-header {
            flex-direction: column;
            gap: 1rem;
        }
        
        .mobile-booking-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
        }
        
        .related-tours-slider {
            grid-template-columns: 1fr;
        }
        
        body {
            padding-bottom: 80px;
        }
    }
    
    @media (max-width: 576px) {
        .tour-hero-content {
            padding: 2rem 0;
        }
        
        .tour-highlights-banner {
            grid-template-columns: 1fr;
        }
        
        .gallery-thumbs {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .share-buttons {
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .rating-category {
            grid-template-columns: 100px 1fr 40px;
        }
    }
    </style>

    <!-- JavaScript -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gallery Slider Functionality
        const galleryMain = document.querySelectorAll('.gallery-main-slide');
        const galleryThumbs = document.querySelectorAll('.gallery-thumb');
        const galleryPrev = document.querySelector('.gallery-nav.prev');
        const galleryNext = document.querySelector('.gallery-nav.next');
        
        let currentSlide = 0;
        const totalSlides = galleryMain.length;
        
        // Show slide function
        function showSlide(index) {
            // Hide all slides
            galleryMain.forEach(slide => {
                slide.classList.remove('active');
            });
            
            // Remove active class from all thumbs
            galleryThumbs.forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Show current slide
            galleryMain[index].classList.add('active');
            
            // Add active class to current thumb
            if (galleryThumbs.length > 0) {
                galleryThumbs[index].classList.add('active');
            }
            
            // Update current slide index
            currentSlide = index;
        }
        
        // Initialize first slide
        if (totalSlides > 0) {
            showSlide(0);
        }
        
        // Add click event to thumbs
        galleryThumbs.forEach((thumb, index) => {
            thumb.addEventListener('click', () => {
                showSlide(index);
            });
        });
        
        // Add click event to prev/next buttons
        if (galleryPrev && galleryNext && totalSlides > 1) {
            galleryPrev.addEventListener('click', () => {
                let prevSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(prevSlide);
            });
            
            galleryNext.addEventListener('click', () => {
                let nextSlide = (currentSlide + 1) % totalSlides;
                showSlide(nextSlide);
            });
        }
        
        // Tabs Functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');
        
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = button.getAttribute('data-target');
                
                // Remove active class from all buttons
                tabButtons.forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Add active class to clicked button
                button.classList.add('active');
                
                // Hide all contents
                tabContents.forEach(content => {
                    content.classList.remove('active');
                });
                
                // Show target content
                document.getElementById(target).classList.add('active');
                
                // Initialize map if location tab
                if (target === 'location' && window.google && window.google.maps) {
                    initMap();
                }
            });
        });
        
        // Initialize Map
        function initMap() {
            const mapElement = document.querySelector('.location-map');
            
            if (mapElement && window.google && window.google.maps) {
                const lat = parseFloat(mapElement.getAttribute('data-lat'));
                const lng = parseFloat(mapElement.getAttribute('data-lng'));
                const zoom = parseInt(mapElement.getAttribute('data-zoom'));
                const title = mapElement.getAttribute('data-title');
                
                const map = new google.maps.Map(mapElement, {
                    center: { lat, lng },
                    zoom: zoom,
                    mapTypeControl: false
                });
                
                const marker = new google.maps.Marker({
                    position: { lat, lng },
                    map: map,
                    title: title,
                })
            }
        }

    }