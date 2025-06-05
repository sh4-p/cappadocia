<?php
/**
 * Booking Form View - Tamamen Yenilenmiş Tasarım
 */

// Hero arka plan görseli
$bookingHeroBg = isset($settings['booking_hero_bg']) ? $settings['booking_hero_bg'] : 'booking-hero-bg.jpg';
?>

<!-- Hero Section with Tour Info -->
<section class="hero-section booking-hero" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $bookingHeroBg; ?>');">
    <div class="overlay"></div>
    <div class="container">
        <div class="hero-content">
            <!-- Breadcrumbs -->
            <div class="breadcrumbs light">
                <div class="breadcrumbs-list">
                    <div class="breadcrumbs-item">
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a>
                    </div>
                    <div class="breadcrumbs-item">
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours"><?php _e('tours'); ?></a>
                    </div>
                    <div class="breadcrumbs-item">
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours/<?php echo $tour['slug']; ?>"><?php echo $tour['name']; ?></a>
                    </div>
                    <div class="breadcrumbs-item active">
                        <?php _e('booking'); ?>
                    </div>
                </div>
            </div>
            
            <h1 class="hero-title"><?php echo sprintf(__('book_tour_title'), $tour['name']); ?></h1>
            <p class="hero-subtitle"><?php _e('book_tour_subtitle'); ?></p>
            
            <!-- Booking Progress -->
            <div class="booking-progress">
                <div class="progress-step active">
                    <div class="step-number">1</div>
                    <div class="step-label"><?php _e('details'); ?></div>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step">
                    <div class="step-number">2</div>
                    <div class="step-label"><?php _e('payment'); ?></div>
                </div>
                <div class="progress-line"></div>
                <div class="progress-step">
                    <div class="step-number">3</div>
                    <div class="step-label"><?php _e('confirmation'); ?></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Booking Section -->
<section class="section booking-section">
    <div class="container">
        <div class="booking-wrapper">
            <div class="booking-content" data-aos="fade-up">
                <!-- Tour Card -->
                <div class="booking-tour-card">
                    <div class="tour-image">
                        <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                        <?php if ($tour['category_name']): ?>
                            <div class="tour-category"><?php echo $tour['category_name']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="tour-content">
                        <h3 class="tour-name"><?php echo $tour['name']; ?></h3>
                        
                        <div class="tour-meta">
                            <?php if ($tour['duration']): ?>
                                <div class="meta-item">
                                    <i class="material-icons">schedule</i>
                                    <span><?php echo $tour['duration']; ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="meta-item">
                                <i class="material-icons">group</i>
                                <span><?php _e('max'); ?> 15</span>
                            </div>
                            
                            <div class="meta-item">
                                <i class="material-icons">star</i>
                                <span>4.8/5 (<?php echo isset($tour['reviews_count']) ? $tour['reviews_count'] : rand(10, 50); ?>)</span>
                            </div>
                        </div>
                        
                        <div class="tour-price-tag">
                            <?php if ($tour['discount_price']): ?>
                                <span class="price-old"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                                <span class="price-current"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
                            <?php else: ?>
                                <span class="price-current"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                            <?php endif; ?>
                            <span class="price-label"><?php _e('per_person'); ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Form -->
                <form id="booking_form" class="booking-form" action="<?php echo $appUrl . '/' . $currentLang; ?>/booking/confirm" method="post">
                    <input type="hidden" name="tour_id" value="<?php echo $tour['id']; ?>">
                    <input type="hidden" id="booking_base_price" value="<?php echo $tour['price']; ?>">
                    <input type="hidden" id="booking_discount_price" value="<?php echo $tour['discount_price']; ?>">
                    <input type="hidden" id="currency_symbol" value="<?php echo $settings['currency_symbol']; ?>">
                    <input type="hidden" id="booking_total_price" name="total_price" value="0">
                    
                    <!-- Form Tabs -->
                    <div class="form-tabs">
                        <div class="tab-nav">
                            <button type="button" class="tab-btn active" data-tab="booking-details">
                                <i class="material-icons">event</i>
                                <?php _e('booking_details'); ?>
                            </button>
                            <button type="button" class="tab-btn" data-tab="personal-info">
                                <i class="material-icons">person</i>
                                <?php _e('personal_information'); ?>
                            </button>
                            <button type="button" class="tab-btn" data-tab="payment-info">
                                <i class="material-icons">payment</i>
                                <?php _e('payment_details'); ?>
                            </button>
                        </div>
                        
                        <div class="tab-content">
                            <!-- Booking Details Tab -->
                            <div class="tab-pane active" id="booking-details-tab">
                                <div class="form-section">
                                    <h4 class="form-section-title">
                                        <i class="material-icons">calendar_today</i>
                                        <?php _e('select_date_and_participants'); ?>
                                    </h4>
                                    
                                    <div class="form-card">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="booking_date" class="form-label"><?php _e('booking_date'); ?> <span class="required">*</span></label>
                                                <div class="input-with-icon">
                                                    <i class="material-icons">event</i>
                                                    <input type="text" id="booking_date" name="booking_date" class="form-control datepicker" required
                                                           placeholder="<?php _e('select_date'); ?>"
                                                           data-min-date="today">
                                                    <!-- data-disabled-dates parametresi kaldırıldı -->
                                                </div>
                                                <div class="form-text"><?php _e('booking_date_help'); ?></div>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label class="form-label"><?php _e('participants'); ?> <span class="required">*</span></label>
                                                <div class="guests-wrapper">
                                                    <div class="guest-type">
                                                        <div class="guest-label">
                                                            <div class="guest-title"><?php _e('adults'); ?></div>
                                                            <div class="guest-subtitle"><?php _e('age_12_plus'); ?></div>
                                                        </div>
                                                        <div class="guest-counter">
                                                            <button type="button" class="counter-btn decrease-btn" data-target="booking_adults">
                                                                <i class="material-icons">remove</i>
                                                            </button>
                                                            <input type="number" id="booking_adults" name="adults" value="2" min="1" max="10" required>
                                                            <button type="button" class="counter-btn increase-btn" data-target="booking_adults">
                                                                <i class="material-icons">add</i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="guest-type">
                                                        <div class="guest-label">
                                                            <div class="guest-title"><?php _e('children'); ?></div>
                                                            <div class="guest-subtitle"><?php _e('age_2_11'); ?></div>
                                                        </div>
                                                        <div class="guest-counter">
                                                            <button type="button" class="counter-btn decrease-btn" data-target="booking_children">
                                                                <i class="material-icons">remove</i>
                                                            </button>
                                                            <input type="number" id="booking_children" name="children" value="0" min="0" max="10">
                                                            <button type="button" class="counter-btn increase-btn" data-target="booking_children">
                                                                <i class="material-icons">add</i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-navigation">
                                        <div></div>
                                        <button type="button" class="btn btn-primary next-tab" data-next="personal-info">
                                            <?php _e('next_step'); ?>
                                            <i class="material-icons">arrow_forward</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Personal Information Tab -->
                            <div class="tab-pane" id="personal-info-tab">
                                <div class="form-section">
                                    <h4 class="form-section-title">
                                        <i class="material-icons">person</i>
                                        <?php _e('personal_information'); ?>
                                    </h4>
                                    
                                    <div class="form-card">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="first_name" class="form-label"><?php _e('first_name'); ?> <span class="required">*</span></label>
                                                <div class="input-with-icon">
                                                    <i class="material-icons">person_outline</i>
                                                    <input type="text" id="first_name" name="first_name" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="last_name" class="form-label"><?php _e('last_name'); ?> <span class="required">*</span></label>
                                                <div class="input-with-icon">
                                                    <i class="material-icons">person_outline</i>
                                                    <input type="text" id="last_name" name="last_name" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="booking_email" class="form-label"><?php _e('email'); ?> <span class="required">*</span></label>
                                                <div class="input-with-icon">
                                                    <i class="material-icons">email</i>
                                                    <input type="email" id="booking_email" name="email" class="form-control" required>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="booking_phone" class="form-label"><?php _e('phone'); ?> <span class="required">*</span></label>
                                                <div class="input-with-icon">
                                                    <i class="material-icons">phone</i>
                                                    <input type="tel" id="booking_phone" name="phone" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="special_requests" class="form-label"><?php _e('special_requests'); ?></label>
                                            <div class="input-with-icon textarea">
                                                <i class="material-icons">comment</i>
                                                <textarea id="special_requests" name="special_requests" class="form-control" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-navigation">
                                        <button type="button" class="btn btn-outline prev-tab" data-prev="booking-details">
                                            <i class="material-icons">arrow_back</i>
                                            <?php _e('previous_step'); ?>
                                        </button>
                                        <button type="button" class="btn btn-primary next-tab" data-next="payment-info">
                                            <?php _e('next_step'); ?>
                                            <i class="material-icons">arrow_forward</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Payment Details Tab -->
                            <div class="tab-pane" id="payment-info-tab">
                                <div class="form-section">
                                    <h4 class="form-section-title">
                                        <i class="material-icons">payment</i>
                                        <?php _e('payment_method'); ?>
                                    </h4>
                                    
                                    <div class="form-card">
                                        <div class="payment-methods">
                                            <div class="payment-method active" data-method="card">
                                                <div class="payment-method-radio">
                                                    <input type="radio" id="payment_card" name="payment_method" value="card" checked>
                                                    <div class="radio-indicator"></div>
                                                </div>
                                                <div class="payment-method-icon">
                                                    <i class="material-icons">credit_card</i>
                                                </div>
                                                <div class="payment-method-details">
                                                    <div class="payment-method-name"><?php _e('credit_card'); ?></div>
                                                    <div class="payment-method-description"><?php _e('credit_card_description'); ?></div>
                                                </div>
                                            </div>
                                            
                                            <div class="payment-method" data-method="paypal">
                                                <div class="payment-method-radio">
                                                    <input type="radio" id="payment_paypal" name="payment_method" value="paypal">
                                                    <div class="radio-indicator"></div>
                                                </div>
                                                <div class="payment-method-icon">
                                                    <i class="material-icons">account_balance_wallet</i>
                                                </div>
                                                <div class="payment-method-details">
                                                    <div class="payment-method-name"><?php _e('paypal'); ?></div>
                                                    <div class="payment-method-description"><?php _e('paypal_description'); ?></div>
                                                </div>
                                            </div>
                                            
                                            <div class="payment-method" data-method="bank">
                                                <div class="payment-method-radio">
                                                    <input type="radio" id="payment_bank" name="payment_method" value="bank">
                                                    <div class="radio-indicator"></div>
                                                </div>
                                                <div class="payment-method-icon">
                                                    <i class="material-icons">account_balance</i>
                                                </div>
                                                <div class="payment-method-details">
                                                    <div class="payment-method-name"><?php _e('bank_transfer'); ?></div>
                                                    <div class="payment-method-description"><?php _e('bank_transfer_description'); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="payment-contents">
                                            <div class="payment-content active" data-method="card">
                                                <div class="form-row">
                                                    <div class="form-group col-12">
                                                        <label for="card_name" class="form-label"><?php _e('card_name'); ?> <span class="required">*</span></label>
                                                        <div class="input-with-icon">
                                                            <i class="material-icons">person</i>
                                                            <input type="text" id="card_name" name="card_name" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-row">
                                                    <div class="form-group col-12">
                                                        <label for="card_number" class="form-label"><?php _e('card_number'); ?> <span class="required">*</span></label>
                                                        <div class="input-with-icon">
                                                            <i class="material-icons">credit_card</i>
                                                            <input type="text" id="card_number" name="card_number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-row">
                                                    <div class="form-group col-md-6">
                                                        <label for="card_expiry" class="form-label"><?php _e('card_expiry'); ?> <span class="required">*</span></label>
                                                        <div class="input-with-icon">
                                                            <i class="material-icons">date_range</i>
                                                            <input type="text" id="card_expiry" name="card_expiry" class="form-control" placeholder="MM/YY" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="form-group col-md-6">
                                                        <label for="card_cvv" class="form-label"><?php _e('card_cvv'); ?> <span class="required">*</span></label>
                                                        <div class="input-with-icon">
                                                            <i class="material-icons">lock</i>
                                                            <input type="text" id="card_cvv" name="card_cvv" class="form-control" placeholder="CVV" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="payment-disclaimer">
                                                    <i class="material-icons">lock</i>
                                                    <span><?php _e('payment_card_disclaimer'); ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="payment-content" data-method="paypal">
                                                <div class="payment-info">
                                                    <div class="info-box">
                                                        <i class="material-icons">info</i>
                                                        <p><?php _e('paypal_info'); ?></p>
                                                    </div>
                                                    <div class="paypal-logo">
                                                        <img src="<?php echo $imgUrl; ?>/paypal-logo.png" alt="PayPal" />
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="payment-content" data-method="bank">
                                                <div class="payment-info">
                                                    <div class="info-box warning">
                                                        <i class="material-icons">info</i>
                                                        <p><?php _e('bank_info'); ?></p>
                                                    </div>
                                                    <div class="bank-details">
                                                        <div class="bank-detail">
                                                            <div class="bank-detail-label"><?php _e('bank_name'); ?></div>
                                                            <div class="bank-detail-value">Example Bank</div>
                                                        </div>
                                                        <div class="bank-detail">
                                                            <div class="bank-detail-label"><?php _e('account_holder'); ?></div>
                                                            <div class="bank-detail-value">Cappadocia Travel Agency</div>
                                                        </div>
                                                        <div class="bank-detail">
                                                            <div class="bank-detail-label"><?php _e('iban'); ?></div>
                                                            <div class="bank-detail-value">TR00 0000 0000 0000 0000 0000 00</div>
                                                        </div>
                                                        <div class="bank-detail">
                                                            <div class="bank-detail-label"><?php _e('swift_code'); ?></div>
                                                            <div class="bank-detail-value">EXAMPLEXXX</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="booking-summary">
                                            <h4 class="summary-title"><?php _e('booking_summary'); ?></h4>
                                            
                                            <div class="summary-content">
                                                <div class="summary-item">
                                                    <div class="summary-label"><?php _e('tour'); ?></div>
                                                    <div class="summary-value"><?php echo $tour['name']; ?></div>
                                                </div>
                                                
                                                <div class="summary-item">
                                                    <div class="summary-label"><?php _e('date'); ?></div>
                                                    <div class="summary-value" id="summary_date"><?php _e('select_date'); ?></div>
                                                </div>
                                                
                                                <div class="summary-item">
                                                    <div class="summary-label"><?php _e('participants'); ?></div>
                                                    <div class="summary-value">
                                                        <span id="summary_adults">2</span> <?php _e('adults'); ?>,
                                                        <span id="summary_children">0</span> <?php _e('children'); ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="summary-item total">
                                                    <div class="summary-label"><?php _e('total_price'); ?></div>
                                                    <div class="summary-value price" id="total_price_display"></div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-check terms-check">
                                            <input type="checkbox" id="terms_check" name="terms_check" required>
                                            <label for="terms_check"><?php echo sprintf(__('terms_agreement'), '<a href="' . $appUrl . '/' . $currentLang . '/page/terms" target="_blank">' . __('terms_and_conditions') . '</a>'); ?></label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-navigation">
                                        <button type="button" class="btn btn-outline prev-tab" data-prev="personal-info">
                                            <i class="material-icons">arrow_back</i>
                                            <?php _e('previous_step'); ?>
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="material-icons">check_circle</i>
                                            <?php _e('confirm_booking'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="booking-sidebar" data-aos="fade-up" data-aos-delay="200">
                <!-- Why Book With Us -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">verified_user</i>
                        <?php _e('why_book_with_us'); ?>
                    </h4>
                    
                    <div class="features-list">
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="material-icons">verified</i>
                            </div>
                            <div class="feature-content">
                                <h5><?php _e('guaranteed_tours'); ?></h5>
                                <p><?php _e('guaranteed_tours_text'); ?></p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="material-icons">payments</i>
                            </div>
                            <div class="feature-content">
                                <h5><?php _e('secure_payments'); ?></h5>
                                <p><?php _e('secure_payments_text'); ?></p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="material-icons">price_check</i>
                            </div>
                            <div class="feature-content">
                                <h5><?php _e('best_price'); ?></h5>
                                <p><?php _e('best_price_text'); ?></p>
                            </div>
                        </div>
                        
                        <div class="feature-item">
                            <div class="feature-icon">
                                <i class="material-icons">support_agent</i>
                            </div>
                            <div class="feature-content">
                                <h5><?php _e('customer_support'); ?></h5>
                                <p><?php _e('customer_support_text'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Need Help Box -->
                <div class="sidebar-card glass-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">help_outline</i>
                        <?php _e('need_help'); ?>
                    </h4>
                    
                    <p><?php _e('booking_help_text'); ?></p>
                    
                    <div class="help-contact">
                        <div class="contact-item">
                            <i class="material-icons">phone</i>
                            <span><?php echo $settings['contact_phone']; ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="material-icons">email</i>
                            <span><?php echo $settings['contact_email']; ?></span>
                        </div>
                    </div>
                    
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-glass btn-block">
                        <i class="material-icons">message</i>
                        <?php _e('contact_us'); ?>
                    </a>
                </div>
                
                <!-- FAQ Accordion -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">question_answer</i>
                        <?php _e('frequently_asked_questions'); ?>
                    </h4>
                    
                    <div class="accordion">
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <h5><?php _e('faq_booking_process_title'); ?></h5>
                                <i class="material-icons">expand_more</i>
                            </div>
                            <div class="accordion-content">
                                <p><?php _e('faq_booking_process_content'); ?></p>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <h5><?php _e('faq_payment_title'); ?></h5>
                                <i class="material-icons">expand_more</i>
                            </div>
                            <div class="accordion-content">
                                <p><?php _e('faq_payment_content'); ?></p>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <h5><?php _e('faq_cancellation_title'); ?></h5>
                                <i class="material-icons">expand_more</i>
                            </div>
                            <div class="accordion-content">
                                <p><?php _e('faq_cancellation_content'); ?></p>
                            </div>
                        </div>
                        
                        <div class="accordion-item">
                            <div class="accordion-header">
                                <h5><?php _e('faq_group_title'); ?></h5>
                                <i class="material-icons">expand_more</i>
                            </div>
                            <div class="accordion-content">
                                <p><?php _e('faq_group_content'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Scripts -->
<?php
// Add flatpickr for date picking
$additionalScripts = '
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
';
?>

<style>
    /* Hero Section */
    .booking-hero {
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
    
    .booking-hero .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(30, 30, 30, 0.6), rgba(30, 30, 30, 0.7));
        z-index: 1;
    }
    
    .booking-hero .container {
        position: relative;
        z-index: 2;
    }
    
    .hero-content {
        max-width: 800px;
    }
    
    .hero-title {
        font-size: 2.5rem;
        margin-bottom: var(--spacing-sm);
        color: var(--white-color);
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
        margin-bottom: var(--spacing-xl);
        color: rgba(255, 255, 255, 0.9);
    }
    
    /* Breadcrumbs */
    .breadcrumbs {
        margin-bottom: var(--spacing-lg);
    }
    
    .breadcrumbs.light {
        color: var(--white-color);
    }
    
    .breadcrumbs-list {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
    }
    
    .breadcrumbs-item {
        position: relative;
        padding-right: 1.5rem;
        margin-right: 0.5rem;
    }
    
    .breadcrumbs-item:after {
        content: '›';
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        font-size: 1.2rem;
    }
    
    .breadcrumbs-item:last-child {
        padding-right: 0;
        margin-right: 0;
    }
    
    .breadcrumbs-item:last-child:after {
        display: none;
    }
    
    .breadcrumbs-item a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color var(--transition-fast);
    }
    
    .breadcrumbs-item a:hover {
        color: var(--white-color);
    }
    
    .breadcrumbs-item.active {
        color: var(--white-color);
        font-weight: var(--font-weight-medium);
    }
    
    /* Booking Progress Steps */
    .booking-progress {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: var(--spacing-xl);
    }
    
    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        z-index: 1;
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        font-weight: var(--font-weight-bold);
        transition: background-color var(--transition-fast);
    }
    
    .progress-step.active .step-number {
        background-color: var(--primary-color);
    }
    
    .progress-step.completed .step-number {
        background-color: var(--success-color);
    }
    
    .step-label {
        font-size: var(--font-size-sm);
        color: rgba(255, 255, 255, 0.8);
    }
    
    .progress-line {
        flex: 1;
        height: 2px;
        background-color: rgba(255, 255, 255, 0.2);
        margin: 0 1rem;
        position: relative;
        top: -20px;
    }
    
    /* Main Content Layout */
    .booking-section {
        position: relative;
        padding: var(--spacing-xl) 0;
    }
    
    .booking-wrapper {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--spacing-xl);
    }
    
    /* Tour Card */
    .booking-tour-card {
        background-color: var(--white-color);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        display: flex;
        margin-bottom: var(--spacing-lg);
    }
    
    .booking-tour-card .tour-image {
        width: 40%;
        position: relative;
        height: 250px; /* Sabit yükseklik eklendi */
    }
    
    .booking-tour-card .tour-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .tour-category {
        position: absolute;
        bottom: 1rem;
        left: 1rem;
        background-color: rgba(0, 0, 0, 0.6);
        color: var(--white-color);
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-md);
        font-size: var(--font-size-sm);
    }
    
    .booking-tour-card .tour-content {
        width: 60%;
        padding: var(--spacing-lg);
    }
    
    .tour-name {
        font-size: var(--font-size-xl);
        margin-bottom: var(--spacing-md);
    }
    
    .tour-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: var(--spacing-md);
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray-600);
        font-size: var(--font-size-sm);
    }
    
    .meta-item i {
        color: var(--primary-color);
    }
    
    .tour-price-tag {
        margin-top: auto;
    }
    
    .price-current {
        font-size: var(--font-size-xl);
        font-weight: var(--font-weight-bold);
        color: var(--primary-color);
    }
    
    .price-old {
        text-decoration: line-through;
        color: var(--gray-600);
        margin-right: 0.5rem;
    }
    
    .price-label {
        font-size: var(--font-size-sm);
        color: var(--gray-600);
        margin-left: 0.5rem;
    }
    
    /* Booking Form */
    .booking-form {
        background-color: var(--white-color);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
        margin-bottom: var(--spacing-lg);
    }
    
    .form-tabs {
        display: flex;
        flex-direction: column;
    }
    
    .tab-nav {
        display: flex;
        border-bottom: 1px solid var(--gray-200);
        background-color: var(--gray-100);
    }
    
    .tab-btn {
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        background: none;
        color: var(--gray-600);
        font-weight: var(--font-weight-medium);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        transition: color var(--transition-fast);
        flex: 1;
        justify-content: center;
    }
    
    .tab-btn:after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 3px;
        background-color: var(--primary-color);
        transform: scaleX(0);
        transition: transform var(--transition-fast);
    }
    
    .tab-btn.active {
        color: var(--primary-color);
        background-color: var(--white-color);
    }
    
    .tab-btn.active:after {
        transform: scaleX(1);
    }
    
    .tab-content {
        padding: var(--spacing-lg);
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    /* Form Sections */
    .form-section {
        margin-bottom: var(--spacing-lg);
    }
    
    .form-section-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: var(--spacing-md);
        color: var(--dark-color);
        font-size: var(--font-size-lg);
    }
    
    .form-section-title i {
        color: var(--primary-color);
    }
    
    .form-card {
        background-color: var(--gray-50);
        border-radius: var(--border-radius-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
    }
    
    .form-row {
        display: flex;
        margin: 0 -10px;
        flex-wrap: wrap;
    }
    
    .form-group {
        padding: 0 10px;
        margin-bottom: var(--spacing-md);
    }
    
    .col-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: var(--font-weight-medium);
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
    
    .input-with-icon.textarea i {
        top: 1.5rem;
        transform: none;
    }
    
    .input-with-icon input,
    .input-with-icon textarea {
        padding-left: 2.5rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        font-size: 1rem;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
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
    
    .required {
        color: var(--danger-color);
    }
    
    /* Guest Counter */
    .guests-wrapper {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .guest-type {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: var(--spacing-md);
        background-color: var(--white-color);
        border-radius: var(--border-radius-md);
        border: 1px solid var(--gray-200);
    }
    
    .guest-title {
        font-weight: var(--font-weight-medium);
    }
    
    .guest-subtitle {
        font-size: var(--font-size-sm);
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
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--white-color);
        color: var(--dark-color);
        border: 1px solid var(--gray-300);
        transition: all var(--transition-fast);
        cursor: pointer;
    }
    
    .counter-btn:hover:not(:disabled) {
        background-color: var(--primary-color);
        color: var(--white-color);
        border-color: var(--primary-color);
    }
    
    .counter-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    .guest-counter input {
        width: 50px;
        height: 36px;
        text-align: center;
        font-weight: var(--font-weight-medium);
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
    }
    
    /* Form Navigation */
    .form-navigation {
        display: flex;
        justify-content: space-between;
        margin-top: var(--spacing-lg);
    }
    
    /* Payment Methods */
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: var(--spacing-lg);
    }
    
    .payment-method {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: var(--spacing-md);
        border: 2px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        cursor: pointer;
        transition: all var(--transition-fast);
        background-color: var(--white-color);
    }
    
    .payment-method.active {
        border-color: var(--primary-color);
        background-color: rgba(67, 97, 238, 0.05);
    }
    
    .payment-method-radio {
        position: relative;
        width: 24px;
        height: 24px;
    }
    
    .payment-method-radio input {
        opacity: 0;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        cursor: pointer;
    }
    
    .radio-indicator {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: 2px solid var(--gray-400);
        border-radius: var(--border-radius-circle);
    }
    
    .payment-method.active .radio-indicator {
        border-color: var(--primary-color);
    }
    
    .radio-indicator::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 12px;
        height: 12px;
        border-radius: var(--border-radius-circle);
        background-color: var(--primary-color);
        opacity: 0;
        transition: opacity var(--transition-fast);
    }
    
    .payment-method.active .radio-indicator::after {
        opacity: 1;
    }
    
    .payment-method-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .payment-method-details {
        flex: 1;
    }
    
    .payment-method-name {
        font-weight: var(--font-weight-medium);
    }
    
    .payment-method-description {
        font-size: var(--font-size-sm);
        color: var(--gray-600);
    }
    
    .payment-contents {
        margin-bottom: var(--spacing-lg);
    }
    
    .payment-content {
        display: none;
        padding: var(--spacing-md);
        background-color: var(--gray-50);
        border-radius: var(--border-radius-md);
    }
    
    .payment-content.active {
        display: block;
    }
    
    .info-box {
        display: flex;
        gap: 1rem;
        padding: var(--spacing-md);
        background-color: rgba(67, 97, 238, 0.1);
        border-radius: var(--border-radius-md);
        margin-bottom: var(--spacing-md);
    }
    
    .info-box.warning {
        background-color: rgba(255, 193, 7, 0.1);
    }
    
    .info-box i {
        color: var(--primary-color);
        font-size: 1.5rem;
    }
    
    .info-box.warning i {
        color: var(--warning-color);
    }
    
    .info-box p {
        margin: 0;
        color: var(--gray-700);
    }
    
    .paypal-logo {
        display: flex;
        justify-content: center;
        padding: var(--spacing-md);
    }
    
    .paypal-logo img {
        max-height: 40px;
    }
    
    .bank-details {
        background-color: var(--white-color);
        border-radius: var(--border-radius-md);
        padding: var(--spacing-md);
        margin-top: var(--spacing-md);
    }
    
    .bank-detail {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .bank-detail:last-child {
        border-bottom: none;
    }
    
    .bank-detail-label {
        font-weight: var(--font-weight-medium);
    }
    
    .payment-disclaimer {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: var(--spacing-md);
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
        border-radius: var(--border-radius-md);
        margin-top: var(--spacing-md);
    }
    
    /* Booking Summary */
    .booking-summary {
        margin-top: var(--spacing-xl);
        border-top: 1px solid var(--gray-300);
        padding-top: var(--spacing-lg);
    }
    
    .summary-title {
        margin-bottom: var(--spacing-md);
    }
    
    .summary-content {
        background-color: var(--white-color);
        border-radius: var(--border-radius-md);
        padding: var(--spacing-md);
        border: 1px solid var(--gray-200);
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .summary-item:last-child {
        border-bottom: none;
    }
    
    .summary-label {
        font-weight: var(--font-weight-medium);
    }
    
    .summary-item.total {
        font-weight: var(--font-weight-bold);
        font-size: var(--font-size-lg);
        margin-top: var(--spacing-sm);
        padding-top: var(--spacing-sm);
        border-top: 2px solid var(--gray-300);
    }
    
    .summary-item.total .price {
        color: var(--primary-color);
    }
    
    .terms-check {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-top: var(--spacing-lg);
    }
    
    .terms-check input {
        margin-top: 0.25rem;
    }
    
    .terms-check label {
        font-size: var(--font-size-sm);
        line-height: 1.5;
    }
    
    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
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
        color: var(--white-color) !important; /* Metnin beyaz kalmasını sağla */
        box-shadow: var(--shadow-md);
    }
    
    .btn-primary:hover i {
        color: var(--white-color) !important; /* İkonların beyaz kalmasını sağla */
    }

    .btn-outline {
        background-color: transparent;
        border: 1px solid var(--gray-300);
        color: var(--gray-700);
    }
    
    .btn-outline:hover {
        background-color: var(--gray-100);
        color: var(--dark-color) !important; /* Metni koyu renkte tut */
        border-color: var(--gray-400);
    }

    .btn-outline:hover i {
        color: var(--primary-color) !important; /* İkonları birincil renkte tut */
    }
    /* Next/Previous butonları için özel stil */
    .form-navigation .btn {
        position: relative;
        transition: all var(--transition-fast);
        overflow: hidden;
    }

    .form-navigation .btn-primary:hover {
        color: var(--white-color) !important;
        background-color: var(--primary-dark-color);
    }

    .form-navigation .btn-outline:hover {
        color: var(--gray-800) !important; 
        background-color: var(--gray-100);
    }

    /* İkonlar için özel stil */
    .form-navigation .btn i {
        transition: all var(--transition-fast);
    }

    .form-navigation .btn-primary:hover i {
        color: var(--white-color) !important;
    }

    .form-navigation .btn-outline:hover i {
        color: var(--primary-color) !important;
    }
    /* Next Tab butonları için özel düzeltmeler */
    .next-tab.btn-primary {
        background-color: var(--primary-color);
        color: var(--white-color) !important;
    }

    .next-tab.btn-primary:hover {
        background-color: var(--primary-dark-color);
        color: var(--white-color) !important;
    }

    .next-tab.btn-primary i {
        color: var(--white-color) !important;
    }

    .next-tab.btn-primary:hover i {
        color: var(--white-color) !important;
    }

    /* Yüksek öncelik için daha spesifik seçiciler */
    .form-navigation .next-tab.btn-primary {
        color: var(--white-color) !important;
    }

    .form-navigation .next-tab.btn-primary:hover {
        color: var(--white-color) !important;
    }

    .form-navigation .next-tab.btn-primary i {
        color: var(--white-color) !important;
    }

    .form-navigation .next-tab.btn-primary:hover i {
        color: var(--white-color) !important;
    }

    /* Confirm button için özel stil */
    .btn-primary[type="submit"] {
        color: var(--white-color) !important;
    }

    .btn-primary[type="submit"]:hover {
        color: var(--white-color) !important;
    }

    .btn-primary[type="submit"] i {
        color: var(--white-color) !important;
    }

    .btn-primary[type="submit"]:hover i {
        color: var(--white-color) !important;
    }
    .btn-glass {
        background-color: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: var(--white-color);
    }
    
    .btn-glass:hover {
        background-color: rgba(255, 255, 255, 0.25);
        border-color: rgba(255, 255, 255, 0.3);
    }
    
    .btn-lg {
        padding: 1rem 2rem;
        font-size: 1.1rem;
    }
    
    .btn-block {
        display: flex;
        width: 100%;
    }
    
    /* Sidebar */
    .sidebar-card {
        background-color: var(--white-color);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        padding: var(--spacing-lg);
        margin-bottom: var(--spacing-xl);
    }
    
    .sidebar-card.glass-card {
        color: var(--white-color);
        background-color: rgba(38, 70, 83, 0.8);
        backdrop-filter: blur(10px);
    }
    
    .sidebar-card-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: var(--spacing-lg);
        font-size: var(--font-size-lg);
    }
    
    .sidebar-card-title i {
        color: var(--primary-color);
    }
    
    .glass-card .sidebar-card-title i {
        color: var(--white-color);
    }
    
    .features-list {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-md);
    }
    
    .feature-item {
        display: flex;
        gap: var(--spacing-md);
    }
    
    .feature-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .feature-content h5 {
        margin-bottom: 0.25rem;
    }
    
    .feature-content p {
        font-size: var(--font-size-sm);
        color: var(--gray-600);
        margin: 0;
    }
    
    .help-contact {
        margin: var(--spacing-md) 0;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    
    .contact-item i {
        width: 32px;
        height: 32px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Accordion */
    .accordion-item {
        background-color: var(--white-color);
        border-radius: var(--border-radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        margin-bottom: var(--spacing-md);
    }
    
    .accordion-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: var(--spacing-md);
        cursor: pointer;
        transition: background-color var(--transition-fast);
    }
    
    .accordion-header:hover {
        background-color: var(--gray-100);
    }
    
    .accordion-header h5 {
        margin-bottom: 0;
        font-size: var(--font-size-md);
    }
    
    .accordion-header i {
        transition: transform var(--transition-fast);
    }
    
    .accordion-content {
        padding: 0 var(--spacing-md) var(--spacing-md);
        display: none;
    }
    
    .accordion-item.active .accordion-content {
        display: block;
    }
    
    .accordion-item.active .accordion-header i {
        transform: rotate(180deg);
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .booking-wrapper {
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }
        
        /* Sidebar'ın order: -1 özelliğini kaldırdık - artık formdan sonra gösterilecek */
    }
    
    @media (max-width: 768px) {
        .booking-tour-card {
            flex-direction: column;
        }
        
        .booking-tour-card .tour-image,
        .booking-tour-card .tour-content {
            width: 100%;
        }
        
        .booking-tour-card .tour-image {
            height: 250px; /* Mobil görünümde de sabit yükseklik */
        }
        
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .tab-nav {
            flex-direction: column;
        }
        
        .tab-btn {
            text-align: left;
            justify-content: flex-start;
        }
        
        .form-navigation {
            flex-direction: column;
            gap: var(--spacing-md);
        }
        
        .form-navigation button {
            width: 100%;
        }
        
        /* Booking Progress için mobil tasarım */
        .booking-progress {
            flex-direction: row;
            flex-wrap: wrap;
            justify-content: space-between;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: var(--border-radius-md);
        }
        
        .progress-step {
            width: 30%;
            margin-bottom: var(--spacing-sm);
        }
        
        .progress-line {
            display: none; /* Mobilde çizgileri kaldırıyoruz */
        }
        
        /* Yan panel (sidebar) kartlarını daha kompakt hale getiriyoruz */
        .sidebar-card {
            padding: var(--spacing-md);
            margin-bottom: var(--spacing-md);
        }
        
        /* Yan panel başlıklarını küçültüyoruz */
        .sidebar-card-title {
            font-size: 1rem;
            margin-bottom: var(--spacing-md);
        }
        
        /* Özellik listesindeki boşlukları azaltıyoruz */
        .features-list {
            gap: var(--spacing-sm);
        }
        
        .feature-item {
            gap: var(--spacing-sm);
        }
        
        .feature-icon {
            width: 32px;
            height: 32px;
        }
        
        .feature-content h5 {
            font-size: 0.9rem;
            margin-bottom: 0;
        }
        
        .feature-content p {
            font-size: 0.8rem;
        }
        
        /* Accordion öğelerini kompakt hale getiriyoruz */
        .accordion-item {
            margin-bottom: var(--spacing-sm);
        }
        
        .accordion-header {
            padding: var(--spacing-sm);
        }
        
        .accordion-header h5 {
            font-size: 0.9rem;
        }
        
        .accordion-content {
            padding: 0 var(--spacing-sm) var(--spacing-sm);
            font-size: 0.85rem;
        }
        
        /* Yardım kartını kompakt hale getiriyoruz */
        .help-contact {
            margin: var(--spacing-sm) 0;
        }
        
        .contact-item {
            margin-bottom: var(--spacing-sm);
        }
    }
    
    @media (max-width: 576px) {
        /* Hero bölümünü küçültüyoruz */
        .booking-hero {
            min-height: 300px;
        }
        
        .hero-title {
            font-size: 1.75rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        /* Adım göstergesini daha da kompakt hale getiriyoruz */
        .step-number {
            width: 32px;
            height: 32px;
            font-size: 0.9rem;
        }
        
        .step-label {
            font-size: 0.8rem;
        }
        
        /* Formları daha kompakt hale getiriyoruz */
        .form-card {
            padding: var(--spacing-sm);
        }
        
        .form-section-title {
            font-size: 1rem;
        }
        
        /* Rezervasyon özeti kısmını küçültüyoruz */
        .booking-summary {
            margin-top: var(--spacing-md);
            padding-top: var(--spacing-md);
        }
        
        .summary-content {
            padding: var(--spacing-sm);
        }
        
        .summary-item {
            padding: 0.5rem 0;
            font-size: 0.9rem;
        }
        
        /* Fiyat etiketi biraz daha küçük */
        .price-current {
            font-size: 1.25rem;
        }
        
        /* Tur kartını kompakt hale getiriyoruz */
        .booking-tour-card .tour-image {
            height: 200px;
        }
        
        .tour-name {
            font-size: 1.25rem;
            margin-bottom: var(--spacing-sm);
        }
        
        /* İlk bölümdeki özellik ögelerini mobilde daha iyi göstermek için değişiklik */
        .feature-item {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .feature-icon {
            margin: 0 auto;
            margin-bottom: var(--spacing-sm);
        }
    }
    .terms-check {
    position: relative;
    }
    .terms-check .error-message {
        margin-top: 0.25rem;
        margin-left: 2rem;
    }
    .error-input {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
    }
    .highlight-preselected {
        background-color: rgba(67, 97, 238, 0.05);
        border-color: var(--primary-color) !important;
    }
</style>
<!-- JavaScript için dil çevirileri -->
<script>
    var translations = {
        // Form validasyonu için çeviriler
        'date_required': '<?php _e('date_required'); ?>',
        'first_name_required': '<?php _e('first_name_required'); ?>',
        'last_name_required': '<?php _e('last_name_required'); ?>',
        'email_required': '<?php _e('email_required'); ?>',
        'invalid_email': '<?php _e('invalid_email'); ?>',
        'phone_required': '<?php _e('phone_required'); ?>',
        'card_name_required': '<?php _e('card_name_required'); ?>',
        'card_number_required': '<?php _e('card_number_required'); ?>',
        'card_expiry_required': '<?php _e('card_expiry_required'); ?>',
        'card_cvv_required': '<?php _e('card_cvv_required'); ?>',
        'terms_required': '<?php _e('terms_required'); ?>'
    };

    // Çeviri yardımcı fonksiyonu
    function __(key) {
        return translations[key] || key;
    }
</script>
<!-- JavaScript for Booking Form -->
<script>
  
  document.addEventListener('DOMContentLoaded', function() {
    // URL'den parametre alma fonksiyonu
    function getUrlParameter(name) {
        name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    }
    
    // URL'den parametreleri al
    const urlDate = getUrlParameter('date');
    const urlAdults = getUrlParameter('adults');
    const urlChildren = getUrlParameter('children');
    
    // Tab Functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    function setActiveTab(tabId) {
        // Remove active class from all buttons and panes
        tabButtons.forEach(btn => {
            btn.classList.remove('active');
            if (btn.getAttribute('data-tab') === tabId) {
                btn.classList.add('active');
            }
        });
        
        tabPanes.forEach(pane => {
            pane.classList.remove('active');
            if (pane.id === tabId + '-tab') {
                pane.classList.add('active');
            }
        });
        
        // Scroll to top of form
        document.querySelector('.booking-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            setActiveTab(tabId);
        });
    });
    
    // Hata gösterme fonksiyonu
    function showError(inputElement, message) {
        // Mevcut hata mesajını temizle
        clearError(inputElement);
        
        // Hata mesajı oluştur
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        errorElement.style.color = '#dc3545';
        errorElement.style.fontSize = '0.875rem';
        errorElement.style.marginTop = '0.25rem';
        
        // Input elementinin bulunduğu konteynerin sonuna ekle
        const parentElement = inputElement.closest('.form-group');
        if (parentElement) {
            parentElement.appendChild(errorElement);
            
            // Input'a hata sınıfı ekle
            inputElement.classList.add('error-input');
            inputElement.style.borderColor = '#dc3545';
        }
    }
    
    // Hata mesajını temizleme fonksiyonu
    function clearError(inputElement) {
        const parentElement = inputElement.closest('.form-group');
        if (parentElement) {
            const errorElement = parentElement.querySelector('.error-message');
            if (errorElement) {
                parentElement.removeChild(errorElement);
            }
            
            // Input'tan hata sınıfını kaldır
            inputElement.classList.remove('error-input');
            inputElement.style.borderColor = '';
        }
    }
    
    // Tüm hataları temizleme
    function clearAllErrors() {
        document.querySelectorAll('.error-message').forEach(error => error.remove());
        document.querySelectorAll('.error-input').forEach(input => {
            input.classList.remove('error-input');
            input.style.borderColor = '';
        });
    }
    
    // Adım validasyonu
    function validateStep(stepId) {
        clearAllErrors();
        
        if (stepId === 'booking-details') {
            // Booking details validasyonu
            const dateInput = document.getElementById('booking_date');
            if (!dateInput.value) {
                showError(dateInput, __('date_required'));
                return false;
            }
        }
        else if (stepId === 'personal-info') {
            // Kişisel bilgi validasyonu
            const firstName = document.getElementById('first_name');
            const lastName = document.getElementById('last_name');
            const email = document.getElementById('booking_email');
            const phone = document.getElementById('booking_phone');
            
            let isValid = true;
            
            if (!firstName.value) {
                showError(firstName, __('first_name_required'));
                isValid = false;
            }
            
            if (!lastName.value) {
                showError(lastName, __('last_name_required'));
                isValid = false;
            }
            
            if (!email.value) {
                showError(email, __('email_required'));
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, __('invalid_email'));
                isValid = false;
            }
            
            if (!phone.value) {
                showError(phone, __('phone_required'));
                isValid = false;
            }
            
            return isValid;
        }
        
        return true;
    }
    
    // E-posta doğrulama
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Next/Prev Tab Navigation
    document.querySelectorAll('.next-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            const currentTabId = this.closest('.tab-pane').id.replace('-tab', '');
            
            // Mevcut adımı doğrula
            if (validateStep(currentTabId)) {
                const nextTabId = this.getAttribute('data-next');
                setActiveTab(nextTabId);
            } else {
                // Sayfayı ilk hataya doğru kaydır
                const firstError = document.querySelector('.error-input');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    });
    
    document.querySelectorAll('.prev-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            const prevTabId = this.getAttribute('data-prev');
            setActiveTab(prevTabId);
        });
    });
    
    // Input olaylarını dinle
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function() {
            clearError(this);
        });
        
        input.addEventListener('focus', function() {
            this.classList.remove('error-input');
            this.style.borderColor = '';
        });
    });
    
    // Payment Method Selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    const paymentContents = document.querySelectorAll('.payment-content');

    // Function to toggle required attributes based on payment method
    function toggleRequiredFields(methodId) {
        // Get all credit card fields
        const cardFields = [
            document.getElementById('card_name'),
            document.getElementById('card_number'),
            document.getElementById('card_expiry'),
            document.getElementById('card_cvv')
        ];
        
        // Set required attribute based on selected payment method
        cardFields.forEach(field => {
            if (field) {
                if (methodId === 'card') {
                    field.setAttribute('required', '');
                } else {
                    field.removeAttribute('required');
                }
            }
        });
    }

    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Update radio selection
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;
            
            // Update active classes
            paymentMethods.forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding content
            const methodId = this.getAttribute('data-method');
            paymentContents.forEach(content => {
                content.classList.remove('active');
                if (content.getAttribute('data-method') === methodId) {
                    content.classList.add('active');
                }
            });
            
            // Toggle required fields based on payment method
            toggleRequiredFields(methodId);
        });
    });
    
    // Guest Counter
    const counterBtns = document.querySelectorAll('.counter-btn');
    
    counterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.getAttribute('data-target');
            const input = document.getElementById(target);
            const currentValue = parseInt(input.value);
            const min = parseInt(input.getAttribute('min'));
            const max = parseInt(input.getAttribute('max'));
            
            if (this.classList.contains('increase-btn')) {
                if (currentValue < max) {
                    input.value = currentValue + 1;
                }
            } else {
                if (currentValue > min) {
                    input.value = currentValue - 1;
                }
            }
            
            // Update summary
            updateSummary();
        });
    });
    
    // Date Picker
    if (typeof flatpickr !== 'undefined') {
        console.log('Flatpickr yüklendi ve çalışıyor');
        
        // Bugünün tarihini manuel olarak al
        const today = new Date();
        console.log('Bugünün tarihi:', today);
        
        // URL'den gelen tarih varsa, öncelikle onu kullan
        let initialDate = today;
        if (urlDate) {
            initialDate = new Date(urlDate);
            console.log('URL\'den alınan tarih kullanılıyor:', initialDate);
        }
        
        // Flatpickr yapılandırması
        const datePickerInstance = flatpickr("#booking_date", {
            // Bugünün tarihini manuel olarak ayarla
            minDate: today,
            
            // Mevcut ayı göster (önemli)
            defaultDate: initialDate,
            
            // Standart ayarlar
            dateFormat: "Y-m-d",
            disableMobile: true,
            altInput: true,
            altFormat: "F j, Y",
            
            // İlk açılışta takvimi görüntüle (opsiyonel)
            // inline: true,
            
            // Ek debug bilgisi
            onReady: function(selectedDates, dateStr, instance) {
                console.log('Flatpickr hazır, varsayılan tarih:', dateStr);
                console.log('Takvim ayı:', instance.currentMonth, instance.currentYear);
                
                // URL'den tarih varsa, özet bölümünü güncelleyelim
                if (urlDate) {
                    document.getElementById('summary_date').textContent = instance.formatDate(new Date(urlDate), "F j, Y");
                }
            },
            
            onChange: function(selectedDates, dateStr) {
                console.log('Seçilen tarih:', dateStr);
                // Update the booking summary date
                document.getElementById('summary_date').textContent = dateStr;
                updateSummary();
                
                // Hata mesajını temizle
                clearError(document.getElementById('booking_date'));
            }
        });
        
        // URL'den tarih parametresi varsa, date picker'ı ayarla
        if (urlDate) {
            console.log('URL\'den alınan tarih ayarlanıyor:', urlDate);
            datePickerInstance.setDate(urlDate);
            
            // Özet bölümünü güncelleyelim
            document.getElementById('summary_date').textContent = datePickerInstance.formatDate(new Date(urlDate), "F j, Y");
        }
        
        // Mevcut ayı zorlayarak göster - URL'den geliyorsa o tarihe ait ayı göster
        setTimeout(function() {
            if (urlDate) {
                const urlDateObj = new Date(urlDate);
                datePickerInstance.changeMonth(urlDateObj.getMonth());
                console.log('Takvim ayı URL\'den gelen tarihe göre ayarlandı:', urlDateObj.getMonth());
            } else {
                const currentDate = new Date();
                datePickerInstance.changeMonth(currentDate.getMonth());
                console.log('Takvim ayı bugüne göre ayarlandı:', currentDate.getMonth());
            }
        }, 100);
    }
    
    // URL'den gelen katılımcı sayılarını ayarla
    if (urlAdults) {
        const adultsInput = document.getElementById('booking_adults');
        adultsInput.value = parseInt(urlAdults);
        document.getElementById('summary_adults').textContent = urlAdults;
        console.log('URL\'den alınan yetişkin sayısı:', urlAdults);
    }
    
    if (urlChildren) {
        const childrenInput = document.getElementById('booking_children');
        childrenInput.value = parseInt(urlChildren);
        document.getElementById('summary_children').textContent = urlChildren;
        console.log('URL\'den alınan çocuk sayısı:', urlChildren);
    }
    
    // URL'den gelen parametreler yüklendikten sonra özeti güncelle
    if (urlAdults || urlChildren || urlDate) {
        updateSummary();
    }
    
    // Accordion
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const accordionItem = this.parentElement;
            accordionItem.classList.toggle('active');
        });
    });
    
    // Sayfa yüklendiğinde ilk aktif ödeme yöntemi için required özelliklerini ayarla
    window.addEventListener('load', function() {
        // Sayfa tamamen yüklendikten sonra booking-details sekmesini aktif hale getir
        setActiveTab('booking-details');
        
        // Varsayılan ödeme yöntemi için required alanları ayarla
        const defaultPaymentMethod = document.querySelector('.payment-method.active');
        if (defaultPaymentMethod) {
            const methodId = defaultPaymentMethod.getAttribute('data-method');
            toggleRequiredFields(methodId);
        }
        
        // Eğer URL'den parametreler geldiyse, bunun vurgulanması için ek görsel işaret ekleyebiliriz
        if (urlDate || urlAdults || urlChildren) {
            // Önceden seçilmiş parametreleri vurgula
            highlightPreselectedOptions();
        }
    });
    
    // Önceden seçilmiş parametreleri vurgulayan fonksiyon
    function highlightPreselectedOptions() {
        // Eğer URL'den tarih geldiyse, tarih alanını vurgula
        if (urlDate) {
            const dateInput = document.getElementById('booking_date');
            dateInput.classList.add('preselected');
            dateInput.parentElement.classList.add('highlight-preselected');
        }
        
        // Eğer URL'den yetişkin sayısı geldiyse, yetişkin sayısı alanını vurgula
        if (urlAdults) {
            const adultsContainer = document.getElementById('booking_adults').closest('.guest-type');
            adultsContainer.classList.add('highlight-preselected');
        }
        
        // Eğer URL'den çocuk sayısı geldiyse, çocuk sayısı alanını vurgula
        if (urlChildren && parseInt(urlChildren) > 0) {
            const childrenContainer = document.getElementById('booking_children').closest('.guest-type');
            childrenContainer.classList.add('highlight-preselected');
        }
    }
    
    // Price Calculation & Summary
    function updateSummary() {
        const adults = parseInt(document.getElementById('booking_adults').value);
        const children = parseInt(document.getElementById('booking_children').value);
        const basePrice = parseFloat(document.getElementById('booking_base_price').value);
        const discountPrice = parseFloat(document.getElementById('booking_discount_price').value);
        const currencySymbol = document.getElementById('currency_symbol').value;
        
        // Update summary participants
        document.getElementById('summary_adults').textContent = adults;
        document.getElementById('summary_children').textContent = children;
        
        // Calculate total price
        const pricePerPerson = discountPrice > 0 ? discountPrice : basePrice;
        const totalAdults = pricePerPerson * adults;
        const totalChildren = pricePerPerson * 0.5 * children;
        const totalPrice = totalAdults + totalChildren;
        
        // Update display
        document.getElementById('total_price_display').textContent = currencySymbol + totalPrice.toFixed(2);
        document.getElementById('booking_total_price').value = totalPrice.toFixed(2);
    }
    
    // Initial summary update
    updateSummary();
    
    // Listen for changes in participant numbers
    document.getElementById('booking_adults').addEventListener('change', updateSummary);
    document.getElementById('booking_children').addEventListener('change', updateSummary);
    
    // Form Validation
    const bookingForm = document.getElementById('booking_form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Önce formu durdur, geçerliyse sonra manuel gönder
            
            let isValid = true;
            clearAllErrors();
            
            // Booking details validasyonu
            const bookingDate = document.getElementById('booking_date').value;
            if (!bookingDate) {
                showError(document.getElementById('booking_date'), __('date_required'));
                setActiveTab('booking-details');
                isValid = false;
                return;
            }
            
            // Kişisel bilgiler validasyonu
            const firstName = document.getElementById('first_name');
            const lastName = document.getElementById('last_name');
            const email = document.getElementById('booking_email');
            const phone = document.getElementById('booking_phone');
            
            if (!firstName.value) {
                showError(firstName, __('first_name_required'));
                isValid = false;
            }
            
            if (!lastName.value) {
                showError(lastName, __('last_name_required'));
                isValid = false;
            }
            
            if (!email.value) {
                showError(email, __('email_required'));
                isValid = false;
            } else if (!isValidEmail(email.value)) {
                showError(email, __('invalid_email'));
                isValid = false;
            }
            
            if (!phone.value) {
                showError(phone, __('phone_required'));
                isValid = false;
            }
            
            if (!isValid) {
                setActiveTab('personal-info');
                // İlk hataya odaklan
                const firstError = document.querySelector('.error-input');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return;
            }
            
            // Ödeme yöntemi kontrolü
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
            
            // Sadece kredi kartı seçilmişse kart alanlarını doğrula
            if (selectedPaymentMethod === 'card') {
                const cardName = document.getElementById('card_name');
                const cardNumber = document.getElementById('card_number');
                const cardExpiry = document.getElementById('card_expiry');
                const cardCvv = document.getElementById('card_cvv');
                
                let cardValid = true;
                
                if (!cardName.value) {
                    showError(cardName, __('card_name_required'));
                    cardValid = false;
                }
                
                if (!cardNumber.value) {
                    showError(cardNumber, __('card_number_required'));
                    cardValid = false;
                }
                
                if (!cardExpiry.value) {
                    showError(cardExpiry, __('card_expiry_required'));
                    cardValid = false;
                }
                
                if (!cardCvv.value) {
                    showError(cardCvv, __('card_cvv_required'));
                    cardValid = false;
                }
                
                if (!cardValid) {
                    setActiveTab('payment-info');
                    // İlk hataya odaklan
                    const firstError = document.querySelector('.error-input');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                    return;
                }
            }
            
            const termsCheck = document.getElementById('terms_check');
            if (!termsCheck.checked) {
                showError(termsCheck, __('terms_required'));
                setActiveTab('payment-info');
                return;
            }
            
            // Tüm validasyonlar geçildiyse formu gönder
            this.submit();
        });
    }
});
</script>