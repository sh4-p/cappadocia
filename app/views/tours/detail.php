<?php
/**
 * Tour Detail View - Complete Redesign
 * Enhanced user experience with a focus on conversions
 */
?>

<!-- Immersive Hero Section -->
    <!-- Tour Hero Section -->
    <section class="tour-hero" style="background-image: url('<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>');">
        <div class="tour-hero-overlay"></div>
        <div class="tour-hero-content">
            <div class="container">
                <div class="tour-badges">
                    <?php if (isset($tour['is_popular']) && $tour['is_popular']): ?>
                        <span class="tour-badge popular">
                            <i class="material-icons">star</i> <?php _e('most_popular'); ?>
                        </span>
                    <?php endif; ?>
                    <?php if (isset($tour['is_new']) && $tour['is_new']): ?>
                        <span class="tour-badge new">
                            <i class="material-icons">new_releases</i> <?php _e('new'); ?>
                        </span>
                    <?php endif; ?>
                </div>
                
                <h1 class="tour-title"><?php echo $tour['name']; ?></h1>
                
                <div class="tour-rating">
                    <div class="stars">
                        <?php 
                        $rating = isset($tour['rating']) ? $tour['rating'] : 5;
                        for ($i = 1; $i <= 5; $i++): ?>
                            <i class="material-icons"><?php echo $i <= $rating ? 'star' : 'star_border'; ?></i>
                        <?php endfor; ?>
                    </div>
                    <span class="rating-text">
                        <?php echo number_format($rating, 1); ?> 
                        (<?php echo isset($tour['reviews_count']) ? $tour['reviews_count'] : rand(15, 50); ?> <?php _e('reviews'); ?>)
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Info Bar -->
    <div class="quick-info-bar">
        <div class="quick-info-grid">
            <div class="quick-info-item">
                <i class="material-icons">schedule</i>
                <div class="quick-info-text">
                    <span class="quick-info-label"><?php _e('duration'); ?></span>
                    <span class="quick-info-value"><?php echo $tour['duration']; ?></span>
                </div>
            </div>
            <div class="quick-info-item">
                <i class="material-icons">group</i>
                <div class="quick-info-text">
                    <span class="quick-info-label"><?php _e('group_size'); ?></span>
                    <span class="quick-info-value"><?php _e('max'); ?> 15</span>
                </div>
            </div>
            <div class="quick-info-item">
                <i class="material-icons">language</i>
                <div class="quick-info-text">
                    <span class="quick-info-label"><?php _e('languages'); ?></span>
                    <span class="quick-info-value">EN, TR</span>
                </div>
            </div>
            <div class="quick-info-item">
                <i class="material-icons">verified_user</i>
                <div class="quick-info-text">
                    <span class="quick-info-label"><?php _e('confirmation'); ?></span>
                    <span class="quick-info-value"><?php _e('instant'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container">
        <div class="tour-content-wrapper">
            <!-- Main Content Area -->
            <div class="main-content">
                <!-- Gallery Section -->
                <div class="tour-gallery">
                    <div class="swiper gallery-swiper">
                        <div class="swiper-wrapper">
                            <?php if (isset($gallery) && !empty($gallery)): ?>
                                <?php foreach ($gallery as $index => $item): ?>
                                    <div class="swiper-slide">
                                        <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" 
                                             alt="<?php echo isset($item['title']) ? $item['title'] : $tour['name']; ?>">
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" 
                                         alt="<?php echo $tour['name']; ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="swiper-pagination"></div>
                        <div class="gallery-counter">
                            <span class="current">1</span> / <span class="total"><?php echo isset($gallery) ? count($gallery) : 1; ?></span>
                        </div>
                    </div>
                </div>

                <!-- Tabs Section -->
                <div class="tabs-container">
                    <div class="tabs-nav">
                        <button class="tab-btn active" data-tab="overview">
                            <?php _e('overview'); ?>
                        </button>
                        <button class="tab-btn" data-tab="itinerary">
                            <?php _e('itinerary'); ?>
                        </button>
                        <button class="tab-btn" data-tab="includes">
                            <?php _e('includes'); ?>
                        </button>
                        <button class="tab-btn" data-tab="location">
                            <?php _e('location'); ?>
                        </button>
                        <button class="tab-btn" data-tab="reviews">
                            <?php _e('reviews'); ?>
                        </button>
                    </div>

                    <!-- Tab Contents -->
                    <div class="tab-content active" id="overview">
                        <div class="content-section">
                            <h3><?php _e('tour_overview'); ?></h3>
                            <div class="tour-description">
                                <?php echo $tour['description']; ?>
                            </div>

                            <h3><?php _e('experience_highlights'); ?></h3>
                            <div class="highlights-grid">
                                <?php 
                                $highlights = isset($tour['highlights']) ? explode("\n", $tour['highlights']) : explode("\n", $tour['includes']);
                                $icons = ['explore', 'photo_camera', 'history_edu', 'restaurant'];
                                foreach (array_slice($highlights, 0, 4) as $index => $highlight): 
                                    if (trim($highlight)):
                                ?>
                                    <div class="highlight-card">
                                        <div class="highlight-icon">
                                            <i class="material-icons"><?php echo $icons[$index % 4]; ?></i>
                                        </div>
                                        <div class="highlight-text"><?php echo trim($highlight); ?></div>
                                    </div>
                                <?php 
                                    endif;
                                endforeach; 
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Updated Itinerary Tab Content -->
                    <div class="tab-content" id="itinerary">
                        <div class="content-section">
                            <h3><?php _e('tour_itinerary'); ?></h3>
                            <div class="itinerary-timeline">
                                <?php
                                if (isset($tour['itinerary']) && !empty($tour['itinerary'])):
                                    // Try to parse as JSON first (new format)
                                    $itineraryData = json_decode($tour['itinerary'], true);
                                    
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($itineraryData)):
                                        // New format: JSON array with day numbers as keys
                                        ksort($itineraryData); // Sort by day number
                                        foreach ($itineraryData as $dayNumber => $dayData):
                                            if (!empty($dayData['title']) || !empty($dayData['description'])):
                                ?>
                                            <div class="itinerary-item">
                                                <div class="itinerary-marker"><?php echo $dayNumber; ?></div>
                                                <div class="itinerary-content">
                                                    <?php if (!empty($dayData['title'])): ?>
                                                        <h4><?php echo htmlspecialchars($dayData['title']); ?></h4>
                                                    <?php endif; ?>
                                                    <?php if (!empty($dayData['description'])): ?>
                                                        <p><?php echo nl2br(htmlspecialchars($dayData['description'])); ?></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                <?php
                                            endif;
                                        endforeach;
                                    else:
                                        // Old format: plain text with double newlines
                                        $itinerary = explode("\n\n", $tour['itinerary']);
                                        foreach ($itinerary as $index => $day):
                                            if (trim($day)):
                                ?>
                                            <div class="itinerary-item">
                                                <div class="itinerary-marker"><?php echo $index + 1; ?></div>
                                                <div class="itinerary-content">
                                                    <?php 
                                                    $lines = explode("\n", $day);
                                                    if (count($lines) > 0): ?>
                                                        <h4><?php echo htmlspecialchars($lines[0]); ?></h4>
                                                        <?php if (count($lines) > 1): ?>
                                                            <p><?php echo nl2br(htmlspecialchars(implode("\n", array_slice($lines, 1)))); ?></p>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                <?php 
                                            endif;
                                        endforeach;
                                    endif;
                                else:
                                ?>
                                    <div class="no-itinerary">
                                        <div class="no-itinerary-icon">
                                            <i class="material-icons">event_note</i>
                                        </div>
                                        <p><?php _e('no_itinerary_available'); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Tour Duration Info -->
                            <?php if (isset($tour['duration_days']) && $tour['duration_days'] > 1): ?>
                            <div class="duration-info">
                                <div class="duration-card">
                                    <div class="duration-icon">
                                        <i class="material-icons">schedule</i>
                                    </div>
                                    <div class="duration-details">
                                        <h5><?php _e('tour_duration'); ?></h5>
                                        <p><?php echo $tour['duration']; ?></p>
                                        <span class="duration-days"><?php echo $tour['duration_days']; ?> <?php _e('days'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="tab-content" id="includes">
                        <div class="content-section">
                            <h3><?php _e('whats_included'); ?></h3>
                            <div class="includes-excludes">
                                <div class="list-section">
                                    <h4><i class="material-icons">check_circle</i> <?php _e('included'); ?></h4>
                                    <ul class="includes-list">
                                        <?php
                                        $includes = explode("\n", $tour['includes']);
                                        foreach ($includes as $include):
                                            if (trim($include)):
                                        ?>
                                            <li>
                                                <i class="material-icons">check</i>
                                                <span><?php echo trim($include); ?></span>
                                            </li>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </ul>
                                </div>

                                <?php if (isset($tour['excludes']) && !empty($tour['excludes'])): ?>
                                <div class="list-section">
                                    <h4><i class="material-icons">remove_circle</i> <?php _e('not_included'); ?></h4>
                                    <ul class="excludes-list">
                                        <?php
                                        $excludes = explode("\n", $tour['excludes']);
                                        foreach ($excludes as $exclude):
                                            if (trim($exclude)):
                                        ?>
                                            <li>
                                                <i class="material-icons">close</i>
                                                <span><?php echo trim($exclude); ?></span>
                                            </li>
                                        <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>

                            <h3 class="mt-2"><?php _e('additional_info'); ?></h3>
                            <div class="info-cards">
                                <div class="info-card">
                                    <i class="material-icons">directions_walk</i>
                                    <h5><?php _e('activity_level'); ?></h5>
                                    <p><?php _e('moderate'); ?></p>
                                </div>
                                <div class="info-card">
                                    <i class="material-icons">schedule</i>
                                    <h5><?php _e('start_time'); ?></h5>
                                    <p>08:00 AM</p>
                                </div>
                                <div class="info-card">
                                    <i class="material-icons">event_available</i>
                                    <h5><?php _e('availability'); ?></h5>
                                    <p><?php _e('daily'); ?></p>
                                </div>
                                <div class="info-card">
                                    <i class="material-icons">update</i>
                                    <h5><?php _e('cancellation'); ?></h5>
                                    <p><?php _e('free_24h'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-content" id="location">
                        <div class="content-section">
                            <h3><?php _e('meeting_point'); ?></h3>
                            <div class="map-container" id="tour-map" 
                                 data-lat="38.642335" 
                                 data-lng="34.827335" 
                                 data-zoom="13">
                            </div>
                            <p class="mt-1"><?php _e('meeting_point_description'); ?></p>
                        </div>
                    </div>

                    <div class="tab-content" id="reviews">
                        <div class="content-section">
                            <h3><?php _e('customer_reviews'); ?></h3>
                            <div class="reviews-summary">
                                <div class="overall-rating"><?php echo number_format($rating, 1); ?></div>
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="material-icons"><?php echo $i <= $rating ? 'star' : 'star_border'; ?></i>
                                    <?php endfor; ?>
                                </div>
                                <p><?php echo isset($tour['reviews_count']) ? $tour['reviews_count'] : rand(15, 50); ?> <?php _e('reviews'); ?></p>

                                <div class="rating-bars">
                                    <?php 
                                    $categories = [
                                        'value' => [__('value_for_money'), 4.8],
                                        'guide' => [__('guide'), 4.9],
                                        'experience' => [__('experience'), 4.7],
                                        'safety' => [__('safety'), 5.0]
                                    ];
                                    foreach ($categories as $key => $category): 
                                    ?>
                                        <div class="rating-bar-item">
                                            <span class="rating-bar-label"><?php echo $category[0]; ?></span>
                                            <div class="rating-bar-track">
                                                <div class="rating-bar-fill" style="width: <?php echo ($category[1] / 5) * 100; ?>%"></div>
                                            </div>
                                            <span class="rating-bar-value"><?php echo number_format($category[1], 1); ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Sample Reviews -->
                            <?php 
                            $reviews = [
                                [
                                    'name' => 'John Smith',
                                    'date' => '2023-10-15',
                                    'rating' => 5,
                                    'text' => 'Amazing experience! The hot air balloon ride was absolutely breathtaking.',
                                    'avatar' => 'avatar-1.jpg'
                                ],
                                [
                                    'name' => 'Maria Garcia',
                                    'date' => '2023-09-22',
                                    'rating' => 4,
                                    'text' => 'Great tour overall. Beautiful landscapes and interesting history.',
                                    'avatar' => 'avatar-2.jpg'
                                ]
                            ];
                            
                            foreach ($reviews as $review): 
                            ?>
                                <div class="review-card">
                                    <div class="review-header">
                                        <div class="review-avatar">
                                            <img src="<?php echo $imgUrl; ?>/<?php echo $review['avatar']; ?>" alt="<?php echo $review['name']; ?>">
                                        </div>
                                        <div class="review-info">
                                            <div class="review-name"><?php echo $review['name']; ?></div>
                                            <div class="review-date"><?php echo date('F j, Y', strtotime($review['date'])); ?></div>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="material-icons"><?php echo $i <= $review['rating'] ? 'star' : 'star_border'; ?></i>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="review-text"><?php echo $review['text']; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Share Section -->
                <div class="share-section">
                    <h3><?php _e('share_this_tour'); ?></h3>
                    <div class="share-buttons">
                        <a href="#" class="share-btn facebook" data-type="facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="share-btn twitter" data-type="twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="share-btn whatsapp" data-type="whatsapp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="share-btn pinterest" data-type="pinterest">
                            <i class="fab fa-pinterest-p"></i>
                        </a>
                    </div>
                </div>

                <!-- Related Tours -->
                <?php if (isset($relatedTours) && !empty($relatedTours)): ?>
                <div class="related-tours">
                    <h3><?php _e('you_might_also_like'); ?></h3>
                    <div class="swiper related-tours-slider">
                        <div class="swiper-wrapper">
                            <?php foreach ($relatedTours as $relatedTour): ?>
                                <div class="swiper-slide related-tour-slide">
                                    <div class="related-tour-card">
                                        <div class="related-tour-image">
                                            <img src="<?php echo $uploadsUrl . '/tours/' . $relatedTour['featured_image']; ?>" 
                                                 alt="<?php echo $relatedTour['name']; ?>">
                                            <div class="related-tour-price">
                                                <?php if (isset($relatedTour['discount_price']) && $relatedTour['discount_price']): ?>
                                                    <?php echo $settings['currency_symbol'] . number_format($relatedTour['discount_price'], 2); ?>
                                                <?php else: ?>
                                                    <?php echo $settings['currency_symbol'] . number_format($relatedTour['price'], 2); ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="related-tour-content">
                                            <h4 class="related-tour-title">
                                                <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $relatedTour['slug']; ?>">
                                                    <?php echo $relatedTour['name']; ?>
                                                </a>
                                            </h4>
                                            <div class="related-tour-meta">
                                                <span><i class="material-icons">schedule</i> <?php echo $relatedTour['duration']; ?></span>
                                                <span>
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="material-icons"><?php echo $i <= $relatedTour['rating'] ? 'star' : 'star_border'; ?></i>
                                                    <?php endfor; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar Content (Desktop) -->
            <div class="sidebar-content">
                <div class="price-booking-section">
                    <div class="price-display">
                        <?php if (isset($tour['discount_price']) && $tour['discount_price']): ?>
                            <span class="price-original"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                            <span class="price-current"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
                        <?php else: ?>
                            <span class="price-current"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                        <?php endif; ?>
                        <span class="price-per">/ <?php _e('per_person'); ?></span>
                    </div>
                    <button class="book-now-btn" onclick="openBookingModal()">
                        <i class="material-icons">shopping_cart</i>
                        <?php _e('book_now'); ?>
                    </button>
                </div>

                <!-- Help Section -->
                <div class="help-section">
                    <h3><?php _e('need_help'); ?></h3>
                    <div class="help-contacts">
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" class="help-contact">
                            <i class="material-icons">phone</i>
                            <?php echo $settings['contact_phone']; ?>
                        </a>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" class="help-contact">
                            <i class="fab fa-whatsapp"></i>
                            <?php _e('chat_with_us'); ?>
                        </a>
                        <a href="mailto:<?php echo $settings['contact_email']; ?>" class="help-contact">
                            <i class="material-icons">email</i>
                            <?php echo $settings['contact_email']; ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Fixed Booking Bar -->
    <div class="price-booking-section" id="mobile-booking-bar">
        <div class="price-display">
            <?php if (isset($tour['discount_price']) && $tour['discount_price']): ?>
                <span class="price-original"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                <span class="price-current"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
            <?php else: ?>
                <span class="price-current"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
            <?php endif; ?>
            <span class="price-per">/ <?php _e('per_person'); ?></span>
        </div>
        <button class="book-now-btn" onclick="openBookingModal()">
            <i class="material-icons">shopping_cart</i>
            <?php _e('book_now'); ?>
        </button>
    </div>
                
    <!-- Booking Modal -->
    <div class="booking-modal" id="bookingModal">
        <div class="booking-modal-content">
            <div class="modal-header">
                <h3><?php _e('book_this_tour'); ?></h3>
                <button class="modal-close" onclick="closeBookingModal()">
                    <i class="material-icons">close</i>
                </button>
            </div>

            <form class="booking-form" action="<?php echo $appUrl . '/' . $currentLang; ?>/booking/tour/<?php echo $tour['id']; ?>" method="get">
                <div class="form-group">
                    <label for="booking_date"><?php _e('select_date'); ?></label>
                    <input type="date" id="booking_date" name="date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>

                <div class="form-group">
                    <label><?php _e('participants'); ?></label>
                    <div class="guest-selector">
                        <div class="guest-item">
                            <div class="guest-info">
                                <div class="guest-label"><?php _e('adults'); ?></div>
                                <div class="guest-sublabel"><?php _e('age_12_plus'); ?></div>
                            </div>
                            <div class="guest-counter">
                                <button type="button" class="counter-btn" onclick="updateGuests('adults', -1)">
                                    <i class="material-icons">remove</i>
                                </button>
                                <span class="counter-value" id="adults-count">2</span>
                                <button type="button" class="counter-btn" onclick="updateGuests('adults', 1)">
                                    <i class="material-icons">add</i>
                                </button>
                            </div>
                        </div>
                        <div class="guest-item">
                            <div class="guest-info">
                                <div class="guest-label"><?php _e('children'); ?></div>
                                <div class="guest-sublabel"><?php _e('age_2_11'); ?></div>
                            </div>
                            <div class="guest-counter">
                                <button type="button" class="counter-btn" onclick="updateGuests('children', -1)">
                                    <i class="material-icons">remove</i>
                                </button>
                                <span class="counter-value" id="children-count">0</span>
                                <button type="button" class="counter-btn" onclick="updateGuests('children', 1)">
                                    <i class="material-icons">add</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="adults" id="adults-input" value="2">
                    <input type="hidden" name="children" id="children-input" value="0">
                </div>

                <div class="booking-summary">
                    <div class="summary-row">
                        <span><?php _e('adults'); ?></span>
                        <span id="adults-summary">2 × <?php echo $settings['currency_symbol'] . number_format($tour['discount_price'] ?: $tour['price'], 2); ?></span>
                    </div>
                    <div class="summary-row" id="children-summary-row" style="display: none;">
                        <span><?php _e('children'); ?></span>
                        <span id="children-summary">0 × <?php echo $settings['currency_symbol'] . number_format(($tour['discount_price'] ?: $tour['price']) * 0.5, 2); ?></span>
                    </div>
                    <div class="summary-row total">
                        <span><?php _e('total'); ?></span>
                        <span id="total-price"><?php echo $settings['currency_symbol'] . number_format(($tour['discount_price'] ?: $tour['price']) * 2, 2); ?></span>
                    </div>
                </div>

                <button type="submit" class="booking-submit">
                    <?php _e('continue_to_booking'); ?>
                </button>
            </form>
        </div>
    </div>
    <!-- Custom CSS Styles -->
    <!-- Custom CSS Styles -->
    <style>
    /* CSS Reset and Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --primary-color: #FF6B35;
        --primary-dark: #E14F1C;
        --secondary-color: #2A9D8F;
        --accent-color: #F4A261;
        --dark-color: #264653;
        --light-color: #F8F9FA;
        --gray-600: #6C757D;
        --gray-700: #495057;
        --success-color: #28a745;
        --danger-color: #dc3545;
        --white-color: #FFFFFF;
        --spacing-xs: 0.25rem;
        --spacing-sm: 0.5rem;
        --spacing-md: 1rem;
        --spacing-lg: 1.5rem;
        --spacing-xl: 2rem;
        --border-radius: 12px;
        --transition: all 0.3s ease;
        --shadow: 0 2px 8px rgba(0,0,0,0.1);
        --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        font-size: 16px;
        line-height: 1.6;
        color: var(--dark-color);
        background-color: var(--light-color);
        -webkit-font-smoothing: antialiased;
    }
    
    /* Force proper viewport behavior */
    html, body {
      overflow-x: hidden !important;
      max-width: 100vw !important;
      position: relative;
      margin: 0;
      padding: 0;
    }
    
    /* HEADER LOGO FIX - En önemli kısım */
    .site-header .logo img,
    .site-header .logo .main-logo,
    .logo img,
    .logo .main-logo,
    .logo .logo-image {
        height: 48px !important;
        max-height: 48px !important;
        max-width: 200px !important;
        width: auto !important;
        transition: all 0.3s ease !important;
    }

    .site-header.scrolled .logo img,
    .site-header.scrolled .logo .main-logo,
    .scrolled .logo img,
    .scrolled .logo .main-logo,
    .scrolled .logo .logo-image {
        height: 40px !important;
        max-height: 40px !important;
        max-width: 180px !important;
    }

    /* Header container düzeltmeleri */
    .site-header {
        position: fixed !important;
        top: 20px !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        width: calc(100% - 40px) !important;
        max-width: 1320px !important;
        z-index: 1020 !important;
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 24px !important;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1) !important;
        padding: 1rem 2rem !important;
        transition: all 0.3s ease !important;
    }

    .site-header.scrolled {
        top: 10px !important;
        background: rgba(38, 70, 83, 0.85) !important;
        backdrop-filter: blur(16px) !important;
        -webkit-backdrop-filter: blur(16px) !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15) !important;
    }

    .header-wrapper {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        position: relative !important;
        z-index: 1 !important;
    }

    .logo {
        position: relative !important;
        z-index: 1001 !important;
        display: flex !important;
        align-items: center !important;
    }

    /* Logo text düzeltmeleri */
    .logo .logo-text {
        color: var(--white-color) !important;
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        text-decoration: none !important;
        transition: all 0.3s ease !important;
    }

    .scrolled .logo .logo-text {
        color: var(--white-color) !important;
    }

    /* Ensure all containers respect viewport width */
    .container, 
    .tour-content-wrapper,
    .main-content,
    .sidebar-content,
    .tour-gallery,
    .tabs-container,
    .tab-content,
    .content-section,
    .highlights-grid,
    .itinerary-timeline,
    .includes-excludes,
    .info-cards,
    .map-container,
    .reviews-summary,
    .review-card,
    .share-section,
    .related-tours,
    .price-booking-section,
    .help-section {
      max-width: 100% !important;
      box-sizing: border-box !important;
      overflow-x: hidden !important;
    }
    
    /* Fix swiper layout issues */
    .swiper, 
    .swiper-wrapper, 
    .swiper-slide {
      width: 100% !important;
      max-width: 100% !important;
      overflow: hidden !important;
    }
    
    /* Ensure images are constrained */
    img {
      max-width: 100% !important;
      height: auto !important;
    }

    /* Fix container width on small screens */
    .container {
        width: 100%;
        max-width: 100%;
        padding: 0 1rem;
        box-sizing: border-box;
        overflow: visible !important;
    }

    /* Mobile-First Hero Section */
    .tour-hero {
        position: relative;
        width: 100%;
        min-height: 60vh;
        background-size: cover;
        background-position: center;
        margin-bottom: 1rem;
    }

    .tour-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.6));
    }

    .tour-hero-content {
        position: relative;
        z-index: 1;
        height: 100%;
        min-height: 60vh;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 2rem 1rem;
        color: var(--white-color);
    }

    .tour-badges {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        flex-wrap: wrap;
    }

    .tour-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .tour-badge.popular {
        background: rgba(245, 166, 35, 0.9);
    }

    .tour-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1.2;
    }

    .tour-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .stars {
        display: flex;
        color: #FFD700;
    }

    .rating-text {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    /* Quick Info Bar */
    .quick-info-bar {
        background: var(--white-color);
        padding: 1rem;
        margin: -1rem 1rem 1rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        position: relative;
        z-index: 2;
    }

    .quick-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .quick-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quick-info-item i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .quick-info-text {
        display: flex;
        flex-direction: column;
        font-size: 0.875rem;
    }

    .quick-info-label {
        color: var(--gray-600);
        font-size: 0.75rem;
    }

    .quick-info-value {
        font-weight: 600;
    }

    /* Gallery Section - Mobile Optimized */
    .tour-gallery {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .swiper {
        width: 100%;
        border-radius: var(--border-radius);
        overflow: hidden;
    }

    .swiper-slide img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }

    .swiper-pagination {
        position: absolute !important;
        bottom: 1rem !important;
    }

    .swiper-pagination-bullet {
        background: var(--white-color);
        opacity: 0.6;
    }

    .swiper-pagination-bullet-active {
        opacity: 1;
    }

    .gallery-counter {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(0,0,0,0.6);
        color: var(--white-color);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.875rem;
        z-index: 10;
    }

    /* Price and Booking Section */
    .price-booking-section {
        background: var(--white-color);
        padding: 1.5rem 1rem;
        margin-bottom: 1rem;
        position: sticky;
        bottom: 0;
        z-index: 100;
        box-shadow: 0 -4px 12px rgba(0,0,0,0.1);
    }

    .price-display {
        display: flex;
        align-items: baseline;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .price-current {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .price-original {
        text-decoration: line-through;
        color: var(--gray-600);
        font-size: 1.125rem;
    }

    .price-per {
        color: var(--gray-600);
        font-size: 0.875rem;
    }

    .book-now-btn {
        width: 100%;
        padding: 1rem;
        background: var(--primary-color);
        color: var(--white-color);
        border: none;
        border-radius: var(--border-radius);
        font-size: 1rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .book-now-btn:active {
        transform: scale(0.98);
    }

    /* Tabs Navigation - Mobile Optimized */
    .tabs-container {
        background: var(--white-color);
        margin-bottom: 1rem;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .tabs-nav {
        display: flex;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        border-bottom: 1px solid #e0e0e0;
    }

    .tabs-nav::-webkit-scrollbar {
        display: none;
    }

    .tab-btn {
        flex: 0 0 auto;
        padding: 1rem 1.25rem;
        background: none;
        border: none;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-700);
        cursor: pointer;
        position: relative;
        white-space: nowrap;
        transition: var(--transition);
    }

    .tab-btn.active {
        color: var(--primary-color);
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary-color);
    }

    .tab-content {
        display: none;
        padding: 1.5rem 1rem;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Content Sections */
    .content-section {
        margin-bottom: 2rem;
    }

    .content-section h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: var(--dark-color);
    }

    .tour-description {
        line-height: 1.8;
        color: var(--gray-700);
    }

    /* Highlights Grid */
    .highlights-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-top: 1rem;
    }

    .highlight-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--light-color);
        border-radius: var(--border-radius);
    }

    .highlight-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 107, 53, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .highlight-text {
        flex: 1;
        font-size: 0.9375rem;
    }

    /* Enhanced Itinerary Styles */
    .itinerary-timeline {
        position: relative;
        padding-left: 3rem;
        margin-bottom: 2rem;
    }

    .itinerary-item {
        position: relative;
        padding-bottom: 2.5rem;
        margin-bottom: 1rem;
    }

    .itinerary-item:not(:last-child)::before {
        content: '';
        position: absolute;
        left: -2rem;
        top: 3rem;
        bottom: -1rem;
        width: 3px;
        background: linear-gradient(to bottom, var(--primary-color), #e0e0e0);
        border-radius: 2px;
    }

    .itinerary-marker {
        position: absolute;
        left: -3rem;
        top: 0;
        width: 2.5rem;
        height: 2.5rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: var(--white-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        z-index: 1;
    }

    .itinerary-content {
        background: var(--white-color);
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        border-left: 4px solid var(--primary-color);
        position: relative;
    }

    .itinerary-content::before {
        content: '';
        position: absolute;
        left: -1rem;
        top: 1.5rem;
        width: 0;
        height: 0;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
        border-right: 8px solid var(--white-color);
    }

    .itinerary-content h4 {
        font-size: 1.125rem;
        margin-bottom: 0.75rem;
        color: var(--dark-color);
        font-weight: 600;
    }

    .itinerary-content p {
        font-size: 0.9375rem;
        color: var(--gray-700);
        line-height: 1.7;
        margin: 0;
    }

    /* No Itinerary State */
    .no-itinerary {
        text-align: center;
        padding: 3rem 1rem;
        background: var(--light-color);
        border-radius: 12px;
        border: 2px dashed #e0e0e0;
    }

    .no-itinerary-icon {
        font-size: 3rem;
        color: var(--gray-400);
        margin-bottom: 1rem;
    }

    .no-itinerary p {
        color: var(--gray-600);
        font-size: 1rem;
        margin: 0;
    }

    /* Duration Info Card */
    .duration-info {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e0e0e0;
    }

    .duration-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, var(--secondary-color), #219a8a);
        color: var(--white-color);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(42, 157, 143, 0.2);
    }

    .duration-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .duration-icon i {
        font-size: 1.75rem;
    }

    .duration-details h5 {
        margin: 0 0 0.5rem 0;
        font-size: 1.125rem;
        font-weight: 600;
    }

    .duration-details p {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        opacity: 0.9;
    }

    .duration-days {
        font-size: 0.875rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        display: inline-block;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
        /* Header mobil düzeltmeleri */
        .site-header {
            top: 10px !important;
            width: calc(100% - 20px) !important;
            padding: 0.75rem 1.5rem !important;
        }
        
        .site-header .logo img,
        .site-header .logo .main-logo,
        .logo img,
        .logo .main-logo {
            height: 40px !important;
            max-height: 40px !important;
            max-width: 160px !important;
        }

        .scrolled .logo img,
        .scrolled .logo .main-logo {
            height: 35px !important;
            max-height: 35px !important;
            max-width: 140px !important;
        }
        
        .itinerary-timeline {
            padding-left: 2rem;
        }
        
        .itinerary-marker {
            left: -2rem;
            width: 2rem;
            height: 2rem;
            font-size: 0.75rem;
        }
        
        .itinerary-content {
            padding: 1rem;
        }
        
        .itinerary-content::before {
            left: -0.75rem;
            top: 1rem;
            border-right-width: 6px;
            border-top-width: 6px;
            border-bottom-width: 6px;
        }
        
        .itinerary-content h4 {
            font-size: 1rem;
        }
        
        .itinerary-content p {
            font-size: 0.875rem;
        }
        
        .duration-card {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        
        .duration-icon {
            width: 50px;
            height: 50px;
        }
        
        .duration-icon i {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 480px) {
        /* En küçük ekranlar için header */
        .site-header .logo img,
        .site-header .logo .main-logo,
        .logo img,
        .logo .main-logo {
            height: 36px !important;
            max-height: 36px !important;
            max-width: 140px !important;
        }

        .scrolled .logo img,
        .scrolled .logo .main-logo {
            height: 32px !important;
            max-height: 32px !important;
            max-width: 120px !important;
        }
        
        .itinerary-timeline {
            padding-left: 1.5rem;
        }
        
        .itinerary-marker {
            left: -1.5rem;
            width: 1.75rem;
            height: 1.75rem;
            font-size: 0.7rem;
        }
        
        .itinerary-item:not(:last-child)::before {
            left: -1.25rem;
        }
        
        .no-itinerary {
            padding: 2rem 0.75rem;
        }
        
        .no-itinerary-icon {
            font-size: 2.5rem;
        }
    }

    /* Includes/Excludes Lists */
    .includes-excludes {
        display: grid;
        gap: 1.5rem;
    }

    .list-section h4 {
        font-size: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .list-section ul {
        list-style: none;
    }

    .list-section li {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
        font-size: 0.9375rem;
    }

    .list-section li i {
        flex-shrink: 0;
        margin-top: 0.125rem;
    }

    .includes-list i {
        color: var(--success-color);
    }

    .excludes-list i {
        color: var(--danger-color);
    }

    /* Info Cards Grid */
    .info-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-top: 1rem;
    }

    .info-card {
        text-align: center;
        padding: 1rem;
        background: var(--light-color);
        border-radius: var(--border-radius);
    }

    .info-card i {
        font-size: 2rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .info-card h5 {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .info-card p {
        font-size: 0.875rem;
        color: var(--gray-600);
        margin: 0;
    }

    /* Map Container */
    .map-container {
        height: 300px;
        border-radius: var(--border-radius);
        overflow: hidden;
        margin-bottom: 1rem;
    }

    /* Reviews Section */
    .reviews-summary {
        background: var(--light-color);
        padding: 1rem;
        border-radius: var(--border-radius);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .overall-rating {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }

    .rating-bars {
        margin-top: 1rem;
    }

    .rating-bar-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .rating-bar-label {
        flex: 0 0 auto;
        width: 80px;
        text-align: left;
    }

    .rating-bar-track {
        flex: 1;
        height: 8px;
        background: #e0e0e0;
        border-radius: 4px;
        overflow: hidden;
    }

    .rating-bar-fill {
        height: 100%;
        background: var(--primary-color);
        border-radius: 4px;
    }

    .rating-bar-value {
        flex: 0 0 auto;
        width: 40px;
        text-align: right;
        font-weight: 600;
    }

    /* Review Card */
    .review-card {
        background: var(--white-color);
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 1rem;
        box-shadow: var(--shadow);
    }

    .review-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }

    .review-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
    }

    .review-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .review-info {
        flex: 1;
    }

    .review-name {
        font-weight: 600;
        font-size: 0.9375rem;
    }

    .review-date {
        font-size: 0.75rem;
        color: var(--gray-600);
    }

    .review-rating {
        display: flex;
        color: #FFD700;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
    }

    .review-text {
        font-size: 0.9375rem;
        line-height: 1.6;
        color: var(--gray-700);
    }

    /* Share Section */
    .share-section {
        padding: 1.5rem 0;
        text-align: center;
    }

    .share-buttons {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 1rem;
    }

    .share-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white-color);
        font-size: 1.25rem;
        transition: var(--transition);
        text-decoration: none;
    }

    .share-btn:active {
        transform: scale(0.95);
    }

    .share-btn.facebook { background: #3b5998; }
    .share-btn.twitter { background: #1da1f2; }
    .share-btn.whatsapp { background: #25d366; }
    .share-btn.pinterest { background: #bd081c; }

    /* Related Tours */
    .related-tours {
        margin-top: 2rem;
    }

    .related-tours h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    .related-tours-slider {
        margin: 0 -1rem;
        padding: 0 1rem;
    }

    .related-tour-slide {
        padding: 0.25rem;
    }

    .related-tour-card {
        background: var(--white-color);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .related-tour-image {
        position: relative;
        height: 160px;
        overflow: hidden;
    }

    .related-tour-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .related-tour-price {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: var(--primary-color);
        color: var(--white-color);
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .related-tour-content {
        padding: 0.75rem;
    }

    .related-tour-title {
        font-size: 0.9375rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .related-tour-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.75rem;
        color: var(--gray-600);
    }

    /* Booking Modal */
    .booking-modal {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.6);
        z-index: 2000 !important; /* Header'dan daha yüksek */
        align-items: flex-end;
    }

    .booking-modal.active {
        display: flex;
    }

    .booking-modal-content {
        background: var(--white-color);
        width: 100%;
        z-index: 2001 !important;
        max-height: 90vh;
        border-radius: 20px 20px 0 0;
        padding: 1.5rem;
        position: relative;
        overflow: visible !important; /* overflow-y: auto yerine visible */
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from { transform: translateY(100%); }
        to { transform: translateY(0); }
    }

    .modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }

    .modal-close {
        width: 36px;
        height: 36px;
        background: var(--light-color);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    /* Date input için özel ayarlar */
    input[type="date"] {
        position: relative;
        z-index: 10;
        -webkit-appearance: none;
        appearance: none;
    }
    .booking-form {
        overflow: visible !important;
    }

    .booking-form .form-group {
        margin-bottom: 1.5rem;
    }

    .booking-form label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9375rem;
    }

    .booking-form input,
    .booking-form select {
        width: 100%;
        padding: 0.875rem;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: var(--transition);
    }

    .booking-form input:focus,
    .booking-form select:focus {
        outline: none;
        border-color: var(--primary-color);
    }

    .guest-selector {
        display: grid;
        gap: 1rem;
    }

    .guest-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .guest-info {
        flex: 1;
    }

    .guest-label {
        font-weight: 600;
        font-size: 0.9375rem;
    }

    .guest-sublabel {
        font-size: 0.75rem;
        color: var(--gray-600);
    }

    .guest-counter {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .counter-btn {
        width: 36px;
        height: 36px;
        border: 1px solid #e0e0e0;
        background: var(--white-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition);
    }

    .counter-btn:active {
        transform: scale(0.95);
    }

    .counter-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .counter-value {
        width: 40px;
        text-align: center;
        font-weight: 600;
    }

    .booking-summary {
        background: var(--light-color);
        padding: 1rem;
        border-radius: var(--border-radius);
        margin-bottom: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.9375rem;
    }

    .summary-row.total {
        font-weight: 700;
        font-size: 1.125rem;
        padding-top: 0.5rem;
        border-top: 1px solid #e0e0e0;
        margin-bottom: 0;
    }

    .booking-submit {
        width: 100%;
        padding: 1rem;
        background: var(--primary-color);
        color: var(--white-color);
        border: none;
        border-radius: var(--border-radius);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .booking-submit:active {
        transform: scale(0.98);
    }

    /* Help Section */
    .help-section {
        background: var(--white-color);
        padding: 1.5rem 1rem;
        margin: 1rem 0;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow);
        text-align: center;
    }

    .help-section h3 {
        font-size: 1.125rem;
        margin-bottom: 1rem;
    }

    .help-contacts {
        display: grid;
        gap: 1rem;
    }

    .help-contact {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: var(--light-color);
        border-radius: 8px;
        text-decoration: none;
        color: var(--dark-color);
        transition: var(--transition);
    }

    .help-contact:active {
        transform: scale(0.98);
    }

    .help-contact i {
        font-size: 1.25rem;
        color: var(--primary-color);
    }
    .site-header {
        overflow: visible !important;
    }
    .header-wrapper {
        overflow: visible !important;
        position: relative;
    }

    /* Language dropdown için z-index ve position düzeltmeleri */
    .language-dropdown {
        position: relative;
        z-index: 1100 !important;
    }

    .dropdown-menu {
        position: absolute !important;
        z-index: 1200 !important;
        overflow: visible !important;
    }
    /* Mobile için dropdown menu düzeltmeleri */
    @media (max-width: 768px) {
        .dropdown-menu {
            position: fixed !important; /* absolute yerine fixed */
            top: auto !important;
            right: 10px !important;
            left: auto !important;
            margin-top: 10px;
            max-width: calc(100vw - 40px);
            width: 200px;
        }
        
        /* Aktif durumda görünür olması için */
        .language-dropdown.active .dropdown-menu,
        .dropdown-menu.active {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            display: block !important;
        }
    }
    /* iOS Safari için özel düzeltmeler */
    @media (max-width: 768px) {
        input[type="date"]::-webkit-calendar-picker-indicator {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: auto;
            height: auto;
            color: transparent;
            background: transparent;
            z-index: 1;
        }
        
        input[type="date"]::-webkit-datetime-edit {
            position: relative;
            z-index: 0;
        }
    }
    /* Responsive Design */
    @media (min-width: 576px) {
        .container {
        max-width: 540px;
        margin: 0 auto;
        }

        .tour-title {
            font-size: 2rem;
        }

        .quick-info-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .swiper-slide img {
            height: 350px;
        }

        .highlights-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .info-cards {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (min-width: 768px) {
        .container {
        max-width: 720px;
        }
        .tour-hero {
            min-height: 70vh;
        }

        .tour-hero-content {
            min-height: 70vh;
            padding: 3rem 2rem;
        }

        .tour-title {
            font-size: 2.5rem;
        }

        .quick-info-bar {
            margin: -2rem 2rem 2rem;
            padding: 1.5rem 2rem;
        }

        .swiper-slide img {
            height: 450px;
        }

        .tabs-container {
            margin-bottom: 2rem;
        }

        .tab-btn {
            padding: 1.25rem 2rem;
            font-size: 1rem;
        }

        .tab-content {
            padding: 2rem;
        }

        .includes-excludes {
            grid-template-columns: 1fr 1fr;
        }

        .map-container {
            height: 400px;
        }

        .booking-modal-content {
            max-width: 600px;
            margin: 0 auto;
            border-radius: 20px;
            margin-bottom: 2rem;
        }
    }

    @media (min-width: 992px) {
        .container {
            padding: 0 2rem;
            max-width: 960px;
        }

        .tour-hero {
            min-height: 80vh;
        }

        .tour-content-wrapper {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
            align-items: start;
        }

        .price-booking-section {
            position: sticky;
            top: 2rem;
            margin-bottom: 0;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
        }

        .main-content {
            order: 1;
        }

        .sidebar-content {
            order: 2;
        }

        .swiper-slide img {
            height: 500px;
        }

        .tab-content {
            padding: 2.5rem;
        }
    }
    @media (min-width: 1200px) {
        .container {
            max-width: 1140px;
        }
    }
    /* Mobil booking bar için düzeltme */
    #mobile-booking-bar {
        position: fixed !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        z-index: 999 !important; /* Header'dan düşük ama diğer içerikten yüksek */
        background: var(--white-color) !important;
        box-shadow: 0 -4px 15px rgba(0,0,0,0.12) !important;
        display: flex;
        align-items: center !important;
        justify-content: space-between !important;
        padding: 0.8rem 1rem !important;
        margin: 0 !important;
        border-top: 1px solid rgba(0,0,0,0.05) !important;
    }

    /* Desktop görünümde mobil barı kesinlikle gizlemek için */
    @media (min-width: 992px) {
    #mobile-booking-bar {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
    }
    
    /* Body padding düzeltmesi */
    body::after {
        height: 0 !important;
    }
    }

    /* Genel hidden-desktop sınıfını daha güçlü hale getirme */
    .hidden-desktop {
    display: none !important;
    visibility: hidden !important;
    opacity: 0 !important;
    pointer-events: none !important;
    }
    
    #mobile-booking-bar .price-display {
      margin-bottom: 0 !important;
      display: flex !important;
      flex-direction: column !important;
    }
    
    #mobile-booking-bar .price-current {
      font-size: 1.25rem !important;
      line-height: 1.2 !important;
    }
    
    #mobile-booking-bar .price-original {
      font-size: 0.8rem !important;
      margin-bottom: 0.1rem !important;
    }
    
    #mobile-booking-bar .price-per {
      font-size: 0.75rem !important;
      opacity: 0.8 !important;
    }
    
    /* Enhanced Book Now Button */
    #mobile-booking-bar .book-now-btn {
      margin-left: 0.75rem !important;
      padding: 0.75rem 1.25rem !important;
      border-radius: 8px !important;
      font-weight: 700 !important;
      letter-spacing: 0.5px !important;
      text-transform: uppercase !important;
      font-size: 0.9rem !important;
      box-shadow: 0 4px 10px rgba(255, 107, 53, 0.3) !important;
      transition: transform 0.2s, box-shadow 0.2s !important;
      white-space: nowrap !important;
    }
    
    #mobile-booking-bar .book-now-btn:active {
      transform: translateY(1px) !important;
      box-shadow: 0 2px 5px rgba(255, 107, 53, 0.3) !important;
    }

    /* Ensure proper body padding */
    body {
      padding-bottom: 0 !important; /* Remove default padding */
    }

    /* Adjust padding based on booking bar */
    body::after {
      content: "";
      display: block;
      height: 70px; /* Default height for the booking bar */
      width: 100%;
    }

    /* Prevent horizontal scrolling on tabs navigation */
    .tabs-nav {
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .tabs-nav::-webkit-scrollbar {
        display: none;
    }
    @media (max-width: 375px) {

    }
    /* Adjust for smaller screens */
    @media (max-width: 375px) {
      #mobile-booking-bar {
        padding: 0.7rem 0.75rem !important;
      }
      
      #mobile-booking-bar .book-now-btn {
        padding: 0.7rem 1rem !important;
        font-size: 0.8rem !important;
      }
      
      body::after {
        height: 65px; /* Smaller bar height for very small screens */
      }
      
      .back-to-top {
        bottom: 75px; /* Adjust for smaller bar */
      }
    }

    /* Add more compact styling for very small screens */
    @media (max-width: 375px) {
        #mobile-booking-bar {
            padding: 0.5rem 0.75rem;
        }
        
        #mobile-booking-bar .price-current {
            font-size: 1.25rem;
        }
        
        #mobile-booking-bar .price-original {
            font-size: 0.875rem;
        }
        
        #mobile-booking-bar .book-now-btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
    }

    /* Fix Back to Top button positioning */
    .back-to-top {
      position: fixed;
      bottom: 115px; /* Position above the mobile booking bar */
      right: 5px;
      z-index: 99;
      width: 40px;
      height: 40px;
      background: var(--primary-color);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      border: none;
      opacity: 0;
      visibility: hidden;
      transition: opacity 0.3s, visibility 0.3s;
    }
    
    .back-to-top.visible {
      opacity: 1;
      visibility: visible;
    }
    .hidden-desktop {
        display: none !important;
    }

    /* Utility Classes */
    .text-center { text-align: center; }
    .mt-1 { margin-top: 0.5rem; }
    .mt-2 { margin-top: 1rem; }
    .mb-1 { margin-bottom: 0.5rem; }
    .mb-2 { margin-bottom: 1rem; }
    </style>

        <!-- JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
    // Language Dropdown Toggle Fonksiyonu
    document.addEventListener('DOMContentLoaded', function() {
        // Language dropdown için click event
        const languageDropdown = document.querySelector('.language-dropdown');
        const dropdownToggle = document.querySelector('.dropdown-toggle');
        const dropdownMenu = document.querySelector('.dropdown-menu');
        
        if (dropdownToggle && dropdownMenu) {
            // Mobile cihazlarda click ile açma/kapama
            dropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Toggle active class
                languageDropdown.classList.toggle('active');
                dropdownMenu.classList.toggle('active');
            });
            
            // Dropdown dışına tıklandığında kapat
            document.addEventListener('click', function(e) {
                if (!languageDropdown.contains(e.target)) {
                    languageDropdown.classList.remove('active');
                    dropdownMenu.classList.remove('active');
                }
            });
            
            // Dropdown içindeki linklere tıklandığında kapat
            dropdownMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    languageDropdown.classList.remove('active');
                    dropdownMenu.classList.remove('active');
                });
            });
        }
        
        // Date picker için özel düzeltme
        const dateInput = document.getElementById('booking_date');
        if (dateInput) {
            // iOS için özel ayar
            dateInput.addEventListener('click', function(e) {
                e.stopPropagation();
                this.showPicker ? this.showPicker() : this.focus();
            });
            
            // Android için düzeltme
            dateInput.addEventListener('touchstart', function(e) {
                e.stopPropagation();
                this.focus();
            });
        }
        
        // Modal açıldığında body scroll'u engelleme düzeltmesi
        const originalOpenBookingModal = window.openBookingModal;
        window.openBookingModal = function() {
            originalOpenBookingModal();
            // Modal açıldığında overflow'u düzelt
            document.body.style.overflow = 'hidden';
            document.body.style.position = 'fixed';
            document.body.style.width = '100%';
        };
        
        const originalCloseBookingModal = window.closeBookingModal;
        window.closeBookingModal = function() {
            originalCloseBookingModal();
            // Modal kapandığında overflow'u geri al
            document.body.style.overflow = '';
            document.body.style.position = '';
            document.body.style.width = '';
        };
    });
    // Define all functions immediately to ensure they're available globally
    function openBookingModal() {
        document.getElementById('bookingModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeBookingModal() {
        document.getElementById('bookingModal').classList.remove('active');
        document.body.style.overflow = '';
    }

    // Guest Counter Functions
    let pricePerAdult = 0; // Will be set once DOM is loaded
    let pricePerChild = 0;
    let currencySymbol = '';

    function updateGuests(type, change) {
        const countElement = document.getElementById(type + '-count');
        const inputElement = document.getElementById(type + '-input');
        let count = parseInt(countElement.textContent);
        
        count += change;
        
        // Validate bounds
        if (type === 'adults' && count < 1) count = 1;
        if (count < 0) count = 0;
        if (count > 10) count = 10;
        
        countElement.textContent = count;
        inputElement.value = count;
        
        updateBookingSummary();
    }

    function updateBookingSummary() {
        const adults = parseInt(document.getElementById('adults-count').textContent);
        const children = parseInt(document.getElementById('children-count').textContent);
        
        // Update summary
        document.getElementById('adults-summary').textContent = 
            adults + ' × ' + currencySymbol + pricePerAdult.toFixed(2);
        
        if (children > 0) {
            document.getElementById('children-summary-row').style.display = 'flex';
            document.getElementById('children-summary').textContent = 
                children + ' × ' + currencySymbol + pricePerChild.toFixed(2);
        } else {
            document.getElementById('children-summary-row').style.display = 'none';
        }
        
        // Calculate total
        const total = (adults * pricePerAdult) + (children * pricePerChild);
        document.getElementById('total-price').textContent = 
            currencySymbol + total.toFixed(2);
    }

    // Booking bar visibility function
    function handleBookingBar() {
        const mobileBar = document.getElementById('mobile-booking-bar');
        const sidebarBooking = document.querySelector('.sidebar-content .price-booking-section');
        const body = document.body;
        
        if (window.innerWidth >= 992) {
            // Desktop view - hide mobile bar
            if (mobileBar) {
                mobileBar.classList.add('hidden-desktop');
                mobileBar.style.display = 'none';
                mobileBar.style.visibility = 'hidden';
            }
            
            // Show sidebar booking section
            if (sidebarBooking) {
                sidebarBooking.style.display = 'block';
            }
            
            // Fix body padding
            body.style.paddingBottom = '0';
        } else {
            // Mobile view - show mobile bar
            if (mobileBar) {
                mobileBar.classList.remove('hidden-desktop');
                mobileBar.style.display = 'flex';
                mobileBar.style.visibility = 'visible';
            }
            
            // Hide sidebar booking section
            if (sidebarBooking) {
                sidebarBooking.style.display = 'none';
            }
            
            // Adjust body padding
            if (mobileBar) {
                const barHeight = mobileBar.offsetHeight;
                body.style.paddingBottom = barHeight + 'px';
            }
        }
    }

    // Initialize Google Map
    function initMap() {
        const mapElement = document.getElementById('tour-map');
        if (!mapElement || mapElement.hasAttribute('data-initialized')) return;
        
        const lat = parseFloat(mapElement.getAttribute('data-lat'));
        const lng = parseFloat(mapElement.getAttribute('data-lng'));
        const zoom = parseInt(mapElement.getAttribute('data-zoom'));
        
        const map = new google.maps.Map(mapElement, {
            center: { lat: lat, lng: lng },
            zoom: zoom,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false
        });
        
        new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: document.querySelector('.tour-title').textContent
        });
        
        mapElement.setAttribute('data-initialized', 'true');
    }

    // Wait for DOM to be fully loaded before initializing
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize price variables
        pricePerAdult = parseFloat(document.querySelector('.price-current').textContent.replace(/[^0-9.]/g, '')) || 0;
        pricePerChild = pricePerAdult * 0.5;
        currencySymbol = document.querySelector('.price-current').textContent.replace(/[0-9.,]/g, '').trim();
        
        // Initialize Swiper Gallery
        const gallerySwiper = new Swiper('.gallery-swiper', {
            slidesPerView: 1,
            spaceBetween: 0,
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true
            },
            on: {
                slideChange: function() {
                    document.querySelector('.gallery-counter .current').textContent = this.realIndex + 1;
                }
            }
        });

        // Initialize Related Tours Swiper
        if (document.querySelector('.related-tours-slider')) {
            const relatedSwiper = new Swiper('.related-tours-slider', {
                slidesPerView: 1.2,
                spaceBetween: 16,
                breakpoints: {
                    576: {
                        slidesPerView: 2.2,
                        spaceBetween: 20
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 24
                    },
                    992: {
                        slidesPerView: 2,
                        spaceBetween: 24
                    }
                }
            });
        }

        // Tab Navigation
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabId = button.getAttribute('data-tab');
                
                // Remove active class from all
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));
                
                // Add active class to clicked
                button.classList.add('active');
                document.getElementById(tabId).classList.add('active');
                
                // Initialize map when location tab is clicked
                if (tabId === 'location' && typeof initMap === 'function') {
                    initMap();
                }
            });
        });

        // Close modal on backdrop click
        document.getElementById('bookingModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBookingModal();
            }
        });

        // Share Buttons
        document.querySelectorAll('.share-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const type = this.getAttribute('data-type');
                const url = encodeURIComponent(window.location.href);
                const title = encodeURIComponent(document.title);
                
                let shareUrl = '';
                
                switch(type) {
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                        break;
                    case 'whatsapp':
                        shareUrl = `https://api.whatsapp.com/send?text=${title}%20${url}`;
                        break;
                    case 'pinterest':
                        const image = encodeURIComponent(document.querySelector('.swiper-slide img').src);
                        shareUrl = `https://pinterest.com/pin/create/button/?url=${url}&media=${image}&description=${title}`;
                        break;
                }
                
                if (shareUrl) {
                    window.open(shareUrl, '_blank', 'width=600,height=500');
                }
            });
        });

        // Call the function on load and resize
        handleBookingBar();
        window.addEventListener('resize', handleBookingBar);
    });
    </script>

    <!-- Google Maps Script -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>