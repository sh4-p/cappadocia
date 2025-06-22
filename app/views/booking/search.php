<?php
/**
 * Booking Search View - Find Your Booking
 */

// Hero arka plan görseli
$searchHeroBg = isset($settings['search_hero_bg']) ? $settings['search_hero_bg'] : 'booking-hero-bg.jpg';
?>

<!-- Hero Section -->
<section class="hero-section search-hero" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $searchHeroBg; ?>');">
    <div class="overlay"></div>
    <div class="container">
        <div class="hero-content text-center">
            <div class="search-icon-wrapper" data-aos="zoom-in">
                <div class="search-icon">
                    <i class="material-icons">search</i>
                </div>
            </div>
            
            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200"><?php _e('find_your_booking'); ?></h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="300"><?php _e('track_booking_subtitle'); ?></p>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="section search-section">
    <div class="container">
        <div class="search-wrapper" data-aos="fade-up">
            <div class="search-content">
                <!-- Search Form Card -->
                <div class="search-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="material-icons">search</i>
                            <?php _e('enter_booking_details'); ?>
                        </h3>
                        <p class="card-subtitle"><?php _e('search_help_text'); ?></p>
                    </div>
                    
                    <div class="card-body">
                        <form action="<?php echo $appUrl . '/' . $currentLang; ?>/booking/search" method="post" class="search-form">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="email" class="form-label"><?php _e('email_address'); ?> <span class="required">*</span></label>
                                    <div class="input-with-icon">
                                        <i class="material-icons">email</i>
                                        <input type="email" id="email" name="email" class="form-control" required 
                                               placeholder="<?php _e('enter_email_placeholder'); ?>"
                                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                                    </div>
                                    <small class="form-text"><?php _e('email_used_for_booking'); ?></small>
                                </div>
                                
                                <div class="form-group col-md-6">
                                    <label for="reference" class="form-label"><?php _e('booking_reference'); ?> <span class="required">*</span></label>
                                    <div class="input-with-icon">
                                        <i class="material-icons">confirmation_number</i>
                                        <input type="text" id="reference" name="reference" class="form-control" required 
                                               placeholder="<?php _e('reference_placeholder'); ?>"
                                               value="<?php echo htmlspecialchars($_POST['reference'] ?? ''); ?>">
                                    </div>
                                    <small class="form-text"><?php _e('reference_help_text'); ?></small>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="material-icons">search</i>
                                    <?php _e('find_my_booking'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Alternative Search Methods -->
                <div class="alternative-methods">
                    <div class="divider">
                        <span><?php _e('or'); ?></span>
                    </div>
                    
                    <div class="method-cards">
                        <div class="method-card">
                            <div class="method-icon">
                                <i class="material-icons">email</i>
                            </div>
                            <div class="method-content">
                                <h4><?php _e('check_your_email'); ?></h4>
                                <p><?php _e('email_contains_tracking_link'); ?></p>
                            </div>
                        </div>
                        
                        <div class="method-card">
                            <div class="method-icon">
                                <i class="material-icons">bookmark</i>
                            </div>
                            <div class="method-content">
                                <h4><?php _e('bookmarked_page'); ?></h4>
                                <p><?php _e('bookmarked_page_text'); ?></p>
                            </div>
                        </div>
                        
                        <div class="method-card">
                            <div class="method-icon">
                                <i class="material-icons">support_agent</i>
                            </div>
                            <div class="method-content">
                                <h4><?php _e('contact_support'); ?></h4>
                                <p><?php _e('support_help_text'); ?></p>
                                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="method-link">
                                    <?php _e('get_help'); ?> <i class="material-icons">arrow_forward</i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Sidebar with Help -->
            <div class="search-sidebar">
                <!-- Tips Card -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">lightbulb</i>
                        <?php _e('search_tips'); ?>
                    </h4>
                    
                    <div class="tips-list">
                        <div class="tip-item">
                            <i class="material-icons">info</i>
                            <p><?php _e('tip_exact_email'); ?></p>
                        </div>
                        
                        <div class="tip-item">
                            <i class="material-icons">info</i>
                            <p><?php _e('tip_reference_format'); ?></p>
                        </div>
                        
                        <div class="tip-item">
                            <i class="material-icons">info</i>
                            <p><?php _e('tip_check_spam'); ?></p>
                        </div>
                        
                        <div class="tip-item">
                            <i class="material-icons">info</i>
                            <p><?php _e('tip_case_sensitive'); ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Card -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">help_outline</i>
                        <?php _e('cant_find_booking'); ?>
                    </h4>
                    
                    <p><?php _e('cant_find_help_text'); ?></p>
                    
                    <div class="contact-options">
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone'] ?? ''); ?>" class="contact-option">
                            <i class="material-icons">phone</i>
                            <div class="contact-details">
                                <div class="contact-label"><?php _e('call_us'); ?></div>
                                <div class="contact-value"><?php echo $settings['contact_phone'] ?? '+90 123 456 7890'; ?></div>
                            </div>
                        </a>
                        
                        <a href="mailto:<?php echo $settings['contact_email'] ?? ''; ?>" class="contact-option">
                            <i class="material-icons">email</i>
                            <div class="contact-details">
                                <div class="contact-label"><?php _e('email_us'); ?></div>
                                <div class="contact-value"><?php echo $settings['contact_email'] ?? 'info@example.com'; ?></div>
                            </div>
                        </a>
                    </div>
                    
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-outline btn-block">
                        <i class="material-icons">chat</i>
                        <?php _e('contact_support'); ?>
                    </a>
                </div>
                
                <!-- FAQ Card -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">question_answer</i>
                        <?php _e('frequently_asked'); ?>
                    </h4>
                    
                    <div class="faq-list">
                        <div class="faq-item">
                            <div class="faq-question">
                                <h5><?php _e('faq_how_long_track'); ?></h5>
                                <i class="material-icons">expand_more</i>
                            </div>
                            <div class="faq-answer">
                                <p><?php _e('faq_track_duration_answer'); ?></p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h5><?php _e('faq_no_email_received'); ?></h5>
                                <i class="material-icons">expand_more</i>
                            </div>
                            <div class="faq-answer">
                                <p><?php _e('faq_no_email_answer'); ?></p>
                            </div>
                        </div>
                        
                        <div class="faq-item">
                            <div class="faq-question">
                                <h5><?php _e('faq_update_booking'); ?></h5>
                                <i class="material-icons">expand_more</i>
                            </div>
                            <div class="faq-answer">
                                <p><?php _e('faq_update_answer'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Search Hero */
.search-hero {
    height: 50vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    position: relative;
    background-position: center;
    background-size: cover;
    background-attachment: fixed;
    color: var(--white-color);
}

.search-hero .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(rgba(67, 97, 238, 0.8), rgba(67, 97, 238, 0.9));
    z-index: 1;
}

.search-hero .container {
    position: relative;
    z-index: 2;
}

.search-icon-wrapper {
    margin-bottom: var(--spacing-lg);
}

.search-icon {
    width: 80px;
    height: 80px;
    border-radius: var(--border-radius-circle);
    background-color: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    backdrop-filter: blur(10px);
}

.search-icon i {
    font-size: 2.5rem;
    color: var(--white-color);
}

/* Search Section */
.search-section {
    padding: var(--spacing-xl) 0;
    background-color: var(--gray-50);
}

.search-wrapper {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--spacing-xl);
}

.search-card {
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    margin-bottom: var(--spacing-lg);
}

.card-header {
    padding: var(--spacing-lg);
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark-color));
    color: var(--white-color);
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0 0 0.5rem 0;
    font-size: var(--font-size-lg);
}

.card-title i {
    color: var(--white-color);
}

.card-subtitle {
    margin: 0;
    color: rgba(255, 255, 255, 0.9);
    font-size: var(--font-size-sm);
}

.card-body {
    padding: var(--spacing-xl);
}

/* Search Form */
.search-form {
    margin-bottom: 0;
}

.form-row {
    display: flex;
    margin: 0 -10px;
    flex-wrap: wrap;
}

.form-group {
    padding: 0 10px;
    margin-bottom: var(--spacing-lg);
}

.col-md-6 {
    flex: 0 0 50%;
    max-width: 50%;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: var(--font-weight-medium);
    color: var(--dark-color);
}

.required {
    color: var(--danger-color);
}

.input-with-icon {
    position: relative;
}

.input-with-icon i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-500);
}

.input-with-icon input {
    padding-left: 2.5rem;
}

.form-control {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid var(--gray-300);
    border-radius: var(--border-radius-md);
    font-size: 1rem;
    transition: all var(--transition-fast);
}

.form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    outline: none;
}

.form-text {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    margin-top: 0.25rem;
}

.form-actions {
    text-align: center;
    margin-top: var(--spacing-lg);
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.875rem 2rem;
    border-radius: var(--border-radius-md);
    font-size: 1rem;
    font-weight: var(--font-weight-medium);
    border: none;
    cursor: pointer;
    transition: all var(--transition-fast);
    text-decoration: none;
}

.btn-primary {
    background-color: var(--primary-color);
    color: var(--white-color);
}

.btn-primary:hover {
    background-color: var(--primary-dark-color);
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

.btn-lg {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
}

/* Alternative Methods */
.alternative-methods {
    margin-top: var(--spacing-xl);
}

.divider {
    text-align: center;
    margin: var(--spacing-xl) 0;
    position: relative;
}

.divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background-color: var(--gray-300);
}

.divider span {
    background-color: var(--gray-50);
    padding: 0 1rem;
    color: var(--gray-600);
    font-weight: var(--font-weight-medium);
}

.method-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--spacing-lg);
}

.method-card {
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-lg);
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-fast);
}

.method-card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-5px);
}

.method-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius-circle);
    background-color: rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-md);
}

.method-icon i {
    font-size: 2rem;
}

.method-content h4 {
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.method-content p {
    margin-bottom: var(--spacing-sm);
    color: var(--gray-600);
    font-size: var(--font-size-sm);
}

.method-link {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: var(--font-weight-medium);
    transition: color var(--transition-fast);
}

.method-link:hover {
    color: var(--primary-dark-color);
}

.method-link i {
    font-size: 1rem;
}

/* Sidebar */
.sidebar-card {
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    padding: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.sidebar-card-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: var(--spacing-md);
    font-size: var(--font-size-md);
    color: var(--dark-color);
}

.sidebar-card-title i {
    color: var(--primary-color);
}

/* Tips */
.tips-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.tip-item {
    display: flex;
    gap: var(--spacing-sm);
    align-items: flex-start;
}

.tip-item i {
    color: var(--primary-color);
    font-size: 1.25rem;
    margin-top: 0.125rem;
    flex-shrink: 0;
}

.tip-item p {
    margin: 0;
    color: var(--gray-700);
    font-size: var(--font-size-sm);
}

/* Contact Options */
.contact-options {
    margin: var(--spacing-md) 0;
}

.contact-option {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    padding: var(--spacing-sm);
    border-radius: var(--border-radius-md);
    text-decoration: none;
    color: inherit;
    transition: background-color var(--transition-fast);
    margin-bottom: var(--spacing-sm);
}

.contact-option:hover {
    background-color: var(--gray-100);
}

.contact-option i {
    width: 36px;
    height: 36px;
    border-radius: var(--border-radius-circle);
    background-color: rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.contact-details {
    flex: 1;
}

.contact-label {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    margin-bottom: 0.125rem;
}

.contact-value {
    font-weight: var(--font-weight-medium);
    color: var(--dark-color);
    font-size: var(--font-size-sm);
}

.btn-outline {
    background-color: transparent;
    border: 2px solid var(--primary-color);
    color: var(--primary-color);
}

.btn-outline:hover {
    background-color: var(--primary-color);
    color: var(--white-color);
}

.btn-block {
    display: flex;
    width: 100%;
    justify-content: center;
}

/* FAQ */
.faq-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.faq-item {
    border: 1px solid var(--gray-200);
    border-radius: var(--border-radius-md);
    overflow: hidden;
}

.faq-question {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-md);
    cursor: pointer;
    transition: background-color var(--transition-fast);
}

.faq-question:hover {
    background-color: var(--gray-100);
}

.faq-question h5 {
    margin: 0;
    font-size: var(--font-size-sm);
    color: var(--dark-color);
}

.faq-question i {
    color: var(--gray-500);
    transition: transform var(--transition-fast);
}

.faq-answer {
    padding: 0 var(--spacing-md) var(--spacing-md);
    display: none;
}

.faq-item.active .faq-answer {
    display: block;
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-answer p {
    margin: 0;
    color: var(--gray-700);
    font-size: var(--font-size-sm);
    line-height: 1.5;
}

/* Responsive */
@media (max-width: 992px) {
    .search-wrapper {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .method-cards {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
}

@media (max-width: 768px) {
    .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .method-cards {
        gap: var(--spacing-sm);
    }
    
    .method-card {
        padding: var(--spacing-md);
    }
    
    .method-icon {
        width: 50px;
        height: 50px;
    }
    
    .method-icon i {
        font-size: 1.5rem;
    }
}

@media (max-width: 576px) {
    .search-hero {
        min-height: 300px;
    }
    
    .card-body {
        padding: var(--spacing-lg);
    }
    
    .btn-lg {
        padding: 0.875rem 2rem;
        font-size: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ accordion functionality
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            const isActive = faqItem.classList.contains('active');
            
            // Close all FAQ items
            document.querySelectorAll('.faq-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // Open clicked item if it wasn't active
            if (!isActive) {
                faqItem.classList.add('active');
            }
        });
    });
    
    // Form validation
    const searchForm = document.querySelector('.search-form');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email').value.trim();
            const reference = document.getElementById('reference').value.trim();
            
            if (!email || !reference) {
                e.preventDefault();
                alert('<?php _e('please_fill_all_fields'); ?>');
                return false;
            }
            
            if (!isValidEmail(email)) {
                e.preventDefault();
                alert('<?php _e('please_enter_valid_email'); ?>');
                return false;
            }
        });
    }
    
    // Email validation function
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Auto-format reference input
    const referenceInput = document.getElementById('reference');
    if (referenceInput) {
        referenceInput.addEventListener('input', function() {
            let value = this.value.replace(/[^0-9]/g, '');
            if (value.length > 0 && !value.startsWith('#')) {
                this.value = '#' + value.padStart(6, '0');
            }
        });
    }
});
</script>