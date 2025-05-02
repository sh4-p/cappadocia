<?php
/**
 * Booking Form View
 */
?>

<!-- Breadcrumbs -->
<div class="breadcrumbs">
    <div class="container">
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
</div>

<!-- Booking Section -->
<section class="section booking-section">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h1 class="section-title"><?php echo sprintf(__('book_tour_title'), $tour['name']); ?></h1>
            <p class="section-subtitle"><?php _e('book_tour_subtitle'); ?></p>
        </div>
        
        <div class="booking-wrapper">
            <div class="booking-content" data-aos="fade-up">
                <div class="booking-tour-info">
                    <div class="booking-tour-image">
                        <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                    </div>
                    <div class="booking-tour-details">
                        <h3 class="booking-tour-title"><?php echo $tour['name']; ?></h3>
                        
                        <div class="booking-tour-meta">
                            <?php if ($tour['duration']): ?>
                                <div class="booking-meta-item">
                                    <i class="material-icons">schedule</i>
                                    <span><?php echo $tour['duration']; ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($tour['category_name']): ?>
                                <div class="booking-meta-item">
                                    <i class="material-icons">category</i>
                                    <span><?php echo $tour['category_name']; ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="booking-tour-price">
                            <?php if ($tour['discount_price']): ?>
                                <div class="price-old"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></div>
                                <div class="price"><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></div>
                            <?php else: ?>
                                <div class="price"><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></div>
                            <?php endif; ?>
                            <div class="price-label"><?php _e('per_person'); ?></div>
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
                    
                    <div class="form-section">
                        <h4 class="form-section-title"><?php _e('booking_details'); ?></h4>
                        
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="booking_date" class="form-label"><?php _e('booking_date'); ?> <span class="required">*</span></label>
                                <input type="text" id="booking_date" name="booking_date" class="form-control datepicker" required
                                       data-min-date="today" 
                                       data-disabled-dates="<?php echo htmlspecialchars(json_encode($availableDates), ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="form-text"><?php _e('booking_date_help'); ?></div>
                            </div>
                            
                            <div class="form-group col-6">
                                <label class="form-label"><?php _e('participants'); ?> <span class="required">*</span></label>
                                <div class="guests-wrapper">
                                    <div class="guest-type">
                                        <div class="guest-label">
                                            <div class="guest-title"><?php _e('adults'); ?></div>
                                            <div class="guest-subtitle"><?php _e('age_12_plus'); ?></div>
                                        </div>
                                        <div class="guest-counter">
                                            <button type="button" class="counter-btn decrease-btn">
                                                <i class="material-icons">remove</i>
                                            </button>
                                            <input type="number" id="booking_adults" name="adults" value="2" min="1" max="10" required>
                                            <button type="button" class="counter-btn increase-btn">
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
                                            <button type="button" class="counter-btn decrease-btn">
                                                <i class="material-icons">remove</i>
                                            </button>
                                            <input type="number" id="booking_children" name="children" value="0" min="0" max="10">
                                            <button type="button" class="counter-btn increase-btn">
                                                <i class="material-icons">add</i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h4 class="form-section-title"><?php _e('personal_information'); ?></h4>
                        
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="first_name" class="form-label"><?php _e('first_name'); ?> <span class="required">*</span></label>
                                <input type="text" id="first_name" name="first_name" class="form-control" required>
                            </div>
                            
                            <div class="form-group col-6">
                                <label for="last_name" class="form-label"><?php _e('last_name'); ?> <span class="required">*</span></label>
                                <input type="text" id="last_name" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group col-6">
                                <label for="booking_email" class="form-label"><?php _e('email'); ?> <span class="required">*</span></label>
                                <input type="email" id="booking_email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group col-6">
                                <label for="booking_phone" class="form-label"><?php _e('phone'); ?> <span class="required">*</span></label>
                                <input type="tel" id="booking_phone" name="phone" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="special_requests" class="form-label"><?php _e('special_requests'); ?></label>
                            <textarea id="special_requests" name="special_requests" class="form-control" rows="4"></textarea>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h4 class="form-section-title"><?php _e('payment_method'); ?></h4>
                        
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
                                        <label for="card_name" class="form-label"><?php _e('card_name'); ?></label>
                                        <input type="text" id="card_name" name="card_name" class="form-control">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <label for="card_number" class="form-label"><?php _e('card_number'); ?></label>
                                        <input type="text" id="card_number" name="card_number" class="form-control" placeholder="XXXX XXXX XXXX XXXX">
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <label for="card_expiry" class="form-label"><?php _e('card_expiry'); ?></label>
                                        <input type="text" id="card_expiry" name="card_expiry" class="form-control" placeholder="MM/YY">
                                    </div>
                                    
                                    <div class="form-group col-6">
                                        <label for="card_cvv" class="form-label"><?php _e('card_cvv'); ?></label>
                                        <input type="text" id="card_cvv" name="card_cvv" class="form-control" placeholder="CVV">
                                    </div>
                                </div>
                                
                                <div class="payment-disclaimer">
                                    <i class="material-icons">lock</i>
                                    <span><?php _e('payment_card_disclaimer'); ?></span>
                                </div>
                            </div>
                            
                            <div class="payment-content" data-method="paypal">
                                <div class="payment-info">
                                    <p><?php _e('paypal_info'); ?></p>
                                </div>
                            </div>
                            
                            <div class="payment-content" data-method="bank">
                                <div class="payment-info">
                                    <p><?php _e('bank_info'); ?></p>
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
                    </div>
                    
                    <div class="form-section booking-summary">
                        <h4 class="form-section-title"><?php _e('booking_summary'); ?></h4>
                        
                        <div class="booking-summary-content">
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
                        
                        <div class="form-check terms-check">
                            <input type="checkbox" id="terms_check" name="terms_check" required>
                            <label for="terms_check"><?php echo sprintf(__('terms_agreement'), '<a href="' . $appUrl . '/' . $currentLang . '/page/terms" target="_blank">' . __('terms_and_conditions') . '</a>'); ?></label>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="material-icons">check_circle</i>
                            <?php _e('confirm_booking'); ?>
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="booking-sidebar" data-aos="fade-up" data-aos-delay="200">
                <div class="booking-help glass-card">
                    <h4 class="booking-help-title"><?php _e('need_help'); ?></h4>
                    <p><?php _e('booking_help_text'); ?></p>
                    
                    <div class="booking-help-contact">
                        <div class="contact-item">
                            <i class="material-icons">phone</i>
                            <span><?php echo $settings['contact_phone']; ?></span>
                        </div>
                        <div class="contact-item">
                            <i class="material-icons">email</i>
                            <span><?php echo $settings['contact_email']; ?></span>
                        </div>
                    </div>
                    
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-glass">
                        <i class="material-icons">message</i>
                        <?php _e('contact_us'); ?>
                    </a>
                </div>
                
                <div class="booking-faqs">
                    <h4 class="booking-faqs-title"><?php _e('frequently_asked_questions'); ?></h4>
                    
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
    /* Booking Styles */
    .booking-wrapper {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: var(--spacing-xl);
    }
    
    /* Tour Info */
    .booking-tour-info {
        display: flex;
        margin-bottom: var(--spacing-lg);
        background-color: var(--white-color);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    
    .booking-tour-image {
        width: 200px;
        height: 200px;
    }
    
    .booking-tour-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .booking-tour-details {
        flex: 1;
        padding: var(--spacing-lg);
        display: flex;
        flex-direction: column;
    }
    
    .booking-tour-title {
        margin-bottom: var(--spacing-sm);
        font-size: var(--font-size-xl);
    }
    
    .booking-tour-meta {
        display: flex;
        margin-bottom: var(--spacing-md);
        gap: 1rem;
    }
    
    .booking-meta-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--gray-600);
    }
    
    .booking-meta-item i {
        font-size: 1.25rem;
        color: var(--primary-color);
    }
    
    .booking-tour-price {
        margin-top: auto;
        text-align: right;
    }
    
    .booking-tour-price .price {
        font-size: var(--font-size-xl);
        font-weight: var(--font-weight-bold);
        color: var(--primary-color);
    }
    
    .booking-tour-price .price-old {
        text-decoration: line-through;
        color: var(--gray-600);
    }
    
    .booking-tour-price .price-label {
        font-size: var(--font-size-sm);
        color: var(--gray-600);
    }
    
    /* Booking Form */
    .booking-form {
        background-color: var(--white-color);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        padding: var(--spacing-lg);
    }
    
    .form-section {
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 1px solid var(--gray-200);
    }
    
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    .form-section-title {
        margin-bottom: var(--spacing-md);
        color: var(--dark-color);
    }
    
    .form-row {
        display: flex;
        margin: 0 -10px;
        flex-wrap: wrap;
    }
    
    .form-row .form-group {
        padding: 0 10px;
        margin-bottom: var(--spacing-md);
    }
    
    .form-row .col-12 {
        flex: 0 0 100%;
        max-width: 100%;
    }
    
    .form-row .col-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    .required {
        color: var(--danger-color);
    }
    
    .form-text {
        font-size: var(--font-size-sm);
        color: var(--gray-600);
        margin-top: 0.25rem;
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
        background-color: var(--gray-100);
        border-radius: var(--border-radius-md);
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
        background-color: var(--gray-100);
        border-radius: var(--border-radius-md);
    }
    
    .payment-content.active {
        display: block;
    }
    
    .payment-info {
        font-size: var(--font-size-sm);
        color: var(--gray-700);
    }
    
    .bank-details {
        margin-top: var(--spacing-md);
        background-color: var(--white-color);
        border-radius: var(--border-radius-md);
        padding: var(--spacing-md);
    }
    
    .bank-detail {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.5rem 0;
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
        font-size: var(--font-size-sm);
    }
    
    /* Booking Summary */
    .booking-summary-content {
        background-color: var(--gray-100);
        border-radius: var(--border-radius-md);
        padding: var(--spacing-md);
        margin-bottom: var(--spacing-md);
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
    }
    
    .summary-item.total .price {
        color: var(--primary-color);
    }
    
    .terms-check {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: var(--spacing-md);
    }
    
    .terms-check label {
        font-size: var(--font-size-sm);
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: center;
    }
    
    /* Booking Sidebar */
    .booking-help {
        color: var(--white-color);
        margin-bottom: var(--spacing-xl);
    }
    
    .booking-help-title {
        margin-bottom: var(--spacing-md);
    }
    
    .booking-help-contact {
        margin: var(--spacing-md) 0;
    }
    
    .contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .booking-faqs-title {
        margin-bottom: var(--spacing-md);
    }
    
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
        
        .booking-sidebar {
            order: -1;
        }
    }
    
    @media (max-width: 768px) {
        .booking-tour-info {
            flex-direction: column;
        }
        
        .booking-tour-image {
            width: 100%;
            height: 200px;
        }
        
        .form-row .col-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>