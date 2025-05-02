<?php
/**
 * About Page View
 */
?>

<!-- Page Header -->
<section class="page-header" style="background-image: url('<?php echo $imgUrl; ?>/about-header.jpg');">
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

<!-- About Content -->
<section class="section about-content">
    <div class="container">
        <div class="row">
            <div class="col-md-6" data-aos="fade-right">
                <div class="about-image">
                    <img src="<?php echo $imgUrl; ?>/about-cappadocia.jpg" alt="<?php _e('about_cappadocia'); ?>">
                    <div class="experience-badge">
                        <span class="years">15+</span>
                        <span class="text"><?php _e('years_experience'); ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <div class="about-text">
                    <h2 class="section-title"><?php _e('welcome_to'); ?> <?php echo $settings['site_title']; ?></h2>
                    
                    <?php if ($aboutPage && $aboutPage['content']): ?>
                        <div class="content-area">
                            <?php echo $aboutPage['content']; ?>
                        </div>
                    <?php else: ?>
                        <p><?php _e('about_welcome_text_1'); ?></p>
                        <p><?php _e('about_welcome_text_2'); ?></p>
                        
                        <div class="about-features">
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="material-icons">verified_user</i>
                                </div>
                                <div class="feature-content">
                                    <h4><?php _e('experienced_guides'); ?></h4>
                                    <p><?php _e('experienced_guides_text'); ?></p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="material-icons">thumb_up</i>
                                </div>
                                <div class="feature-content">
                                    <h4><?php _e('quality_service'); ?></h4>
                                    <p><?php _e('quality_service_text'); ?></p>
                                </div>
                            </div>
                            
                            <div class="feature-item">
                                <div class="feature-icon">
                                    <i class="material-icons">monetization_on</i>
                                </div>
                                <div class="feature-content">
                                    <h4><?php _e('best_price'); ?></h4>
                                    <p><?php _e('best_price_text'); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Our Mission -->
<section class="section mission-section" style="background-image: url('<?php echo $imgUrl; ?>/mission-bg.jpg');">
    <div class="container">
        <div class="row">
            <div class="col-md-6" data-aos="fade-right">
                <div class="glass-card">
                    <h2><?php _e('our_mission'); ?></h2>
                    <p><?php _e('mission_text_1'); ?></p>
                    <p><?php _e('mission_text_2'); ?></p>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <div class="glass-card">
                    <h2><?php _e('our_vision'); ?></h2>
                    <p><?php _e('vision_text_1'); ?></p>
                    <p><?php _e('vision_text_2'); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section -->
<section class="section team-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('meet_our_team'); ?></h2>
            <p class="section-subtitle"><?php _e('team_subtitle'); ?></p>
        </div>
        
        <div class="team-grid">
            <?php foreach ($teamMembers as $index => $member): ?>
                <div class="team-member" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="member-image">
                        <img src="<?php echo $imgUrl . '/team/' . $member['image']; ?>" alt="<?php echo $member['name']; ?>">
                        <div class="member-social">
                            <?php foreach ($member['social'] as $platform => $url): ?>
                                <a href="<?php echo $url; ?>" target="_blank" class="social-link <?php echo $platform; ?>">
                                    <i class="fab fa-<?php echo $platform; ?>"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="member-info">
                        <h3 class="member-name"><?php echo $member['name']; ?></h3>
                        <p class="member-position"><?php echo $member['position']; ?></p>
                        <p class="member-bio"><?php echo $member['bio']; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<?php if (!empty($testimonials)): ?>
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
<?php endif; ?>

<!-- Call to Action Section -->
<section class="cta-section" style="background-image: url('<?php echo $imgUrl; ?>/cta-bg.jpg');">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title"><?php _e('start_your_journey'); ?></h2>
            <p class="cta-text"><?php _e('cta_about_text'); ?></p>
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-lg">
                <i class="material-icons">flight_takeoff</i>
                <?php _e('explore_tours'); ?>
            </a>
        </div>
    </div>
</section>

<!-- Custom CSS for About page -->
<style>
    .about-content {
        padding-top: var(--spacing-xxl);
        padding-bottom: var(--spacing-xl);
    }
    
    .about-image {
        position: relative;
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }
    
    .about-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .experience-badge {
        position: absolute;
        bottom: 30px;
        right: 30px;
        width: 120px;
        height: 120px;
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
    }
    
    .experience-badge .years {
        font-size: 2.5rem;
        line-height: 1;
    }
    
    .experience-badge .text {
        font-size: 0.9rem;
        text-transform: uppercase;
    }
    
    .about-text {
        padding: var(--spacing-lg);
    }
    
    .about-features {
        margin-top: var(--spacing-xl);
    }
    
    .feature-item {
        display: flex;
        margin-bottom: var(--spacing-lg);
    }
    
    .feature-icon {
        width: 50px;
        height: 50px;
        min-width: 50px;
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: var(--spacing-md);
    }
    
    .feature-content h4 {
        margin-bottom: var(--spacing-xs);
    }
    
    .feature-content p {
        margin-bottom: 0;
        color: var(--gray-600);
    }
    
    .mission-section {
        position: relative;
        color: var(--white-color);
        background-position: center;
        background-size: cover;
        background-attachment: fixed;
        padding: var(--spacing-xxl) 0;
    }
    
    .mission-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
    }
    
    .mission-section .container {
        position: relative;
        z-index: 1;
    }
    
    .mission-section .glass-card {
        height: 100%;
    }
    
    .team-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-lg);
    }
    
    .team-member {
        background-color: var(--white-color);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        transition: transform var(--transition-medium), box-shadow var(--transition-medium);
    }
    
    .team-member:hover {
        transform: translateY(-10px);
        box-shadow: var(--shadow-lg);
    }
    
    .member-image {
        position: relative;
        height: 280px;
        overflow: hidden;
    }
    
    .member-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-medium);
    }
    
    .team-member:hover .member-image img {
        transform: scale(1.1);
    }
    
    .member-social {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        padding: var(--spacing-md);
        background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        display: flex;
        justify-content: center;
        gap: var(--spacing-sm);
        transform: translateY(100%);
        transition: transform var(--transition-medium);
    }
    
    .team-member:hover .member-social {
        transform: translateY(0);
    }
    
    .social-link {
        width: 36px;
        height: 36px;
        border-radius: var(--border-radius-circle);
        background-color: var(--white-color);
        color: var(--dark-color);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color var(--transition-fast), color var(--transition-fast);
    }
    
    .social-link:hover {
        background-color: var(--primary-color);
        color: var(--white-color);
    }
    
    .social-link.facebook:hover {
        background-color: #3b5998;
    }
    
    .social-link.twitter:hover {
        background-color: #1da1f2;
    }
    
    .social-link.instagram:hover {
        background-color: #e1306c;
    }
    
    .social-link.linkedin:hover {
        background-color: #0077b5;
    }
    
    .member-info {
        padding: var(--spacing-lg);
        text-align: center;
    }
    
    .member-name {
        margin-bottom: var(--spacing-xs);
    }
    
    .member-position {
        color: var(--primary-color);
        font-weight: var(--font-weight-medium);
        margin-bottom: var(--spacing-md);
    }
    
    .member-bio {
        color: var(--gray-600);
        margin-bottom: 0;
    }
    
    @media (max-width: 1200px) {
        .team-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 992px) {
        .team-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .mission-section .row {
            gap: var(--spacing-lg);
        }
    }
    
    @media (max-width: 768px) {
        .about-content .row {
            flex-direction: column-reverse;
        }
        
        .about-image {
            margin-top: var(--spacing-xl);
        }
        
        .team-grid {
            grid-template-columns: 1fr;
        }
    }
</style>