
<?php
/**
 * Homepage view
 */
?>

<!-- Hero Section -->
<section class="hero-section" style="background-image: url('<?php echo $imgUrl; ?>/hero-bg.jpg');">
    <div class="container">
        <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
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
        </div>
    </div>
</section>

<!-- Featured Tours Section -->
<section class="section" id="featured-tours">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('featured_tours'); ?></h2>
            <p class="section-subtitle"><?php _e('featured_tours_subtitle'); ?></p>
        </div>
        
        <div class="tours-grid">
            <?php foreach ($featuredTours as $tour): ?>
                <div class="tour-card" data-aos="fade-up" data-aos-delay="<?php echo $loop * 100; ?>">
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
        
        <div class="text-center" data-aos="fade-up">
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary">
                <i class="material-icons">arrow_forward</i>
                <?php _e('view_all_tours'); ?>
            </a>
        </div>
    </div>
</section>

<!-- About Section with Glassmorphism Card -->
<section class="section about-section" style="background-image: url('<?php echo $imgUrl; ?>/about-bg.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="glass-card" data-aos="fade-right">
                    <h2><?php _e('about_cappadocia'); ?></h2>
                    <p><?php _e('about_cappadocia_text_1'); ?></p>
                    <p><?php _e('about_cappadocia_text_2'); ?></p>
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/about" class="btn btn-glass">
                        <?php _e('learn_more'); ?>
                        <i class="material-icons">arrow_forward</i>
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Placeholder for image or video -->
                <div class="about-image" data-aos="fade-left">
                    <img src="<?php echo $imgUrl; ?>/about-cappadocia.jpg" alt="<?php _e('about_cappadocia'); ?>">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Destinations Section -->
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
                            $tourCount = $this->tourModel->countTours(['category_id' => $destination['id'], 'is_active' => 1]);
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

<!-- Why Choose Us Section -->
<section class="section why-choose-us">
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

<!-- Testimonials Section -->
<section class="section testimonials-section" style="background-image: url('<?php echo $imgUrl; ?>/testimonials-bg.jpg');">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title" style="color: var(--white-color);"><?php _e('testimonials'); ?></h2>
            <p class="section-subtitle" style="color: var(--white-color);"><?php _e('testimonials_subtitle'); ?></p>
        </div>
        
        <div class="testimonial-slider" data-aos="fade-up">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="testimonial-slide">
                    <div class="testimonial-image">
                        <?php if ($testimonial['image']): ?>
                            <img src="<?php echo $uploadsUrl . '/testimonials/' . $testimonial['image']; ?>" alt="<?php echo $testimonial['name']; ?>">
                        <?php else: ?>
                            <img src="<?php echo $imgUrl; ?>/default-avatar.png" alt="<?php echo $testimonial['name']; ?>">
                        <?php endif; ?>
                    </div>
                    <p class="testimonial-quote"><?php echo $testimonial['content']; ?></p>
                    <h4 class="testimonial-name"><?php echo $testimonial['name']; ?></h4>
                    <?php if ($testimonial['position']): ?>
                        <p class="testimonial-position"><?php echo $testimonial['position']; ?></p>
                    <?php endif; ?>
                    <div class="testimonial-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="material-icons"><?php echo $i <= $testimonial['rating'] ? 'star' : 'star_border'; ?></i>
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

<!-- Gallery Section -->
<section class="section" id="gallery">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('gallery'); ?></h2>
            <p class="section-subtitle"><?php _e('gallery_subtitle'); ?></p>
        </div>
        
        <div class="gallery-grid">
            <?php foreach ($galleryItems as $index => $item): ?>
                <div class="gallery-item" data-aos="fade-up" data-aos-delay="<?php echo ($index % 4) * 100; ?>">
                    <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" alt="<?php echo $item['title'] ?: __('gallery_image'); ?>" class="gallery-image">
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

<!-- Call to Action Section -->
<section class="cta-section" style="background-image: url('<?php echo $imgUrl; ?>/cta-bg.jpg');">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title"><?php _e('ready_for_adventure'); ?></h2>
            <p class="cta-text"><?php _e('ready_for_adventure_text'); ?></p>
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-lg">
                <i class="material-icons">flight_takeoff</i>
                <?php _e('book_now'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Custom CSS for homepage styles -->
<style>
    /* Features Grid */
    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-lg);
        margin-top: var(--spacing-xl);
    }
    
    .feature-card {
        text-align: center;
        padding: var(--spacing-xl);
        transition: transform var(--transition-medium), box-shadow var(--transition-medium);
    }
    
    .feature-card:hover {
        transform: translateY(-10px);
    }
    
    .feature-icon {
        width: 80px;
        height: 80px;
        border-radius: var(--border-radius-circle);
        background-color: var(--primary-color);
        color: var(--white-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--spacing-lg);
        font-size: 2.5rem;
    }
    
    .feature-title {
        margin-bottom: var(--spacing-sm);
    }
    
    .feature-text {
        color: #777;
        margin-bottom: 0;
    }
    
    /* About Section */
    .about-section {
        position: relative;
        color: var(--white-color);
        background-position: center;
        background-size: cover;
    }
    
    .about-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3));
    }
    
    .about-section .container {
        position: relative;
        z-index: 1;
    }
    
    .about-image {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .about-image img {
        width: 100%;
        max-width: 500px;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-lg);
    }
    
    /* Testimonials Section */
    .testimonials-section {
        position: relative;
        color: var(--white-color);
        background-position: center;
        background-size: cover;
    }
    
    .testimonials-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
    }
    
    .testimonials-section .container {
        position: relative;
        z-index: 1;
    }
    
    /* Responsive styles */
    @media (max-width: 992px) {
        .features-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .about-image {
            margin-top: var(--spacing-xl);
        }
    }
    
    @media (max-width: 576px) {
        .features-grid {
            grid-template-columns: 1fr;
        }
    }
</style>