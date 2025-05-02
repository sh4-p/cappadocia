<?php
/**
 * Booking Confirmation View
 */
?>

<!-- Booking Confirmation Section -->
<section class="booking-confirmation-section">
    <div class="container">
        <div class="booking-confirmation">
            <div class="confirmation-header text-center" data-aos="fade-up">
                <div class="confirmation-icon">
                    <i class="material-icons">check_circle</i>
                </div>
                <h1 class="confirmation-title"><?php _e('booking_confirmed'); ?></h1>
                <p class="confirmation-subtitle"><?php _e('booking_confirmed_message'); ?></p>
                
                <!-- Booking Reference -->
                <div class="booking-reference">
                    <span><?php _e('booking_reference'); ?>:</span>
                    <strong><?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                </div>
            </div>
            
            <div class="confirmation-content" data-aos="fade-up" data-aos-delay="200">
                <!-- Booking Details -->
                <div class="booking-details material-card">
                    <div class="card-header">
                        <h3><?php _e('booking_details'); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="booking-tour-info">
                            <div class="tour-image">
                                <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                            </div>
                            <div class="tour-content">
                                <h4 class="tour-name"><?php echo $tour['name']; ?></h4>
                                <div class="tour-meta">
                                    <div class="meta-item">
                                        <i class="material-icons">event</i>
                                        <span><?php echo DateHelper::format($booking['booking_date'], 'l, F j, Y'); ?></span>
                                    </div>
                                    <?php if (!empty($tour['duration'])): ?>
                                    <div class="meta-item">
                                        <i class="material-icons">schedule</i>
                                        <span><?php echo $tour['duration']; ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="booking-info-grid">
                            <div class="info-group">
                                <h5><?php _e('guest_information'); ?></h5>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('full_name'); ?></div>
                                    <div class="info-value"><?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('email'); ?></div>
                                    <div class="info-value"><?php echo $booking['email']; ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('phone'); ?></div>
                                    <div class="info-value"><?php echo $booking['phone']; ?></div>
                                </div>
                            </div>
                            
                            <div class="info-group">
                                <h5><?php _e('booking_information'); ?></h5>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('adults'); ?></div>
                                    <div class="info-value"><?php echo $booking['adults']; ?></div>
                                </div>
                                <?php if ($booking['children'] > 0): ?>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('children'); ?></div>
                                    <div class="info-value"><?php echo $booking['children']; ?></div>
                                </div>
                                <?php endif; ?>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('total_price'); ?></div>
                                    <div class="info-value price"><?php echo $settings['currency_symbol'] . number_format($booking['total_price'], 2); ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('status'); ?></div>
                                    <div class="info-value status-<?php echo $booking['status']; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($booking['special_requests'])): ?>
                        <div class="special-requests">
                            <h5><?php _e('special_requests'); ?></h5>
                            <p><?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="payment-information material-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="card-header">
                        <h3><?php _e('payment_information'); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="payment-details">
                            <div class="payment-method">
                                <h5><?php _e('payment_method'); ?></h5>
                                <div class="method-info">
                                    <?php if ($payment['method'] == 'credit_card'): ?>
                                        <i class="material-icons">credit_card</i>
                                        <span><?php _e('credit_card'); ?></span>
                                    <?php elseif ($payment['method'] == 'paypal'): ?>
                                        <i class="material-icons">account_balance_wallet</i>
                                        <span>PayPal</span>
                                    <?php elseif ($payment['method'] == 'bank_transfer'): ?>
                                        <i class="material-icons">account_balance</i>
                                        <span><?php _e('bank_transfer'); ?></span>
                                    <?php else: ?>
                                        <i class="material-icons">payments</i>
                                        <span><?php _e('pay_at_location'); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="payment-summary">
                                <div class="summary-row">
                                    <div class="summary-label"><?php _e('tour_price'); ?></div>
                                    <div class="summary-value">
                                        <?php 
                                        $tourPrice = $tour['discount_price'] > 0 ? $tour['discount_price'] : $tour['price'];
                                        echo $settings['currency_symbol'] . number_format($tourPrice, 2); 
                                        ?> × <?php echo $booking['adults']; ?>
                                    </div>
                                </div>
                                
                                <?php if ($booking['children'] > 0): ?>
                                <div class="summary-row">
                                    <div class="summary-label"><?php _e('children_price'); ?></div>
                                    <div class="summary-value">
                                        <?php echo $settings['currency_symbol'] . number_format($tourPrice * 0.5, 2); ?> × <?php echo $booking['children']; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="summary-row total">
                                    <div class="summary-label"><?php _e('total'); ?></div>
                                    <div class="summary-value">
                                        <?php echo $settings['currency_symbol'] . number_format($booking['total_price'], 2); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($payment['method'] == 'bank_transfer'): ?>
                        <div class="bank-information">
                            <h5><?php _e('bank_information'); ?></h5>
                            <div class="bank-details">
                                <div class="info-row">
                                    <div class="info-label"><?php _e('bank_name'); ?></div>
                                    <div class="info-value"><?php echo $settings['bank_name']; ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('account_name'); ?></div>
                                    <div class="info-value"><?php echo $settings['account_name']; ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('account_number'); ?></div>
                                    <div class="info-value"><?php echo $settings['account_number']; ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('iban'); ?></div>
                                    <div class="info-value"><?php echo $settings['iban']; ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('swift_code'); ?></div>
                                    <div class="info-value"><?php echo $settings['swift_code']; ?></div>
                                </div>
                            </div>
                            <div class="payment-note">
                                <p><?php _e('bank_transfer_note'); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Next Steps -->
                <div class="booking-next-steps material-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-header">
                        <h3><?php _e('what_next'); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="next-steps">
                            <div class="step">
                                <div class="step-icon">
                                    <i class="material-icons">email</i>
                                </div>
                                <div class="step-content">
                                    <h5><?php _e('confirmation_email'); ?></h5>
                                    <p><?php _e('confirmation_email_text'); ?></p>
                                </div>
                            </div>
                            
                            <div class="step">
                                <div class="step-icon">
                                    <i class="material-icons">event_available</i>
                                </div>
                                <div class="step-content">
                                    <h5><?php _e('prepare_for_tour'); ?></h5>
                                    <p><?php _e('prepare_for_tour_text'); ?></p>
                                </div>
                            </div>
                            
                            <div class="step">
                                <div class="step-icon">
                                    <i class="material-icons">help_outline</i>
                                </div>
                                <div class="step-content">
                                    <h5><?php _e('questions'); ?></h5>
                                    <p><?php _e('questions_text'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="booking-actions" data-aos="fade-up" data-aos-delay="500">
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary">
                        <i class="material-icons">explore</i>
                        <?php _e('browse_more_tours'); ?>
                    </a>
                    <a href="javascript:window.print();" class="btn btn-outline">
                        <i class="material-icons">print</i>
                        <?php _e('print_booking'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Custom CSS for booking confirmation -->
<style>
    .booking-confirmation-section {
        padding: var(--spacing-xxl) 0;
    }
    
    .confirmation-header {
        margin-bottom: var(--spacing-xl);
    }
    
    .confirmation-icon {
        width: 80px;
        height: 80px;
        border-radius: var(--border-radius-circle);
        background-color: var(--success-color);
        color: var(--white-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto var(--spacing-md);
        font-size: 2.5rem;
    }
    
    .confirmation-title {
        color: var(--success-color);
        margin-bottom: var(--spacing-sm);
    }
    
    .booking-reference {
        display: inline-block;
        padding: var(--spacing-sm) var(--spacing-lg);
        background-color: var(--gray-100);
        border-radius: var(--border-radius-md);
        margin-top: var(--spacing-md);
    }
    
    .booking-reference strong {
        font-size: var(--font-size-lg);
        color: var(--primary-color);
        margin-left: var(--spacing-sm);
    }
    
    .material-card {
        margin-bottom: var(--spacing-lg);
    }
    
    .booking-tour-info {
        display: flex;
        gap: var(--spacing-lg);
        margin-bottom: var(--spacing-lg);
        padding-bottom: var(--spacing-lg);
        border-bottom: 1px solid var(--gray-200);
    }
    
    .tour-image {
        width: 120px;
        height: 80px;
        border-radius: var(--border-radius-md);
        overflow: hidden;
    }
    
    .tour-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .tour-meta {
        display: flex;
        gap: 1rem;
        margin-top: var(--spacing-sm);
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: var(--gray-600);
        font-size: var(--font-size-sm);
    }
    
    .booking-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-lg);
    }
    
    .info-group h5 {
        margin-bottom: var(--spacing-md);
        padding-bottom: var(--spacing-sm);
        border-bottom: 1px solid var(--gray-200);
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--spacing-sm);
    }
    
    .info-label {
        color: var(--gray-600);
    }
    
    .info-value {
        font-weight: var(--font-weight-medium);
        color: var(--dark-color);
    }
    
    .info-value.price {
        color: var(--primary-color);
        font-weight: var(--font-weight-bold);
    }
    
    .status-pending {
        color: var(--warning-color);
    }
    
    .status-confirmed {
        color: var(--success-color);
    }
    
    .status-cancelled {
        color: var(--danger-color);
    }
    
    .special-requests {
        margin-top: var(--spacing-lg);
        padding-top: var(--spacing-lg);
        border-top: 1px solid var(--gray-200);
    }
    
    .payment-details {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: var(--spacing-lg);
    }
    
    .method-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: var(--spacing-md);
    }
    
    .method-info i {
        font-size: 2rem;
        color: var(--primary-color);
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: var(--spacing-sm);
    }
    
    .summary-row.total {
        margin-top: var(--spacing-md);
        padding-top: var(--spacing-md);
        border-top: 1px solid var(--gray-200);
        font-weight: var(--font-weight-bold);
    }
    
    .summary-value {
        color: var(--dark-color);
    }
    
    .bank-information {
        margin-top: var(--spacing-lg);
        padding-top: var(--spacing-lg);
        border-top: 1px solid var(--gray-200);
    }
    
    .bank-details {
        background-color: var(--gray-100);
        padding: var(--spacing-md);
        border-radius: var(--border-radius-md);
        margin-top: var(--spacing-md);
    }
    
    .payment-note {
        margin-top: var(--spacing-md);
        font-size: var(--font-size-sm);
        color: var(--gray-600);
    }
    
    .next-steps {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-lg);
    }
    
    .step {
        display: flex;
        gap: var(--spacing-lg);
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
        font-size: 1.5rem;
    }
    
    .step-content h5 {
        margin-bottom: var(--spacing-xs);
    }
    
    .step-content p {
        color: var(--gray-600);
        margin-bottom: 0;
    }
    
    .booking-actions {
        display: flex;
        gap: var(--spacing-md);
        margin-top: var(--spacing-lg);
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .booking-info-grid,
        .payment-details {
            grid-template-columns: 1fr;
        }
        
        .booking-actions {
            flex-direction: column;
        }
    }
    
    @media (max-width: 576px) {
        .booking-tour-info {
            flex-direction: column;
            gap: var(--spacing-md);
        }
        
        .tour-image {
            width: 100%;
            height: 150px;
        }
        
        .step {
            flex-direction: column;
            gap: var(--spacing-sm);
            text-align: center;
        }
        
        .step-icon {
            margin: 0 auto;
        }
    }
    
    /* Print Styles */
    @media print {
        body {
            font-size: 12pt;
            color: #000;
            background: #fff;
        }
        
        .site-header, .site-footer, .back-to-top, 
        .booking-actions, .confirmation-icon {
            display: none !important;
        }
        
        .container {
            max-width: 100%;
            width: 100%;
            padding: 0;
        }
        
        .material-card {
            box-shadow: none;
            border: 1px solid #ccc;
            break-inside: avoid;
        }
        
        .booking-confirmation-section {
            padding: 0;
        }
        
        .confirmation-title {
            color: #000;
        }
        
        .step-icon {
            background-color: #f0f0f0;
            color: #000;
        }
        
        @page {
            margin: 2cm;
        }
    }
</style>