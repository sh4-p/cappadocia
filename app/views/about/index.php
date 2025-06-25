<?php
/**
 * About Page View - Modernized Design
 */
?>

<!-- Page Header -->
<section class="page-header about-header" style="background-image: url('<?php echo $imgUrl; ?>/about-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="page-title"><?php _e('about_us'); ?></h1>
            <div class="breadcrumbs">
                <div class="breadcrumbs-list">
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>" class="breadcrumbs-item"><?php _e('home'); ?></a>
                    <span class="breadcrumbs-item active"><?php _e('about_us'); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Hero Section -->
<section class="about-hero-section">
    <div class="container">
        <div class="about-hero-wrapper">
            <div class="about-hero-content" data-aos="fade-right">
                <div class="about-text-content">
                    <h2 class="about-main-title"><?php _e('welcome_to'); ?> <span class="highlight"><?php echo $settings['site_title']; ?></span></h2>
                    
                    <?php if ($aboutPage && $aboutPage['content']): ?>
                        <div class="about-content-area">
                            <?php echo $aboutPage['content']; ?>
                        </div>
                    <?php else: ?>
                        <div class="about-description">
                            <p class="lead-text"><?php _e('about_welcome_text_1'); ?></p>
                            <p><?php _e('about_welcome_text_2'); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <div class="about-stats">
                        <div class="stat-item">
                            <div class="stat-number">15+</div>
                            <div class="stat-label"><?php _e('years_experience'); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">1000+</div>
                            <div class="stat-label"><?php _e('happy_travelers'); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">50+</div>
                            <div class="stat-label"><?php _e('tour_packages'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="about-hero-image" data-aos="fade-left">
                <div class="about-image-wrapper">
                    <img src="<?php echo $imgUrl; ?>/about-cappadocia.jpg" alt="<?php _e('about_cappadocia'); ?>" class="main-about-image">
                    <div class="image-overlay">
                        <div class="play-button">
                            <i class="material-icons">play_arrow</i>
                        </div>
                    </div>
                    <div class="floating-badge">
                        <div class="badge-content">
                            <i class="material-icons">star</i>
                            <span>4.9/5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="about-features-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('why_choose_us'); ?></h2>
            <p class="section-subtitle"><?php _e('features_subtitle'); ?></p>
        </div>
        
        <div class="features-grid">
            <div class="feature-card" data-aos="fade-up" data-aos-delay="100">
                <div class="feature-icon">
                    <i class="material-icons">verified_user</i>
                </div>
                <div class="feature-content">
                    <h3><?php _e('experienced_guides'); ?></h3>
                    <p><?php _e('experienced_guides_text'); ?></p>
                </div>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-icon">
                    <i class="material-icons">thumb_up</i>
                </div>
                <div class="feature-content">
                    <h3><?php _e('quality_service'); ?></h3>
                    <p><?php _e('quality_service_text'); ?></p>
                </div>
            </div>
            
            <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                <div class="feature-icon">
                    <i class="material-icons">monetization_on</i>
                </div>
                <div class="feature-content">
                    <h3><?php _e('best_price'); ?></h3>
                    <p><?php _e('best_price_text'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision Section -->
<section class="mission-vision-section" style="background-image: url('<?php echo $imgUrl; ?>/mission-bg.jpg');">
    <div class="container">
        <div class="mission-vision-wrapper">
            <div class="mission-card glass-card" data-aos="fade-right">
                <div class="card-icon">
                    <i class="material-icons">rocket_launch</i>
                </div>
                <h2><?php _e('our_mission'); ?></h2>
                <p><?php _e('mission_text_1'); ?></p>
                <p><?php _e('mission_text_2'); ?></p>
            </div>
            
            <div class="vision-card glass-card" data-aos="fade-left">
                <div class="card-icon">
                    <i class="material-icons">visibility</i>
                </div>
                <h2><?php _e('our_vision'); ?></h2>
                <p><?php _e('vision_text_1'); ?></p>
                <p><?php _e('vision_text_2'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="about-values-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('our_values'); ?></h2>
            <p class="section-subtitle"><?php _e('values_subtitle'); ?></p>
        </div>
        
        <div class="values-grid">
            <div class="value-item" data-aos="zoom-in" data-aos-delay="100">
                <div class="value-icon">
                    <i class="material-icons">favorite</i>
                </div>
                <h3><?php _e('passion'); ?></h3>
                <p><?php _e('passion_text'); ?></p>
            </div>
            
            <div class="value-item" data-aos="zoom-in" data-aos-delay="200">
                <div class="value-icon">
                    <i class="material-icons">security</i>
                </div>
                <h3><?php _e('safety'); ?></h3>
                <p><?php _e('safety_text'); ?></p>
            </div>
            
            <div class="value-item" data-aos="zoom-in" data-aos-delay="300">
                <div class="value-icon">
                    <i class="material-icons">eco</i>
                </div>
                <h3><?php _e('sustainability'); ?></h3>
                <p><?php _e('sustainability_text'); ?></p>
            </div>
            
            <div class="value-item" data-aos="zoom-in" data-aos-delay="400">
                <div class="value-icon">
                    <i class="material-icons">groups</i>
                </div>
                <h3><?php _e('community'); ?></h3>
                <p><?php _e('community_text'); ?></p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<?php if (!empty($testimonials)): ?>
<section class="about-testimonials-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('what_travelers_say'); ?></h2>
            <p class="section-subtitle"><?php _e('testimonials_subtitle'); ?></p>
        </div>
        
        <div class="testimonials-carousel" data-aos="fade-up">
            <div class="testimonials-wrapper">
                <?php foreach ($testimonials as $index => $testimonial): ?>
                    <div class="testimonial-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="testimonial-content">
                            <div class="testimonial-text">
                                <p>"<?php echo $testimonial['content']; ?>"</p>
                            </div>
                            <div class="testimonial-author">
                                <div class="author-image">
                                    <?php if ($testimonial['image']): ?>
                                        <img src="<?php echo $uploadsUrl . '/testimonials/' . $testimonial['image']; ?>" alt="<?php echo $testimonial['name']; ?>">
                                    <?php else: ?>
                                        <img src="<?php echo $imgUrl; ?>/default-avatar.png" alt="<?php echo $testimonial['name']; ?>">
                                    <?php endif; ?>
                                </div>
                                <div class="author-info">
                                    <h4><?php echo $testimonial['name']; ?></h4>
                                    <?php if ($testimonial['position']): ?>
                                        <p><?php echo $testimonial['position']; ?></p>
                                    <?php endif; ?>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="material-icons"><?php echo $i <= $testimonial['rating'] ? 'star' : 'star_border'; ?></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="testimonials-controls">
                <button class="control-btn prev-btn">
                    <i class="material-icons">chevron_left</i>
                </button>
                <div class="testimonials-dots">
                    <?php foreach ($testimonials as $index => $testimonial): ?>
                        <button class="dot <?php echo $index === 0 ? 'active' : ''; ?>" data-slide="<?php echo $index; ?>"></button>
                    <?php endforeach; ?>
                </div>
                <button class="control-btn next-btn">
                    <i class="material-icons">chevron_right</i>
                </button>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action Section -->
<section class="about-cta-section">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <div class="cta-text">
                <h2><?php _e('ready_to_explore'); ?></h2>
                <p><?php _e('cta_about_text'); ?></p>
            </div>
            <div class="cta-actions">
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-lg">
                    <i class="material-icons">explore</i>
                    <?php _e('explore_tours'); ?>
                </a>
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-outline btn-lg">
                    <i class="material-icons">mail</i>
                    <?php _e('contact_us'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced About Page Styles -->
<style>
/* About Page Header */
.about-header {
    height: 500px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    position: relative;
    background-attachment: fixed;
}

.about-header::before {
    background: linear-gradient(
        135deg,
        rgba(38, 70, 83, 0.8) 0%,
        rgba(255, 107, 53, 0.3) 100%
    );
}

.page-header-content {
    position: relative;
    z-index: 2;
}

/* About Hero Section */
.about-hero-section {
    padding: var(--spacing-xxl) 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.about-hero-wrapper {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--spacing-xxl);
    align-items: center;
    min-height: 600px;
}

.about-main-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: var(--font-weight-bold);
    line-height: 1.2;
    margin-bottom: var(--spacing-lg);
    color: var(--dark-color);
}

.about-main-title .highlight {
    color: var(--primary-color);
    position: relative;
}

.about-main-title .highlight::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
    border-radius: 2px;
}

.lead-text {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-medium);
    color: var(--gray-700);
    margin-bottom: var(--spacing-lg);
    line-height: 1.6;
}

.about-description p {
    color: var(--gray-600);
    line-height: 1.8;
    margin-bottom: var(--spacing-md);
}

.about-stats {
    display: flex;
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xl);
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: var(--font-size-4xl);
    font-weight: var(--font-weight-bold);
    color: var(--primary-color);
    display: block;
    line-height: 1;
    margin-bottom: var(--spacing-xs);
}

.stat-label {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: var(--font-weight-medium);
}

.about-image-wrapper {
    position: relative;
    border-radius: var(--border-radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-xl);
    transition: transform var(--transition-medium);
}

.about-image-wrapper:hover {
    transform: translateY(-10px);
}

.main-about-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    display: block;
}

.image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity var(--transition-medium);
}

.about-image-wrapper:hover .image-overlay {
    opacity: 1;
}

.play-button {
    width: 80px;
    height: 80px;
    background: var(--primary-color);
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white-color);
    font-size: 2rem;
    cursor: pointer;
    transition: transform var(--transition-fast);
}

.play-button:hover {
    transform: scale(1.1);
}

.floating-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--white-color);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
}

.badge-content {
    display: flex;
    align-items: center;
    gap: var(--spacing-sm);
    color: var(--primary-color);
    font-weight: var(--font-weight-semibold);
}

.badge-content i {
    color: #FFD700;
}

/* Features Section */
.about-features-section {
    padding: var(--spacing-xxl) 0;
    background: var(--white-color);
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-xl);
    margin-top: var(--spacing-xxl);
}

.feature-card {
    background: var(--white-color);
    padding: var(--spacing-xl);
    border-radius: var(--border-radius-xl);
    box-shadow: var(--shadow-md);
    text-align: center;
    transition: transform var(--transition-medium), box-shadow var(--transition-medium);
    border: 1px solid var(--gray-200);
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: var(--shadow-xl);
}

.feature-card .feature-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: var(--white-color);
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-lg);
    font-size: 2rem;
}

.feature-card h3 {
    color: var(--dark-color);
    margin-bottom: var(--spacing-md);
    font-size: var(--font-size-xl);
}

.feature-card p {
    color: var(--gray-600);
    line-height: 1.7;
}

/* Mission & Vision Section */
.mission-vision-section {
    position: relative;
    padding: var(--spacing-xxl) 0;
    background-attachment: fixed;
    background-size: cover;
    background-position: center;
}

.mission-vision-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1;
}

.mission-vision-wrapper {
    position: relative;
    z-index: 2;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: var(--spacing-xl);
}

.mission-card,
.vision-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--border-radius-xl);
    padding: var(--spacing-xl);
    color: var(--white-color);
    transition: transform var(--transition-medium);
}

.mission-card:hover,
.vision-card:hover {
    transform: translateY(-10px);
}

.card-icon {
    width: 60px;
    height: 60px;
    background: var(--primary-color);
    color: var(--white-color);
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: var(--spacing-lg);
    font-size: 1.5rem;
}

.mission-card h2,
.vision-card h2 {
    color: var(--white-color);
    margin-bottom: var(--spacing-lg);
    font-size: var(--font-size-xxl);
}

.mission-card p,
.vision-card p {
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.8;
}

/* Values Section */
.about-values-section {
    padding: var(--spacing-xxl) 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.values-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xxl);
}

.value-item {
    text-align: center;
    padding: var(--spacing-lg);
    border-radius: var(--border-radius-lg);
    background: var(--white-color);
    box-shadow: var(--shadow-sm);
    transition: transform var(--transition-medium), box-shadow var(--transition-medium);
}

.value-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
}

.value-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
    color: var(--white-color);
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
    font-size: 1.5rem;
}

.value-item h3 {
    color: var(--dark-color);
    margin-bottom: var(--spacing-sm);
    font-size: var(--font-size-lg);
}

.value-item p {
    color: var(--gray-600);
    line-height: 1.6;
    font-size: var(--font-size-sm);
}

/* Testimonials Section */
.about-testimonials-section {
    padding: var(--spacing-xxl) 0;
    background: var(--white-color);
}

.testimonials-carousel {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.testimonials-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: var(--border-radius-xl);
}

.testimonial-item {
    display: none;
    padding: var(--spacing-xl);
    text-align: center;
}

.testimonial-item.active {
    display: block;
}

.testimonial-text {
    margin-bottom: var(--spacing-xl);
}

.testimonial-text p {
    font-size: var(--font-size-lg);
    font-style: italic;
    color: var(--gray-700);
    line-height: 1.8;
    position: relative;
}

.testimonial-text p::before,
.testimonial-text p::after {
    content: '"';
    font-size: 3rem;
    color: var(--primary-color);
    opacity: 0.3;
    position: absolute;
}

.testimonial-text p::before {
    top: -20px;
    left: -30px;
}

.testimonial-text p::after {
    bottom: -40px;
    right: -30px;
}

.testimonial-author {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-md);
}

.author-image {
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius-circle);
    overflow: hidden;
    border: 3px solid var(--primary-color);
}

.author-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.author-info h4 {
    color: var(--dark-color);
    margin-bottom: var(--spacing-xs);
    font-size: var(--font-size-md);
}

.author-info p {
    color: var(--gray-600);
    font-size: var(--font-size-sm);
    margin-bottom: var(--spacing-xs);
}

.rating {
    display: flex;
    gap: 2px;
    justify-content: center;
}

.rating i {
    color: #FFD700;
    font-size: 1rem;
}

.testimonials-controls {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-lg);
    margin-top: var(--spacing-xl);
}

.control-btn {
    width: 44px;
    height: 44px;
    background: var(--primary-color);
    color: var(--white-color);
    border: none;
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color var(--transition-fast);
}

.control-btn:hover {
    background: var(--primary-dark);
}

.testimonials-dots {
    display: flex;
    gap: var(--spacing-sm);
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: var(--border-radius-circle);
    background: var(--gray-300);
    border: none;
    cursor: pointer;
    transition: background-color var(--transition-fast);
}

.dot.active,
.dot:hover {
    background: var(--primary-color);
}

/* CTA Section */
.about-cta-section {
    padding: var(--spacing-xxl) 0;
    background: linear-gradient(135deg, var(--dark-color) 0%, var(--primary-color) 100%);
    color: var(--white-color);
    text-align: center;
}

.cta-text h2 {
    color: var(--white-color);
    margin-bottom: var(--spacing-md);
    font-size: var(--font-size-4xl);
}

.cta-text p {
    color: rgba(255, 255, 255, 0.9);
    font-size: var(--font-size-lg);
    margin-bottom: var(--spacing-xl);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.cta-actions {
    display: flex;
    gap: var(--spacing-lg);
    justify-content: center;
    flex-wrap: wrap;
}

.cta-actions .btn {
    min-width: 200px;
}

/* Responsive Design */
@media (max-width: 992px) {
    .about-hero-wrapper {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
        text-align: center;
    }
    
    .about-hero-content {
        order: 2;
    }
    
    .about-hero-image {
        order: 1;
    }
    
    .about-stats {
        justify-content: center;
    }
    
    .mission-vision-wrapper {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .values-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }
    
    .testimonial-author {
        flex-direction: column;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .about-header {
        height: 400px;
    }
    
    .about-hero-section {
        padding: var(--spacing-lg) 0;
    }
    
    .about-hero-wrapper {
        min-height: auto;
    }
    
    .main-about-image {
        height: 300px;
    }
    
    .about-stats {
        flex-direction: column;
        gap: var(--spacing-md);
    }
    
    .features-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .feature-card {
        padding: var(--spacing-lg);
    }
    
    .values-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
    
    .cta-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .cta-actions .btn {
        width: 100%;
        max-width: 300px;
    }
    
    .testimonial-text p::before,
    .testimonial-text p::after {
        display: none;
    }
}

@media (max-width: 576px) {
    .about-main-title {
        font-size: 2rem;
    }
    
    .lead-text {
        font-size: var(--font-size-lg);
    }
    
    .stat-number {
        font-size: var(--font-size-3xl);
    }
    
    .mission-card,
    .vision-card {
        padding: var(--spacing-lg);
    }
    
    .feature-card .feature-icon,
    .value-icon {
        width: 60px;
        height: 60px;
        font-size: 1.25rem;
    }
    
    .testimonial-item {
        padding: var(--spacing-lg);
    }
    
    .testimonials-controls {
        gap: var(--spacing-md);
    }
}

/* Animation Enhancements */
@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.feature-card,
.value-item {
    animation: fadeInScale 0.6s ease-out;
}

/* Parallax Effect */
@media (min-width: 1024px) {
    .about-header,
    .mission-vision-section {
        background-attachment: fixed;
    }
}
</style>
<script>
/**
 * About Page JavaScript Enhancements
 * Modern interactions and animations for the about page
 */

// Initialize about page functionality when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Initialize all about page components
    aboutPageInit();
});

/**
 * Initialize all about page functionality
 */
function aboutPageInit() {
    // Initialize testimonials carousel
    initTestimonialsCarousel();
    
    // Initialize counter animations
    initCounterAnimations();
    
    // Initialize play button functionality
    initPlayButton();
    
    // Initialize parallax effects
    initParallaxEffects();
    
    // Initialize intersection observer for animations
    initScrollAnimations();
    
    // Initialize floating badge animation
    initFloatingBadge();
}

/**
 * Initialize testimonials carousel
 */
function initTestimonialsCarousel() {
    const carousel = document.querySelector('.testimonials-carousel');
    
    if (!carousel) return;
    
    const wrapper = carousel.querySelector('.testimonials-wrapper');
    const items = carousel.querySelectorAll('.testimonial-item');
    const dots = carousel.querySelectorAll('.dot');
    const prevBtn = carousel.querySelector('.prev-btn');
    const nextBtn = carousel.querySelector('.next-btn');
    
    if (items.length <= 1) return;
    
    let currentSlide = 0;
    const totalSlides = items.length;
    let autoSlideInterval;
    
    // Show specific slide
    function showSlide(index, direction = 'next') {
        // Hide all slides
        items.forEach((item, i) => {
            item.classList.remove('active');
            if (i === currentSlide) {
                item.style.opacity = '0';
                item.style.transform = direction === 'next' ? 'translateX(-50px)' : 'translateX(50px)';
            }
        });
        
        // Update dots
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
        
        // Show new slide with animation
        setTimeout(() => {
            items[currentSlide].style.display = 'none';
            items[index].style.display = 'block';
            items[index].style.opacity = '0';
            items[index].style.transform = direction === 'next' ? 'translateX(50px)' : 'translateX(-50px)';
            
            // Force reflow
            items[index].offsetHeight;
            
            items[index].style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            items[index].style.opacity = '1';
            items[index].style.transform = 'translateX(0)';
            items[index].classList.add('active');
            
            currentSlide = index;
        }, 300);
    }
    
    // Auto slide function
    function startAutoSlide() {
        autoSlideInterval = setInterval(() => {
            if (document.hasFocus() && !document.hidden) {
                const nextIndex = (currentSlide + 1) % totalSlides;
                showSlide(nextIndex, 'next');
            }
        }, 5000);
    }
    
    // Previous slide
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            clearInterval(autoSlideInterval);
            const prevIndex = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(prevIndex, 'prev');
            startAutoSlide();
        });
    }
    
    // Next slide
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            clearInterval(autoSlideInterval);
            const nextIndex = (currentSlide + 1) % totalSlides;
            showSlide(nextIndex, 'next');
            startAutoSlide();
        });
    }
    
    // Dots navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            if (index !== currentSlide) {
                clearInterval(autoSlideInterval);
                const direction = index > currentSlide ? 'next' : 'prev';
                showSlide(index, direction);
                startAutoSlide();
            }
        });
    });
    
    // Touch/swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    
    wrapper.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    wrapper.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            clearInterval(autoSlideInterval);
            
            if (diff > 0) {
                // Swipe left - next slide
                const nextIndex = (currentSlide + 1) % totalSlides;
                showSlide(nextIndex, 'next');
            } else {
                // Swipe right - previous slide
                const prevIndex = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(prevIndex, 'prev');
            }
            
            startAutoSlide();
        }
    }
    
    // Pause on hover
    carousel.addEventListener('mouseenter', () => {
        clearInterval(autoSlideInterval);
    });
    
    carousel.addEventListener('mouseleave', () => {
        startAutoSlide();
    });
    
    // Start auto slide
    startAutoSlide();
    
    // Pause when page is not visible
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            clearInterval(autoSlideInterval);
        } else {
            startAutoSlide();
        }
    });
}

/**
 * Initialize counter animations
 */
function initCounterAnimations() {
    const counters = document.querySelectorAll('.stat-number');
    
    if (counters.length === 0) return;
    
    // Function to animate counter
    function animateCounter(element) {
        const target = parseInt(element.textContent.replace(/[^0-9]/g, ''));
        const duration = 2000;
        const start = 0;
        const increment = target / (duration / 16);
        const suffix = element.textContent.replace(/[0-9]/g, '');
        
        let current = start;
        const startTime = performance.now();
        
        function updateCounter(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function (ease-out)
            const easeOut = 1 - Math.pow(1 - progress, 3);
            current = start + (target - start) * easeOut;
            
            element.textContent = Math.floor(current) + suffix;
            
            if (progress < 1) {
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target + suffix;
            }
        }
        
        requestAnimationFrame(updateCounter);
    }
    
    // Intersection Observer for counter animation
    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                entry.target.dataset.animated = 'true';
                setTimeout(() => {
                    animateCounter(entry.target);
                }, 200);
            }
        });
    }, {
        threshold: 0.5,
        rootMargin: '0px 0px -50px 0px'
    });
    
    counters.forEach(counter => {
        counterObserver.observe(counter);
    });
}

/**
 * Initialize play button functionality
 */
function initPlayButton() {
    const playButton = document.querySelector('.play-button');
    
    if (playButton) {
        playButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            // Open video modal
            openVideoModal();
        });
    }
}

/**
 * Create and open video modal
 */
function openVideoModal() {
    // Video URL - YouTube embed with autoplay and start time
    const videoId = 'f3lv65BFmaA';
    const startTime = 2; // seconds
    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&start=${startTime}&rel=0&modestbranding=1`;
    
    // Create modal HTML
    const modalHTML = `
        <div class="video-modal" id="videoModal">
            <div class="video-modal-backdrop"></div>
            <div class="video-modal-content">
                <div class="video-container">
                    <iframe 
                        src="${embedUrl}" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen>
                    </iframe>
                    <button class="video-close-btn" aria-label="Close video">
                        <i class="material-icons">close</i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHTML);
    
    // Get modal elements
    const modal = document.getElementById('videoModal');
    const closeBtn = modal.querySelector('.video-close-btn');
    const backdrop = modal.querySelector('.video-modal-backdrop');
    
    // Show modal with animation
    requestAnimationFrame(() => {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    });
    
    // Close modal function
    function closeVideoModal() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
        
        setTimeout(() => {
            if (document.body.contains(modal)) {
                document.body.removeChild(modal);
            }
        }, 300);
    }
    
    // Event listeners
    closeBtn.addEventListener('click', closeVideoModal);
    backdrop.addEventListener('click', closeVideoModal);
    
    // Close on escape key
    const handleEscape = (e) => {
        if (e.key === 'Escape') {
            closeVideoModal();
            document.removeEventListener('keydown', handleEscape);
        }
    };
    
    document.addEventListener('keydown', handleEscape);
}

/**
 * Initialize parallax effects for desktop
 */
function initParallaxEffects() {
    if (window.innerWidth < 1024) return; // Only on desktop
    
    const parallaxElements = document.querySelectorAll('.about-header, .mission-vision-section');
    
    if (parallaxElements.length === 0) return;
    
    function updateParallax() {
        const scrolled = window.pageYOffset;
        
        parallaxElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            const speed = 0.5;
            
            if (rect.bottom >= 0 && rect.top <= window.innerHeight) {
                const yPos = -(scrolled * speed);
                element.style.backgroundPosition = `center ${yPos}px`;
            }
        });
    }
    
    // Throttled scroll event
    let ticking = false;
    
    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
            setTimeout(() => {
                ticking = false;
            }, 16);
        }
    }
    
    window.addEventListener('scroll', requestTick);
}

/**
 * Initialize scroll animations
 */
function initScrollAnimations() {
    // Enhanced intersection observer for scroll animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                
                // Add animation class based on data attribute
                const animationType = element.dataset.animate || 'fadeInUp';
                element.classList.add('animate-' + animationType);
                
                // Add stagger delay for grid items
                if (element.classList.contains('feature-card') || 
                    element.classList.contains('value-item')) {
                    const siblings = Array.from(element.parentNode.children);
                    const index = siblings.indexOf(element);
                    element.style.animationDelay = `${index * 100}ms`;
                }
                
                // Unobserve after animation
                scrollObserver.unobserve(element);
            }
        });
    }, observerOptions);
    
    // Observe elements with data-animate attribute
    const animatedElements = document.querySelectorAll('[data-animate], .feature-card, .value-item, .mission-card, .vision-card');
    animatedElements.forEach(element => {
        scrollObserver.observe(element);
    });
}

/**
 * Initialize floating badge animation
 */
function initFloatingBadge() {
    const floatingBadge = document.querySelector('.floating-badge');
    
    if (floatingBadge) {
        // Floating animation
        function floatAnimation() {
            floatingBadge.style.animation = 'float 3s ease-in-out infinite';
        }
        
        // Add CSS keyframe for floating
        const style = document.createElement('style');
        style.textContent = `
            @keyframes float {
                0%, 100% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
            }
            
            .floating-badge {
                animation: float 3s ease-in-out infinite;
            }
            
            .floating-badge:hover {
                animation-play-state: paused;
                transform: scale(1.05);
                transition: transform 0.3s ease;
            }
        `;
        document.head.appendChild(style);
    }
}

/**
 * Show notification (utility function)
 */
function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="material-icons">${type === 'info' ? 'info' : 'check_circle'}</i>
            <span>${message}</span>
        </div>
    `;
    
    // Add notification styles
    const style = document.createElement('style');
    style.textContent = `
        .notification {
            position: fixed;
            top: 100px;
            right: 20px;
            background: var(--white-color);
            color: var(--dark-color);
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            z-index: 10000;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
            max-width: 300px;
            border-left: 4px solid var(--primary-color);
        }
        
        .notification.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .notification-content i {
            color: var(--primary-color);
        }
    `;
    
    if (!document.querySelector('#notification-styles')) {
        style.id = 'notification-styles';
        document.head.appendChild(style);
    }
    
    // Add to document
    document.body.appendChild(notification);
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Auto remove
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

/**
 * Add CSS animations for scroll effects
 */
function addScrollAnimationStyles() {
    const style = document.createElement('style');
    style.textContent = `
        /* Video Modal Styles */
        .video-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        
        .video-modal.show {
            opacity: 1;
            visibility: visible;
        }
        
        .video-modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }
        
        .video-modal-content {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            z-index: 2;
        }
        
        .video-container {
            position: relative;
            width: 100%;
            max-width: 900px;
            aspect-ratio: 16/9;
            background: #000;
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }
        
        .video-modal.show .video-container {
            transform: scale(1);
        }
        
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .video-close-btn {
            position: absolute;
            top: -15px;
            right: -15px;
            width: 44px;
            height: 44px;
            background: var(--primary-color);
            color: var(--white-color);
            border: none;
            border-radius: var(--border-radius-circle);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: var(--shadow-lg);
            transition: all 0.3s ease;
            z-index: 3;
        }
        
        .video-close-btn:hover {
            background: var(--primary-dark);
            transform: scale(1.1);
        }
        
        .video-close-btn i {
            font-size: 1.5rem;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .video-modal-content {
                padding: 1rem;
            }
            
            .video-container {
                max-width: 100%;
                border-radius: var(--border-radius-md);
            }
            
            .video-close-btn {
                top: -10px;
                right: -10px;
                width: 36px;
                height: 36px;
            }
            
            .video-close-btn i {
                font-size: 1.25rem;
            }
        }
        
        @media (max-width: 480px) {
            .video-modal-content {
                padding: 0.5rem;
            }
            
            .video-close-btn {
                top: 10px;
                right: 10px;
                background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
            }
        }
        
        /* Scroll Animation Classes */
        .animate-fadeInUp {
            animation: aboutFadeInUp 0.8s ease-out forwards;
        }
        
        .animate-fadeInLeft {
            animation: aboutFadeInLeft 0.8s ease-out forwards;
        }
        
        .animate-fadeInRight {
            animation: aboutFadeInRight 0.8s ease-out forwards;
        }
        
        .animate-zoomIn {
            animation: aboutZoomIn 0.6s ease-out forwards;
        }
        
        .animate-slideInUp {
            animation: aboutSlideInUp 0.8s ease-out forwards;
        }
        
        /* Keyframes */
        @keyframes aboutFadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes aboutFadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes aboutFadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes aboutZoomIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes aboutSlideInUp {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Hover Effects */
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
            transition: transform 0.3s ease;
        }
        
        .value-item:hover .value-icon {
            transform: scale(1.1) rotate(-5deg);
            transition: transform 0.3s ease;
        }
        
        .about-image-wrapper:hover .main-about-image {
            transform: scale(1.05);
            transition: transform 0.5s ease;
        }
        
        /* Play Button Enhanced Styles */
        .play-button {
            position: relative;
            overflow: hidden;
        }
        
        .play-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.3);
            border-radius: var(--border-radius-circle);
            transform: translate(-50%, -50%);
            transition: width 0.3s ease, height 0.3s ease;
        }
        
        .play-button:hover::before {
            width: 120%;
            height: 120%;
        }
        
        .play-button i {
            position: relative;
            z-index: 2;
        }
        
        /* Responsive Animations */
        @media (max-width: 768px) {
            .animate-fadeInLeft,
            .animate-fadeInRight {
                animation: aboutFadeInUp 0.8s ease-out forwards;
            }
        }
        
        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            .animate-fadeInUp,
            .animate-fadeInLeft,
            .animate-fadeInRight,
            .animate-zoomIn,
            .animate-slideInUp,
            .video-container {
                animation: none;
                opacity: 1;
                transform: none;
                transition: none;
            }
            
            .video-modal.show .video-container {
                transform: none;
            }
        }
    `;
    
    if (!document.querySelector('#about-animation-styles')) {
        style.id = 'about-animation-styles';
        document.head.appendChild(style);
    }
}

// Add animation styles when script loads
addScrollAnimationStyles();
</script>