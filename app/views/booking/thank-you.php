<?php
/**
 * Booking Thank You Page
 * app/views/booking/thank-you.php
 */

// Security check - ensure we have booking data
if (!$bookingId || !$bookingData) {
    header('Location: ' . $appUrl . '/' . $currentLang . '/tours');
    exit;
}
?>

<!-- Page Header -->
<section class="page-header" style="background-image: url('<?php echo $imgUrl; ?>/hero_bg.png');">
    <div class="container">
        <div class="page-header-content">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumbs-list">
                    <li class="breadcrumbs-item">
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a>
                    </li>
                    <li class="breadcrumbs-item">
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours"><?php _e('tours'); ?></a>
                    </li>
                    <li class="breadcrumbs-item active">
                        <?php _e('booking_confirmed'); ?>
                    </li>
                </ol>
            </nav>
            <h1 class="page-title"><?php _e('booking_confirmed'); ?></h1>
        </div>
    </div>
</section>

<!-- Thank You Content -->
<section class="section">
    <div class="container">
        <div class="thank-you-wrapper">
            <!-- Success Message -->
            <div class="thank-you-header">
                <div class="success-icon">
                    <i class="material-icons">check_circle</i>
                </div>
                <h2><?php _e('booking_success_title'); ?></h2>
                <p class="success-message"><?php _e('booking_success_message'); ?></p>
            </div>

            <!-- Booking Details Card -->
            <div class="booking-confirmation-card">
                <div class="card-header">
                    <h3><?php _e('booking_details'); ?></h3>
                    <span class="booking-number"><?php _e('booking_number'); ?>: #<?php echo str_pad($bookingId, 6, '0', STR_PAD_LEFT); ?></span>
                </div>
                
                <div class="card-body">
                    <div class="booking-details-grid">
                        <!-- Tour Information -->
                        <div class="detail-section">
                            <h4><?php _e('tour_information'); ?></h4>
                            <div class="detail-item">
                                <span class="label"><?php _e('tour_name'); ?>:</span>
                                <span class="value"><?php echo htmlspecialchars($bookingData['tour_name']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label"><?php _e('booking_date'); ?>:</span>
                                <span class="value"><?php echo date('d M Y', strtotime($bookingData['booking_date'])); ?></span>
                            </div>
                        </div>

                        <!-- Customer Information -->
                        <div class="detail-section">
                            <h4><?php _e('customer_information'); ?></h4>
                            <div class="detail-item">
                                <span class="label"><?php _e('full_name'); ?>:</span>
                                <span class="value"><?php echo htmlspecialchars($bookingData['first_name'] . ' ' . $bookingData['last_name']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label"><?php _e('email'); ?>:</span>
                                <span class="value"><?php echo htmlspecialchars($bookingData['email']); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label"><?php _e('phone'); ?>:</span>
                                <span class="value"><?php echo htmlspecialchars($bookingData['phone']); ?></span>
                            </div>
                        </div>

                        <!-- Guests Information -->
                        <div class="detail-section">
                            <h4><?php _e('guests_information'); ?></h4>
                            <div class="detail-item">
                                <span class="label"><?php _e('adults'); ?>:</span>
                                <span class="value"><?php echo $bookingData['adults']; ?> <?php _e('person'); ?><?php echo $bookingData['adults'] > 1 ? 's' : ''; ?></span>
                            </div>
                            <?php if ($bookingData['children'] > 0): ?>
                            <div class="detail-item">
                                <span class="label"><?php _e('children'); ?>:</span>
                                <span class="value"><?php echo $bookingData['children']; ?> <?php _e('child'); ?><?php echo $bookingData['children'] > 1 ? 'ren' : ''; ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Payment Information -->
                        <div class="detail-section">
                            <h4><?php _e('payment_information'); ?></h4>
                            <div class="detail-item">
                                <span class="label"><?php _e('total_amount'); ?>:</span>
                                <span class="value total-price"><?php echo $settings['currency_symbol'] . number_format($bookingData['total_price'], 2); ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="label"><?php _e('status'); ?>:</span>
                                <span class="value">
                                    <span class="status-badge status-pending"><?php _e('pending_payment'); ?></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($bookingData['special_requests'])): ?>
                    <!-- Special Requests -->
                    <div class="special-requests">
                        <h4><?php _e('special_requests'); ?></h4>
                        <p><?php echo nl2br(htmlspecialchars($bookingData['special_requests'])); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="next-steps-card">
                <h3><?php _e('what_happens_next'); ?></h3>
                <div class="steps-list">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h4><?php _e('confirmation_email'); ?></h4>
                            <p><?php _e('confirmation_email_description'); ?></p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h4><?php _e('contact_from_team'); ?></h4>
                            <p><?php _e('contact_from_team_description'); ?></p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h4><?php _e('payment_instructions'); ?></h4>
                            <p><?php _e('payment_instructions_description'); ?></p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h4><?php _e('enjoy_your_tour'); ?></h4>
                            <p><?php _e('enjoy_your_tour_description'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="contact-info-card">
                <h3><?php _e('need_help'); ?></h3>
                <p><?php _e('contact_us_if_questions'); ?></p>
                <div class="contact-methods">
                    <a href="tel:<?php echo $settings['contact_phone']; ?>" class="contact-method">
                        <i class="material-icons">phone</i>
                        <span><?php echo $settings['contact_phone']; ?></span>
                    </a>
                    <a href="mailto:<?php echo $settings['contact_email']; ?>" class="contact-method">
                        <i class="material-icons">email</i>
                        <span><?php echo $settings['contact_email']; ?></span>
                    </a>
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="contact-method">
                        <i class="material-icons">chat</i>
                        <span><?php _e('contact_form'); ?></span>
                    </a>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="thank-you-actions">
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-outline">
                    <i class="material-icons">explore</i>
                    <?php _e('browse_more_tours'); ?>
                </a>
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>" class="btn btn-primary">
                    <i class="material-icons">home</i>
                    <?php _e('back_to_homepage'); ?>
                </a>
                <button onclick="window.print()" class="btn btn-secondary">
                    <i class="material-icons">print</i>
                    <?php _e('print_confirmation'); ?>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Recommended Tours Section -->
<section class="section" style="background-color: var(--gray-100);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title"><?php _e('you_might_also_like'); ?></h2>
            <p class="section-subtitle"><?php _e('discover_more_amazing_tours'); ?></p>
        </div>
        
        <?php
        // Get recommended tours (you would implement this in the controller)
        if (isset($recommendedTours) && !empty($recommendedTours)): 
        ?>
        <div class="tours-grid">
            <?php foreach ($recommendedTours as $tour): ?>
            <article class="tour-card">
                <div class="tour-image">
                    <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo htmlspecialchars($tour['name']); ?>">
                    <div class="tour-price">
                        <?php if ($tour['discount_price'] > 0): ?>
                            <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                            <?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?>
                        <?php else: ?>
                            <?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($tour['category_name'])): ?>
                    <div class="tour-category"><?php echo htmlspecialchars($tour['category_name']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="tour-content">
                    <h3 class="tour-title">
                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>">
                            <?php echo htmlspecialchars($tour['name']); ?>
                        </a>
                    </h3>
                    <div class="tour-meta">
                        <span class="tour-meta-item">
                            <i class="material-icons">schedule</i>
                            <?php echo $tour['duration']; ?> <?php _e('days'); ?>
                        </span>
                        <span class="tour-meta-item">
                            <i class="material-icons">people</i>
                            <?php echo $tour['max_people']; ?> <?php _e('people'); ?>
                        </span>
                    </div>
                    <p class="tour-description"><?php echo substr(strip_tags($tour['short_description']), 0, 100); ?>...</p>
                    <div class="tour-footer">
                        <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $tour['slug']; ?>" class="btn btn-primary btn-sm">
                            <?php _e('view_details'); ?>
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Custom Styles for Thank You Page -->
<style>
.thank-you-wrapper {
    max-width: 800px;
    margin: 0 auto;
}

.thank-you-header {
    text-align: center;
    margin-bottom: var(--spacing-xxl);
}

.success-icon {
    width: 100px;
    height: 100px;
    border-radius: var(--border-radius-circle);
    background-color: var(--success-color);
    color: var(--white-color);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--spacing-lg);
    font-size: 3rem;
    animation: successPulse 2s ease-in-out infinite;
}

.success-icon i {
    font-size: 3rem;
}

.thank-you-header h2 {
    color: var(--success-color);
    margin-bottom: var(--spacing-md);
}

.success-message {
    font-size: var(--font-size-lg);
    color: var(--gray-600);
}

.booking-confirmation-card,
.next-steps-card,
.contact-info-card {
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    margin-bottom: var(--spacing-xl);
    overflow: hidden;
}

.card-header {
    padding: var(--spacing-lg);
    background-color: var(--gray-100);
    border-bottom: 1px solid var(--gray-200);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.card-header h3 {
    margin-bottom: 0;
    color: var(--dark-color);
}

.booking-number {
    background-color: var(--primary-color);
    color: var(--white-color);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius-md);
    font-weight: var(--font-weight-medium);
    font-size: var(--font-size-sm);
}

.card-body {
    padding: var(--spacing-lg);
}

.booking-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-lg);
}

.detail-section h4 {
    color: var(--primary-color);
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-sm);
    border-bottom: 2px solid var(--primary-color);
}

.detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: var(--spacing-sm);
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-100);
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-item .label {
    font-weight: var(--font-weight-medium);
    color: var(--gray-600);
}

.detail-item .value {
    font-weight: var(--font-weight-medium);
    color: var(--dark-color);
}

.total-price {
    font-size: var(--font-size-lg);
    color: var(--primary-color);
    font-weight: var(--font-weight-bold);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
}

.status-pending {
    background-color: rgba(255, 193, 7, 0.1);
    color: var(--warning-color);
}

.special-requests {
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--gray-200);
}

.special-requests h4 {
    margin-bottom: var(--spacing-sm);
}

.special-requests p {
    background-color: var(--gray-100);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    font-style: italic;
}

.steps-list {
    padding: var(--spacing-lg);
}

.step-item {
    display: flex;
    margin-bottom: var(--spacing-lg);
}

.step-item:last-child {
    margin-bottom: 0;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius-circle);
    background-color: var(--primary-color);
    color: var(--white-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: var(--font-weight-bold);
    margin-right: var(--spacing-md);
    flex-shrink: 0;
}

.step-content h4 {
    margin-bottom: var(--spacing-xs);
    color: var(--dark-color);
}

.step-content p {
    margin-bottom: 0;
    color: var(--gray-600);
}

.contact-methods {
    display: flex;
    gap: var(--spacing-md);
    padding: var(--spacing-lg);
}

.contact-method {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: var(--spacing-md);
    background-color: var(--gray-100);
    border-radius: var(--border-radius-md);
    color: var(--dark-color);
    transition: all var(--transition-fast);
    flex: 1;
    text-align: center;
    justify-content: center;
}

.contact-method:hover {
    background-color: var(--primary-color);
    color: var(--white-color);
    transform: translateY(-2px);
}

.thank-you-actions {
    display: flex;
    gap: var(--spacing-md);
    justify-content: center;
    flex-wrap: wrap;
}

.thank-you-actions .btn {
    min-width: 200px;
}

@keyframes successPulse {
    0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
    }
    50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
    }
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .booking-details-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: var(--spacing-sm);
    }
    
    .contact-methods {
        flex-direction: column;
    }
    
    .thank-you-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .thank-you-actions .btn {
        width: 100%;
        max-width: 300px;
    }
    
    .detail-item {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .detail-item .label {
        font-size: var(--font-size-sm);
    }
}

/* Print Styles */
@media print {
    .thank-you-actions,
    .next-steps-card,
    .contact-info-card {
        display: none;
    }
    
    .success-icon {
        animation: none;
    }
    
    .booking-confirmation-card {
        box-shadow: none;
        border: 1px solid #ddd;
    }
}
</style>