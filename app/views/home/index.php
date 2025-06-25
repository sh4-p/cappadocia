<?php
/**
 * Homepage view - Modern Redesign
 * Sales-driven, mobile-optimized layout
 */

 // Hero background image
$heroBgImage = isset($settings['hero_bg']) ? $settings['hero_bg'] : 'hero-bg.jpg';

// Stats background image
$statsBgImage = isset($settings['stats_bg']) ? $settings['stats_bg'] : 'stats-bg.jpg';

// About section background image
$aboutBgImage = isset($settings['about_bg']) ? $settings['about_bg'] : 'about-bg.jpg';

// About section images
$aboutImage1 = isset($settings['about_image1']) ? $settings['about_image1'] : 'about-cappadocia-1.jpg';
$aboutImage2 = isset($settings['about_image2']) ? $settings['about_image2'] : 'about-cappadocia-2.jpg';

// Testimonials background image
$testimonialsBgImage = isset($settings['testimonials_bg']) ? $settings['testimonials_bg'] : 'testimonials-bg.jpg';

// Call-to-action background image
$ctaBgImage = isset($settings['cta_bg']) ? $settings['cta_bg'] : 'cta-bg.jpg';
?>

<!-- Hero Section with Dynamic Background -->
<section class="hero-section" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $heroBgImage; ?>');">
    <div class="container">
        <div class="hero-content fade-in-up">
            <h1 class="hero-title"><?php _e('discover_magical_cappadocia'); ?></h1>
            <p class="hero-subtitle"><?php _e('home_hero_subtitle'); ?></p>
            <div class="hero-buttons">
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-lg">
                    <i class="material-icons">explore</i>
                    <?php _e('explore_tours'); ?>
                </a>
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-glass btn-lg">
                    <i class="material-icons">chat</i>
                    <?php _e('contact_us'); ?>
                </a>
            </div>
            
            <!-- Quick Booking Form with AJAX search -->
            <div class="hero-booking-form glass-card">
                <h3><?php _e('quick_booking'); ?></h3>
                <form id="quick-booking-form" class="quick-booking-form" data-app-url="<?php echo $appUrl; ?>">
                    <div class="form-row">
                        <div class="form-group">
                            <div class="input-with-icon">
                                <i class="material-icons">search</i>
                                <input type="text" name="keyword" id="quick_booking_keyword" placeholder="<?php _e('search_by_keywords'); ?>" class="form-control" autocomplete="off">
                            </div>
                            <!-- Add results container for AJAX search -->
                            <div id="quick-search-results" class="quick-search-results"></div>
                        </div>
                        <div class="form-group">
                            <div class="input-with-icon">
                                <i class="material-icons">event</i>
                                <input type="text" name="date" id="quick_booking_date" placeholder="<?php _e('select_date'); ?>" class="form-control datepicker" autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-with-icon">
                                <i class="material-icons">group</i>
                                <select name="guests" class="form-control">
                                    <option value=""><?php _e('guests'); ?></option>
                                    <option value="1">1 <?php _e('person'); ?></option>
                                    <option value="2">2 <?php _e('persons'); ?></option>
                                    <option value="3">3 <?php _e('persons'); ?></option>
                                    <option value="4">4 <?php _e('persons'); ?></option>
                                    <option value="5+">5+ <?php _e('persons'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="material-icons">search</i>
                                <?php _e('find_tours'); ?>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator">
        <a href="#featured-tours">
            <i class="material-icons">keyboard_arrow_down</i>
        </a>
    </div>
</section>

<!-- Featured Tours Section - Enhanced Card Design -->
<section class="section" id="featured-tours" >
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('featured_tours'); ?></h2>
            <p class="section-subtitle"><?php _e('featured_tours_subtitle'); ?></p>
        </div>
        
        <div class="tours-grid">
            <?php foreach ($featuredTours as $index => $tour): ?>
                <div class="tour-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
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
                            <div class="tour-meta-item">
                                <i class="material-icons">group</i>
                                <span><?php _e('max'); ?> 15</span>
                            </div>
                        </div>
                        <div class="tour-rating">
                            <?php 
                            $rating = isset($tour['rating']) ? $tour['rating'] : 5;
                            for ($i = 1; $i <= 5; $i++): ?>
                                <i class="material-icons"><?php echo $i <= $rating ? 'star' : 'star_border'; ?></i>
                            <?php endfor; ?>
                            <span class="rating-count">(<?php echo isset($tour['reviews_count']) ? $tour['reviews_count'] : rand(10, 50); ?>)</span>
                        </div>
                        <p class="tour-description">
                            <?php echo substr(strip_tags($tour['short_description']), 0, 120) . '...'; ?>
                        </p>
                        <div class="tour-footer">
                            <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>" class="btn btn-primary btn-sm">
                                <?php _e('view_details'); ?>
                            </a>
                            <button class="btn-favorite" data-tour-id="<?php echo $tour['id']; ?>" title="<?php _e('add_to_favorites'); ?>">
                                <i class="material-icons">favorite_border</i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center" data-aos="fade-up">
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary">
                <i class="material-icons">arrow_forward</i>
                <?php _e('view_all_tours'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Stats Section - Fixed z-index and positioning -->
<section class="section stats-section" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $statsBgImage; ?>'); position: relative; z-index: 1;">
    <div class="container">
        <div class="stats-grid" data-aos="fade-up">
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="material-icons">people</i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value" data-count="15000">0</h3>
                    <p class="stat-label"><?php _e('happy_customers'); ?></p>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="material-icons">explore</i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value" data-count="120">0</h3>
                    <p class="stat-label"><?php _e('tours_completed'); ?></p>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="material-icons">place</i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value" data-count="25">0</h3>
                    <p class="stat-label"><?php _e('destinations'); ?></p>
                </div>
            </div>
            
            <div class="stat-item">
                <div class="stat-icon">
                    <i class="material-icons">verified_user</i>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value" data-count="15">0</h3>
                    <p class="stat-label"><?php _e('years_experience'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Cappadocia Section - Fixed positioning and spacing -->
<section class="section about-section" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $aboutBgImage; ?>'); position: relative; z-index: 1;">
    <div class="container">
        <div class="row">
            <div class="col-md-6" data-aos="fade-right">
                <div class="glass-card dark">
                    <h2><?php _e('about_cappadocia'); ?></h2>
                    <p><?php _e('about_cappadocia_text_1'); ?></p>
                    <p><?php _e('about_cappadocia_text_2'); ?></p>
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/about" class="btn btn-glass">
                        <?php _e('learn_more'); ?>
                        <i class="material-icons">arrow_forward</i>
                    </a>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <div class="about-image-container">
                    <div class="about-image main-image">
                        <img src="<?php echo $imgUrl; ?>/<?php echo $aboutImage1; ?>" alt="<?php _e('about_cappadocia'); ?>">
                    </div>
                    <div class="about-image secondary-image">
                        <img src="<?php echo $imgUrl; ?>/<?php echo $aboutImage2; ?>" alt="<?php _e('about_cappadocia'); ?>">
                    </div>
                    <div class="experience-badge">
                        <span class="years">15+</span>
                        <span class="text"><?php _e('years_experience'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Destinations Section - Visual Enhancement -->
<section class="section" id="destinations">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('popular_destinations'); ?></h2>
            <p class="section-subtitle"><?php _e('popular_destinations_subtitle'); ?></p>
        </div>
        
        <div class="destination-grid">
            <?php foreach ($destinations as $index => $destination): ?>
                <div class="destination-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <img src="<?php echo $uploadsUrl . '/categories/' . $destination['image']; ?>" alt="<?php echo $destination['name']; ?>" class="destination-image">
                    <div class="destination-content">
                        <h3 class="destination-title"><?php echo $destination['name']; ?></h3>
                        <div class="destination-tours">
                            <?php 
                            // Count tours in this category
                            $tourCount = isset($destination['tour_count']) ? $destination['tour_count'] : 
                                      (method_exists($this, 'tourModel') ? $this->tourModel->countTours(['category_id' => $destination['id'], 'is_active' => 1]) : 0);
                            echo sprintf(__('tours_count'), $tourCount);
                            ?>
                        </div>
                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours?category=' . $destination['slug']; ?>" class="btn btn-glass btn-sm">
                            <?php _e('explore'); ?>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Why Choose Us Section - Enhanced Visual Design -->
<section class="section why-choose-us" style="background-color: var(--gray-300); position: relative; z-index: 1;">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('why_choose_us'); ?></h2>
            <p class="section-subtitle"><?php _e('why_choose_us_subtitle'); ?></p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card material-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="material-icons">verified_user</i>
                </div>
                <h3 class="feature-title"><?php _e('experienced_guides'); ?></h3>
                <p class="feature-text"><?php _e('experienced_guides_text'); ?></p>
            </div>
            
            <div class="feature-card material-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="material-icons">star</i>
                </div>
                <h3 class="feature-title"><?php _e('quality_service'); ?></h3>
                <p class="feature-text"><?php _e('quality_service_text'); ?></p>
            </div>
            
            <div class="feature-card material-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                    <i class="material-icons">monetization_on</i>
                </div>
                <h3 class="feature-title"><?php _e('best_price'); ?></h3>
                <p class="feature-text"><?php _e('best_price_text'); ?></p>
            </div>
            
            <div class="feature-card material-card" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-icon">
                    <i class="material-icons">schedule</i>
                </div>
                <h3 class="feature-title"><?php _e('flexible_booking'); ?></h3>
                <p class="feature-text"><?php _e('flexible_booking_text'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section - Visual Enhancement -->
<section class="section testimonials-section" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $testimonialsBgImage; ?>');">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title" style="color: var(--white-color);"><?php _e('testimonials'); ?></h2>
            <p class="section-subtitle" style="color: var(--white-color);"><?php _e('testimonials_subtitle'); ?></p>
        </div>
        
        <div class="testimonial-slider" data-aos="fade-up">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="testimonial-slide">
                    <div class="testimonial-image">
                        <?php if (isset($testimonial['image']) && $testimonial['image']): ?>
                            <img src="<?php echo $uploadsUrl . '/testimonials/' . $testimonial['image']; ?>" alt="<?php echo $testimonial['name']; ?>">
                        <?php else: ?>
                            <img src="<?php echo $imgUrl; ?>/default-avatar.png" alt="<?php echo $testimonial['name']; ?>">
                        <?php endif; ?>
                    </div>
                    <p class="testimonial-quote"><?php echo $testimonial['content']; ?></p>
                    <h4 class="testimonial-name"><?php echo $testimonial['name']; ?></h4>
                    <?php if (isset($testimonial['position']) && $testimonial['position']): ?>
                        <p class="testimonial-position"><?php echo $testimonial['position']; ?></p>
                    <?php endif; ?>
                    <div class="testimonial-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="material-icons"><?php echo $i <= (isset($testimonial['rating']) ? $testimonial['rating'] : 5) ? 'star' : 'star_border'; ?></i>
                        <?php endfor; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="testimonial-navigation">
                <button class="testimonial-nav-button testimonial-prev">
                    <i class="material-icons">arrow_back</i>
                </button>
                <button class="testimonial-nav-button testimonial-next">
                    <i class="material-icons">arrow_forward</i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section - Visual Enhancement -->
<section class="section" id="gallery">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('gallery'); ?></h2>
            <p class="section-subtitle"><?php _e('gallery_subtitle'); ?></p>
        </div>
        
        <div class="gallery-grid">
            <?php foreach ($galleryItems as $index => $item): ?>
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4) * 100; ?>">
                    <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" alt="<?php echo isset($item['title']) ? $item['title'] : __('gallery_image'); ?>" class="gallery-image">
                    <div class="gallery-overlay">
                        <i class="material-icons gallery-icon">zoom_in</i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center" data-aos="fade-up" style="margin-top: var(--spacing-xl);">
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/gallery" class="btn btn-primary">
                <i class="material-icons">photo_library</i>
                <?php _e('view_all_photos'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Newsletter Section - Updated for System -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content" data-aos="fade-up">
            <div class="newsletter-icon">
                <i class="material-icons">mail</i>
            </div>
            <h2 class="newsletter-title"><?php _e('subscribe_newsletter'); ?></h2>
            <p class="newsletter-text"><?php _e('newsletter_text'); ?></p>
            
            <!-- Newsletter Alert Messages -->
            <div id="newsletter-alerts" class="newsletter-alerts">
                <?php if ($session->getFlash('newsletter_success')): ?>
                    <div class="alert alert-success">
                        <i class="material-icons">check_circle</i>
                        <?php echo $session->getFlash('newsletter_success'); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($session->getFlash('newsletter_error')): ?>
                    <div class="alert alert-error">
                        <i class="material-icons">error</i>
                        <?php echo $session->getFlash('newsletter_error'); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Newsletter Subscription Form -->
            <form action="<?php echo $appUrl . '/' . $currentLang; ?>/newsletter/subscribe" method="post" class="newsletter-form" id="newsletter-form">
                <div class="newsletter-form-group">
                    <div class="newsletter-input-row">
                        <div class="newsletter-input-wrap">
                            <input type="email" 
                                   name="email" 
                                   id="newsletter_email"
                                   placeholder="<?php _e('your_email'); ?>" 
                                   required 
                                   class="newsletter-input"
                                   autocomplete="email">
                            <label for="newsletter_email" class="newsletter-label"><?php _e('email_address'); ?></label>
                        </div>
                        
                        <div class="newsletter-input-wrap">
                            <input type="text" 
                                   name="name" 
                                   id="newsletter_name"
                                   placeholder="<?php _e('your_name_optional'); ?>" 
                                   class="newsletter-input"
                                   autocomplete="name">
                            <label for="newsletter_name" class="newsletter-label"><?php _e('name_optional'); ?></label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary newsletter-button" id="newsletter-submit">
                        <span class="button-text">
                            <i class="material-icons">send</i>
                            <?php _e('subscribe'); ?>
                        </span>
                        <span class="button-loading" style="display: none;">
                            <i class="material-icons spinning">hourglass_empty</i>
                            <?php _e('sending'); ?>...
                        </span>
                    </button>
                </div>
                
                <!-- Privacy Notice -->
                <div class="newsletter-privacy">
                    <small class="privacy-text">
                        <i class="material-icons">lock</i>
                        <?php _e('newsletter_privacy_notice'); ?>
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/privacy" target="_blank"><?php _e('privacy_policy'); ?></a>
                    </small>
                </div>
            </form>
            
            <!-- Newsletter Features -->
            <div class="newsletter-features">
                <div class="newsletter-feature">
                    <i class="material-icons">notifications</i>
                    <span><?php _e('latest_tour_updates'); ?></span>
                </div>
                <div class="newsletter-feature">
                    <i class="material-icons">local_offer</i>
                    <span><?php _e('exclusive_discounts'); ?></span>
                </div>
                <div class="newsletter-feature">
                    <i class="material-icons">event</i>
                    <span><?php _e('seasonal_offers'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action Section - Enhanced Design -->
<section class="cta-section" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $ctaBgImage; ?>');">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title"><?php _e('ready_for_adventure'); ?></h2>
            <p class="cta-text"><?php _e('ready_for_adventure_text'); ?></p>
            <div class="cta-buttons">
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-lg">
                    <i class="material-icons">flight_takeoff</i>
                    <?php _e('book_now'); ?>
                </a>
                <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" class="btn btn-glass btn-lg">
                    <i class="material-icons">phone</i>
                    <?php _e('call_us'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Additional Styles for Homepage -->
<style>
    /* About Section Enhancements - Desktop optimized */
    .about-section {
        position: relative;
        background-position: center;
        background-size: cover;
        padding: var(--spacing-xxl) 0;
    }
    
    .about-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(38, 70, 83, 0.75);
    }
    
    .about-section .container {
        position: relative;
        z-index: 1;
    }
    
    .about-section .row {
        align-items: center;
        min-height: 600px;
    }
    
    .about-image-container {
        position: relative;
        height: 100%;
        display: flex;
        justify-content: center;
        min-height: 500px;
        padding: var(--spacing-lg);
    }
    
    .about-image {
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        width: 100%;
        position: relative;
    }
    
    .main-image {
        width: 85%;
        height: 400px;
        z-index: 2;
        position: relative;
    }
    
    .secondary-image {
        position: absolute;
        width: 65%;
        height: 280px;
        bottom: 15%;
        right: -5%;
        z-index: 1;
        box-shadow: var(--shadow-xl);
    }
    
    .main-image img,
    .secondary-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    
    .experience-badge {
        position: absolute;
        bottom: 25px;
        right: 25px;
        width: 140px;
        height: 140px;
        background-color: var(--primary-color);
        color: var(--white-color);
        border-radius: var(--border-radius-circle);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-weight: var(--font-weight-bold);
        box-shadow: var(--shadow-lg);
        z-index: 3;
        border: 4px solid var(--white-color);
    }
    
    .experience-badge .years {
        font-size: 2.8rem;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .experience-badge .text {
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    /* Hero Section Enhancements */
    .hero-section {
        height: 100vh;
        min-height: 800px;
        display: flex;
        align-items: center;
        position: relative;
    }
    
    .hero-content {
        max-width: 650px;
        position: relative;
        z-index: 2;
    }
    
    .hero-booking-form {
        margin-top: var(--spacing-xl);
        padding: var(--spacing-lg);
        max-width: 100%;
    }
    
    .hero-booking-form h3 {
        margin-bottom: var(--spacing-md);
        font-size: var(--font-size-lg);
    }
    
    .form-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-md);
    }
    
    .scroll-indicator {
        position: absolute;
        bottom: 30px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 2;
        animation: bounce 2s infinite;
    }
    
    .scroll-indicator a {
        display: block;
        width: 50px;
        height: 50px;
        background-color: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white-color);
        transition: background-color var(--transition-fast);
    }
    
    .scroll-indicator a:hover {
        background-color: var(--primary-color);
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateX(-50%) translateY(0);
        }
        40% {
            transform: translateX(-50%) translateY(-10px);
        }
        60% {
            transform: translateX(-50%) translateY(-5px);
        }
    }
    
    /* Tour Card Enhancements */
    .tour-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background-color: var(--primary-color);
        color: var(--white-color);
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-md);
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-medium);
        display: flex;
        align-items: center;
        gap: 0.25rem;
        box-shadow: var(--shadow-sm);
        z-index: 1;
    }
    
    .tour-rating {
        display: flex;
        align-items: center;
        color: #FFD700;
        margin-bottom: var(--spacing-sm);
    }
    
    .tour-rating .material-icons {
        font-size: 1rem;
    }
    
    .rating-count {
        color: var(--gray-600);
        font-size: var(--font-size-sm);
        margin-left: 0.25rem;
    }
    
    .btn-favorite {
        width: 36px;
        height: 36px;
        border-radius: var(--border-radius-circle);
        background-color: var(--white-color);
        color: var(--gray-600);
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--gray-300);
        transition: all var(--transition-fast);
        cursor: pointer;
    }
    
    .btn-favorite:hover, .btn-favorite.active {
        background-color: var(--primary-color);
        color: var(--white-color);
        border-color: var(--primary-color);
    }
    
    .tour-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* Stats Section */
    .stats-section {
        position: relative;
        background-position: center;
        background-size: cover;
        padding: var(--spacing-xl) 0;
    }
    
    .stats-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(38, 70, 83, 0.85);
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-xl);
        position: relative;
        z-index: 1;
    }
    
    .stat-item {
        text-align: center;
        color: var(--white-color);
    }
    
    .stat-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto var(--spacing-md);
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: var(--font-weight-bold);
        margin-bottom: var(--spacing-xs);
        color: var(--white-color);
    }
    
    .stat-label {
        color: rgba(255, 255, 255, 0.8);
        font-size: var(--font-size-md);
    }
    
    /* About Section Enhancements */
    .about-image-container {
        position: relative;
        height: 100%;
        display: flex;
        justify-content: center;
    }
    
    .about-image {
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }
    
    .main-image {
        width: 80%;
        z-index: 2;
    }
    
    .secondary-image {
        position: absolute;
        width: 60%;
        bottom: 10%;
        right: 0;
        z-index: 1;
    }
    
    /* Newsletter Section Base Styles */
    .newsletter-section {
        background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
        padding: var(--spacing-xxl) 0;
        position: relative;
        overflow: hidden;
    }
    
    .newsletter-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="30" r="1.5" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        animation: float 20s ease-in-out infinite;
        pointer-events: none;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    .newsletter-content {
        text-align: center;
        max-width: 800px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }
    
    .newsletter-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto var(--spacing-lg);
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--white-color);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .newsletter-title {
        font-size: var(--font-size-3xl);
        font-weight: var(--font-weight-bold);
        margin-bottom: var(--spacing-md);
        color: var(--white-color);
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .newsletter-text {
        color: rgba(255, 255, 255, 0.9);
        font-size: var(--font-size-lg);
        margin-bottom: var(--spacing-xl);
        line-height: 1.6;
    }
    
    /* Alert Messages */
    .newsletter-alerts {
        margin-bottom: var(--spacing-lg);
    }
    
    .newsletter-alerts .alert {
        display: inline-flex;
        align-items: center;
        gap: var(--spacing-sm);
        padding: var(--spacing-md) var(--spacing-lg);
        border-radius: var(--border-radius-lg);
        font-size: var(--font-size-md);
        font-weight: var(--font-weight-medium);
        box-shadow: var(--shadow-md);
        margin-bottom: var(--spacing-md);
        animation: slideDown 0.3s ease-out;
    }
    
    .alert-success {
        background-color: #10b981;
        color: var(--white-color);
    }
    
    .alert-error {
        background-color: #ef4444;
        color: var(--white-color);
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Form Styles */
    .newsletter-form {
        max-width: 600px;
        margin: 0 auto var(--spacing-xl);
    }
    
    .newsletter-form-group {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }
    
    .newsletter-input-row {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--spacing-md);
        margin-bottom: var(--spacing-md);
    }
    
    .newsletter-input-wrap {
        position: relative;
    }
    
    .newsletter-input {
        width: 100%;
        height: 60px;
        border: 2px solid rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius-lg);
        padding: 0 var(--spacing-lg);
        font-size: var(--font-size-md);
        color: var(--white-color);
        transition: all var(--transition-medium);
        outline: none;
    }
    
    .newsletter-input::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }
    
    .newsletter-input:focus {
        border-color: rgba(255, 255, 255, 0.5);
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.1);
    }
    
    .newsletter-input:valid {
        border-color: rgba(16, 185, 129, 0.5);
    }
    
    .newsletter-label {
        position: absolute;
        top: -8px;
        left: var(--spacing-md);
        background: var(--primary-color);
        color: var(--white-color);
        font-size: var(--font-size-xs);
        font-weight: var(--font-weight-medium);
        padding: 2px 8px;
        border-radius: var(--border-radius-sm);
        opacity: 0;
        transform: translateY(5px);
        transition: all var(--transition-fast);
        pointer-events: none;
    }
    
    .newsletter-input:focus + .newsletter-label,
    .newsletter-input:valid + .newsletter-label {
        opacity: 1;
        transform: translateY(0);
    }
    
    .newsletter-button {
        height: 60px;
        border-radius: var(--border-radius-lg);
        background: var(--white-color);
        color: var(--primary-color);
        border: none;
        font-size: var(--font-size-md);
        font-weight: var(--font-weight-semibold);
        transition: all var(--transition-medium);
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .newsletter-button:hover:not(:disabled) {
        background: rgba(255, 255, 255, 0.95);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .newsletter-button:active {
        transform: translateY(0);
    }
    
    .newsletter-button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    .spinning {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Privacy Notice */
    .newsletter-privacy {
        text-align: center;
        margin-top: var(--spacing-md);
    }
    
    .privacy-text {
        color: rgba(255, 255, 255, 0.8);
        font-size: var(--font-size-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: var(--spacing-xs);
        flex-wrap: wrap;
    }
    
    .privacy-text a {
        color: var(--white-color);
        text-decoration: underline;
        font-weight: var(--font-weight-medium);
    }
    
    .privacy-text a:hover {
        text-decoration: none;
    }
    
    /* Newsletter Features */
    .newsletter-features {
        display: flex;
        justify-content: center;
        gap: var(--spacing-xl);
        flex-wrap: wrap;
        margin-top: var(--spacing-lg);
    }
    
    .newsletter-feature {
        display: flex;
        align-items: center;
        gap: var(--spacing-sm);
        color: rgba(255, 255, 255, 0.9);
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-medium);
    }
    
    .newsletter-feature i {
        font-size: 1.2rem;
        color: rgba(255, 255, 255, 0.8);
    }
    
    /* CTA Section Enhancements */
    .cta-buttons {
        display: flex;
        gap: var(--spacing-md);
        justify-content: center;
    }
    
    /* Quick Search Results styling */
    .quick-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        background-color: var(--white-color);
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-lg);
        z-index: 1000;
        display: none;
        margin-top: 5px;
    }
    
    .quick-search-result {
        border-bottom: 1px solid var(--gray-200);
    }
    
    .quick-search-result:last-child {
        border-bottom: none;
    }
    
    .quick-search-result a {
        display: flex;
        padding: var(--spacing-md);
        color: var(--dark-color);
        transition: background-color var(--transition-fast);
        text-decoration: none;
    }
    
    .quick-search-result a:hover {
        background-color: var(--gray-100);
    }
    
    .result-image {
        width: 60px;
        height: 60px;
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        margin-right: var(--spacing-md);
        flex-shrink: 0;
    }
    
    .result-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .result-content {
        flex: 1;
        min-width: 0; /* Prevent overflow in flexbox */
    }
    
    .result-content h4 {
        margin-bottom: 4px;
        font-size: var(--font-size-md);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .result-price {
        font-weight: var(--font-weight-medium);
        color: var(--primary-color);
    }
    
    .result-price del {
        color: var(--gray-600);
        margin-right: 5px;
    }
    
    .search-loading, .no-results, .search-error {
        padding: var(--spacing-md);
        text-align: center;
        color: var(--gray-600);
    }
    
    .search-loading .spinner {
        width: 20px;
        height: 20px;
        border: 2px solid var(--gray-300);
        border-top-color: var(--primary-color);
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    /* Features grid enhancement */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-xl);
    }
    
    .feature-card {
        text-align: center;
        padding: var(--spacing-lg);
        transition: transform var(--transition-medium), box-shadow var(--transition-medium);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        background-color: rgba(255, 107, 53, 0.1);
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--spacing-md);
        color: var(--primary-color);
        font-size: 2rem;
    }
    
    .feature-title {
        margin-bottom: var(--spacing-sm);
        font-size: var(--font-size-lg);
    }
    
    .feature-text {
        color: var(--gray-600);
        margin-bottom: 0;
    }
    
    /* Fix for datepicker */
    .flatpickr-calendar.quick-booking-calendar {
        border-radius: var(--border-radius-md);
        box-shadow: var(--shadow-lg);
        border: none;
    }
    
    /* Responsive styles - will be overridden by responsive.css */
    @media (max-width: 992px) {
        .form-row {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .features-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .hero-section{
            height: 127vh;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }
        
        .newsletter-input-row {
            grid-template-columns: 1fr;
        }
        
        .newsletter-title {
            font-size: var(--font-size-2xl);
        }
        
        .newsletter-text {
            font-size: var(--font-size-md);
        }
        
        .newsletter-features {
            flex-direction: column;
            align-items: center;
            gap: var(--spacing-md);
        }
        
        .newsletter-icon {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
        
        .privacy-text {
            flex-direction: column;
            gap: var(--spacing-xs);
        }
        
        .cta-buttons {
            flex-direction: column;
        }
        
        .features-grid {
            grid-template-columns: 1fr;
        }
        
        .quick-search-results {
            position: fixed;
            top: auto;
            left: 10px;
            right: 10px;
            width: auto;
            max-height: 60vh;
            z-index: 1005;
        }
    }
    
    @media (max-width: 480px) {
        .hero-section {
            min-height: 700px;
        }
        
        .hero-title {
            font-size: 2.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.1rem;
        }
        
        .newsletter-section {
            padding: var(--spacing-xl) 0;
        }
        
        .newsletter-input,
        .newsletter-button {
            height: 50px;
        }
        
        .newsletter-button {
            font-size: var(--font-size-sm);
        }
    }
</style>

<!-- Initialize quick booking form -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize quick booking form with autocomplete
    const quickSearchForm = document.getElementById('quick-booking-form');
    const searchInput = document.getElementById('quick_booking_keyword');
    const resultsContainer = document.getElementById('quick-search-results');
    
    if (quickSearchForm && searchInput && resultsContainer) {
        let searchTimeout;
        
        // Get app base URL from data attribute
        const appBaseUrl = quickSearchForm.getAttribute('data-app-url') || '';
        
        // Search input event listener
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Hide results if query is empty
            if (query.length < 2) {
                resultsContainer.innerHTML = '';
                resultsContainer.style.display = 'none';
                return;
            }
            
            // Set timeout to prevent excessive requests
            searchTimeout = setTimeout(function() {
                // Show loading indicator
                resultsContainer.innerHTML = '<div class="search-loading"><div class="spinner"></div></div>';
                resultsContainer.style.display = 'block';
                
                // Get current language from URL or HTML tag
                const pathParts = window.location.pathname.split('/');
                const currentLang = pathParts[1] && pathParts[1].length === 2 ? pathParts[1] : 
                                  (document.documentElement.lang || 'en');
                
                // Make AJAX request with correct URL
                fetch(`${appBaseUrl}/${currentLang}/tours/ajax-search?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.length > 0) {
                        // Build results
                        let html = '';
                        
                        data.forEach(function(tour) {
                            // Ensure image URL has correct base path
                            let imageUrl = tour.image;
                            if (imageUrl && !imageUrl.startsWith('http')) {
                                imageUrl = `${appBaseUrl}${imageUrl}`;
                            }
                            
                            html += `
                                <div class="quick-search-result">
                                    <a href="${tour.url}">
                                        <div class="result-image">
                                            <img src="${imageUrl}" alt="${tour.name}">
                                        </div>
                                        <div class="result-content">
                                            <h4>${tour.name}</h4>
                                            <div class="result-price">
                                                ${tour.discount_price ? 
                                                    `<del>${tour.price}</del> ${tour.discount_price}` : 
                                                    tour.price}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                        });
                        
                        resultsContainer.innerHTML = html;
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div class="no-results">Arama kriterlerinize uygun tur bulunamadı.</div>';
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    resultsContainer.innerHTML = '<div class="search-error">Bir hata oluştu. Lütfen tekrar deneyin.</div>';
                });
            }, 300);
        });
        
        // Close results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.style.display = 'none';
            }
        });
        
        // Handle result click
        resultsContainer.addEventListener('click', function(e) {
            const resultItem = e.target.closest('.quick-search-result a');
            if (resultItem) {
                e.preventDefault();
                window.location.href = resultItem.getAttribute('href');
            }
        });
        
        // Form submission
        quickSearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const keywordValue = searchInput.value.trim();
            const dateValue = document.getElementById('quick_booking_date').value;
            const guestsValue = this.querySelector('select[name="guests"]').value;
            
            // Get current language
            const pathParts = window.location.pathname.split('/');
            const currentLang = pathParts[1] && pathParts[1].length === 2 ? pathParts[1] : 
                              (document.documentElement.lang || 'en');
            
            // Create URL with correct base path
            let url = `${appBaseUrl}/${currentLang}/tours?`;
            
            if (keywordValue) url += `keyword=${encodeURIComponent(keywordValue)}&`;
            if (dateValue) url += `date=${encodeURIComponent(dateValue)}&`;
            if (guestsValue) url += `guests=${encodeURIComponent(guestsValue)}`;
            
            window.location.href = url;
        });
    }
    
    // Improved datepicker initialization
    const dateInput = document.getElementById('quick_booking_date');
    if (dateInput) {
        if (typeof flatpickr !== 'undefined') {
            flatpickr(dateInput, {
                minDate: 'today',
                dateFormat: 'Y-m-d',
                disableMobile: true,
                altInput: true,
                altFormat: "F j, Y",
                onOpen: function() {
                    // Add custom class to the calendar for styling
                    setTimeout(function() {
                        const calendar = document.querySelector('.flatpickr-calendar');
                        if (calendar) calendar.classList.add('quick-booking-calendar');
                    }, 0);
                }
            });
        } else {
            // Fallback if flatpickr is not available
            dateInput.type = 'date';
            const today = new Date().toISOString().split('T')[0];
            dateInput.min = today;
        }
    }
    
    // Favorites button
    const favoriteButtons = document.querySelectorAll('.btn-favorite');
    favoriteButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            this.classList.toggle('active');
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (this.classList.contains('active')) {
                icon.textContent = 'favorite';
            } else {
                icon.textContent = 'favorite_border';
            }
        });
    });
    
    // Smooth scroll for indicators
    const scrollIndicator = document.querySelector('.scroll-indicator a');
    if (scrollIndicator) {
        scrollIndicator.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);
            
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    }
    
    // Newsletter form handling
    const newsletterForm = document.getElementById('newsletter-form');
    const submitButton = document.getElementById('newsletter-submit');
    const buttonText = submitButton ? submitButton.querySelector('.button-text') : null;
    const buttonLoading = submitButton ? submitButton.querySelector('.button-loading') : null;
    const alertsContainer = document.getElementById('newsletter-alerts');
    
    if (newsletterForm) {
        // Enhanced form submission with AJAX fallback
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const email = formData.get('email');
            const name = formData.get('name');
            
            // Basic validation
            if (!email || !validateEmail(email)) {
                showAlert('error', '<?php _e("please_enter_valid_email"); ?>');
                return;
            }
            
            // Set loading state
            setLoadingState(true);
            
            // Try AJAX first, fallback to normal submission
            fetch(this.action.replace('/subscribe', '/ajax-subscribe'), {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams(formData)
            })
            .then(response => response.json())
            .then(data => {
                setLoadingState(false);
                
                if (data.success) {
                    showAlert('success', data.message);
                    newsletterForm.reset();
                    
                    // Optional: Track subscription event
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'newsletter_subscription', {
                            'event_category': 'engagement',
                            'event_label': 'newsletter'
                        });
                    }
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                console.warn('AJAX submission failed, falling back to regular form submission:', error);
                setLoadingState(false);
                
                // Fallback to regular form submission
                this.submit();
            });
        });
        
        // Real-time email validation
        const emailInput = document.getElementById('newsletter_email');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const email = this.value.trim();
                if (email && !validateEmail(email)) {
                    this.setCustomValidity('<?php _e("please_enter_valid_email"); ?>');
                } else {
                    this.setCustomValidity('');
                }
            });
            
            emailInput.addEventListener('input', function() {
                this.setCustomValidity('');
            });
        }
    }
    
    function setLoadingState(loading) {
        if (!submitButton || !buttonText || !buttonLoading) return;
        
        if (loading) {
            submitButton.disabled = true;
            buttonText.style.display = 'none';
            buttonLoading.style.display = 'flex';
        } else {
            submitButton.disabled = false;
            buttonText.style.display = 'flex';
            buttonLoading.style.display = 'none';
        }
    }
    
    function showAlert(type, message) {
        if (!alertsContainer) return;
        
        // Remove existing alerts
        alertsContainer.innerHTML = '';
        
        // Create new alert
        const alert = document.createElement('div');
        alert.className = `alert alert-${type}`;
        alert.innerHTML = `
            <i class="material-icons">${type === 'success' ? 'check_circle' : 'error'}</i>
            ${message}
        `;
        
        alertsContainer.appendChild(alert);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.animation = 'slideUp 0.3s ease-out forwards';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
        
        // Scroll to alert
        alertsContainer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Auto-hide flash messages after 5 seconds
    if (alertsContainer) {
        setTimeout(function() {
            const existingAlerts = alertsContainer.querySelectorAll('.alert');
            existingAlerts.forEach(function(alert) {
                alert.style.animation = 'slideUp 0.3s ease-out forwards';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    }
});

// Add slideUp animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(style);
</script>