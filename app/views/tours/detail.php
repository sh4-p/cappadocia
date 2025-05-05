<?php
/**
 * Tour Detail View - Modern Redesign
 * Conversion-optimized layout with user-friendly features
 */
?>

<!-- Tour Header with Immersive Hero Image -->
<section class="tour-header" style="background-image: url('<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>');">
    <div class="container">
        <div class="tour-header-content">
            <!-- Breadcrumbs -->
            <div class="breadcrumbs">
                <ul class="breadcrumbs-list">
                    <li class="breadcrumbs-item"><a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a></li>
                    <li class="breadcrumbs-item"><a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours"><?php _e('tours'); ?></a></li>
                    <?php if (isset($tour['category_name']) && $tour['category_name']): ?>
                        <li class="breadcrumbs-item"><a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours?category=<?php echo $tour['category_slug']; ?>"><?php echo $tour['category_name']; ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumbs-item active"><?php echo $tour['name']; ?></li>
                </ul>
            </div>
            
            <!-- Tour Badge - New Element -->
            <?php if (isset($tour['is_popular']) && $tour['is_popular']): ?>
                <div class="tour-badge">
                    <i class="material-icons">star</i> <?php _e('popular'); ?>
                </div>
            <?php elseif (isset($tour['is_new']) && $tour['is_new']): ?>
                <div class="tour-badge">
                    <i class="material-icons">new_releases</i> <?php _e('new'); ?>
                </div>
            <?php endif; ?>
            
            <!-- Tour Title -->
            <h1 class="tour-title"><?php echo $tour['name']; ?></h1>
            
            <!-- Tour Meta Information -->
            <div class="tour-meta">
                <div class="tour-meta-item">
                    <i class="material-icons">schedule</i>
                    <span><?php echo $tour['duration']; ?></span>
                </div>
                <?php if (isset($tour['category_name']) && $tour['category_name']): ?>
                    <div class="tour-meta-item">
                        <i class="material-icons">category</i>
                        <span><?php echo $tour['category_name']; ?></span>
                    </div>
                <?php endif; ?>
                <div class="tour-meta-item">
                    <i class="material-icons">group</i>
                    <span><?php _e('max'); ?> 15 <?php _e('people'); ?></span>
                </div>
                <div class="tour-meta-item">
                    <i class="material-icons">language</i>
                    <span>English, Türkçe</span>
                </div>
            </div>
            
            <!-- Tour Rating - New Element -->
            <div class="tour-header-rating">
                <div class="rating-stars">
                    <?php 
                    $rating = isset($tour['rating']) ? $tour['rating'] : 5;
                    for ($i = 1; $i <= 5; $i++): ?>
                        <i class="material-icons"><?php echo $i <= $rating ? 'star' : 'star_border'; ?></i>
                    <?php endfor; ?>
                </div>
                <span class="rating-count"><?php echo isset($tour['reviews_count']) ? $tour['reviews_count'] : rand(15, 50); ?> <?php _e('reviews'); ?></span>
            </div>
            
            <!-- Tour Price Display -->
            <div class="tour-price">
                <?php if (isset($tour['discount_price']) && $tour['discount_price']): ?>
                    <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                    <span><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
                    <div class="tour-discount-badge">
                        <?php echo round((($tour['price'] - $tour['discount_price']) / $tour['price']) * 100); ?>% <?php _e('discount'); ?>
                    </div>
                <?php else: ?>
                    <span><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                <?php endif; ?>
                <div class="price-per-person"><?php _e('per_person'); ?></div>
            </div>
            
            <!-- Quick Action Buttons - New Element -->
            <div class="tour-quick-actions">
                <button class="action-btn btn-favorite" title="<?php _e('add_to_favorites'); ?>">
                    <i class="material-icons">favorite_border</i>
                </button>
                <button class="action-btn btn-share" title="<?php _e('share'); ?>">
                    <i class="material-icons">share</i>
                </button>
                <a href="#booking-section" class="btn btn-primary book-now-btn">
                    <i class="material-icons">shopping_cart</i>
                    <?php _e('book_now'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Tour Content Section with Sticky Sidebar -->
<section class="tour-section section">
    <div class="container">
        <div class="tour-content-wrapper">
            <!-- Tour Main Content -->
            <div class="tour-main-content">
                <!-- Tour Gallery Slider - New Visual Element -->
                <div class="tour-gallery-slider" data-aos="fade-up">
                    <div class="gallery-slider-main">
                        <?php if (isset($gallery) && !empty($gallery)): ?>
                            <?php foreach ($gallery as $index => $item): ?>
                                <div class="gallery-slide">
                                    <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" alt="<?php echo isset($item['title']) ? $item['title'] : $tour['name']; ?>">
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="gallery-slide">
                                <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (isset($gallery) && count($gallery) > 1): ?>
                        <div class="gallery-slider-thumbs">
                            <?php foreach ($gallery as $index => $item): ?>
                                <div class="gallery-thumb">
                                    <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" alt="<?php echo isset($item['title']) ? $item['title'] : $tour['name']; ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Tour Tabs - Enhanced Design -->
                <div class="tour-tabs" data-aos="fade-up">
                    <div class="tour-tabs-nav">
                        <button class="tour-tab-button active" data-target="overview"><?php _e('overview'); ?></button>
                        <button class="tour-tab-button" data-target="itinerary"><?php _e('itinerary'); ?></button>
                        <button class="tour-tab-button" data-target="includes"><?php _e('includes_excludes'); ?></button>
                        <button class="tour-tab-button" data-target="location"><?php _e('location'); ?></button>
                        <button class="tour-tab-button" data-target="reviews"><?php _e('reviews'); ?></button>
                    </div>
                    
                    <!-- Overview Tab - New Structure -->
                    <div class="tour-tab-content active" data-id="overview">
                        <div class="tour-overview">
                            <div class="tour-description">
                                <h2><?php _e('tour_description'); ?></h2>
                                <?php if (isset($tour['short_description'])): ?>
                                    <div class="overview-summary">
                                        <?php echo $tour['short_description']; ?>
                                    </div>
                                <?php endif; ?>
                                <?php echo $tour['description']; ?>
                            </div>
                            
                            <!-- Tour Highlights - New Element -->
                            <div class="tour-highlights">
                                <h3><?php _e('tour_highlights'); ?></h3>
                                <div class="highlights-grid">
                                    <?php 
                                    // Use highlights if available, otherwise generate from includes
                                    $highlights = isset($tour['highlights']) ? explode("\n", $tour['highlights']) : explode("\n", $tour['includes']);
                                    $highlightIcons = ['explore', 'photo_camera', 'history_edu', 'restaurant', 'directions_bus', 'hotel'];
                                    
                                    foreach (array_slice($highlights, 0, 6) as $index => $highlight): 
                                        if (trim($highlight)):
                                    ?>
                                        <div class="highlight-item">
                                            <div class="highlight-icon">
                                                <i class="material-icons"><?php echo isset($highlightIcons[$index]) ? $highlightIcons[$index] : 'check_circle'; ?></i>
                                            </div>
                                            <div class="highlight-content">
                                                <h4><?php echo trim($highlight); ?></h4>
                                            </div>
                                        </div>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Itinerary Tab - Enhanced Design -->
                    <div class="tour-tab-content" data-id="itinerary">
                        <div class="tour-itinerary">
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
                                        echo '<div class="tour-itinerary-item">';
                                        $inItem = true;
                                        
                                        if (isset($matches[1])):
                                            $day = $matches[1];
                                        endif;
                                        
                                        echo '<div class="tour-itinerary-day">
                                                <span class="day-number">' . $day . '</span>
                                                <span class="day-text">' . __('day') . '</span>
                                              </div>';
                                        
                                        // Extract title (after Day X:)
                                        $title = preg_replace('/^Day\s+\d+[:\.\-\s]*/i', '', $line);
                                        $title = preg_replace('/^\d+\.\s*/i', '', $title);
                                        
                                        echo '<div class="tour-itinerary-content">';
                                        if (!empty($title)):
                                            echo '<h3 class="tour-itinerary-title">' . $title . '</h3>';
                                        endif;
                                        
                                        echo '<div class="tour-itinerary-details">';
                                    else:
                                        // Regular content line
                                        if (!$inItem):
                                            // Start new item if not already in one
                                            echo '<div class="tour-itinerary-item">';
                                            echo '<div class="tour-itinerary-day">
                                                    <span class="day-number">' . $day . '</span>
                                                    <span class="day-text">' . __('day') . '</span>
                                                  </div>';
                                            echo '<div class="tour-itinerary-content">';
                                            echo '<h3 class="tour-itinerary-title">' . __('activities') . '</h3>';
                                            echo '<div class="tour-itinerary-details">';
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
                                echo '<div class="tour-no-itinerary">';
                                echo '<p>' . __('no_itinerary_available') . '</p>';
                                echo '</div>';
                            endif;
                            ?>
                        </div>
                    </div>
                    
                    <!-- Includes & Excludes Tab -->
                    <div class="tour-tab-content" data-id="includes">
                        <div class="includes-excludes-grid">
                            <!-- Tour Includes -->
                            <div class="tour-includes">
                                <h3><?php _e('tour_includes'); ?></h3>
                                <ul class="includes-list">
                                    <?php
                                    // Parse includes as list
                                    $includes = explode("\n", $tour['includes']);
                                    foreach ($includes as $include):
                                        if (trim($include)):
                                    ?>
                                        <li class="includes-item">
                                            <i class="material-icons">check_circle</i>
                                            <span><?php echo trim($include); ?></span>
                                        </li>
                                    <?php
                                        endif;
                                    endforeach;
                                    ?>
                                </ul>
                            </div>
                            
                            <!-- Tour Excludes -->
                            <div class="tour-excludes">
                                <h3><?php _e('tour_excludes'); ?></h3>
                                <ul class="excludes-list">
                                    <?php
                                    // Parse excludes as list
                                    if (isset($tour['excludes']) && !empty($tour['excludes'])):
                                        $excludes = explode("\n", $tour['excludes']);
                                        foreach ($excludes as $exclude):
                                            if (trim($exclude)):
                                    ?>
                                        <li class="excludes-item">
                                            <i class="material-icons">remove_circle</i>
                                            <span><?php echo trim($exclude); ?></span>
                                        </li>
                                    <?php
                                            endif;
                                        endforeach;
                                    else:
                                    ?>
                                        <li class="excludes-item">
                                            <i class="material-icons">info</i>
                                            <span><?php _e('no_excludes_specified'); ?></span>
                                        </li>
                                    <?php
                                    endif;
                                    ?>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Additional Information - New Element -->
                        <div class="tour-additional-info">
                            <h3><?php _e('additional_info'); ?></h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="material-icons">schedule</i>
                                    </div>
                                    <div class="info-content">
                                        <h4><?php _e('tour_duration'); ?></h4>
                                        <p><?php echo $tour['duration']; ?></p>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="material-icons">directions_walk</i>
                                    </div>
                                    <div class="info-content">
                                        <h4><?php _e('activity_level'); ?></h4>
                                        <p><?php _e('moderate'); ?></p>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="material-icons">groups</i>
                                    </div>
                                    <div class="info-content">
                                        <h4><?php _e('group_size'); ?></h4>
                                        <p><?php _e('min'); ?> 2, <?php _e('max'); ?> 15</p>
                                    </div>
                                </div>
                                
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="material-icons">access_time</i>
                                    </div>
                                    <div class="info-content">
                                        <h4><?php _e('tour_starts'); ?></h4>
                                        <p>08:00 AM</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Location Tab -->
                    <div class="tour-tab-content" data-id="location">
                        <div class="tour-location">
                            <!-- Tour Map Box -->
                            <div class="tour-map-container">
                                <h3><?php _e('tour_location'); ?></h3>
                                <div id="tour_map" class="tour-map" data-lat="38.642335" data-lng="34.827335" data-zoom="13" data-title="<?php echo $tour['name']; ?>"></div>
                            </div>
                            
                            <!-- Meeting Point - New Element -->
                            <div class="meeting-point">
                                <h3><?php _e('meeting_point'); ?></h3>
                                <div class="meeting-point-details">
                                    <div class="meeting-point-icon">
                                        <i class="material-icons">location_on</i>
                                    </div>
                                    <div class="meeting-point-content">
                                        <p><?php _e('meeting_point_description'); ?></p>
                                        <a href="#" class="btn btn-sm btn-outline get-directions-btn">
                                            <i class="material-icons">directions</i>
                                            <?php _e('get_directions'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Reviews Tab - New Element -->
                    <div class="tour-tab-content" data-id="reviews">
                        <div class="tour-reviews">
                            <div class="reviews-summary">
                                <div class="rating-overall">
                                    <div class="rating-number"><?php echo number_format($rating, 1); ?></div>
                                    <div class="rating-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="material-icons"><?php echo $i <= $rating ? 'star' : 'star_border'; ?></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="rating-label"><?php echo isset($tour['reviews_count']) ? $tour['reviews_count'] : rand(15, 50); ?> <?php _e('reviews'); ?></div>
                                </div>
                                
                                <!-- Rating Breakdown -->
                                <div class="rating-breakdown">
                                    <?php 
                                    $breakdowns = [
                                        'value' => [__('value_for_money'), 4.8],
                                        'guide' => [__('tour_guide'), 4.9],
                                        'experience' => [__('experience'), 4.7],
                                        'safety' => [__('safety'), 5.0]
                                    ];
                                    
                                    foreach ($breakdowns as $key => $breakdown): 
                                    ?>
                                        <div class="breakdown-item">
                                            <div class="breakdown-label"><?php echo $breakdown[0]; ?></div>
                                            <div class="breakdown-bar">
                                                <div class="breakdown-fill" style="width: <?php echo ($breakdown[1] / 5) * 100; ?>%;"></div>
                                            </div>
                                            <div class="breakdown-value"><?php echo number_format($breakdown[1], 1); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            
                            <!-- Reviews List -->
                            <div class="reviews-list">
                                <h3><?php _e('customer_reviews'); ?></h3>
                                
                                <?php 
                                // Sample reviews - in a real implementation, these would come from a database
                                $sampleReviews = [
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
                                
                                foreach ($sampleReviews as $review): 
                                ?>
                                    <div class="review-item">
                                        <div class="review-header">
                                            <div class="reviewer-info">
                                                <div class="reviewer-avatar">
                                                    <img src="<?php echo $imgUrl; ?>/<?php echo $review['image']; ?>" alt="<?php echo $review['name']; ?>">
                                                </div>
                                                <div class="reviewer-details">
                                                    <h4 class="reviewer-name"><?php echo $review['name']; ?></h4>
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
                                
                                <!-- Review Pagination -->
                                <div class="review-pagination">
                                    <button class="btn btn-outline load-more-reviews">
                                        <i class="material-icons">refresh</i>
                                        <?php _e('load_more_reviews'); ?>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Leave a Review - New Element -->
                            <div class="review-form">
                                <h3><?php _e('leave_a_review'); ?></h3>
                                <form action="<?php echo $appUrl . '/' . $currentLang; ?>/tours/review" method="post">
                                    <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                                    
                                    <div class="form-group">
                                        <label for="review_rating"><?php _e('your_rating'); ?></label>
                                        <div class="rating-select">
                                            <?php for ($i = 5; $i >= 1; $i--): ?>
                                                <input type="radio" name="rating" id="rating-<?php echo $i; ?>" value="<?php echo $i; ?>" <?php echo $i === 5 ? 'checked' : ''; ?>>
                                                <label for="rating-<?php echo $i; ?>"><i class="material-icons">star</i></label>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="review_name"><?php _e('your_name'); ?></label>
                                        <input type="text" id="review_name" name="name" class="form-control" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="review_email"><?php _e('your_email'); ?></label>
                                        <input type="email" id="review_email" name="email" class="form-control" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="review_content"><?php _e('your_review'); ?></label>
                                        <textarea id="review_content" name="content" class="form-control" rows="5" required></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-icons">rate_review</i>
                                        <?php _e('submit_review'); ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Share Section - Enhanced Design -->
                <div class="tour-share" data-aos="fade-up">
                    <h3><?php _e('share_this_tour'); ?></h3>
                    <div class="share-buttons">
                        <a href="#" class="share-button" data-type="facebook">
                            <i class="fab fa-facebook-f"></i>
                            <span>Facebook</span>
                        </a>
                        <a href="#" class="share-button" data-type="twitter">
                            <i class="fab fa-twitter"></i>
                            <span>Twitter</span>
                        </a>
                        <a href="#" class="share-button" data-type="pinterest" data-image="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>">
                            <i class="fab fa-pinterest-p"></i>
                            <span>Pinterest</span>
                        </a>
                        <a href="#" class="share-button" data-type="whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            <span>WhatsApp</span>
                        </a>
                        <a href="#" class="share-button" data-type="email">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </a>
                    </div>
                </div>
                
                <!-- Related Tours - Enhanced Design -->
                <?php if (isset($relatedTours) && !empty($relatedTours)): ?>
                <div class="related-tours" data-aos="fade-up">
                    <h2><?php _e('you_might_also_like'); ?></h2>
                    <div class="tours-grid">
                        <?php foreach ($relatedTours as $relatedTour): ?>
                            <div class="tour-card">
                                <div class="tour-image">
                                    <img src="<?php echo $uploadsUrl . '/tours/' . $relatedTour['featured_image']; ?>" alt="<?php echo $relatedTour['name']; ?>">
                                    <div class="tour-price">
                                        <?php if (isset($relatedTour['discount_price']) && $relatedTour['discount_price']): ?>
                                            <del><?php echo $settings['currency_symbol'] . number_format($relatedTour['price'], 2); ?></del>
                                            <?php echo $settings['currency_symbol'] . number_format($relatedTour['discount_price'], 2); ?>
                                        <?php else: ?>
                                            <?php echo $settings['currency_symbol'] . number_format($relatedTour['price'], 2); ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (isset($relatedTour['category_name']) && $relatedTour['category_name']): ?>
                                        <div class="tour-category"><?php echo $relatedTour['category_name']; ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="tour-content">
                                    <h3 class="tour-title">
                                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $relatedTour['slug']; ?>">
                                            <?php echo $relatedTour['name']; ?>
                                        </a>
                                    </h3>
                                    <div class="tour-meta">
                                        <div class="tour-meta-item">
                                            <i class="material-icons">schedule</i>
                                            <span><?php echo $relatedTour['duration']; ?></span>
                                        </div>
                                    </div>
                                    <div class="tour-rating">
                                        <?php 
                                        $relatedRating = isset($relatedTour['rating']) ? $relatedTour['rating'] : 5;
                                        for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="material-icons"><?php echo $i <= $relatedRating ? 'star' : 'star_border'; ?></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p class="tour-description">
                                        <?php echo substr(strip_tags($relatedTour['short_description']), 0, 100) . '...'; ?>
                                    </p>
                                    <div class="tour-footer">
                                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $relatedTour['slug']; ?>" class="btn btn-primary btn-sm">
                                            <?php _e('view_details'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Tour Sidebar - Enhanced Design -->
            <div class="tour-sidebar">
                <!-- Booking Widget - Conversion Optimized -->
                <div class="tour-booking-widget glass-card" data-aos="fade-left" id="booking-section">
                    <h3><?php _e('book_this_tour'); ?></h3>
                    
                    <!-- Tour Price Display -->
                    <div class="booking-price">
                        <?php if (isset($tour['discount_price']) && $tour['discount_price']): ?>
                            <div class="price-display">
                                <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                                <span id="price_display"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
                            </div>
                            <div class="booking-price-per"><?php _e('per_person'); ?></div>
                            <div class="discount-label"><?php echo round((($tour['price'] - $tour['discount_price']) / $tour['price']) * 100); ?>% <?php _e('off'); ?></div>
                        <?php else: ?>
                            <div class="price-display">
                                <span id="price_display"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                            </div>
                            <div class="booking-price-per"><?php _e('per_person'); ?></div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Booking Form -->
                    <form action="<?php echo $appUrl . '/' . $currentLang; ?>/booking/tour/<?php echo $tour['id']; ?>" method="get" class="booking-widget-form">
                        <div class="form-group">
                            <label for="booking_date"><?php _e('select_date'); ?></label>
                            <div class="input-with-icon">
                                <i class="material-icons">calendar_today</i>
                                <input type="text" id="booking_date" name="date" class="form-control" placeholder="<?php _e('select_date'); ?>" required>
                            </div>
                            
                            <!-- Availability Calendar Preview - New Element -->
                            <div class="date-availability">
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
                        
                        <div class="form-group">
                            <label><?php _e('guests'); ?></label>
                            <div class="guests-selector">
                                <div class="guest-type">
                                    <span><?php _e('adults'); ?></span>
                                    <div class="guest-counter">
                                        <button type="button" class="decrease-btn">-</button>
                                        <input type="number" name="adults" id="booking_adults" value="2" min="1" max="10">
                                        <button type="button" class="increase-btn">+</button>
                                    </div>
                                </div>
                                <div class="guest-type">
                                    <span><?php _e('children'); ?></span>
                                    <div class="guest-counter">
                                        <button type="button" class="decrease-btn">-</button>
                                        <input type="number" name="children" id="booking_children" value="0" min="0" max="10">
                                        <button type="button" class="increase-btn">+</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Total Price Calculation - New Design -->
                        <div class="booking-total">
                            <div class="total-label"><?php _e('total'); ?>:</div>
                            <div class="total-value" id="total_price_display">
                                <?php echo $settings['currency_symbol']; ?><?php echo number_format(isset($tour['discount_price']) && $tour['discount_price'] > 0 ? $tour['discount_price'] * 2 : $tour['price'] * 2, 2); ?>
                            </div>
                        </div>
                        
                        <input type="hidden" id="booking_base_price" value="<?php echo $tour['price']; ?>">
                        <input type="hidden" id="booking_discount_price" value="<?php echo isset($tour['discount_price']) ? $tour['discount_price'] : 0; ?>">
                        <input type="hidden" id="currency_symbol" value="<?php echo $settings['currency_symbol']; ?>">
                        
                        <!-- Book Now Button - Enhanced Design -->
                        <button type="submit" class="btn btn-primary btn-block book-now-btn">
                            <i class="material-icons">shopping_cart</i>
                            <?php _e('book_now'); ?>
                        </button>
                        
                        <!-- Last Minute Availability - New Element -->
                        <div class="last-minute-availability">
                            <i class="material-icons">timer</i>
                            <span><?php _e('last_minute_available'); ?></span>
                        </div>
                    </form>
                    
                    <!-- Trust Badges - New Element -->
                    <div class="trust-badges">
                        <div class="trust-badge">
                            <i class="material-icons">verified_user</i>
                            <span><?php _e('secure_payment'); ?></span>
                        </div>
                        <div class="trust-badge">
                            <i class="material-icons">event_available</i>
                            <span><?php _e('instant_confirmation'); ?></span>
                        </div>
                        <div class="trust-badge">
                            <i class="material-icons">update</i>
                            <span><?php _e('free_cancellation'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Why Book With Us - New Element -->
                <div class="sidebar-widget material-card" data-aos="fade-left">
                    <h3><?php _e('why_book_with_us'); ?></h3>
                    <ul class="benefits-list">
                        <li class="benefit-item">
                            <i class="material-icons">verified_user</i>
                            <span><?php _e('trusted_operator'); ?></span>
                        </li>
                        <li class="benefit-item">
                            <i class="material-icons">language</i>
                            <span><?php _e('multilingual_guides'); ?></span>
                        </li>
                        <li class="benefit-item">
                            <i class="material-icons">star</i>
                            <span><?php _e('top_rated_experiences'); ?></span>
                        </li>
                        <li class="benefit-item">
                            <i class="material-icons">local_offer</i>
                            <span><?php _e('best_price_guarantee'); ?></span>
                        </li>
                        <li class="benefit-item">
                            <i class="material-icons">headset_mic</i>
                            <span><?php _e('24_7_support'); ?></span>
                        </li>
                    </ul>
                </div>
                
                <!-- Tour Information - Enhanced Design -->
                <div class="sidebar-widget material-card" data-aos="fade-left">
                    <h3><?php _e('tour_information'); ?></h3>
                    <ul class="tour-info-list">
                        <li class="tour-info-item">
                            <div class="tour-info-icon">
                                <i class="material-icons">schedule</i>
                            </div>
                            <div class="tour-info-details">
                                <div class="tour-info-label"><?php _e('duration'); ?></div>
                                <div class="tour-info-value"><?php echo $tour['duration']; ?></div>
                            </div>
                        </li>
                        <li class="tour-info-item">
                            <div class="tour-info-icon">
                                <i class="material-icons">groups</i>
                            </div>
                            <div class="tour-info-details">
                                <div class="tour-info-label"><?php _e('group_size'); ?></div>
                                <div class="tour-info-value"><?php _e('max'); ?> 15</div>
                            </div>
                        </li>
                        <li class="tour-info-item">
                            <div class="tour-info-icon">
                                <i class="material-icons">language</i>
                            </div>
                            <div class="tour-info-details">
                                <div class="tour-info-label"><?php _e('languages'); ?></div>
                                <div class="tour-info-value">English, Türkçe</div>
                            </div>
                        </li>
                        <li class="tour-info-item">
                            <div class="tour-info-icon">
                                <i class="material-icons">event_available</i>
                            </div>
                            <div class="tour-info-details">
                                <div class="tour-info-label"><?php _e('availability'); ?></div>
                                <div class="tour-info-value"><?php _e('daily'); ?></div>
                            </div>
                        </li>
                        <?php if (isset($tour['category_name']) && $tour['category_name']): ?>
                            <li class="tour-info-item">
                                <div class="tour-info-icon">
                                    <i class="material-icons">category</i>
                                </div>
                                <div class="tour-info-details">
                                    <div class="tour-info-label"><?php _e('category'); ?></div>
                                    <div class="tour-info-value"><?php echo $tour['category_name']; ?></div>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Need Help Widget - Enhanced Design -->
                <div class="sidebar-widget glass-card dark" data-aos="fade-left">
                    <h3><?php _e('need_help'); ?></h3>
                    <div class="help-info">
                        <div class="help-item">
                            <i class="material-icons">phone</i>
                            <div class="help-content">
                                <div class="help-label"><?php _e('call_us'); ?></div>
                                <div class="help-value">
                                    <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>">
                                        <?php echo $settings['contact_phone']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="help-item">
                            <i class="material-icons">email</i>
                            <div class="help-content">
                                <div class="help-label"><?php _e('email_us'); ?></div>
                                <div class="help-value">
                                    <a href="mailto:<?php echo $settings['contact_email']; ?>">
                                        <?php echo $settings['contact_email']; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- WhatsApp Support - New Element -->
                        <div class="help-item">
                            <i class="fab fa-whatsapp"></i>
                            <div class="help-content">
                                <div class="help-label"><?php _e('whatsapp'); ?></div>
                                <div class="help-value">
                                    <a href="https://wa.me/<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" target="_blank">
                                        <?php _e('chat_with_us'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Help CTA -->
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-glass btn-block">
                        <i class="material-icons">message</i>
                        <?php _e('send_message'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section - Enhanced Design -->
<section class="cta-section" style="background-image: url('<?php echo $imgUrl; ?>/cta-bg.jpg');">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title"><?php _e('discover_more_tours'); ?></h2>
            <p class="cta-text"><?php _e('discover_more_tours_text'); ?></p>
            <div class="cta-buttons">
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-lg">
                    <i class="material-icons">explore</i>
                    <?php _e('view_all_tours'); ?>
                </a>
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-glass btn-lg">
                    <i class="material-icons">help</i>
                    <?php _e('have_questions'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Additional Styles for Tour Detail Page -->
<style>
    /* Tour Header Enhancements */
    .tour-header {
        min-height: 600px;
        padding-top: 100px;
        background-position: center;
        background-size: cover;
        position: relative;
    }
    
    .tour-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7));
    }
    
    .tour-header-content {
        position: relative;
        z-index: 2;
        color: var(--white-color);
        max-width: 800px;
    }
    
    .tour-badge {
        display: inline-flex;
        align-items: center;
        background-color: var(--primary-color);
        color: var(--white-color);
        padding: 0.4rem 0.75rem;
        border-radius: var(--border-radius-md);
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-medium);
        margin-bottom: var(--spacing-md);
        gap: 0.4rem;
    }
    
    .tour-title {
        font-size: var(--font-size-4xl);
        margin-bottom: var(--spacing-md);
        color: var(--white-color);
        line-height: 1.2;
    }
    
    .tour-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1.25rem;
        margin-bottom: var(--spacing-md);
    }
    
    .tour-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .tour-header-rating {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: var(--spacing-md);
    }
    
    .rating-stars {
        display: flex;
        color: #FFD700;
    }
    
    .rating-count {
        color: rgba(255, 255, 255, 0.8);
        font-size: var(--font-size-sm);
    }
    
    .tour-price {
        margin-bottom: var(--spacing-lg);
    }
    
    .tour-price span {
        font-size: var(--font-size-3xl);
        font-weight: var(--font-weight-bold);
        color: var(--white-color);
    }
    
    .tour-price del {
        font-size: var(--font-size-xl);
        opacity: 0.7;
        margin-right: 0.5rem;
    }
    
    .price-per-person {
        font-size: var(--font-size-sm);
        opacity: 0.8;
        margin-top: 0.25rem;
    }
    
    .tour-discount-badge {
        display: inline-block;
        background-color: var(--primary-color);
        color: var(--white-color);
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-md);
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-medium);
        margin-left: 0.75rem;
    }
    
    .tour-quick-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .action-btn {
        width: 44px;
        height: 44px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(5px);
        color: var(--white-color);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all var(--transition-fast);
    }
    
    .action-btn:hover {
        background-color: var(--primary-color);
        transform: translateY(-3px);
    }
    
    .book-now-btn {
        font-weight: var(--font-weight-medium);
        padding: 0.6rem 1.25rem;
    }
    
    /* Tour Gallery Slider */
    .tour-gallery-slider {
        margin-bottom: var(--spacing-xl);
    }
    
    .gallery-slider-main {
        position: relative;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        margin-bottom: var(--spacing-md);
        height: 500px;
    }
    
    .gallery-slide {
        height: 100%;
    }
    
    .gallery-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .gallery-slider-thumbs {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: var(--spacing-sm);
    }
    
    .gallery-thumb {
        height: 80px;
        border-radius: var(--border-radius-md);
        overflow: hidden;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity var(--transition-fast);
    }
    
    .gallery-thumb:hover,
    .gallery-thumb.active {
        opacity: 1;
    }
    
    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    /* Tour Overview */
    .overview-summary {
        background-color: var(--gray-100);
        padding: var(--spacing-lg);
        border-left: 4px solid var(--primary-color);
        border-radius: 0 var(--border-radius-md) var(--border-radius-md) 0;
        margin-bottom: var(--spacing-lg);
    }
    
    .tour-highlights {
        margin-top: var(--spacing-xl);
    }
    
    .highlights-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-lg);
        margin-top: var(--spacing-md);
    }
    
    .highlight-item {
        display: flex;
        align-items: flex-start;
        gap: var(--spacing-md);
    }
    
    .highlight-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        background-color: rgba(42, 157, 143, 0.1);
        color: var(--secondary-color);
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .highlight-content h4 {
        margin-bottom: 0.25rem;
        color: var(--dark-color);
        font-size: var(--font-size-md);
    }
    
    /* Tour Tabs */
    .tour-tabs {
        margin: var(--spacing-xl) 0;
    }
    
    .tour-tabs-nav {
        display: flex;
        border-bottom: 1px solid var(--gray-300);
        margin-bottom: var(--spacing-lg);
        position: relative;
    }
    
    .tour-tab-button {
        padding: var(--spacing-md) var(--spacing-lg);
        font-weight: var(--font-weight-medium);
        color: var(--gray-700);
        cursor: pointer;
        position: relative;
        white-space: nowrap;
    }
    
    .tour-tab-button::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 0;
        height: 3px;
        background-color: var(--primary-color);
        transition: width var(--transition-medium);
    }
    
    .tour-tab-button.active {
        color: var(--primary-color);
    }
    
    .tour-tab-button.active::after {
        width: 100%;
    }
    
    .tour-tab-content {
        display: none;
        opacity: 0;
        transition: opacity var(--transition-medium);
    }
    
    .tour-tab-content.active {
        display: block;
        opacity: 1;
    }
    
    /* Tour Itinerary */
    .tour-itinerary-item {
        display: flex;
        margin-bottom: var(--spacing-xl);
    }
    
    .tour-itinerary-day {
        min-width: 80px;
        width: 80px;
        height: 80px;
        background-color: var(--primary-color);
        color: var(--white-color);
        border-radius: var(--border-radius-circle);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-right: var(--spacing-lg);
    }
    
    .day-number {
        font-size: var(--font-size-3xl);
        font-weight: var(--font-weight-bold);
        line-height: 1;
    }
    
    .day-text {
        font-size: var(--font-size-sm);
        text-transform: uppercase;
    }
    
    .tour-itinerary-content {
        position: relative;
        flex: 1;
    }
    
    .tour-itinerary-title {
        margin-bottom: var(--spacing-sm);
        color: var(--dark-color);
        font-size: var(--font-size-lg);
    }
    
    .tour-itinerary-details {
        color: var(--gray-700);
    }
    
    /* Includes/Excludes */
    .includes-excludes-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--spacing-xl);
    }
    
    .includes-list,
    .excludes-list {
        display: grid;
        gap: var(--spacing-sm);
    }
    
    .includes-item,
    .excludes-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .includes-item i {
        color: var(--secondary-color);
        font-size: 1.25rem;
    }
    
    .excludes-item i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }
    
    .tour-additional-info {
        margin-top: var(--spacing-xl);
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-lg);
        margin-top: var(--spacing-md);
    }
    
    .info-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .info-icon {
        width: 60px;
        height: 60px;
        background-color: rgba(247, 162, 97, 0.1);
        color: var(--accent-color);
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin-bottom: var(--spacing-sm);
    }
    
    .info-content h4 {
        margin-bottom: 0.25rem;
        font-size: var(--font-size-md);
    }
    
    .info-content p {
        color: var(--gray-600);
        margin-bottom: 0;
    }
    
    /* Location Tab */
    .tour-map-container {
        margin-bottom: var(--spacing-xl);
    }
    
    .tour-map {
        height: 400px;
        border-radius: var(--border-radius-md);
        overflow: hidden;
        margin-top: var(--spacing-md);
    }
    
    .meeting-point {
        background-color: var(--gray-100);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-lg);
    }
    
    .meeting-point-details {
        display: flex;
        gap: var(--spacing-lg);
        margin-top: var(--spacing-md);
    }
    
    .meeting-point-icon {
        min-width: 40px;
        width: 40px;
        height: 40px;
        background-color: var(--primary-color);
        color: var(--white-color);
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .meeting-point-content p {
        margin-bottom: var(--spacing-md);
    }
    
    .get-directions-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Reviews Tab */
    .reviews-summary {
        display: flex;
        align-items: center;
        gap: var(--spacing-xl);
        padding: var(--spacing-lg);
        background-color: var(--gray-100);
        border-radius: var(--border-radius-lg);
        margin-bottom: var(--spacing-xl);
    }
    
    .rating-overall {
        text-align: center;
        padding-right: var(--spacing-lg);
        border-right: 1px solid var(--gray-300);
    }
    
    .rating-number {
        font-size: var(--font-size-4xl);
        font-weight: var(--font-weight-bold);
        color: var(--dark-color);
        line-height: 1;
    }
    
    .rating-label {
        margin-top: var(--spacing-xs);
        color: var(--gray-600);
        font-size: var(--font-size-sm);
    }
    
    .rating-breakdown {
        flex: 1;
        display: grid;
        gap: var(--spacing-sm);
    }
    
    .breakdown-item {
        display: grid;
        grid-template-columns: 150px 1fr 40px;
        align-items: center;
        gap: 1rem;
    }
    
    .breakdown-label {
        font-size: var(--font-size-sm);
        color: var(--gray-700);
    }
    
    .breakdown-bar {
        height: 8px;
        background-color: var(--gray-200);
        border-radius: var(--border-radius-sm);
        overflow: hidden;
    }
    
    .breakdown-fill {
        height: 100%;
        background-color: var(--secondary-color);
        border-radius: var(--border-radius-sm);
    }
    
    .breakdown-value {
        font-weight: var(--font-weight-medium);
        text-align: right;
        color: var(--dark-color);
    }
    
    .reviews-list {
        margin-bottom: var(--spacing-xl);
    }
    
    .review-item {
        border-bottom: 1px solid var(--gray-200);
        padding: var(--spacing-lg) 0;
    }
    
    .review-item:last-child {
        border-bottom: none;
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--spacing-md);
    }
    
    .reviewer-info {
        display: flex;
        gap: var(--spacing-md);
    }
    
    .reviewer-avatar {
        width: 50px;
        height: 50px;
        border-radius: var(--border-radius-circle);
        overflow: hidden;
    }
    
    .reviewer-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .reviewer-name {
        margin-bottom: 0.25rem;
        font-size: var(--font-size-md);
    }
    
    .review-date {
        font-size: var(--font-size-sm);
        color: var(--gray-600);
    }
    
    .review-pagination {
        text-align: center;
        margin-top: var(--spacing-lg);
    }
    
    .review-form {
        background-color: var(--gray-100);
        padding: var(--spacing-lg);
        border-radius: var(--border-radius-lg);
    }
    
    .rating-select {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }
    
    .rating-select input {
        display: none;
    }
    
    .rating-select label {
        color: var(--gray-400);
        font-size: 1.5rem;
        padding: 0 0.1rem;
        cursor: pointer;
        transition: color var(--transition-fast);
    }
    
    .rating-select label:hover,
    .rating-select label:hover ~ label,
    .rating-select input:checked ~ label {
        color: #FFD700;
    }
    
    /* Tour Booking Widget */
    .tour-booking-widget {
        position: sticky;
        top: 120px;
        padding: 0;
        overflow: hidden;
    }
    
    .tour-booking-widget h3 {
        padding: var(--spacing-md) var(--spacing-lg);
        background-color: var(--primary-color);
        color: var(--white-color);
        margin-bottom: 0;
        text-align: center;
        font-size: var(--font-size-lg);
    }
    
    .booking-price {
        background-color: rgba(255, 255, 255, 0.1);
        padding: var(--spacing-md);
        text-align: center;
        position: relative;
    }
    
    .price-display {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .price-display #price_display {
        font-size: var(--font-size-xl);
        font-weight: var(--font-weight-bold);
        color: var(--white-color);
        display: block;
    }
    
    .price-display del {
        font-size: var(--font-size-md);
        color: rgba(255, 255, 255, 0.7);
    }
    
    .booking-price-per {
        font-size: var(--font-size-sm);
        color: rgba(255, 255, 255, 0.7);
    }
    
    .discount-label {
        position: absolute;
        top: 0;
        right: 0;
        background-color: var(--primary-color);
        color: var(--white-color);
        padding: 0.25rem 0.75rem;
        font-size: var(--font-size-xs);
        font-weight: var(--font-weight-medium);
        border-radius: 0 0 0 var(--border-radius-md);
    }
    
    .booking-widget-form {
        padding: var(--spacing-lg);
    }
    
    .date-availability {
        display: flex;
        gap: var(--spacing-sm);
        margin-top: var(--spacing-sm);
        font-size: var(--font-size-xs);
    }
    
    .availability-item {
        flex: 1;
        padding: 0.5rem;
        border-radius: var(--border-radius-sm);
        text-align: center;