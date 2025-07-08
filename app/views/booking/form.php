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
                    <input type="hidden" id="booking_extras_data" name="extras_data" value="">
                    <input type="hidden" id="booking_extras_total" name="extras_total" value="0">
                    
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
                                                    <input type="text" id="booking_date" name="booking_date" class="form-control datepicker" required
                                                           placeholder="<?php _e('select_date'); ?>"
                                                           data-min-date="today">
                                                           <i class="material-icons">event</i>
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
                                            <?php 
                                            $first = true;
                                            foreach ($activePaymentMethods as $methodId => $method): 
                                            ?>
                                                <div class="payment-method <?php echo $first ? 'active' : ''; ?>" data-method="<?php echo $methodId; ?>">
                                                    <div class="payment-method-radio">
                                                        <input type="radio" id="payment_<?php echo $methodId; ?>" name="payment_method" value="<?php echo $methodId; ?>" <?php echo $first ? 'checked' : ''; ?>>
                                                        <div class="radio-indicator"></div>
                                                    </div>
                                                    <div class="payment-method-icon">
                                                        <i class="material-icons"><?php echo $method['icon']; ?></i>
                                                    </div>
                                                    <div class="payment-method-details">
                                                        <div class="payment-method-name"><?php echo $method['name']; ?></div>
                                                        <div class="payment-method-description"><?php echo $method['description']; ?></div>
                                                    </div>
                                                </div>
                                            <?php 
                                                $first = false;
                                            endforeach; 
                                            ?>
                                        </div>
                                        
                                        <div class="payment-contents">
                                            <?php 
                                            $first = true;
                                            foreach ($activePaymentMethods as $methodId => $method): 
                                            ?>
                                                
                                                <!-- Card Payment Content -->
                                                <?php if ($methodId === 'card'): ?>
                                                    <div class="payment-content <?php echo $first ? 'active' : ''; ?>" data-method="card">
                                                        <div class="form-row">
                                                            <div class="form-group col-12">
                                                                <label for="card_name" class="form-label"><?php _e('card_name'); ?> <span class="required">*</span></label>
                                                                <div class="input-with-icon">
                                                                    <i class="material-icons">person</i>
                                                                    <input type="text" id="card_name" name="card_name" class="form-control" <?php echo $first ? 'required' : ''; ?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-row">
                                                            <div class="form-group col-12">
                                                                <label for="card_number" class="form-label"><?php _e('card_number'); ?> <span class="required">*</span></label>
                                                                <div class="input-with-icon">
                                                                    <i class="material-icons">credit_card</i>
                                                                    <input type="text" id="card_number" name="card_number" class="form-control" placeholder="XXXX XXXX XXXX XXXX" <?php echo $first ? 'required' : ''; ?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="card_expiry" class="form-label"><?php _e('card_expiry'); ?> <span class="required">*</span></label>
                                                                <div class="input-with-icon">
                                                                    <i class="material-icons">date_range</i>
                                                                    <input type="text" id="card_expiry" name="card_expiry" class="form-control" placeholder="MM/YY" <?php echo $first ? 'required' : ''; ?>>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group col-md-6">
                                                                <label for="card_cvv" class="form-label"><?php _e('card_cvv'); ?> <span class="required">*</span></label>
                                                                <div class="input-with-icon">
                                                                    <i class="material-icons">lock</i>
                                                                    <input type="text" id="card_cvv" name="card_cvv" class="form-control" placeholder="CVV" <?php echo $first ? 'required' : ''; ?>>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="payment-disclaimer">
                                                            <i class="material-icons">lock</i>
                                                            <span><?php _e('payment_card_disclaimer'); ?></span>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- PayPal Payment Content -->
                                                <?php if ($methodId === 'paypal'): ?>
                                                    <div class="payment-content <?php echo $first ? 'active' : ''; ?>" data-method="paypal">
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
                                                <?php endif; ?>
                                                
                                                <!-- Bank Transfer Payment Content -->
                                                <?php if ($methodId === 'bank'): ?>
                                                    <div class="payment-content <?php echo $first ? 'active' : ''; ?>" data-method="bank">
                                                        <div class="payment-info">
                                                            <div class="info-box warning">
                                                                <i class="material-icons">info</i>
                                                                <p><?php _e('bank_info'); ?></p>
                                                            </div>
                                                            <div class="bank-details">
                                                                <div class="bank-detail">
                                                                    <div class="bank-detail-label"><?php _e('bank_name'); ?></div>
                                                                    <div class="bank-detail-value"><?php echo isset($settings['bank_name']) ? $settings['bank_name'] : 'Example Bank'; ?></div>
                                                                </div>
                                                                <div class="bank-detail">
                                                                    <div class="bank-detail-label"><?php _e('account_holder'); ?></div>
                                                                    <div class="bank-detail-value"><?php echo isset($settings['account_name']) ? $settings['account_name'] : 'Cappadocia Travel Agency'; ?></div>
                                                                </div>
                                                                <div class="bank-detail">
                                                                    <div class="bank-detail-label"><?php _e('account_number'); ?></div>
                                                                    <div class="bank-detail-value"><?php echo isset($settings['account_number']) ? $settings['account_number'] : '0000 0000 0000 0000'; ?></div>
                                                                </div>
                                                                <div class="bank-detail">
                                                                    <div class="bank-detail-label"><?php _e('iban'); ?></div>
                                                                    <div class="bank-detail-value"><?php echo isset($settings['iban']) ? $settings['iban'] : 'TR00 0000 0000 0000 0000 0000 00'; ?></div>
                                                                </div>
                                                                <div class="bank-detail">
                                                                    <div class="bank-detail-label"><?php _e('swift_code'); ?></div>
                                                                    <div class="bank-detail-value"><?php echo isset($settings['swift_code']) ? $settings['swift_code'] : 'EXAMPLEXXX'; ?></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Cash Payment Content -->
                                                <?php if ($methodId === 'cash'): ?>
                                                    <div class="payment-content <?php echo $first ? 'active' : ''; ?>" data-method="cash">
                                                        <div class="payment-info">
                                                            <div class="info-box">
                                                                <i class="material-icons">info</i>
                                                                <p><?php _e('cash_info'); ?></p>
                                                            </div>
                                                            <div class="cash-instructions">
                                                                <h5><?php _e('cash_payment_instructions'); ?></h5>
                                                                <ul>
                                                                    <li><?php _e('cash_instruction_1'); ?></li>
                                                                    <li><?php _e('cash_instruction_2'); ?></li>
                                                                    <li><?php _e('cash_instruction_3'); ?></li>
                                                                </ul>
                                                            </div>
                                                            <div class="payment-disclaimer">
                                                                <i class="material-icons">info</i>
                                                                <span><?php _e('cash_payment_disclaimer'); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                
                                            <?php 
                                                $first = false;
                                            endforeach; 
                                            ?>
                                        </div>
                                        
                                        <!-- Rezervasyon özeti ve diğer alanlar burada devam eder... -->
                                        
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
                                                
                                                <div class="summary-item" id="extras_summary_section">
                                                    <div class="summary-label"><?php _e('extras'); ?></div>
                                                    <div class="summary-value">
                                                        <div id="extras_summary_list">
                                                            <div class="no-extras-message"><?php _e('no_extras_selected'); ?></div>
                                                        </div>
                                                        <div class="extras-actions">
                                                            <button type="button" class="btn-link" onclick="toggleExtrasModal()">
                                                                <span id="extras_button_text"><?php _e('add_extras'); ?></span>
                                                            </button>
                                                        </div>
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
            
            <!-- Extras Modal -->
            <div id="extras_modal" class="modal" style="display: none !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><?php _e('select_extras'); ?></h3>
                        <button type="button" class="modal-close" onclick="closeExtrasModal()">
                            <i class="material-icons">close</i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="extras_modal_content">
                            <!-- Extras will be loaded here -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeExtrasModal()"><?php _e('cancel'); ?></button>
                        <button type="button" class="btn btn-primary" onclick="saveExtrasSelection()"><?php _e('save_selection'); ?></button>
                    </div>
                </div>
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
    .cash-instructions {
        background-color: var(--white-color);
        border-radius: var(--border-radius-md);
        padding: var(--spacing-md);
        margin-top: var(--spacing-md);
    }

    .cash-instructions h5 {
        margin-bottom: var(--spacing-sm);
        color: var(--dark-color);
        font-weight: var(--font-weight-medium);
    }

    .cash-instructions ul {
        margin: 0;
        padding-left: 1.5rem;
        list-style-type: disc;
    }

    .cash-instructions li {
        margin-bottom: 0.5rem;
        color: var(--gray-700);
        line-height: 1.5;
    }

    .cash-instructions li:last-child {
        margin-bottom: 0;
    }

    /* Payment method dinamik seçimi için ek stiller */
    .payment-method[data-method="cash"] .payment-method-icon {
        background-color: rgba(40, 167, 69, 0.1);
        color: var(--success-color);
    }

    .payment-method[data-method="paypal"] .payment-method-icon {
        background-color: rgba(0, 123, 255, 0.1);
        color: #007bff;
    }

    .payment-method[data-method="bank"] .payment-method-icon {
        background-color: rgba(255, 193, 7, 0.1);
        color: var(--warning-color);
    }
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
        top: 1rem;
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

    .next-tab.btn-primary i {
        color: var(--white-color) !important;
    }

    .next-tab.btn-primary:hover i {
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
    
    /* Extras Styling */
    .extras-actions {
        margin-top: 0.5rem;
    }
    
    .btn-link {
        background: none;
        border: none;
        color: var(--primary-color);
        text-decoration: underline;
        cursor: pointer;
        padding: 0;
        font-size: 0.875rem;
    }
    
    .btn-link:hover {
        color: var(--primary-dark);
    }
    
    .extras-summary-item {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .extras-summary-item:last-child {
        margin-bottom: 0;
    }
    
    .extras-summary-name {
        flex: 1;
    }
    
    .extras-summary-quantity {
        margin-left: 0.5rem;
        color: var(--gray-600);
    }
    
    .extras-summary-price {
        margin-left: 0.5rem;
        font-weight: 600;
        color: var(--primary-color);
    }
    
    .no-extras-message {
        color: var(--gray-600);
        font-size: 0.875rem;
        font-style: italic;
    }
    
    /* Modal Styling */
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    
    .modal-content {
        background: white;
        border-radius: 12px;
        max-width: 600px;
        width: 90%;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--gray-600);
        padding: 0.25rem;
        border-radius: 50%;
        transition: all 0.2s ease;
    }
    
    .modal-close:hover {
        background: var(--gray-100);
        color: var(--gray-800);
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1rem 1.5rem;
        border-top: 1px solid #e0e0e0;
    }
    
    .modal-footer .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .modal-footer .btn-secondary {
        background: var(--gray-200);
        color: var(--gray-700);
    }
    
    .modal-footer .btn-secondary:hover {
        background: var(--gray-300);
    }
    
    .modal-footer .btn-primary {
        background: var(--primary-color);
        color: white;
    }
    
    .modal-footer .btn-primary:hover {
        background: var(--primary-dark);
    }
    
    /* Extras within modal */
    .modal-extras-selector {
        display: grid;
        gap: 1rem;
    }
    
    .modal-extra-item {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.2s ease;
    }
    
    .modal-extra-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 2px 8px rgba(255, 107, 53, 0.1);
    }
    
    .modal-extra-checkbox {
        position: relative;
    }
    
    .modal-extra-checkbox input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }
    
    .modal-extra-label {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        padding: 1rem;
        cursor: pointer;
        margin-bottom: 0;
        font-weight: 400;
        transition: all 0.2s ease;
    }
    
    .modal-extra-label .modal-extra-quantity {
        pointer-events: none;
    }
    
    .modal-extra-label .modal-extra-quantity * {
        pointer-events: auto;
    }
    
    .modal-extra-checkbox input[type="checkbox"]:checked + .modal-extra-label {
        background: rgba(255, 107, 53, 0.05);
        border-radius: 8px;
    }
    
    .modal-extra-info {
        flex: 1;
    }
    
    .modal-extra-name {
        font-weight: 600;
        font-size: 0.9375rem;
        margin-bottom: 0.25rem;
    }
    
    .modal-extra-description {
        font-size: 0.8125rem;
        color: var(--gray-600);
        line-height: 1.4;
        margin-bottom: 0.75rem;
    }
    
    .modal-extra-price {
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1rem;
        margin-left: 1rem;
        white-space: nowrap;
    }
    
    .modal-extra-quantity {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f0f0f0;
        pointer-events: auto !important;
        position: relative;
        z-index: 998;
    }
    
    .modal-extra-quantity-label {
        font-size: 0.8125rem;
        color: var(--gray-600);
        min-width: 80px;
    }
    
    .modal-extra-quantity .guest-counter {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        pointer-events: auto !important;
        position: relative;
        z-index: 999;
    }
    
    .modal-extra-quantity .counter-btn {
        width: 32px;
        height: 32px;
        border: 2px solid var(--primary-color);
        background: white !important;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer !important;
        font-size: 1rem;
        font-weight: bold;
        color: var(--primary-color);
        transition: all 0.2s ease;
        user-select: none;
        pointer-events: auto !important;
        position: relative !important;
        z-index: 10000 !important;
        outline: none;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .modal-extra-quantity .counter-btn:hover {
        background: var(--primary-color);
        color: white;
        transform: scale(1.1);
    }
    
    .modal-extra-quantity .counter-btn:active {
        transform: scale(0.95);
    }
    
    .modal-extra-quantity .counter-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    .modal-extra-quantity .counter-value {
        width: 40px;
        text-align: center;
        font-weight: 600;
        font-size: 0.875rem;
    }
</style>
<!-- JavaScript için dil çevirileri -->
<script>
    // Language translations for JavaScript
    window.translations = {
        quantity: "<?php echo htmlspecialchars(__('quantity')); ?>",
        add_extras: "<?php echo htmlspecialchars(__('add_extras')); ?>",
        modify_extras: "<?php echo htmlspecialchars(__('modify_extras')); ?>",
        no_extras_selected: "<?php echo htmlspecialchars(__('no_extras_selected')); ?>",
        // Form validasyonu için çeviriler
        date_required: "<?php echo htmlspecialchars(__('date_required')); ?>",
        first_name_required: "<?php echo htmlspecialchars(__('first_name_required')); ?>",
        last_name_required: "<?php echo htmlspecialchars(__('last_name_required')); ?>",
        email_required: "<?php echo htmlspecialchars(__('email_required')); ?>",
        invalid_email: "<?php echo htmlspecialchars(__('invalid_email')); ?>",
        phone_required: "<?php echo htmlspecialchars(__('phone_required')); ?>",
        card_name_required: "<?php echo htmlspecialchars(__('card_name_required')); ?>",
        card_number_required: "<?php echo htmlspecialchars(__('card_number_required')); ?>",
        card_expiry_required: "<?php echo htmlspecialchars(__('card_expiry_required')); ?>",
        card_cvv_required: "<?php echo htmlspecialchars(__('card_cvv_required')); ?>",
        terms_required: "<?php echo htmlspecialchars(__('terms_required')); ?>"
    };
    
    // Global extras functions - defined before DOMContentLoaded
    window.toggleExtrasModal = function() {
        const modal = document.getElementById('extras_modal');
        if (modal.style.display === 'none' || modal.style.display === '') {
            window.loadExtrasModal();
            modal.style.display = 'flex';
            modal.style.setProperty('display', 'flex', 'important');
        } else {
            modal.style.display = 'none';
            modal.style.setProperty('display', 'none', 'important');
        }
    };
    
    window.closeExtrasModal = function() {
        const modal = document.getElementById('extras_modal');
        modal.style.display = 'none';
        modal.style.setProperty('display', 'none', 'important');
    };
    
    window.saveExtrasSelection = function() {
        
        // Update hidden form inputs
        const extrasDataInput = document.getElementById('booking_extras_data');
        const extrasTotalInput = document.getElementById('booking_extras_total');
        
        // Calculate total (always calculate, regardless of inputs)
        const adults = parseInt(document.getElementById('booking_adults').value) || 2;
        const children = parseInt(document.getElementById('booking_children').value) || 0;
        const totalPersons = adults + children;
        
        let extrasTotal = 0;
        const extrasData = {};
        
        if (window.selectedExtras && Object.keys(window.selectedExtras).length > 0) {
            Object.keys(window.selectedExtras).forEach(extraId => {
                const extra = window.availableExtras.find(e => e.id == extraId);
                if (extra) {
                    const quantity = window.selectedExtras[extraId].quantity;
                    
                    let price = parseFloat(extra.base_price || 0);
                    if (extra.pricing && extra.pricing.length > 0) {
                        let applicableTier = null;
                        for (let tier of extra.pricing) {
                            if (totalPersons >= tier.persons) {
                                applicableTier = tier;
                            } else {
                                break;
                            }
                        }
                        if (applicableTier) {
                            price = parseFloat(applicableTier.price_per_person) * totalPersons;
                        }
                    }
                    
                    const totalPrice = price * quantity;
                    extrasTotal += totalPrice;
                    
                    extrasData[extraId] = {
                        id: extraId,
                        name: extra.name,
                        quantity: quantity,
                        base_price: extra.base_price,
                        calculated_price: price,
                        total_price: totalPrice
                    };
                }
            });
        }
        
        
        // Update hidden inputs if they exist
        if (extrasDataInput && extrasTotalInput) {
            extrasDataInput.value = JSON.stringify(extrasData);
            extrasTotalInput.value = extrasTotal.toFixed(2);
            
        } else {
        }
        
        // Update display functions
        if (typeof window.updateExtrasDisplay === 'function') {
            window.updateExtrasDisplay();
        } else {
        }
        
        // Try to call updateSummary if it exists, otherwise just update the total manually
        if (typeof updateSummary === 'function') {
            updateSummary();
        } else if (typeof window.updateSummary === 'function') {
            window.updateSummary();
        } else {
            // Manually update the total display
            const totalDisplay = document.getElementById('total_price_display');
            const totalInput = document.getElementById('booking_total_price');
            const currencySymbol = document.getElementById('currency_symbol').value || '$';
            
            if (totalDisplay && totalInput) {
                const currentTotal = parseFloat(totalInput.value) || 0;
                const newTotal = currentTotal + extrasTotal;
                totalDisplay.textContent = currencySymbol + newTotal.toFixed(2);
                totalInput.value = newTotal.toFixed(2);
            }
        }
        
        window.closeExtrasModal();
    };
    
    window.toggleModalExtraQuantity = function(extraId) {
        const checkbox = document.getElementById(`modal_extra_${extraId}`);
        const quantityDiv = document.getElementById(`modal_extra_quantity_${extraId}`);
        
        if (checkbox && checkbox.checked) {
            quantityDiv.style.display = 'flex';
            if (!window.selectedExtras[extraId]) {
                const extra = window.availableExtras.find(e => e.id == extraId);
                if (extra) {
                    window.selectedExtras[extraId] = {
                        quantity: 1,
                        name: extra.name,
                        price: extra.base_price
                    };
                }
            }
        } else if (quantityDiv) {
            quantityDiv.style.display = 'none';
            delete window.selectedExtras[extraId];
        }
        
        // Update prices after toggle
        if (typeof updateModalExtraPrices === 'function') {
            updateModalExtraPrices();
        }
    };
    
    window.updateModalExtraQuantity = function(extraId, change) {
        
        const quantityElement = document.getElementById(`modal_extra_quantity_value_${extraId}`);
        
        if (quantityElement) {
            const currentQuantity = parseInt(quantityElement.textContent);
            const newQuantity = Math.max(1, currentQuantity + change);
            
            // Update the display immediately
            quantityElement.textContent = newQuantity;
            
            // Ensure selectedExtras entry exists
            if (!window.selectedExtras[extraId]) {
                const extra = window.availableExtras.find(e => e.id == extraId);
                if (extra) {
                    window.selectedExtras[extraId] = {
                        quantity: newQuantity,
                        name: extra.name,
                        price: extra.base_price
                    };
                }
            } else {
                window.selectedExtras[extraId].quantity = newQuantity;
            }
            
            
            // Update prices after quantity change
            if (typeof updateModalExtraPrices === 'function') {
                    updateModalExtraPrices();
            } else {
            }
        } else {
        }
    };
    
    // Global variables for extras
    window.availableExtras = [];
    window.selectedExtras = {};
    
    // Load extras modal content
    window.loadExtrasModal = function() {
        const modalContent = document.getElementById('extras_modal_content');
        
        if (!window.availableExtras || window.availableExtras.length === 0) {
            modalContent.innerHTML = '<div class="no-extras-available"><p>No extras available for this tour.</p></div>';
            return;
        }
        
        const adults = parseInt(document.getElementById('booking_adults').value) || 2;
        const children = parseInt(document.getElementById('booking_children').value) || 0;
        const totalPersons = adults + children;
        const currencySymbol = document.getElementById('currency_symbol').value;
        
        let html = '<div class="modal-extras-selector">';
        
        window.availableExtras.forEach(extra => {
            const isSelected = window.selectedExtras && window.selectedExtras[extra.id];
            const quantity = isSelected ? window.selectedExtras[extra.id].quantity : 1;
            
            
            // Calculate price
            let price = parseFloat(extra.base_price || 0);
            if (extra.pricing && extra.pricing.length > 0) {
                // Find applicable pricing tier
                let applicableTier = null;
                for (let tier of extra.pricing) {
                    if (totalPersons >= tier.persons) {
                        applicableTier = tier;
                    } else {
                        break;
                    }
                }
                if (applicableTier) {
                    price = parseFloat(applicableTier.price_per_person) * totalPersons;
                }
            }
            
            html += `
                <div class="modal-extra-item">
                    <div class="modal-extra-checkbox">
                        <input type="checkbox" id="modal_extra_${extra.id}" value="${extra.id}" ${isSelected ? 'checked' : ''} onchange="window.toggleModalExtraQuantity(${extra.id})">
                        <label for="modal_extra_${extra.id}" class="modal-extra-label">
                            <div class="modal-extra-info">
                                <div class="modal-extra-name">${extra.name}</div>
                                ${extra.description ? `<div class="modal-extra-description">${extra.description}</div>` : ''}
                            </div>
                            <div class="modal-extra-price" id="modal_extra_price_${extra.id}">
                                ${currencySymbol}${(price * quantity).toFixed(2)}
                            </div>
                        </label>
                        <div class="modal-extra-quantity" id="modal_extra_quantity_${extra.id}" style="display: flex !important; margin-top: 10px; padding: 10px; border-top: 1px solid #eee; flex-direction: column; gap: 10px;">
                            <div class="modal-extra-quantity-label" style="font-weight: bold;">${window.translations.quantity + ':'}</div>
                            <div class="guest-counter" style="display: flex; align-items: center; gap: 15px; justify-content: center;">
                                <button type="button" class="counter-btn decrease-btn" data-extra-id="${extra.id}">
                                    -
                                </button>
                                <span class="counter-value" id="modal_extra_quantity_value_${extra.id}">${quantity}</span>
                                <button type="button" class="counter-btn increase-btn" data-extra-id="${extra.id}">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        modalContent.innerHTML = html;
        
        // Debug: Check if buttons were actually created
        setTimeout(() => {
            const buttons = modalContent.querySelectorAll('.counter-btn');
            buttons.forEach((btn, index) => {
                const computedStyle = window.getComputedStyle(btn);
                    'data-extra-id': btn.getAttribute('data-extra-id'),
                    'data-change': btn.getAttribute('data-change'),
                    'onclick': btn.onclick,
                    'visible': btn.offsetWidth > 0 && btn.offsetHeight > 0,
                    'display': computedStyle.display,
                    'visibility': computedStyle.visibility,
                    'opacity': computedStyle.opacity,
                    'pointerEvents': computedStyle.pointerEvents,
                    'zIndex': computedStyle.zIndex,
                    'position': computedStyle.position,
                    'width': computedStyle.width,
                    'height': computedStyle.height,
                    'background': computedStyle.backgroundColor
                });
            });
            
            // Test button visibility
            const testBtns = modalContent.querySelectorAll('[onclick*="alert"]');
            
            // Check modal z-index issues
            const modal = document.getElementById('extras_modal');
            const modalStyle = window.getComputedStyle(modal);
        }, 100);
        
        // Add event delegation for counter buttons with detailed logging
        modalContent.addEventListener('click', function(e) {
            
            // Check if clicked element or its parent is a counter button
            let targetBtn = e.target;
            if (!targetBtn.classList.contains('counter-btn')) {
                targetBtn = e.target.closest('.counter-btn');
            }
            
            if (targetBtn && targetBtn.classList.contains('counter-btn')) {
                e.preventDefault();
                e.stopPropagation();
                
                const extraId = targetBtn.getAttribute('data-extra-id');
                let change = 0;
                
                if (targetBtn.classList.contains('decrease-btn')) {
                    change = -1;
                } else if (targetBtn.classList.contains('increase-btn')) {
                    change = 1;
                }
                
                
                if (extraId && change !== 0) {
                    window.updateModalExtraQuantity(extraId, change);
                } else {
                }
            }
        });
        
        // Also add direct event listeners to each button as backup
        const counterButtons = modalContent.querySelectorAll('.counter-btn');
        
        counterButtons.forEach((button, index) => {
            
            button.addEventListener('click', function(e) {
                
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                const extraId = this.getAttribute('data-extra-id');
                let change = 0;
                
                if (this.classList.contains('decrease-btn')) {
                    change = -1;
                } else if (this.classList.contains('increase-btn')) {
                    change = 1;
                }
                
                
                if (extraId && change !== 0) {
                    window.updateModalExtraQuantity(extraId, change);
                } else {
                }
            });
            
            // Also try mousedown as backup
            button.addEventListener('mousedown', function(e) {
                e.preventDefault();
                const extraId = this.getAttribute('data-extra-id');
                let change = this.classList.contains('decrease-btn') ? -1 : 1;
                if (extraId) {
                    window.updateModalExtraQuantity(extraId, change);
                }
            });
        });
        
        // Prevent label clicks from interfering with counter buttons
        const extraLabels = modalContent.querySelectorAll('.modal-extra-label');
        extraLabels.forEach(label => {
            label.addEventListener('click', function(e) {
                // If the click is on a counter button or its container, don't toggle checkbox
                if (e.target.closest('.modal-extra-quantity')) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        });
    };
    
    // Çeviri yardımcı fonksiyonu
    function __(key) {
        return window.translations[key] || key;
    }
</script>
<!-- JavaScript for Booking Form -->
<script>
  
// JavaScript for Booking Form - SMART VALIDATION
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
    
    // Progress Steps Management
    function updateProgressSteps(activeTabId) {
        const progressSteps = document.querySelectorAll('.progress-step');
        
        const tabStepMap = {
            'booking-details': 1,
            'personal-info': 2,
            'payment-info': 3
        };
        
        const currentStep = tabStepMap[activeTabId] || 1;
        
        progressSteps.forEach((step, index) => {
            const stepNumber = index + 1;
            
            step.classList.remove('active', 'completed');
            
            if (stepNumber < currentStep) {
                step.classList.add('completed');
            } else if (stepNumber === currentStep) {
                step.classList.add('active');
            }
        });
    }
    
    // Tab Functionality with Progress Updates
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    function setActiveTab(tabId) {
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
        
        updateProgressSteps(tabId);
        document.querySelector('.booking-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');
            setActiveTab(tabId);
        });
    });
    
    // Payment Method Selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    const paymentContents = document.querySelectorAll('.payment-content');

    function toggleRequiredFields(methodId) {
        const cardFields = [
            document.getElementById('card_name'),
            document.getElementById('card_number'),
            document.getElementById('card_expiry'),
            document.getElementById('card_cvv')
        ];
        
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
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
            
            paymentMethods.forEach(m => m.classList.remove('active'));
            this.classList.add('active');
            
            const methodId = this.getAttribute('data-method');
            paymentContents.forEach(content => {
                content.classList.remove('active');
                if (content.getAttribute('data-method') === methodId) {
                    content.classList.add('active');
                }
            });
            
            toggleRequiredFields(methodId);
        });
    });
    
    // Hata gösterme fonksiyonu
    function showError(inputElement, message) {
        clearError(inputElement);
        
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        errorElement.style.color = '#dc3545';
        errorElement.style.fontSize = '0.875rem';
        errorElement.style.marginTop = '0.25rem';
        
        const parentElement = inputElement.closest('.form-group') || inputElement.closest('.terms-check');
        if (parentElement) {
            parentElement.appendChild(errorElement);
            inputElement.classList.add('error-input');
            inputElement.style.borderColor = '#dc3545';
        }
    }
    
    function clearError(inputElement) {
        const parentElement = inputElement.closest('.form-group') || inputElement.closest('.terms-check');
        if (parentElement) {
            const errorElement = parentElement.querySelector('.error-message');
            if (errorElement) {
                parentElement.removeChild(errorElement);
            }
            inputElement.classList.remove('error-input');
            inputElement.style.borderColor = '';
        }
    }
    
    function clearAllErrors() {
        document.querySelectorAll('.error-message').forEach(error => error.remove());
        document.querySelectorAll('.error-input').forEach(input => {
            input.classList.remove('error-input');
            input.style.borderColor = '';
        });
    }
    
    function isValidEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }
    
    // AKILLI VALIDATION - Her tab'ı ayrı ayrı kontrol eder
    function validateAllSteps() {
        const errors = [];
        
        // 1. Booking Details Validation
        const bookingDate = document.getElementById('booking_date');
        if (!bookingDate?.value) {
            errors.push({
                tab: 'booking-details',
                element: bookingDate,
                message: __('date_required')
            });
        }
        
        // 2. Personal Info Validation
        const firstName = document.getElementById('first_name');
        const lastName = document.getElementById('last_name');
        const email = document.getElementById('booking_email');
        const phone = document.getElementById('booking_phone');
        
        if (!firstName?.value) {
            errors.push({
                tab: 'personal-info',
                element: firstName,
                message: __('first_name_required')
            });
        }
        
        if (!lastName?.value) {
            errors.push({
                tab: 'personal-info',
                element: lastName,
                message: __('last_name_required')
            });
        }
        
        if (!email?.value) {
            errors.push({
                tab: 'personal-info',
                element: email,
                message: __('email_required')
            });
        } else if (!isValidEmail(email.value)) {
            errors.push({
                tab: 'personal-info',
                element: email,
                message: __('invalid_email')
            });
        }
        
        if (!phone?.value) {
            errors.push({
                tab: 'personal-info',
                element: phone,
                message: __('phone_required')
            });
        }
        
        // 3. Payment Info Validation
        const selectedPaymentMethodElement = document.querySelector('input[name="payment_method"]:checked');
        if (!selectedPaymentMethodElement) {
            errors.push({
                tab: 'payment-info',
                element: null,
                message: 'Payment method required'
            });
        } else {
            const selectedPaymentMethod = selectedPaymentMethodElement.value;
            
            // Sadece kredi kartı seçilmişse kart alanlarını doğrula
            if (selectedPaymentMethod === 'card') {
                const cardName = document.getElementById('card_name');
                const cardNumber = document.getElementById('card_number');
                const cardExpiry = document.getElementById('card_expiry');
                const cardCvv = document.getElementById('card_cvv');
                
                if (cardName && !cardName.value) {
                    errors.push({
                        tab: 'payment-info',
                        element: cardName,
                        message: __('card_name_required')
                    });
                }
                
                if (cardNumber && !cardNumber.value) {
                    errors.push({
                        tab: 'payment-info',
                        element: cardNumber,
                        message: __('card_number_required')
                    });
                }
                
                if (cardExpiry && !cardExpiry.value) {
                    errors.push({
                        tab: 'payment-info',
                        element: cardExpiry,
                        message: __('card_expiry_required')
                    });
                }
                
                if (cardCvv && !cardCvv.value) {
                    errors.push({
                        tab: 'payment-info',
                        element: cardCvv,
                        message: __('card_cvv_required')
                    });
                }
            }
        }
        
        const termsCheck = document.getElementById('terms_check');
        if (!termsCheck?.checked) {
            errors.push({
                tab: 'payment-info',
                element: termsCheck,
                message: __('terms_required')
            });
        }
        
        return errors;
    }
    
    function validateStep(stepId) {
        clearAllErrors();
        
        if (stepId === 'booking-details') {
            const dateInput = document.getElementById('booking_date');
            if (!dateInput.value) {
                showError(dateInput, __('date_required'));
                return false;
            }
        }
        else if (stepId === 'personal-info') {
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
    
    // Next/Prev Tab Navigation
    document.querySelectorAll('.next-tab').forEach(btn => {
        btn.addEventListener('click', function() {
            const currentTabId = this.closest('.tab-pane').id.replace('-tab', '');
            
            if (validateStep(currentTabId)) {
                const nextTabId = this.getAttribute('data-next');
                setActiveTab(nextTabId);
            } else {
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
    
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function() {
            clearError(this);
        });
        
        input.addEventListener('focus', function() {
            this.classList.remove('error-input');
            this.style.borderColor = '';
        });
    });
    
    // Price Calculation & Summary
    function updateSummary() {
        const adults = parseInt(document.getElementById('booking_adults')?.value) || 1;
        const children = parseInt(document.getElementById('booking_children')?.value) || 0;
        const basePrice = parseFloat(document.getElementById('booking_base_price')?.value) || 0;
        const discountPrice = parseFloat(document.getElementById('booking_discount_price')?.value) || 0;
        const currencySymbol = document.getElementById('currency_symbol')?.value || '$';
        
        // Update summary participants
        const summaryAdults = document.getElementById('summary_adults');
        const summaryChildren = document.getElementById('summary_children');
        
        if (summaryAdults) summaryAdults.textContent = adults;
        if (summaryChildren) summaryChildren.textContent = children;
        
        // Calculate total price
        const pricePerPerson = discountPrice > 0 ? discountPrice : basePrice;
        const totalAdults = pricePerPerson * adults;
        const totalChildren = pricePerPerson * 0.5 * children;
        const totalPrice = totalAdults + totalChildren;
        
        // Update display
        const totalDisplay = document.getElementById('total_price_display');
        const totalInput = document.getElementById('booking_total_price');
        
        if (totalDisplay) totalDisplay.textContent = currencySymbol + totalPrice.toFixed(2);
        if (totalInput) totalInput.value = totalPrice.toFixed(2);
    }
    
    // Guest Counter - FIXED VERSION
    document.querySelectorAll('.counter-btn').forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.matches('.counter-btn') && !e.target.closest('.counter-btn')) {
            return;
        }
        
        e.preventDefault();
        e.stopImmediatePropagation();
        
        const button = e.target.closest('.counter-btn');
        const target = button.getAttribute('data-target');
        const input = document.getElementById(target);
        
        if (!input) return;
        
        const currentValue = parseInt(input.value) || 0;
        const min = parseInt(input.getAttribute('min')) || 0;
        const max = parseInt(input.getAttribute('max')) || 10;
        
        let newValue = currentValue;
        
        if (button.classList.contains('increase-btn')) {
            if (currentValue < max) {
                newValue = currentValue + 1;
            }
        } else if (button.classList.contains('decrease-btn')) {
            if (currentValue > min) {
                newValue = currentValue - 1;
            }
        }
        
        if (newValue !== currentValue) {
            input.value = newValue;
            setTimeout(updateSummary, 10);
        }
    }, true);
    
    // Input değişikliklerini izle
    const adultsInput = document.getElementById('booking_adults');
    const childrenInput = document.getElementById('booking_children');
    
    if (adultsInput) {
        const newAdultsInput = adultsInput.cloneNode(true);
        adultsInput.parentNode.replaceChild(newAdultsInput, adultsInput);
        
        newAdultsInput.addEventListener('input', function() {
            setTimeout(updateSummary, 10);
        });
    }
    
    if (childrenInput) {
        const newChildrenInput = childrenInput.cloneNode(true);
        childrenInput.parentNode.replaceChild(newChildrenInput, childrenInput);
        
        newChildrenInput.addEventListener('input', function() {
            setTimeout(updateSummary, 10);
        });
    }
    
    // Date Picker
    if (typeof flatpickr !== 'undefined') {
        const today = new Date();
        let initialDate = today;
        
        if (urlDate) {
            initialDate = new Date(urlDate);
        }
        
        const datePickerInstance = flatpickr("#booking_date", {
            minDate: today,
            defaultDate: initialDate,
            dateFormat: "Y-m-d",
            disableMobile: true,
            altInput: true,
            altFormat: "F j, Y",
            
            onReady: function(selectedDates, dateStr, instance) {
                if (urlDate) {
                    const summaryDate = document.getElementById('summary_date');
                    if (summaryDate) {
                        summaryDate.textContent = instance.formatDate(new Date(urlDate), "F j, Y");
                    }
                }
            },
            
            onChange: function(selectedDates, dateStr) {
                const summaryDate = document.getElementById('summary_date');
                if (summaryDate) {
                    summaryDate.textContent = dateStr;
                }
                updateSummary();
                clearError(document.getElementById('booking_date'));
            }
        });
        
        if (urlDate) {
            datePickerInstance.setDate(urlDate);
            const summaryDate = document.getElementById('summary_date');
            if (summaryDate) {
                summaryDate.textContent = datePickerInstance.formatDate(new Date(urlDate), "F j, Y");
            }
        }
        
        setTimeout(function() {
            if (urlDate) {
                const urlDateObj = new Date(urlDate);
                datePickerInstance.changeMonth(urlDateObj.getMonth());
            } else {
                const currentDate = new Date();
                datePickerInstance.changeMonth(currentDate.getMonth());
            }
        }, 100);
    }
    
    // URL parametrelerini ayarla
    if (urlAdults) {
        const adultsInputFresh = document.getElementById('booking_adults');
        if (adultsInputFresh) {
            adultsInputFresh.value = parseInt(urlAdults);
            const summaryAdults = document.getElementById('summary_adults');
            if (summaryAdults) summaryAdults.textContent = urlAdults;
        }
    }
    
    if (urlChildren) {
        const childrenInputFresh = document.getElementById('booking_children');
        if (childrenInputFresh) {
            childrenInputFresh.value = parseInt(urlChildren);
            const summaryChildren = document.getElementById('summary_children');
            if (summaryChildren) summaryChildren.textContent = urlChildren;
        }
    }
    
    // Accordion
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const accordionItem = this.parentElement;
            accordionItem.classList.toggle('active');
        });
    });
    
    // Initial summary update
    updateSummary();
    
    // AKILLI FORM VALIDATION - HTML5 validation'ını devre dışı bırak
    const bookingForm = document.getElementById('booking_form');
    if (bookingForm) {
        // HTML5 validation'ını kapat
        bookingForm.setAttribute('novalidate', 'true');
        
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            
            // Tüm adımları kontrol et
            const allErrors = validateAllSteps();
            
            if (allErrors.length > 0) {
                
                // İlk hatanın bulunduğu tab'ı belirle
                const firstError = allErrors[0];
                
                // O tab'a geç
                setActiveTab(firstError.tab);
                
                // Hataları göster
                clearAllErrors();
                allErrors.forEach(error => {
                    if (error.element) {
                        showError(error.element, error.message);
                    }
                });
                
                // İlk hatalı elemente odaklan
                setTimeout(() => {
                    if (firstError.element) {
                        firstError.element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.element.focus();
                    }
                }, 300);
                
                return false;
            }
            
            
            // Tüm validasyonlar geçildiyse formu gönder
            // HTML5 validation'ını geçici olarak tekrar aç
            bookingForm.removeAttribute('novalidate');
            this.submit();
        });
    }
    
    // Sayfa initialization
    function initializePage() {
        setActiveTab('booking-details');
        
        const defaultPaymentMethod = document.querySelector('.payment-method.active');
        if (defaultPaymentMethod) {
            const methodId = defaultPaymentMethod.getAttribute('data-method');
            toggleRequiredFields(methodId);
        }
        
        if (urlDate || urlAdults || urlChildren) {
            highlightPreselectedOptions();
        }
    }
    
    function highlightPreselectedOptions() {
        if (urlDate) {
            const dateInput = document.getElementById('booking_date');
            if (dateInput) {
                dateInput.classList.add('preselected');
                dateInput.parentElement.classList.add('highlight-preselected');
            }
        }
        
        if (urlAdults) {
            const adultsContainer = document.getElementById('booking_adults')?.closest('.guest-type');
            if (adultsContainer) {
                adultsContainer.classList.add('highlight-preselected');
            }
        }
        
        if (urlChildren && parseInt(urlChildren) > 0) {
            const childrenContainer = document.getElementById('booking_children')?.closest('.guest-type');
            if (childrenContainer) {
                childrenContainer.classList.add('highlight-preselected');
            }
        }
    }
    
    // Load pre-selected extras from server-side data
    function loadExtrasFromURL() {
        // Load pre-selected extras from server-side
        <?php if (!empty($preSelectedExtras)): ?>
        const preSelectedExtras = <?php echo json_encode($preSelectedExtras); ?>;
        
        Object.keys(preSelectedExtras).forEach(extraId => {
            window.selectedExtras[extraId] = {
                quantity: preSelectedExtras[extraId].quantity || 1,
                name: preSelectedExtras[extraId].name,
                price: preSelectedExtras[extraId].base_price
            };
        });
        
        updateExtrasDisplay();
        updateSummary();
        <?php else: ?>
        <?php endif; ?>
    }
    
    // Load available extras for the tour from server-side data
    function loadAvailableExtras() {
        // Use server-side data directly and assign to global window variable
        <?php if (!empty($availableExtras)): ?>
        window.availableExtras = <?php echo json_encode($availableExtras); ?>;
        <?php else: ?>
        window.availableExtras = [];
        <?php endif; ?>
        
        return Promise.resolve();
    }
    
    function loadExtrasModal() {
        // Use the global loadExtrasModal function
        if (typeof window.loadExtrasModal === 'function') {
            window.loadExtrasModal();
        }
    }
    
    window.updateModalExtraPrices = function() {
        const adults = parseInt(document.getElementById('booking_adults').value) || 2;
        const children = parseInt(document.getElementById('booking_children').value) || 0;
        const totalPersons = adults + children;
        const currencySymbol = document.getElementById('currency_symbol').value;
        
        if (!window.availableExtras) return;
        
        window.availableExtras.forEach(extra => {
            const priceElement = document.getElementById(`modal_extra_price_${extra.id}`);
            if (priceElement) {
                const quantity = window.selectedExtras[extra.id] ? window.selectedExtras[extra.id].quantity : 1;
                
                let price = parseFloat(extra.base_price || 0);
                if (extra.pricing && extra.pricing.length > 0) {
                    let applicableTier = null;
                    for (let tier of extra.pricing) {
                        if (totalPersons >= tier.persons) {
                            applicableTier = tier;
                        } else {
                            break;
                        }
                    }
                    if (applicableTier) {
                        price = parseFloat(applicableTier.price_per_person) * totalPersons;
                    }
                }
                
                priceElement.textContent = `${currencySymbol}${(price * quantity).toFixed(2)}`;
            }
        });
    };
    
    window.updateExtrasDisplay = function() {
        const extrasSection = document.getElementById('extras_summary_section');
        const extrasList = document.getElementById('extras_summary_list');
        const buttonText = document.getElementById('extras_button_text');
        
        if (!window.selectedExtras || Object.keys(window.selectedExtras).length === 0) {
            extrasList.innerHTML = '<div class="no-extras-message">' + window.translations.no_extras_selected + '</div>';
            if (buttonText) buttonText.textContent = window.translations.add_extras;
            return;
        }
        
        if (buttonText) buttonText.textContent = window.translations.modify_extras;
        
        const adults = parseInt(document.getElementById('booking_adults').value) || 2;
        const children = parseInt(document.getElementById('booking_children').value) || 0;
        const totalPersons = adults + children;
        const currencySymbol = document.getElementById('currency_symbol').value;
        
        let html = '';
        Object.keys(window.selectedExtras).forEach(extraId => {
            const extra = window.availableExtras.find(e => e.id == extraId);
            if (extra) {
                const quantity = window.selectedExtras[extraId].quantity;
                
                let price = parseFloat(extra.base_price || 0);
                if (extra.pricing && extra.pricing.length > 0) {
                    let applicableTier = null;
                    for (let tier of extra.pricing) {
                        if (totalPersons >= tier.persons) {
                            applicableTier = tier;
                        } else {
                            break;
                        }
                    }
                    if (applicableTier) {
                        price = parseFloat(applicableTier.price_per_person) * totalPersons;
                    }
                }
                
                const totalPrice = price * quantity;
                
                html += `
                    <div class="extras-summary-item">
                        <span class="extras-summary-name">${extra.name}</span>
                        <span class="extras-summary-quantity">×${quantity}</span>
                        <span class="extras-summary-price">${currencySymbol}${totalPrice.toFixed(2)}</span>
                    </div>
                `;
            }
        });
        
        extrasList.innerHTML = html;
    };
    
    // Update the main updateSummary function to include extras
    const originalUpdateSummary = updateSummary;
    updateSummary = function() {
        originalUpdateSummary();
        
        // Add extras to total
        const adults = parseInt(document.getElementById('booking_adults').value) || 2;
        const children = parseInt(document.getElementById('booking_children').value) || 0;
        const totalPersons = adults + children;
        
        // Get prices from hidden inputs
        const basePrice = parseFloat(document.getElementById('booking_base_price').value) || 0;
        const discountPrice = parseFloat(document.getElementById('booking_discount_price').value) || 0;
        const currencySymbol = document.getElementById('currency_symbol').value || '$';
        const pricePerPerson = discountPrice > 0 ? discountPrice : basePrice;
        const tourTotal = (pricePerPerson * adults) + (pricePerPerson * 0.5 * children);
        
        let extrasTotal = 0;
        if (window.selectedExtras && window.availableExtras) {
            Object.keys(window.selectedExtras).forEach(extraId => {
                const extra = window.availableExtras.find(e => e.id == extraId);
                if (extra) {
                    const quantity = window.selectedExtras[extraId].quantity;
                    
                    let price = parseFloat(extra.base_price || 0);
                    if (extra.pricing && extra.pricing.length > 0) {
                        let applicableTier = null;
                        for (let tier of extra.pricing) {
                            if (totalPersons >= tier.persons) {
                                applicableTier = tier;
                            } else {
                                break;
                            }
                        }
                        if (applicableTier) {
                            price = parseFloat(applicableTier.price_per_person) * totalPersons;
                        }
                    }
                    
                    extrasTotal += price * quantity;
                }
            });
        }
        
        const finalTotal = tourTotal + extrasTotal;
        
        const totalDisplay = document.getElementById('total_price_display');
        const totalInput = document.getElementById('booking_total_price');
        const extrasInput = document.getElementById('booking_extras_total');
        
        if (totalDisplay) totalDisplay.textContent = currencySymbol + finalTotal.toFixed(2);
        if (totalInput) totalInput.value = finalTotal.toFixed(2);
        if (extrasInput) extrasInput.value = extrasTotal.toFixed(2);
        
        updateExtrasDisplay();
    };
    
    // Initialize globals
    if (!window.selectedExtras) {
        window.selectedExtras = {};
    }
    if (!window.availableExtras) {
        window.availableExtras = [];
    }
    
    // Initialize everything
    initializePage();
    loadAvailableExtras().then(() => {
        loadExtrasFromURL();
        // Initial update after everything is loaded
        updateSummary();
    });
});
</script>