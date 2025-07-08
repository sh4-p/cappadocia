<?php
/**
 * Booking Thank You/Confirmation View - Updated Messages
 */

// Hero arka plan görseli
$confirmationHeroBg = isset($settings['confirmation_hero_bg']) ? $settings['confirmation_hero_bg'] : 'booking-hero-bg.jpg';
?>

<!-- Hero Section -->
<section class="hero-section confirmation-hero" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $confirmationHeroBg; ?>');">
    <div class="overlay"></div>
    <div class="container">
        <div class="hero-content text-center">
            <!-- Success Icon -->
            <div class="success-icon-wrapper" data-aos="zoom-in">
                <div class="success-icon">
                    <i class="material-icons">receipt</i>
                </div>
            </div>
            
            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200"><?php _e('booking_received'); ?></h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="300"><?php _e('booking_received_message'); ?></p>
            
            <!-- Booking ID -->
            <div class="booking-id-badge" data-aos="fade-up" data-aos-delay="400">
                <span class="booking-id-label"><?php _e('booking_reference'); ?>:</span>
                <span class="booking-id-number">#<?php echo str_pad($bookingId, 6, '0', STR_PAD_LEFT); ?></span>
            </div>
            
            <!-- Tracking Link Notice -->
            <div class="tracking-notice" data-aos="fade-up" data-aos-delay="500">
                <i class="material-icons">bookmark</i>
                <p><?php _e('save_tracking_link_message'); ?></p>
                <div class="tracking-link-box">
                    <input type="text" id="tracking-link" readonly value="<?php echo $appUrl . '/' . $currentLang . '/booking/track/' . $bookingData['tracking_token']; ?>">
                    <button type="button" id="copy-link" class="btn-copy">
                        <i class="material-icons">content_copy</i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Confirmation Details Section -->
<section class="section confirmation-section">
    <div class="container">
        <div class="confirmation-wrapper" data-aos="fade-up">
            <div class="confirmation-content">
                <!-- Booking Summary Card -->
                <div class="confirmation-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="material-icons">receipt</i>
                            <?php _e('booking_summary'); ?>
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <!-- Status Alert -->
                        <div class="status-alert">
                            <div class="alert alert-info">
                                <i class="material-icons">info</i>
                                <div class="alert-content">
                                    <h4><?php _e('booking_pending_title'); ?></h4>
                                    <p><?php _e('booking_pending_message'); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="booking-info">
                            <!-- Tour Information -->
                            <div class="info-section">
                                <h4 class="info-title"><?php _e('tour_information'); ?></h4>
                                <div class="info-details">
                                    <div class="info-item">
                                        <div class="info-label"><?php _e('tour_name'); ?></div>
                                        <div class="info-value"><?php echo htmlspecialchars($bookingData['tour_name']); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label"><?php _e('booking_date'); ?></div>
                                        <div class="info-value"><?php echo date('F j, Y', strtotime($bookingData['booking_date'])); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label"><?php _e('participants'); ?></div>
                                        <div class="info-value">
                                            <?php echo $bookingData['adults']; ?> <?php _e('adults'); ?>
                                            <?php if ($bookingData['children'] > 0): ?>
                                                , <?php echo $bookingData['children']; ?> <?php _e('children'); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Customer Information -->
                            <div class="info-section">
                                <h4 class="info-title"><?php _e('customer_information'); ?></h4>
                                <div class="info-details">
                                    <div class="info-item">
                                        <div class="info-label"><?php _e('name'); ?></div>
                                        <div class="info-value"><?php echo htmlspecialchars($bookingData['first_name'] . ' ' . $bookingData['last_name']); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label"><?php _e('email'); ?></div>
                                        <div class="info-value"><?php echo htmlspecialchars($bookingData['email']); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label"><?php _e('phone'); ?></div>
                                        <div class="info-value"><?php echo htmlspecialchars($bookingData['phone']); ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Payment Information -->
                            <div class="info-section">
                                <h4 class="info-title"><?php _e('payment_information'); ?></h4>
                                <div class="info-details">
                                    <div class="info-item">
                                        <div class="info-label"><?php _e('payment_method'); ?></div>
                                        <div class="info-value">
                                            <div class="payment-method-display">
                                                <?php
                                                $paymentMethodIcons = [
                                                    'card' => 'credit_card',
                                                    'paypal' => 'account_balance_wallet',
                                                    'bank' => 'account_balance',
                                                    'cash' => 'money'
                                                ];
                                                
                                                $paymentMethodNames = [
                                                    'card' => __('credit_card'),
                                                    'paypal' => __('paypal'),
                                                    'bank' => __('bank_transfer'),
                                                    'cash' => __('cash_payment')
                                                ];
                                                
                                                $paymentMethod = $bookingData['payment_method'] ?? 'card';
                                                $icon = $paymentMethodIcons[$paymentMethod] ?? 'payment';
                                                $name = $paymentMethodNames[$paymentMethod] ?? $paymentMethod;
                                                ?>
                                                <i class="material-icons"><?php echo $icon; ?></i>
                                                <span><?php echo $name; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Extras Section -->
                                    <?php if (!empty($bookingData['extras_data'])): ?>
                                        <?php 
                                        $extrasData = json_decode($bookingData['extras_data'], true);
                                        if ($extrasData && is_array($extrasData) && count($extrasData) > 0):
                                        ?>
                                            <div class="info-item extras-section">
                                                <div class="info-label"><?php _e('extras'); ?></div>
                                                <div class="info-value">
                                                    <div class="extras-list">
                                                        <?php 
                                                        $extrasTotal = 0;
                                                        foreach ($extrasData as $extraId => $extraInfo): 
                                                            // Load extra details (you might need to get this from database)
                                                            // For now, we'll use the stored data or display basic info
                                                            $quantity = $extraInfo['quantity'] ?? 1;
                                                            $extraName = $extraInfo['name'] ?? "Extra #$extraId";
                                                            $extraPrice = $extraInfo['price'] ?? 0;
                                                            $extraTotal = $extraPrice * $quantity;
                                                            $extrasTotal += $extraTotal;
                                                        ?>
                                                            <div class="extra-item">
                                                                <span class="extra-name"><?php echo htmlspecialchars($extraName); ?></span>
                                                                <span class="extra-quantity">×<?php echo $quantity; ?></span>
                                                                <span class="extra-price"><?php echo $settings['currency_symbol'] ?? '€'; ?><?php echo number_format($extraTotal, 2); ?></span>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <div class="info-item total">
                                        <div class="info-label"><?php _e('total_amount'); ?></div>
                                        <div class="info-value price"><?php echo $settings['currency_symbol'] ?? '€'; ?><?php echo number_format($bookingData['total_price'], 2); ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label"><?php _e('booking_status'); ?></div>
                                        <div class="info-value">
                                            <span class="status-badge status-pending"><?php _e('awaiting_confirmation'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Special Requests -->
                            <?php if (!empty($bookingData['special_requests'])): ?>
                                <div class="info-section">
                                    <h4 class="info-title"><?php _e('special_requests'); ?></h4>
                                    <div class="info-details">
                                        <div class="special-requests">
                                            <?php echo nl2br(htmlspecialchars($bookingData['special_requests'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Next Steps Card -->
                <div class="confirmation-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="material-icons">schedule</i>
                            <?php _e('what_happens_next'); ?>
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div class="steps-list">
                            <div class="step-item">
                                <div class="step-icon completed">
                                    <i class="material-icons">check</i>
                                </div>
                                <div class="step-content">
                                    <h5><?php _e('booking_received_step'); ?></h5>
                                    <p><?php _e('booking_received_step_text'); ?></p>
                                </div>
                            </div>
                            
                            <div class="step-item">
                                <div class="step-icon pending">
                                    <i class="material-icons">email</i>
                                </div>
                                <div class="step-content">
                                    <h5><?php _e('team_review_step'); ?></h5>
                                    <p><?php _e('team_review_step_text'); ?></p>
                                </div>
                            </div>
                            
                            <div class="step-item">
                                <div class="step-icon pending">
                                    <i class="material-icons">person</i>
                                </div>
                                <div class="step-content">
                                    <h5><?php _e('confirmation_contact_step'); ?></h5>
                                    <p><?php _e('confirmation_contact_step_text'); ?></p>
                                </div>
                            </div>
                            
                            <!-- Payment-specific steps -->
                            <?php if ($bookingData['payment_method'] === 'bank'): ?>
                                <div class="step-item">
                                    <div class="step-icon pending">
                                        <i class="material-icons">account_balance</i>
                                    </div>
                                    <div class="step-content">
                                        <h5><?php _e('bank_transfer_instructions'); ?></h5>
                                        <p><?php _e('bank_transfer_next_step_text'); ?></p>
                                    </div>
                                </div>
                            <?php elseif ($bookingData['payment_method'] === 'cash'): ?>
                                <div class="step-item">
                                    <div class="step-icon pending">
                                        <i class="material-icons">money</i>
                                    </div>
                                    <div class="step-content">
                                        <h5><?php _e('cash_payment_instructions'); ?></h5>
                                        <p><?php _e('cash_payment_next_step_text'); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="step-item">
                                <div class="step-icon pending">
                                    <i class="material-icons">explore</i>
                                </div>
                                <div class="step-content">
                                    <h5><?php _e('enjoy_tour'); ?></h5>
                                    <p><?php _e('enjoy_tour_text'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="confirmation-sidebar">
                <!-- Tracking Card -->
                <div class="sidebar-card highlight-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">track_changes</i>
                        <?php _e('track_your_booking'); ?>
                    </h4>
                    
                    <p><?php _e('track_booking_text'); ?></p>
                    
                    <div class="tracking-info">
                        <div class="tracking-reference">
                            <label><?php _e('your_reference'); ?>:</label>
                            <span class="reference-number">#<?php echo str_pad($bookingId, 6, '0', STR_PAD_LEFT); ?></span>
                        </div>
                        
                        <a href="<?php echo $appUrl . '/' . $currentLang . '/booking/track/' . $bookingData['tracking_token']; ?>" class="btn btn-primary btn-block tracking-btn">
                            <i class="material-icons">visibility</i>
                            <?php _e('view_booking_status'); ?>
                        </a>
                        
                        <div class="tracking-note">
                            <i class="material-icons">info</i>
                            <small><?php _e('bookmark_this_page'); ?></small>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Card -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">support_agent</i>
                        <?php _e('need_help'); ?>
                    </h4>
                    
                    <p><?php _e('booking_questions_text'); ?></p>
                    
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="material-icons">phone</i>
                            <div class="contact-details">
                                <div class="contact-label"><?php _e('phone'); ?></div>
                                <div class="contact-value"><?php echo $settings['contact_phone'] ?? '+90 123 456 7890'; ?></div>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="material-icons">email</i>
                            <div class="contact-details">
                                <div class="contact-label"><?php _e('email'); ?></div>
                                <div class="contact-value"><?php echo $settings['contact_email'] ?? 'info@cappadocia-travel.com'; ?></div>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <i class="material-icons">schedule</i>
                            <div class="contact-details">
                                <div class="contact-label"><?php _e('working_hours'); ?></div>
                                <div class="contact-value"><?php echo $settings['working_hours'] ?? '09:00 - 18:00'; ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-outline btn-block">
                        <i class="material-icons">message</i>
                        <?php _e('contact_us'); ?>
                    </a>
                </div>
                
                <!-- Actions Card -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">more_horiz</i>
                        <?php _e('quick_actions'); ?>
                    </h4>
                    
                    <div class="action-buttons">
                        <a href="javascript:window.print()" class="btn btn-outline btn-block">
                            <i class="material-icons">print</i>
                            <?php _e('print_confirmation'); ?>
                        </a>
                        
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-block">
                            <i class="material-icons">explore</i>
                            <?php _e('browse_more_tours'); ?>
                        </a>
                        
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>" class="btn btn-outline btn-block">
                            <i class="material-icons">home</i>
                            <?php _e('back_to_home'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.root:{
    --success-color: #28a745;
}

/* Updated styles for new elements */
.tracking-notice {
    background-color: rgba(255, 255, 255, 0.15);
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-lg);
    margin-top: var(--spacing-lg);
    backdrop-filter: blur(10px);
    text-align: center;
}

.tracking-notice i {
    font-size: 2rem;
    margin-bottom: 0.5rem;
    color: var(--white-color);
}

.tracking-notice p {
    margin-bottom: var(--spacing-md);
    color: rgba(255, 255, 255, 0.9);
}

.tracking-link-box {
    display: flex;
    gap: 0.5rem;
    max-width: 500px;
    margin: 0 auto;
}

.tracking-link-box input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius-md);
    background-color: rgba(255, 255, 255, 0.1);
    color: var(--white-color);
    font-size: var(--font-size-sm);
    backdrop-filter: blur(5px);
}

.btn-copy {
    padding: 0.75rem;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: var(--border-radius-md);
    background-color: rgba(255, 255, 255, 0.2);
    color: var(--white-color);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.btn-copy:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

.status-alert {
    margin-bottom: var(--spacing-lg);
}

.alert {
    display: flex;
    align-items: flex-start;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    border-left: 4px solid;
}

.alert-info {
    background-color: rgba(67, 97, 238, 0.1);
    border-left-color: var(--primary-color);
}

.alert i {
    color: var(--primary-color);
    font-size: 1.5rem;
    margin-top: 0.25rem;
}

.alert-content h4 {
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.alert-content p {
    margin: 0;
    color: var(--gray-700);
}

.step-icon.completed {
    background-color: var(--success-color);
    color: var(--white-color);
}

.step-icon.pending {
    background-color: var(--gray-300);
    color: var(--gray-600);
}

.highlight-card {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05));
    border: 2px solid var(--primary-color);
}

.tracking-info {
    margin: var(--spacing-md) 0;
}

.tracking-reference {
    background-color: var(--gray-100);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    text-align: center;
    margin-bottom: var(--spacing-md);
}

.tracking-reference label {
    display: block;
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    margin-bottom: 0.25rem;
}

.reference-number {
    font-family: monospace;
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    color: var(--primary-color);
}

.tracking-btn {
    margin: var(--spacing-md) 0;
}

.tracking-note {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-align: center;
    justify-content: center;
    color: var(--gray-600);
}

.tracking-note i {
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .tracking-link-box {
        flex-direction: column;
    }
}
/* Confirmation Hero */
.confirmation-hero {
    height: 120vh;
    min-height: 500px;
    display: flex;
    align-items: center;
    position: relative;
    background-position: center;
    background-size: cover;
    background-attachment: fixed;
    color: var(--white-color);
}

.confirmation-hero .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(rgba(40, 167, 69, 0.8), rgba(40, 167, 69, 0.9));
    z-index: 1;
}

.confirmation-hero .container {
    position: relative;
    z-index: 2;
}

.success-icon-wrapper {
    margin-bottom: var(--spacing-lg);
}

.success-icon {
    width: 100px;
    height: 100px;
    border-radius: var(--border-radius-circle);
    background-color: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    backdrop-filter: blur(10px);
}

.success-icon i {
    font-size: 3rem;
    color: var(--white-color);
}

.booking-id-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    background-color: rgba(255, 255, 255, 0.15);
    border-radius: var(--border-radius-lg);
    backdrop-filter: blur(10px);
    margin-top: var(--spacing-lg);
}

.booking-id-label {
    font-size: var(--font-size-md);
    color: rgba(255, 255, 255, 0.9);
}

.booking-id-number {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    color: var(--white-color);
    font-family: monospace;
}

/* Confirmation Section */
.confirmation-section {
    padding: var(--spacing-xl) 0;
    background-color: var(--gray-50);
}

.confirmation-wrapper {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--spacing-xl);
}

.confirmation-card {
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
    margin-bottom: var(--spacing-lg);
}

.card-header {
    padding: var(--spacing-lg);
    background-color: var(--gray-100);
    border-bottom: 1px solid var(--gray-200);
}

.card-title {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 0;
    font-size: var(--font-size-lg);
    color: var(--dark-color);
}

.card-title i {
    color: var(--primary-color);
}

.card-body {
    padding: var(--spacing-lg);
}

/* Booking Info */
.info-section {
    margin-bottom: var(--spacing-xl);
}

.info-section:last-child {
    margin-bottom: 0;
}

.info-title {
    font-size: var(--font-size-md);
    font-weight: var(--font-weight-bold);
    color: var(--dark-color);
    margin-bottom: var(--spacing-md);
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
}

.info-details {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: var(--spacing-sm) 0;
    border-bottom: 1px solid var(--gray-200);
}

.info-item:last-child {
    border-bottom: none;
}

.info-item.total {
    font-weight: var(--font-weight-bold);
    font-size: var(--font-size-lg);
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 2px solid var(--gray-300);
    border-bottom: none;
}

.info-label {
    font-weight: var(--font-weight-medium);
    color: var(--gray-600);
    flex: 0 0 40%;
}

.info-value {
    text-align: right;
    color: var(--dark-color);
    flex: 1;
}

.info-value.price {
    color: var(--primary-color);
    font-weight: var(--font-weight-bold);
}

.payment-method-display {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: 0.5rem;
}

.payment-method-display i {
    color: var(--primary-color);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius-md);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-medium);
}

.status-pending {
    background-color: rgba(255, 193, 7, 0.15);
    color: var(--warning-color);
}

.special-requests {
    padding: var(--spacing-md);
    background-color: var(--gray-100);
    border-radius: var(--border-radius-md);
    font-style: italic;
    color: var(--gray-700);
}

/* Steps List */
.steps-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-lg);
}

.step-item {
    display: flex;
    gap: var(--spacing-md);
}

.step-icon {
    width: 50px;
    height: 50px;
    border-radius: var(--border-radius-circle);
    background-color: rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.step-content h5 {
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.step-content p {
    margin: 0;
    color: var(--gray-600);
    font-size: var(--font-size-sm);
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

.contact-info {
    margin: var(--spacing-md) 0;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}

.contact-item i {
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

.contact-details {
    flex: 1;
}

.contact-label {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    margin-bottom: 0.25rem;
}

.contact-value {
    font-weight: var(--font-weight-medium);
    color: var(--dark-color);
}

/* Social Share */
.social-share {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.social-btn {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius-md);
    text-decoration: none;
    font-weight: var(--font-weight-medium);
    transition: all var(--transition-fast);
}

.social-btn.facebook {
    background-color: #1877f2;
    color: var(--white-color);
}

.social-btn.twitter {
    background-color: #1da1f2;
    color: var(--white-color);
}

.social-btn.instagram {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
    color: var(--white-color);
}

.social-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-sm);
}

.btn-block {
    display: flex;
    width: 100%;
    justify-content: center;
}

/* Print Styles */
@media print {
    .confirmation-hero,
    .sidebar-card,
    .btn,
    .social-share {
        display: none !important;
    }
    
    .confirmation-wrapper {
        grid-template-columns: 1fr;
    }
    
    .confirmation-card {
        box-shadow: none;
        border: 1px solid var(--gray-300);
    }
}

/* Responsive */
@media (max-width: 992px) {
    .confirmation-wrapper {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
}

@media (max-width: 768px) {
    .confirmation-hero {
        height: 95vh;
        min-height: 400px;
    }
    
    .success-icon {
        width: 80px;
        height: 80px;
    }
    
    .success-icon i {
        font-size: 2.5rem;
    }
    
    .booking-id-badge {
        padding: 0.75rem 1.5rem;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .info-value {
        text-align: left;
    }
    
    .payment-method-display {
        justify-content: flex-start;
    }
    
    /* Extras Styling */
    .extras-section .info-value {
        width: 100%;
    }
    
    .extras-list {
        width: 100%;
    }
    
    .extra-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.9rem;
    }
    
    .extra-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .extra-item:first-child {
        padding-top: 0;
    }
    
    .extra-name {
        flex: 1;
        font-weight: 500;
    }
    
    .extra-quantity {
        margin-left: 0.5rem;
        color: var(--gray-600);
        font-size: 0.85rem;
    }
    
    .extra-price {
        margin-left: 0.5rem;
        font-weight: 600;
        color: var(--primary-color);
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy tracking link functionality
    const copyBtn = document.getElementById('copy-link');
    const trackingInput = document.getElementById('tracking-link');
    
    if (copyBtn && trackingInput) {
        copyBtn.addEventListener('click', function() {
            trackingInput.select();
            trackingInput.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                document.execCommand('copy');
                
                // Visual feedback
                const originalIcon = this.querySelector('i');
                originalIcon.textContent = 'check';
                this.style.backgroundColor = 'rgba(40, 167, 69, 0.3)';
                
                setTimeout(() => {
                    originalIcon.textContent = 'content_copy';
                    this.style.backgroundColor = '';
                }, 2000);
                
            } catch (err) {
                console.error('Failed to copy text: ', err);
            }
        });
    }
});
</script>