<?php
/**
 * Tour Detail View
 */
?>

<!-- Tour Header -->
<div class="tour-header" style="background-image: url('<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>');">
    <div class="container">
        <div class="tour-header-content">
            <div class="breadcrumbs">
                <ul class="breadcrumbs-list">
                    <li class="breadcrumbs-item"><a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a></li>
                    <li class="breadcrumbs-item"><a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours"><?php _e('tours'); ?></a></li>
                    <?php if ($tour['category_name']): ?>
                        <li class="breadcrumbs-item"><a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours?category=<?php echo $tour['category_slug']; ?>"><?php echo $tour['category_name']; ?></a></li>
                    <?php endif; ?>
                    <li class="breadcrumbs-item active"><?php echo $tour['name']; ?></li>
                </ul>
            </div>
            
            <h1 class="tour-title"><?php echo $tour['name']; ?></h1>
            
            <div class="tour-meta">
                <div class="tour-meta-item">
                    <i class="material-icons">schedule</i>
                    <span><?php echo $tour['duration']; ?></span>
                </div>
                <?php if ($tour['category_name']): ?>
                    <div class="tour-meta-item">
                        <i class="material-icons">category</i>
                        <span><?php echo $tour['category_name']; ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="tour-price">
                <?php if ($tour['discount_price']): ?>
                    <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                    <span><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
                    <div class="tour-discount-badge">
                        <?php echo round((($tour['price'] - $tour['discount_price']) / $tour['price']) * 100); ?>% <?php _e('discount'); ?>
                    </div>
                <?php else: ?>
                    <span><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Tour Content -->
<section class="tour-section section">
    <div class="container">
        <div class="tour-content-wrapper">
            <!-- Tour Main Content -->
            <div class="tour-main-content">
                <!-- Tour Description -->
                <div class="tour-description" data-aos="fade-up">
                    <h2><?php _e('tour_description'); ?></h2>
                    <?php echo $tour['short_description']; ?>
                    <?php echo $tour['description']; ?>
                </div>
                
                <!-- Tour Features -->
                <div class="tour-features" data-aos="fade-up">
                    <h2><?php _e('tour_includes'); ?></h2>
                    <div class="tour-features-list">
                        <?php
                        // Parse includes as list
                        $includes = explode("\n", $tour['includes']);
                        foreach ($includes as $include):
                            if (trim($include)):
                        ?>
                            <div class="tour-feature">
                                <i class="material-icons">check_circle</i>
                                <span><?php echo trim($include); ?></span>
                            </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
                
                <!-- Tour Excludes -->
                <?php if (!empty($tour['excludes'])): ?>
                <div class="tour-excludes" data-aos="fade-up">
                    <h2><?php _e('tour_excludes'); ?></h2>
                    <div class="tour-excludes-list">
                        <?php
                        // Parse excludes as list
                        $excludes = explode("\n", $tour['excludes']);
                        foreach ($excludes as $exclude):
                            if (trim($exclude)):
                        ?>
                            <div class="tour-exclude">
                                <i class="material-icons">remove_circle</i>
                                <span><?php echo trim($exclude); ?></span>
                            </div>
                        <?php
                            endif;
                        endforeach;
                        ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Tour Tabs -->
                <div class="tour-tabs" data-aos="fade-up">
                    <div class="tour-tabs-nav">
                        <button class="tour-tab-button active" data-target="itinerary"><?php _e('itinerary'); ?></button>
                        <button class="tour-tab-button" data-target="location"><?php _e('location'); ?></button>
                        <button class="tour-tab-button" data-target="gallery"><?php _e('gallery'); ?></button>
                    </div>
                    
                    <!-- Itinerary Tab -->
                    <div class="tour-tab-content active" data-id="itinerary">
                        <div class="tour-itinerary">
                            <?php
                            if (!empty($tour['itinerary'])):
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
                                            echo '</div>';
                                            $inItem = false;
                                        endif;
                                        
                                        // Start new item
                                        echo '<div class="tour-itinerary-item">';
                                        $inItem = true;
                                        
                                        if (isset($matches[1])):
                                            $day = $matches[1];
                                        endif;
                                        
                                        echo '<div class="tour-itinerary-day">' . __('day') . ' ' . $day . '</div>';
                                        
                                        // Extract title (after Day X:)
                                        $title = preg_replace('/^Day\s+\d+[:\.\-\s]*/i', '', $line);
                                        $title = preg_replace('/^\d+\.\s*/i', '', $title);
                                        
                                        if (!empty($title)):
                                            echo '<h3 class="tour-itinerary-title">' . $title . '</h3>';
                                        endif;
                                        
                                        echo '<div class="tour-itinerary-details">';
                                    else:
                                        // Regular content line
                                        if (!$inItem):
                                            // Start new item if not already in one
                                            echo '<div class="tour-itinerary-item">';
                                            echo '<div class="tour-itinerary-day">' . __('day') . ' ' . $day . '</div>';
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
                                    echo '</div></div>';
                                endif;
                            else:
                                echo '<div class="tour-no-itinerary">';
                                echo '<p>' . __('no_itinerary_available') . '</p>';
                                echo '</div>';
                            endif;
                            ?>
                        </div>
                    </div>
                    
                    <!-- Location Tab -->
                    <div class="tour-tab-content" data-id="location">
                        <div class="tour-location">
                            <div id="tour_map" class="tour-map" data-lat="38.642335" data-lng="34.827335" data-zoom="13" data-title="<?php echo $tour['name']; ?>"></div>
                        </div>
                    </div>
                    
                    <!-- Gallery Tab -->
                    <div class="tour-tab-content" data-id="gallery">
                        <div class="tour-gallery">
                            <?php if (empty($gallery)): ?>
                                <div class="tour-no-gallery">
                                    <p><?php _e('no_gallery_available'); ?></p>
                                </div>
                            <?php else: ?>
                                <div class="tour-gallery-grid">
                                    <?php foreach ($gallery as $item): ?>
                                        <div class="tour-gallery-item">
                                            <a href="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" title="<?php echo $item['title']; ?>">
                                                <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                                                <div class="tour-gallery-overlay">
                                                    <i class="material-icons">zoom_in</i>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Share Buttons -->
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
                
                <!-- Related Tours -->
                <?php if (!empty($relatedTours)): ?>
                <div class="related-tours" data-aos="fade-up">
                    <h2><?php _e('related_tours'); ?></h2>
                    <div class="tours-grid">
                        <?php foreach ($relatedTours as $relatedTour): ?>
                            <div class="tour-card">
                                <div class="tour-image">
                                    <img src="<?php echo $uploadsUrl . '/tours/' . $relatedTour['featured_image']; ?>" alt="<?php echo $relatedTour['name']; ?>">
                                    <div class="tour-price">
                                        <?php if ($relatedTour['discount_price']): ?>
                                            <del><?php echo $settings['currency_symbol'] . number_format($relatedTour['price'], 2); ?></del>
                                            <?php echo $settings['currency_symbol'] . number_format($relatedTour['discount_price'], 2); ?>
                                        <?php else: ?>
                                            <?php echo $settings['currency_symbol'] . number_format($relatedTour['price'], 2); ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($relatedTour['category_name']): ?>
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
                                    <p class="tour-description">
                                        <?php echo substr(strip_tags($relatedTour['short_description']), 0, 120) . '...'; ?>
                                    </p>
                                    <div class="tour-footer">
                                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $relatedTour['slug']; ?>" class="btn btn-outline btn-sm">
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
            
            <!-- Tour Sidebar -->
            <div class="tour-sidebar">
                <!-- Booking Widget -->
                <div class="tour-booking-widget glass-card" data-aos="fade-left">
                    <h3><?php _e('book_this_tour'); ?></h3>
                    
                    <!-- Tour Price Display -->
                    <div class="booking-price">
                        <?php if ($tour['discount_price']): ?>
                            <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                            <span id="price_display"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
                            <div class="booking-price-per"><?php _e('per_person'); ?></div>
                        <?php else: ?>
                            <span id="price_display"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
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
                        
                        <div class="booking-total">
                            <span><?php _e('total'); ?>:</span>
                            <span id="total_price_display"><?php echo $settings['currency_symbol']; ?><?php echo number_format($tour['discount_price'] > 0 ? $tour['discount_price'] * 2 : $tour['price'] * 2, 2); ?></span>
                        </div>
                        
                        <input type="hidden" id="booking_base_price" value="<?php echo $tour['price']; ?>">
                        <input type="hidden" id="booking_discount_price" value="<?php echo $tour['discount_price']; ?>">
                        <input type="hidden" id="currency_symbol" value="<?php echo $settings['currency_symbol']; ?>">
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="material-icons">shopping_cart</i>
                            <?php _e('book_now'); ?>
                        </button>
                    </form>
                    
                    <!-- Booking Info -->
                    <div class="booking-info">
                        <div class="booking-info-item">
                            <i class="material-icons">info</i>
                            <span><?php _e('booking_no_charge'); ?></span>
                        </div>
                        <div class="booking-info-item">
                            <i class="material-icons">event_available</i>
                            <span><?php _e('booking_instant_confirmation'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Tour Information -->
                <div class="sidebar-widget material-card" data-aos="fade-left">
                    <h3><?php _e('tour_information'); ?></h3>
                    <ul class="tour-info-list">
                        <li class="tour-info-item">
                            <div class="tour-info-label"><?php _e('duration'); ?></div>
                            <div class="tour-info-value"><?php echo $tour['duration']; ?></div>
                        </li>
                        <li class="tour-info-item">
                            <div class="tour-info-label"><?php _e('group_size'); ?></div>
                            <div class="tour-info-value"><?php _e('max'); ?> 15</div>
                        </li>
                        <li class="tour-info-item">
                            <div class="tour-info-label"><?php _e('languages'); ?></div>
                            <div class="tour-info-value">English, Türkçe</div>
                        </li>
                        <?php if ($tour['category_name']): ?>
                            <li class="tour-info-item">
                                <div class="tour-info-label"><?php _e('category'); ?></div>
                                <div class="tour-info-value"><?php echo $tour['category_name']; ?></div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
                
                <!-- Need Help Widget -->
                <div class="sidebar-widget glass-card" data-aos="fade-left">
                    <h3><?php _e('need_help'); ?></h3>
                    <div class="help-info">
                        <div class="help-item">
                            <i class="material-icons">phone</i>
                            <div class="help-content">
                                <div class="help-label"><?php _e('phone'); ?></div>
                                <div class="help-value"><?php echo $settings['contact_phone']; ?></div>
                            </div>
                        </div>
                        <div class="help-item">
                            <i class="material-icons">email</i>
                            <div class="help-content">
                                <div class="help-label"><?php _e('email'); ?></div>
                                <div class="help-value"><?php echo $settings['contact_email']; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section" style="background-image: url('<?php echo $imgUrl; ?>/cta-bg.jpg');">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title"><?php _e('discover_more_tours'); ?></h2>
            <p class="cta-text"><?php _e('discover_more_tours_text'); ?></p>
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-lg">
                <i class="material-icons">explore</i>
                <?php _e('view_all_tours'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Load Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initTourMap" async defer></script>