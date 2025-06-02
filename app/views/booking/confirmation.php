<?php
/**
 * Booking Confirmation View - Tamamen Yenilenmiş Tasarım
 */

// Hero arka plan görseli
$confirmationBgImage = isset($settings['confirmation_bg']) ? $settings['confirmation_bg'] : 'confirmation-bg.jpg';

// Önerilen turlar arkaplan görseli
$recommendedBgImage = isset($settings['recommended_bg']) ? $settings['recommended_bg'] : 'recommended-bg.jpg';

// CTA arka plan görseli
$ctaBgImage = isset($settings['cta_bg']) ? $settings['cta_bg'] : 'cta-bg.jpg';

// Rastgele önerilen turları belirle (gerçek uygulamada bu dinamik olacaktır)
$recommendedTours = isset($featuredTours) ? array_slice($featuredTours, 0, 3) : [];
?>

<!-- Hero Section with Confirmation Message -->
<section class="hero-section confirmation-hero" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $confirmationBgImage; ?>');">
    <div class="overlay"></div>
    <div class="container">
        <div class="hero-content text-center fade-in-up">
            <div class="confirmation-icon-wrapper">
                <div class="confirmation-icon">
                    <i class="material-icons">check_circle</i>
                </div>
            </div>
            <h1 class="hero-title"><?php _e('booking_confirmed'); ?></h1>
            <p class="hero-subtitle"><?php _e('booking_confirmed_message'); ?></p>
            
            <!-- Booking Reference - Glassy Card -->
            <div class="booking-reference glass-card">
                <div class="reference-label"><?php _e('booking_reference'); ?></div>
                <div class="reference-number"><?php echo str_pad($booking['id'], 6, '0', STR_PAD_LEFT); ?></div>
                <div class="reference-help"><?php _e('reference_help_text'); ?></div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="javascript:window.print();" class="btn-action">
                    <i class="material-icons">print</i>
                    <span><?php _e('print_booking'); ?></span>
                </a>
                <a href="mailto:?subject=<?php echo urlencode(__('my_booking_details')); ?>&body=<?php echo urlencode(__('booking_email_body') . ' ' . str_pad($booking['id'], 6, '0', STR_PAD_LEFT)); ?>" class="btn-action">
                    <i class="material-icons">email</i>
                    <span><?php _e('email_booking'); ?></span>
                </a>
                <a href="#tour-details" class="btn-action smooth-scroll">
                    <i class="material-icons">arrow_downward</i>
                    <span><?php _e('view_details'); ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Tour and Booking Details Section -->
<section class="section" id="tour-details">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h2 class="section-title"><?php _e('your_adventure'); ?></h2>
            <p class="section-subtitle"><?php _e('booking_details_subtitle'); ?></p>
        </div>
        
        <!-- Tour Card - Visual Enhancement -->
        <div class="booking-tour-card" data-aos="fade-up">
            <div class="tour-image">
                <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                <div class="tour-status status-<?php echo $booking['status']; ?>">
                    <?php echo ucfirst($booking['status']); ?>
                </div>
            </div>
            <div class="tour-content">
                <h3 class="tour-name"><?php echo $tour['name']; ?></h3>
                
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
                    <div class="meta-item">
                        <i class="material-icons">group</i>
                        <span><?php echo $booking['adults']; ?> <?php _e('adults'); ?><?php echo $booking['children'] > 0 ? ' + ' . $booking['children'] . ' ' . __('children') : ''; ?></span>
                    </div>
                </div>
                
                <?php if (!empty($tour['short_description'])): ?>
                <div class="tour-description">
                    <p><?php echo substr(strip_tags($tour['short_description']), 0, 150) . '...'; ?></p>
                </div>
                <?php endif; ?>
                
                <div class="tour-price-tag">
                    <span class="price-label"><?php _e('total_price'); ?>:</span>
                    <span class="price-value"><?php echo $settings['currency_symbol'] . number_format($booking['total_price'], 2); ?></span>
                </div>
                
                <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>" class="btn btn-primary btn-sm">
                    <i class="material-icons">info</i>
                    <?php _e('tour_details'); ?>
                </a>
            </div>
        </div>
        
        <!-- Booking Details Tabs -->
        <div class="booking-details-tabs" data-aos="fade-up" data-aos-delay="100">
            <div class="tabs-nav">
                <button class="tab-btn active" data-tab="details">
                    <i class="material-icons">person</i>
                    <?php _e('guest_details'); ?>
                </button>
                <button class="tab-btn" data-tab="payment">
                    <i class="material-icons">payment</i>
                    <?php _e('payment_details'); ?>
                </button>
                <button class="tab-btn" data-tab="next-steps">
                    <i class="material-icons">directions</i>
                    <?php _e('what_next'); ?>
                </button>
            </div>
            
            <div class="tabs-content">
                <!-- Guest Details Tab -->
                <div class="tab-pane active" id="details-tab">
                    <div class="material-card">
                        <div class="card-body">
                            <div class="booking-info-grid">
                                <div class="info-group">
                                    <h5><i class="material-icons">person</i> <?php _e('guest_information'); ?></h5>
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
                                    <h5><i class="material-icons">book_online</i> <?php _e('booking_information'); ?></h5>
                                    <div class="info-row">
                                        <div class="info-label"><?php _e('booking_date'); ?></div>
                                        <div class="info-value"><?php echo DateHelper::format($booking['booking_date'], 'F j, Y'); ?></div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label"><?php _e('booking_time'); ?></div>
                                        <div class="info-value"><?php echo !empty($booking['booking_time']) ? $booking['booking_time'] : __('as_per_tour_schedule'); ?></div>
                                    </div>
                                    <div class="info-row">
                                        <div class="info-label"><?php _e('booking_status'); ?></div>
                                        <div class="info-value status-tag status-<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($booking['special_requests'])): ?>
                            <div class="special-requests">
                                <h5><i class="material-icons">speaker_notes</i> <?php _e('special_requests'); ?></h5>
                                <div class="request-box">
                                    <p><?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Details Tab -->
                <div class="tab-pane" id="payment-tab">
                    <div class="material-card">
                        <div class="card-body">
                            <div class="payment-overview">
                                <div class="payment-method">
                                    <h5><i class="material-icons">credit_card</i> <?php _e('payment_method'); ?></h5>
                                    <div class="method-badge">
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
                                    
                                    <?php if ($payment['method'] == 'credit_card' && !empty($payment['card_last4'])): ?>
                                    <div class="card-info">
                                        <span class="card-type"><?php echo ucfirst($payment['card_type']); ?></span>
                                        <span class="card-number">**** **** **** <?php echo $payment['card_last4']; ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="payment-details">
                                    <h5><i class="material-icons">receipt</i> <?php _e('payment_details'); ?></h5>
                                    
                                    <div class="payment-summary">
                                        <div class="summary-row">
                                            <div class="summary-label"><?php _e('tour_base_price'); ?></div>
                                            <div class="summary-value">
                                                <?php 
                                                $tourPrice = $tour['discount_price'] > 0 ? $tour['discount_price'] : $tour['price'];
                                                echo $settings['currency_symbol'] . number_format($tourPrice, 2); 
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <div class="summary-row">
                                            <div class="summary-label"><?php _e('adults'); ?> (<?php echo $booking['adults']; ?> × <?php echo $settings['currency_symbol'] . number_format($tourPrice, 2); ?>)</div>
                                            <div class="summary-value">
                                                <?php echo $settings['currency_symbol'] . number_format($tourPrice * $booking['adults'], 2); ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ($booking['children'] > 0): ?>
                                        <div class="summary-row">
                                            <div class="summary-label"><?php _e('children'); ?> (<?php echo $booking['children']; ?> × <?php echo $settings['currency_symbol'] . number_format($tourPrice * 0.5, 2); ?>)</div>
                                            <div class="summary-value">
                                                <?php echo $settings['currency_symbol'] . number_format($tourPrice * 0.5 * $booking['children'], 2); ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($booking['extras']) && is_array($booking['extras'])): ?>
                                        <?php foreach ($booking['extras'] as $extra): ?>
                                        <div class="summary-row">
                                            <div class="summary-label"><?php echo $extra['name']; ?> (<?php echo $extra['quantity']; ?> × <?php echo $settings['currency_symbol'] . number_format($extra['price'], 2); ?>)</div>
                                            <div class="summary-value">
                                                <?php echo $settings['currency_symbol'] . number_format($extra['price'] * $extra['quantity'], 2); ?>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($booking['discount_amount']) && $booking['discount_amount'] > 0): ?>
                                        <div class="summary-row discount">
                                            <div class="summary-label"><?php _e('discount'); ?> <?php echo !empty($booking['discount_code']) ? '(' . $booking['discount_code'] . ')' : ''; ?></div>
                                            <div class="summary-value">
                                                -<?php echo $settings['currency_symbol'] . number_format($booking['discount_amount'], 2); ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                        
                                        <div class="summary-row total">
                                            <div class="summary-label"><?php _e('total'); ?></div>
                                            <div class="summary-value">
                                                <?php echo $settings['currency_symbol'] . number_format($booking['total_price'], 2); ?>
                                            </div>
                                        </div>
                                        
                                        <div class="summary-row payment-status">
                                            <div class="summary-label"><?php _e('payment_status'); ?></div>
                                            <div class="summary-value">
                                                <span class="status-tag status-<?php echo !empty($payment['status']) ? $payment['status'] : 'pending'; ?>">
                                                    <?php echo !empty($payment['status']) ? ucfirst($payment['status']) : __('pending'); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if ($payment['method'] == 'bank_transfer'): ?>
                            <div class="bank-information">
                                <h5><i class="material-icons">account_balance</i> <?php _e('bank_information'); ?></h5>
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
                                <div class="bank-note">
                                    <i class="material-icons">info</i>
                                    <p><?php _e('bank_transfer_note'); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($payment['transaction_id'])): ?>
                            <div class="transaction-info">
                                <div class="info-row">
                                    <div class="info-label"><?php _e('transaction_id'); ?></div>
                                    <div class="info-value"><?php echo $payment['transaction_id']; ?></div>
                                </div>
                                <div class="info-row">
                                    <div class="info-label"><?php _e('payment_date'); ?></div>
                                    <div class="info-value"><?php echo !empty($payment['payment_date']) ? DateHelper::format($payment['payment_date'], 'F j, Y H:i') : '-'; ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Next Steps Tab -->
                <div class="tab-pane" id="next-steps-tab">
                    <div class="material-card">
                        <div class="card-body">
                            <div class="next-steps">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="timeline-marker done">
                                            <i class="material-icons">check_circle</i>
                                        </div>
                                        <div class="timeline-content">
                                            <h5><?php _e('booking_confirmed'); ?></h5>
                                            <p><?php _e('booking_confirmed_step_text'); ?></p>
                                            <div class="timeline-date"><?php echo DateHelper::format(date('Y-m-d H:i:s'), 'F j, Y H:i'); ?></div>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-marker <?php echo $booking['status'] == 'confirmed' ? 'done' : 'pending'; ?>">
                                            <i class="material-icons"><?php echo $booking['status'] == 'confirmed' ? 'check_circle' : 'schedule'; ?></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h5><?php _e('confirmation_email'); ?></h5>
                                            <p><?php _e('confirmation_email_text'); ?></p>
                                            <?php if ($booking['status'] == 'confirmed'): ?>
                                            <div class="timeline-date"><?php echo DateHelper::format(date('Y-m-d H:i:s'), 'F j, Y H:i'); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-marker pending">
                                            <i class="material-icons">schedule</i>
                                        </div>
                                        <div class="timeline-content">
                                            <h5><?php _e('prepare_for_tour'); ?></h5>
                                            <p><?php _e('prepare_for_tour_text'); ?></p>
                                            <div class="checklist">
                                                <div class="checklist-item">
                                                    <i class="material-icons">check_circle_outline</i>
                                                    <span><?php _e('checklist_item_1'); ?></span>
                                                </div>
                                                <div class="checklist-item">
                                                    <i class="material-icons">check_circle_outline</i>
                                                    <span><?php _e('checklist_item_2'); ?></span>
                                                </div>
                                                <div class="checklist-item">
                                                    <i class="material-icons">check_circle_outline</i>
                                                    <span><?php _e('checklist_item_3'); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="timeline-item">
                                        <div class="timeline-marker pending">
                                            <i class="material-icons">schedule</i>
                                        </div>
                                        <div class="timeline-content">
                                            <h5><?php _e('enjoy_your_tour'); ?></h5>
                                            <p><?php _e('enjoy_your_tour_text'); ?></p>
                                            <div class="timeline-date">
                                                <?php echo DateHelper::format($booking['booking_date'], 'F j, Y'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="help-box">
                                    <div class="help-icon">
                                        <i class="material-icons">help_outline</i>
                                    </div>
                                    <div class="help-content">
                                        <h5><?php _e('need_help'); ?></h5>
                                        <p><?php _e('need_help_text'); ?></p>
                                        <div class="help-contacts">
                                            <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone']); ?>" class="help-contact-item">
                                                <i class="material-icons">phone</i>
                                                <span><?php echo $settings['contact_phone']; ?></span>
                                            </a>
                                            <a href="mailto:<?php echo $settings['contact_email']; ?>" class="help-contact-item">
                                                <i class="material-icons">email</i>
                                                <span><?php echo $settings['contact_email']; ?></span>
                                            </a>
                                            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="help-contact-item">
                                                <i class="material-icons">support_agent</i>
                                                <span><?php _e('contact_support'); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- You May Also Like Section - Similar to Featured Tours -->
<?php if (!empty($recommendedTours)): ?>
<section class="section recommended-tours-section" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $recommendedBgImage; ?>');">
    <div class="overlay"></div>
    <div class="container">
        <div class="section-header light" data-aos="fade-up">
            <h2 class="section-title"><?php _e('you_may_also_like'); ?></h2>
            <p class="section-subtitle"><?php _e('recommended_tours_subtitle'); ?></p>
        </div>
        
        <div class="recommended-tours-grid">
            <?php foreach ($recommendedTours as $index => $recTour): ?>
                <div class="tour-card" data-aos="fade-up" data-aos-delay="<?php echo $index * 100; ?>">
                    <div class="tour-image">
                        <img src="<?php echo $uploadsUrl . '/tours/' . $recTour['featured_image']; ?>" alt="<?php echo $recTour['name']; ?>">
                        <div class="tour-price">
                            <?php if (isset($recTour['discount_price']) && $recTour['discount_price'] > 0): ?>
                                <del><?php echo $settings['currency_symbol'] . number_format($recTour['price'], 2); ?></del>
                                <?php echo $settings['currency_symbol'] . number_format($recTour['discount_price'], 2); ?>
                            <?php else: ?>
                                <?php echo $settings['currency_symbol'] . number_format($recTour['price'], 2); ?>
                            <?php endif; ?>
                        </div>
                        <?php if (isset($recTour['category_name'])): ?>
                            <div class="tour-category"><?php echo $recTour['category_name']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="tour-content">
                        <h3 class="tour-title">
                            <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $recTour['slug']; ?>">
                                <?php echo $recTour['name']; ?>
                            </a>
                        </h3>
                        <div class="tour-meta">
                            <div class="tour-meta-item">
                                <i class="material-icons">schedule</i>
                                <span><?php echo $recTour['duration']; ?></span>
                            </div>
                            <div class="tour-meta-item">
                                <i class="material-icons">group</i>
                                <span><?php _e('max'); ?> 15</span>
                            </div>
                        </div>
                        <div class="tour-rating">
                            <?php 
                            $rating = isset($recTour['rating']) ? $recTour['rating'] : 5;
                            for ($i = 1; $i <= 5; $i++): ?>
                                <i class="material-icons"><?php echo $i <= $rating ? 'star' : 'star_border'; ?></i>
                            <?php endfor; ?>
                            <span class="rating-count">(<?php echo isset($recTour['reviews_count']) ? $recTour['reviews_count'] : rand(10, 50); ?>)</span>
                        </div>
                        <div class="tour-footer">
                            <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $recTour['slug']; ?>" class="btn btn-primary btn-sm">
                                <?php _e('view_details'); ?>
                            </a>
                            <button class="btn-favorite" data-tour-id="<?php echo $recTour['id']; ?>" title="<?php _e('add_to_favorites'); ?>">
                                <i class="material-icons">favorite_border</i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action Section -->
<section class="cta-section" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $ctaBgImage; ?>');">
    <div class="container">
        <div class="cta-content" data-aos="fade-up">
            <h2 class="cta-title"><?php _e('ready_for_more_adventures'); ?></h2>
            <p class="cta-text"><?php _e('ready_for_more_adventures_text'); ?></p>
            <div class="cta-buttons">
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-lg">
                    <i class="material-icons">flight_takeoff</i>
                    <?php _e('explore_more_tours'); ?>
                </a>
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-glass btn-lg">
                    <i class="material-icons">chat</i>
                    <?php _e('contact_us'); ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Custom Styles for the new design -->
<style>
    /* Hero Section */
    .confirmation-hero {
        height: 85vh;
        min-height: 700px;
        display: flex;
        align-items: center;
        position: relative;
        background-position: center;
        background-size: cover;
        background-attachment: fixed;
        color: var(--white-color);
        text-align: center;
    }
    
    .confirmation-hero .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(30, 30, 30, 0.6), rgba(30, 30, 30, 0.7));
        z-index: 1;
    }
    
    .confirmation-hero .container {
        position: relative;
        z-index: 2;
    }
    
    .hero-content {
        max-width: 700px;
        margin: 0 auto;
    }
    
    .confirmation-icon-wrapper {
        margin-bottom: var(--spacing-xl);
    }
    
    .confirmation-icon {
        width: 120px;
        height: 120px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(40, 167, 69, 0.9);
        color: var(--white-color);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        font-size: 4rem;
        box-shadow: 0 0 30px rgba(40, 167, 69, 0.5);
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(40, 167, 69, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
        }
    }
    
    .hero-title {
        font-size: 3rem;
        margin-bottom: var(--spacing-sm);
        color: var(--white-color);
    }
    
    .hero-subtitle {
        font-size: 1.25rem;
        margin-bottom: var(--spacing-xl);
        color: rgba(255, 255, 255, 0.9);
    }
    
    .booking-reference {
        display: inline-block;
        padding: var(--spacing-lg);
        margin: var(--spacing-lg) auto;
        max-width: 400px;
        backdrop-filter: blur(10px);
        border-radius: var(--border-radius-lg);
        border: 1px solid rgba(255, 255, 255, 0.2);
        background-color: rgba(255, 255, 255, 0.15);
    }
    
    .reference-label {
        font-size: var(--font-size-sm);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: var(--spacing-sm);
        color: rgba(255, 255, 255, 0.8);
    }
    
    .reference-number {
        font-size: 2.5rem;
        font-weight: var(--font-weight-bold);
        color: var(--white-color);
        letter-spacing: 2px;
        margin-bottom: var(--spacing-sm);
    }
    
    .reference-help {
        font-size: var(--font-size-sm);
        color: rgba(255, 255, 255, 0.7);
    }
    
    .quick-actions {
        display: flex;
        justify-content: center;
        gap: var(--spacing-lg);
        margin-top: var(--spacing-xl);
    }
    
    .btn-action {
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--white-color);
        text-decoration: none;
        transition: transform var(--transition-fast);
    }
    
    .btn-action:hover {
        transform: translateY(-5px);
    }
    
    .btn-action i {
        font-size: 2rem;
        margin-bottom: var(--spacing-xs);
        background-color: rgba(255, 255, 255, 0.2);
        width: 50px;
        height: 50px;
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color var(--transition-fast);
    }
    
    .btn-action:hover i {
        background-color: var(--primary-color);
    }
    
    .btn-action span {
        font-size: var(--font-size-sm);
    }
    
    /* Section Styles */
    .section {
        padding: var(--spacing-xxl) 0;
        position: relative;
    }
    
    .section-header {
        text-align: center;
        margin-bottom: var(--spacing-xl);
    }
    
    .section-header.light {
        color: var(--white-color);
    }
    
    .section-title {
        font-size: 2.5rem;
        margin-bottom: var(--spacing-sm);
        color: var(--dark-color);
    }
    
    .section-header.light .section-title {
        color: var(--white-color);
    }
    
    .section-subtitle {
        font-size: 1.1rem;
        color: var(--gray-600);
        max-width: 700px;
        margin: 0 auto;
    }
    
    .section-header.light .section-subtitle {
        color: rgba(255, 255, 255, 0.8);
    }
    
    /* Booking Tour Card */
    .booking-tour-card {
        display: flex;
        background-color: var(--white-color);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        margin-bottom: var(--spacing-xl);
    }
    
    .booking-tour-card .tour-image {
        width: 40%;
        position: relative;
    }
    
    .booking-tour-card .tour-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .tour-status {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-md);
        font-size: var(--font-size-sm);
        font-weight: var(--font-weight-medium);
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    
    .status-confirmed {
        background-color: rgba(40, 167, 69, 0.9);
        color: var(--white-color);
    }
    
    .status-pending {
        background-color: rgba(255, 193, 7, 0.9);
        color: var(--dark-color);
    }
    
    .status-cancelled {
        background-color: rgba(220, 53, 69, 0.9);
        color: var(--white-color);
    }
    
    .booking-tour-card .tour-content {
        width: 60%;
        padding: var(--spacing-lg);
    }
    
    .tour-name {
        font-size: 1.75rem;
        margin-bottom: var(--spacing-md);
        color: var(--dark-color);
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
    
    .tour-description {
        margin-bottom: var(--spacing-md);
        color: var(--gray-600);
        line-height: 1.6;
    }
    
    .tour-price-tag {
        display: inline-block;
        background-color: var(--light-color);
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-md);
        margin-bottom: var(--spacing-md);
    }
    
    .price-label {
        color: var(--gray-600);
        margin-right: 0.5rem;
    }
    
    .price-value {
        font-weight: var(--font-weight-bold);
        color: var(--primary-color);
        font-size: var(--font-size-lg);
    }
    
    /* Booking Details Tabs */
    .booking-details-tabs {
        margin-bottom: var(--spacing-xl);
    }
    
    .tabs-nav {
        display: flex;
        border-bottom: 1px solid var(--gray-300);
        margin-bottom: var(--spacing-lg);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .tab-btn {
        padding: var(--spacing-md) var(--spacing-lg);
        border: none;
        background: none;
        color: var(--gray-600);
        font-weight: var(--font-weight-medium);
        font-size: var(--font-size-md);
        cursor: pointer;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        position: relative;
        transition: color var(--transition-fast);
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
    }
    
    .tab-btn.active:after {
        transform: scaleX(1);
    }
    
    .tab-pane {
        display: none;
    }
    
    .tab-pane.active {
        display: block;
    }
    
    /* Card Styles */
    .material-card {
        background-color: var(--white-color);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-md);
        overflow: hidden;
    }
    
    .card-body {
        padding: var(--spacing-lg);
    }
    
    /* Info Grid Styles */
    .booking-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: var(--spacing-xl);
        margin-bottom: var(--spacing-lg);
    }
    
    .info-group h5 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: var(--spacing-md);
        padding-bottom: var(--spacing-sm);
        border-bottom: 1px solid var(--gray-200);
        color: var(--dark-color);
        font-weight: var(--font-weight-medium);
    }
    
    .info-group h5 i {
        color: var(--primary-color);
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
    
    /* Special Requests */
    .special-requests {
        margin-top: var(--spacing-lg);
    }
    
    .request-box {
        background-color: var(--light-color);
        padding: var(--spacing-md);
        border-radius: var(--border-radius-md);
        margin-top: var(--spacing-sm);
    }
    
    .request-box p {
        margin: 0;
        line-height: 1.6;
        color: var(--gray-700);
    }
    
    /* Payment Overview */
    .payment-overview {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: var(--spacing-xl);
        margin-bottom: var(--spacing-lg);
    }
    
    .method-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background-color: var(--light-color);
        padding: 0.75rem 1rem;
        border-radius: var(--border-radius-md);
        margin-top: var(--spacing-sm);
    }
    
    .method-badge i {
        color: var(--primary-color);
    }
    
    .card-info {
        margin-top: var(--spacing-md);
        padding: var(--spacing-sm) var(--spacing-md);
        background-color: var(--gray-100);
        border-radius: var(--border-radius-md);
        display: flex;
        flex-direction: column;
    }
    
    .card-type {
        font-size: var(--font-size-sm);
        color: var(--gray-600);
    }
    
    .card-number {
        font-weight: var(--font-weight-medium);
        color: var(--dark-color);
    }
    
    .payment-summary {
        margin-top: var(--spacing-sm);
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: var(--spacing-sm) 0;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .summary-row:last-child {
        border-bottom: none;
    }
    
    .summary-label {
        color: var(--gray-600);
    }
    
    .summary-value {
        font-weight: var(--font-weight-medium);
        color: var(--dark-color);
    }
    
    .summary-row.discount .summary-value {
        color: var(--success-color);
    }
    
    .summary-row.total {
        margin-top: var(--spacing-sm);
        padding-top: var(--spacing-md);
        border-top: 2px solid var(--gray-300);
        font-weight: var(--font-weight-bold);
    }
    
    .summary-row.total .summary-label {
        font-size: var(--font-size-lg);
        color: var(--dark-color);
    }
    
    .summary-row.total .summary-value {
        font-size: var(--font-size-xl);
        color: var(--primary-color);
    }
    
    .status-tag {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-md);
        font-size: var(--font-size-xs);
        font-weight: var(--font-weight-medium);
        text-transform: uppercase;
    }
    
    /* Bank Information */
    .bank-information {
        margin-top: var(--spacing-xl);
        padding-top: var(--spacing-lg);
        border-top: 1px solid var(--gray-200);
    }
    
    .bank-details {
        background-color: var(--light-color);
        padding: var(--spacing-md);
        border-radius: var(--border-radius-md);
        margin-top: var(--spacing-sm);
    }
    
    .bank-note {
        margin-top: var(--spacing-md);
        padding: var(--spacing-md);
        background-color: rgba(255, 193, 7, 0.1);
        border-radius: var(--border-radius-md);
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .bank-note i {
        color: var(--warning-color);
        margin-top: 0.25rem;
    }
    
    .bank-note p {
        margin: 0;
        color: var(--gray-700);
        line-height: 1.6;
    }
    
    /* Transaction Info */
    .transaction-info {
        margin-top: var(--spacing-lg);
        padding-top: var(--spacing-md);
        border-top: 1px solid var(--gray-200);
    }
    
    /* Timeline */
    .timeline {
        position: relative;
        margin: 0 0 var(--spacing-xl);
    }
    
    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        left: 20px;
        height: 100%;
        width: 2px;
        background-color: var(--gray-300);
    }
    
    .timeline-item {
        position: relative;
        padding-left: 60px;
        padding-bottom: var(--spacing-xl);
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-marker {
        position: absolute;
        top: 0;
        left: 0;
        width: 40px;
        height: 40px;
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }
    
    .timeline-marker.done {
        background-color: var(--success-color);
        color: var(--white-color);
    }
    
    .timeline-marker.pending {
        background-color: var(--gray-300);
        color: var(--white-color);
    }
    
    .timeline-content {
        position: relative;
    }
    
    .timeline-content h5 {
        margin-bottom: var(--spacing-xs);
        color: var(--dark-color);
    }
    
    .timeline-content p {
        color: var(--gray-600);
        margin-bottom: var(--spacing-sm);
        line-height: 1.6;
    }
    
    .timeline-date {
        font-size: var(--font-size-sm);
        color: var(--gray-500);
    }
    
    /* Checklist */
    .checklist {
        margin-top: var(--spacing-sm);
    }
    
    .checklist-item {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: var(--spacing-xs);
    }
    
    .checklist-item i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }
    
    /* Help Box */
    .help-box {
        background-color: var(--light-color);
        border-radius: var(--border-radius-lg);
        padding: var(--spacing-lg);
        display: flex;
        gap: var(--spacing-lg);
        margin-top: var(--spacing-xl);
    }
    
    .help-icon {
        width: 60px;
        height: 60px;
        background-color: var(--primary-color);
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white-color);
        font-size: 2rem;
        flex-shrink: 0;
    }
    
    .help-content {
        flex: 1;
    }
    
    .help-content h5 {
        margin-bottom: var(--spacing-sm);
        color: var(--dark-color);
    }
    
    .help-content p {
        color: var(--gray-600);
        margin-bottom: var(--spacing-md);
        line-height: 1.6;
    }
    
    .help-contacts {
        display: flex;
        flex-wrap: wrap;
        gap: var(--spacing-md);
    }
    
    .help-contact-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--primary-color);
        text-decoration: none;
        transition: color var(--transition-fast);
    }
    
    .help-contact-item:hover {
        color: var(--primary-dark-color);
    }
    
    /* Recommended Tours Section */
    .recommended-tours-section {
        position: relative;
        background-position: center;
        background-size: cover;
        background-attachment: fixed;
    }
    
    .recommended-tours-section .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(30, 30, 30, 0.8), rgba(30, 30, 30, 0.8));
        z-index: 1;
    }
    
    .recommended-tours-section .container {
        position: relative;
        z-index: 2;
    }
    
    .recommended-tours-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--spacing-xl);
    }
    
    .tour-card {
        background-color: var(--white-color);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-lg);
        transition: transform var(--transition-medium);
    }
    
    .tour-card:hover {
        transform: translateY(-10px);
    }
    
    .tour-card .tour-image {
        position: relative;
        height: 200px;
    }
    
    .tour-card .tour-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .tour-price {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background-color: var(--primary-color);
        color: var(--white-color);
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-md);
        font-weight: var(--font-weight-medium);
        z-index: 1;
    }
    
    .tour-price del {
        opacity: 0.7;
        margin-right: 0.5rem;
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
        z-index: 1;
    }
    
    .tour-card .tour-content {
        padding: var(--spacing-md);
    }
    
    .tour-card .tour-title {
        font-size: var(--font-size-lg);
        margin-bottom: var(--spacing-sm);
    }
    
    .tour-card .tour-title a {
        color: var(--dark-color);
        text-decoration: none;
        transition: color var(--transition-fast);
    }
    
    .tour-card .tour-title a:hover {
        color: var(--primary-color);
    }
    
    .tour-card .tour-meta {
        margin-bottom: var(--spacing-sm);
    }
    
    .tour-rating {
        display: flex;
        align-items: center;
        color: #FFD700;
        margin-bottom: var(--spacing-md);
    }
    
    .tour-rating .material-icons {
        font-size: 1rem;
    }
    
    .rating-count {
        color: var(--gray-600);
        font-size: var(--font-size-sm);
        margin-left: 0.25rem;
    }
    
    .tour-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    
    /* CTA Section */
    .cta-section {
        position: relative;
        background-position: center;
        background-size: cover;
        padding: var(--spacing-xxl) 0;
        text-align: center;
    }
    
    .cta-section:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(30, 30, 30, 0.6), rgba(30, 30, 30, 0.7));
    }
    
    .cta-content {
        position: relative;
        max-width: 700px;
        margin: 0 auto;
    }
    
    .cta-title {
        font-size: 2.5rem;
        color: var(--white-color);
        margin-bottom: var(--spacing-md);
    }
    
    .cta-text {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: var(--spacing-xl);
        font-size: var(--font-size-lg);
    }
    
    .cta-buttons {
        display: flex;
        justify-content: center;
        gap: var(--spacing-lg);
    }
    
    /* Button Styles */
    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius-md);
        font-weight: var(--font-weight-medium);
        text-decoration: none;
        transition: all var(--transition-fast);
        cursor: pointer;
    }
    
    .btn-primary {
        background-color: var(--primary-color);
        color: var(--white-color);
        border: none;
    }
    
    .btn-primary:hover {
        background-color: var(--primary-dark-color);
        box-shadow: var(--shadow-md);
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
    
    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: var(--font-size-sm);
    }
    
    .btn-lg {
        padding: 1rem 2rem;
        font-size: var(--font-size-md);
    }
    
    /* Responsive Styles */
    @media (max-width: 992px) {
        .hero-title {
            font-size: 2.5rem;
        }
        
        .booking-tour-card {
            flex-direction: column;
        }
        
        .booking-tour-card .tour-image,
        .booking-tour-card .tour-content {
            width: 100%;
        }
        
        .payment-overview {
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }
        
        .recommended-tours-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .reference-number {
            font-size: 2rem;
        }
        
        .quick-actions {
            flex-wrap: wrap;
            gap: var(--spacing-md);
        }
        
        .booking-info-grid {
            grid-template-columns: 1fr;
            gap: var(--spacing-md);
        }
        
        .recommended-tours-grid {
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }
        
        .cta-buttons {
            flex-direction: column;
            gap: var(--spacing-md);
        }
    }
    
    @media (max-width: 576px) {
        .confirmation-hero {
            min-height: 600px;
        }
        
        .confirmation-icon {
            width: 100px;
            height: 100px;
            font-size: 3rem;
        }
        
        .hero-title {
            font-size: 1.75rem;
        }
        
        .hero-subtitle {
            font-size: 1rem;
        }
        
        .reference-number {
            font-size: 1.75rem;
        }
        
        .help-box {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .tabs-nav {
            flex-wrap: wrap;
        }
        
        .tab-btn {
            flex: 1 0 auto;
            justify-content: center;
        }
    }
    
    /* Animation Classes */
    .fade-in-up {
        animation: fadeInUp 1s ease-out;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
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
        .quick-actions, .tab-btn, .recommended-tours-section, .cta-section {
            display: none !important;
        }
        
        .confirmation-hero {
            height: auto;
            min-height: auto;
            background-image: none !important;
            color: #000;
            padding: 2cm 0 1cm;
        }
        
        .confirmation-hero .overlay {
            display: none;
        }
        
        .hero-title, .hero-subtitle {
            color: #000;
        }
        
        .booking-reference {
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            backdrop-filter: none;
        }
        
        .reference-label, .reference-number, .reference-help {
            color: #000;
        }
        
        .booking-tour-card {
            border: 1px solid #ccc;
            box-shadow: none;
        }
        
        .material-card {
            border: 1px solid #ccc;
            box-shadow: none;
        }
        
        .tab-pane {
            display: block !important;
            margin-bottom: 1cm;
        }
        
        .tab-pane:before {
            content: attr(id);
            display: block;
            font-weight: bold;
            font-size: 16pt;
            margin-bottom: 0.5cm;
            text-transform: capitalize;
        }
        
        .status-tag, .tour-status {
            border: 1px solid #000;
            background-color: transparent !important;
            color: #000 !important;
        }
        
        .timeline:before {
            background-color: #ccc;
        }
        
        .timeline-marker {
            background-color: #f0f0f0 !important;
            color: #000 !important;
            border: 1px solid #ccc;
        }
        
        @page {
            margin: 1.5cm;
            size: portrait;
        }
    }
</style>

<!-- JavaScript for Tabs and Animation -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabPanes = document.querySelectorAll('.tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Show corresponding tab pane
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });
    
    // Smooth scrolling for anchor links
    document.querySelectorAll('.smooth-scroll').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
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
    });
    
    // Favorites button functionality
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
    
    // Stats Counter Animation
    function animateCounter(target) {
        const value = parseInt(target.getAttribute('data-count'));
        const duration = 2000;
        const start = 0;
        const increment = Math.ceil(value / (duration / 16));
        
        let current = start;
        const timer = setInterval(function() {
            current += increment;
            if (current >= value) {
                current = value;
                clearInterval(timer);
            }
            target.textContent = current;
        }, 16);
    }
    
    // Intersection Observer for counting animation
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('stat-value')) {
                        animateCounter(entry.target);
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        document.querySelectorAll('.stat-value').forEach(counter => {
            observer.observe(counter);
        });
    }
});
</script>