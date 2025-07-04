<?php
/**
 * Booking Track View - Display Booking Status
 */

// Hero arka plan görseli
$trackingHeroBg = isset($settings['tracking_hero_bg']) ? $settings['tracking_hero_bg'] : 'booking-hero-bg.jpg';
?>

<!-- Hero Section -->
<section class="hero-section tracking-hero" style="background-image: url('<?php echo $imgUrl; ?>/<?php echo $trackingHeroBg; ?>');">
    <div class="overlay"></div>
    <div class="container">
        <div class="hero-content text-center">
            <!-- Status Icon -->
            <div class="status-icon-wrapper" data-aos="zoom-in">
                <div class="status-icon status-<?php echo $booking['status']; ?>">
                    <?php
                    $statusIcons = [
                        'pending' => 'schedule',
                        'confirmed' => 'check_circle',
                        'cancelled' => 'cancel',
                        'completed' => 'verified'
                    ];
                    $icon = isset($statusIcons[$booking['status']]) ? $statusIcons[$booking['status']] : 'help';
                    ?>
                    <i class="material-icons"><?php echo $icon; ?></i>
                </div>
            </div>
            
            <h1 class="hero-title" data-aos="fade-up" data-aos-delay="200">
                <?php echo sprintf(__('booking_status_title'), Booking::formatReference($booking['id'])); ?>
            </h1>
            <p class="hero-subtitle" data-aos="fade-up" data-aos-delay="300">
                <?php echo Booking::getStatusLabel($booking['status']); ?>
            </p>
            
            <!-- Quick Info -->
            <div class="quick-info" data-aos="fade-up" data-aos-delay="400">
                <div class="info-item">
                    <i class="material-icons">event</i>
                    <span><?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></span>
                </div>
                <div class="info-item">
                    <i class="material-icons">group</i>
                    <span><?php echo $booking['adults']; ?> <?php _e('adults'); ?><?php echo $booking['children'] > 0 ? ' + ' . $booking['children'] . ' ' . __('children') : ''; ?></span>
                </div>
                <div class="info-item">
                    <i class="material-icons">payments</i>
                    <span><?php echo $settings['currency_symbol']; ?><?php echo number_format($booking['total_price'], 2); ?></span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Tracking Content Section -->
<section class="section tracking-section">
    <div class="container">
        <div class="tracking-wrapper" data-aos="fade-up">
            <div class="tracking-content">
                
                <!-- Status Timeline -->
                <div class="tracking-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="material-icons">timeline</i>
                            <?php _e('booking_timeline'); ?>
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item <?php echo $booking['status'] === 'pending' ? 'active' : 'completed'; ?>">
                                <div class="timeline-marker">
                                    <i class="material-icons">add_circle</i>
                                </div>
                                <div class="timeline-content">
                                    <h5><?php _e('booking_created'); ?></h5>
                                    <p><?php _e('booking_created_description'); ?></p>
                                    <div class="timeline-date">
                                        <?php echo date('F j, Y \a\t H:i', strtotime($booking['created_at'])); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="timeline-item <?php echo in_array($booking['status'], ['confirmed', 'completed']) ? 'completed' : ($booking['status'] === 'pending' ? 'active' : 'inactive'); ?>">
                                <div class="timeline-marker">
                                    <i class="material-icons">email</i>
                                </div>
                                <div class="timeline-content">
                                    <h5><?php _e('under_review'); ?></h5>
                                    <p><?php _e('under_review_description'); ?></p>
                                    <?php if (in_array($booking['status'], ['confirmed', 'completed'])): ?>
                                        <div class="timeline-date">
                                            <?php echo date('F j, Y \a\t H:i', strtotime($booking['updated_at'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="timeline-item <?php echo in_array($booking['status'], ['confirmed', 'completed']) ? 'completed' : 'inactive'; ?>">
                                <div class="timeline-marker">
                                    <i class="material-icons">check_circle</i>
                                </div>
                                <div class="timeline-content">
                                    <h5><?php _e('booking_confirmed'); ?></h5>
                                    <p><?php _e('booking_confirmed_description'); ?></p>
                                    <?php if (in_array($booking['status'], ['confirmed', 'completed'])): ?>
                                        <div class="timeline-date">
                                            <?php echo date('F j, Y \a\t H:i', strtotime($booking['updated_at'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="timeline-item <?php echo $booking['status'] === 'completed' ? 'completed' : 'inactive'; ?>">
                                <div class="timeline-marker">
                                    <i class="material-icons">explore</i>
                                </div>
                                <div class="timeline-content">
                                    <h5><?php _e('tour_completed'); ?></h5>
                                    <p><?php _e('tour_completed_description'); ?></p>
                                    <?php if ($booking['status'] === 'completed'): ?>
                                        <div class="timeline-date">
                                            <?php echo date('F j, Y', strtotime($booking['booking_date'])); ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="timeline-date">
                                            <?php echo date('F j, Y', strtotime($booking['booking_date'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <?php if ($booking['status'] === 'cancelled'): ?>
                                <div class="timeline-item cancelled">
                                    <div class="timeline-marker">
                                        <i class="material-icons">cancel</i>
                                    </div>
                                    <div class="timeline-content">
                                        <h5><?php _e('booking_cancelled'); ?></h5>
                                        <p><?php _e('booking_cancelled_description'); ?></p>
                                        <div class="timeline-date">
                                            <?php echo date('F j, Y \a\t H:i', strtotime($booking['updated_at'])); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Booking Details -->
                <div class="tracking-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="material-icons">receipt</i>
                            <?php _e('booking_details'); ?>
                        </h3>
                    </div>
                    
                    <div class="card-body">
                        <div class="booking-details-grid">
                            <!-- Tour Information -->
                            <div class="detail-section">
                                <h4 class="section-title"><?php _e('tour_information'); ?></h4>
                                <div class="tour-preview">
                                    <?php if ($booking['featured_image']): ?>
                                        <div class="tour-image">
                                            <img src="<?php echo $uploadsUrl . '/tours/' . $booking['featured_image']; ?>" alt="<?php echo $booking['tour_name']; ?>">
                                        </div>
                                    <?php endif; ?>
                                    <div class="tour-info">
                                        <h5><?php echo htmlspecialchars($booking['tour_name']); ?></h5>
                                        <div class="detail-row">
                                            <span class="label"><?php _e('tour_date'); ?>:</span>
                                            <span class="value"><?php echo date('F j, Y', strtotime($booking['booking_date'])); ?></span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="label"><?php _e('participants'); ?>:</span>
                                            <span class="value">
                                                <?php echo $booking['adults']; ?> <?php _e('adults'); ?>
                                                <?php if ($booking['children'] > 0): ?>
                                                    + <?php echo $booking['children']; ?> <?php _e('children'); ?>
                                                <?php endif; ?>
                                            </span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="label"><?php _e('total_price'); ?>:</span>
                                            <span class="value price"><?php echo $settings['currency_symbol']; ?><?php echo number_format($booking['total_price'], 2); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Customer Information -->
                            <div class="detail-section">
                                <h4 class="section-title"><?php _e('customer_information'); ?></h4>
                                <div class="customer-info">
                                    <div class="detail-row">
                                        <span class="label"><?php _e('name'); ?>:</span>
                                        <span class="value"><?php echo htmlspecialchars($booking['first_name'] . ' ' . $booking['last_name']); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label"><?php _e('email'); ?>:</span>
                                        <span class="value"><?php echo htmlspecialchars($booking['email']); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label"><?php _e('phone'); ?>:</span>
                                        <span class="value"><?php echo htmlspecialchars($booking['phone']); ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <span class="label"><?php _e('payment_method'); ?>:</span>
                                        <span class="value">
                                            <?php
                                            $paymentMethods = [
                                                'card' => __('credit_card'),
                                                'paypal' => __('paypal'),
                                                'bank' => __('bank_transfer'),
                                                'cash' => __('cash_payment')
                                            ];
                                            echo isset($paymentMethods[$booking['payment_method']]) ? $paymentMethods[$booking['payment_method']] : ucfirst($booking['payment_method']);
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($booking['special_requests'])): ?>
                            <div class="special-requests">
                                <h4 class="section-title"><?php _e('special_requests'); ?></h4>
                                <div class="requests-content">
                                    <?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Status-specific information -->
                <?php if ($booking['status'] === 'pending'): ?>
                    <div class="tracking-card status-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="material-icons">info</i>
                                <?php _e('what_happens_next'); ?>
                            </h3>
                        </div>
                        
                        <div class="card-body">
                            <div class="info-alert alert-info">
                                <i class="material-icons">schedule</i>
                                <div class="alert-content">
                                    <h5><?php _e('awaiting_confirmation'); ?></h5>
                                    <p><?php _e('pending_booking_message'); ?></p>
                                </div>
                            </div>
                            
                            <div class="next-steps">
                                <h5><?php _e('next_steps'); ?>:</h5>
                                <ul>
                                    <li><?php _e('review_team_message'); ?></li>
                                    <li><?php _e('confirmation_email_message'); ?></li>
                                    <li><?php _e('payment_instructions_message'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php elseif ($booking['status'] === 'confirmed'): ?>
                    <div class="tracking-card status-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="material-icons">check_circle</i>
                                <?php _e('booking_confirmed'); ?>
                            </h3>
                        </div>
                        
                        <div class="card-body">
                            <div class="info-alert alert-success">
                                <i class="material-icons">check_circle</i>
                                <div class="alert-content">
                                    <h5><?php _e('great_news'); ?>!</h5>
                                    <p><?php _e('confirmed_booking_message'); ?></p>
                                </div>
                            </div>
                            
                            <div class="preparation-tips">
                                <h5><?php _e('preparation_tips'); ?>:</h5>
                                <ul>
                                    <li><?php _e('arrive_on_time'); ?></li>
                                    <li><?php _e('bring_comfortable_shoes'); ?></li>
                                    <li><?php _e('bring_camera'); ?></li>
                                    <li><?php _e('check_weather'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php elseif ($booking['status'] === 'cancelled'): ?>
                    <div class="tracking-card status-info">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="material-icons">cancel</i>
                                <?php _e('booking_cancelled'); ?>
                            </h3>
                        </div>
                        
                        <div class="card-body">
                            <div class="info-alert alert-warning">
                                <i class="material-icons">info</i>
                                <div class="alert-content">
                                    <h5><?php _e('booking_cancelled_title'); ?></h5>
                                    <p><?php _e('cancelled_booking_message'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Sidebar -->
            <div class="tracking-sidebar">
                <!-- Contact Card -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">support_agent</i>
                        <?php _e('need_assistance'); ?>
                    </h4>
                    
                    <p><?php _e('booking_assistance_text'); ?></p>
                    
                    <div class="contact-methods">
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $settings['contact_phone'] ?? ''); ?>" class="contact-method">
                            <i class="material-icons">phone</i>
                            <div class="contact-info">
                                <div class="contact-label"><?php _e('call_us'); ?></div>
                                <div class="contact-value"><?php echo $settings['contact_phone'] ?? '+90 123 456 7890'; ?></div>
                            </div>
                        </a>
                        
                        <a href="mailto:<?php echo $settings['contact_email'] ?? ''; ?>" class="contact-method">
                            <i class="material-icons">email</i>
                            <div class="contact-info">
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
                
                <!-- Share & Actions -->
                <div class="sidebar-card">
                    <h4 class="sidebar-card-title">
                        <i class="material-icons">more_horiz</i>
                        <?php _e('actions'); ?>
                    </h4>
                    
                    <div class="action-buttons">
                        <button onclick="window.print()" class="btn btn-outline btn-block">
                            <i class="material-icons">print</i>
                            <?php _e('print_details'); ?>
                        </button>
                        
                        <button onclick="copyTrackingLink()" class="btn btn-outline btn-block" id="copy-btn">
                            <i class="material-icons">link</i>
                            <?php _e('copy_tracking_link'); ?>
                        </button>
                        
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-primary btn-block">
                            <i class="material-icons">explore</i>
                            <?php _e('explore_more_tours'); ?>
                        </a>
                        
                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/booking/search" class="btn btn-outline btn-block">
                            <i class="material-icons">search</i>
                            <?php _e('track_another_booking'); ?>
                        </a>
                    </div>
                </div>
                
                <!-- Bookmark Notice -->
                <div class="sidebar-card highlight-card">
                    <div class="bookmark-notice">
                        <i class="material-icons">bookmark</i>
                        <h5><?php _e('bookmark_this_page'); ?></h5>
                        <p><?php _e('bookmark_page_message'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Tracking Hero */
.tracking-hero {
    height: 70vh;
    min-height: 400px;
    display: flex;
    align-items: center;
    position: relative;
    background-position: center;
    background-size: cover;
    background-attachment: fixed;
    color: var(--white-color);
}

.tracking-hero .overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(rgba(30, 30, 30, 0.7), rgba(30, 30, 30, 0.8));
    z-index: 1;
}

.tracking-hero .container {
    position: relative;
    z-index: 2;
}

.status-icon-wrapper {
    margin-bottom: var(--spacing-lg);
}

.status-icon {
    width: 80px;
    height: 80px;
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    backdrop-filter: blur(10px);
}

.status-icon.status-pending {
    background-color: rgba(255, 193, 7, 0.9);
    color: var(--dark-color);
}

.status-icon.status-confirmed {
    background-color: rgba(40, 167, 69, 0.9);
    color: var(--white-color);
}

.status-icon.status-cancelled {
    background-color: rgba(220, 53, 69, 0.9);
    color: var(--white-color);
}

.status-icon.status-completed {
    background-color: rgba(67, 97, 238, 0.9);
    color: var(--white-color);
}

.status-icon i {
    font-size: 2.5rem;
}

.quick-info {
    display: flex;
    justify-content: center;
    gap: var(--spacing-lg);
    margin-top: var(--spacing-lg);
    flex-wrap: wrap;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background-color: rgba(255, 255, 255, 0.15);
    padding: 0.75rem 1rem;
    border-radius: var(--border-radius-md);
    backdrop-filter: blur(10px);
}

.info-item i {
    color: var(--white-color);
}

/* Tracking Section */
.tracking-section {
    padding: var(--spacing-xl) 0;
    background-color: var(--gray-50);
}

.tracking-wrapper {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: var(--spacing-xl);
}

.tracking-card {
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

/* Timeline */
.timeline {
    position: relative;
    margin: 0;
}

.timeline::before {
    content: '';
    position: absolute;
    top: 0;
    left: 25px;
    height: 100%;
    width: 2px;
    background-color: var(--gray-300);
}

.timeline-item {
    position: relative;
    padding-left: 70px;
    padding-bottom: var(--spacing-xl);
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-marker {
    position: absolute;
    top: 0;
    left: 0;
    width: 50px;
    height: 50px;
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--white-color);
    border: 3px solid var(--gray-300);
    z-index: 1;
}

.timeline-item.completed .timeline-marker {
    background-color: var(--success-color);
    border-color: var(--success-color);
    color: var(--white-color);
}

.timeline-item.active .timeline-marker {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: var(--white-color);
    animation: pulse 2s infinite;
}

.timeline-item.cancelled .timeline-marker {
    background-color: var(--danger-color);
    border-color: var(--danger-color);
    color: var(--white-color);
}

.timeline-item.inactive .timeline-marker {
    background-color: var(--gray-100);
    border-color: var(--gray-300);
    color: var(--gray-500);
}

.timeline-content h5 {
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.timeline-content p {
    margin-bottom: var(--spacing-sm);
    color: var(--gray-600);
    font-size: var(--font-size-sm);
}

.timeline-date {
    font-size: var(--font-size-xs);
    color: var(--gray-500);
    font-weight: var(--font-weight-medium);
}

/* Booking Details */
.booking-details-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-xl);
    margin-bottom: var(--spacing-lg);
}

.detail-section {
    padding: var(--spacing-md);
    background-color: var(--gray-50);
    border-radius: var(--border-radius-md);
}

.section-title {
    font-size: var(--font-size-md);
    font-weight: var(--font-weight-bold);
    color: var(--dark-color);
    margin-bottom: var(--spacing-md);
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--primary-color);
}

.tour-preview {
    display: flex;
    gap: var(--spacing-md);
}

.tour-image {
    width: 80px;
    height: 80px;
    border-radius: var(--border-radius-md);
    overflow: hidden;
    flex-shrink: 0;
}

.tour-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.tour-info {
    flex: 1;
}

.tour-info h5 {
    margin-bottom: var(--spacing-sm);
    color: var(--dark-color);
}

.detail-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.detail-row .label {
    color: var(--gray-600);
    font-size: var(--font-size-sm);
}

.detail-row .value {
    font-weight: var(--font-weight-medium);
    color: var(--dark-color);
    font-size: var(--font-size-sm);
}

.detail-row .value.price {
    color: var(--primary-color);
    font-weight: var(--font-weight-bold);
}

.customer-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.special-requests {
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--gray-200);
}

.requests-content {
    background-color: var(--gray-100);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    font-style: italic;
    color: var(--gray-700);
}

/* Status Info */
.status-info .card-body {
    padding: var(--spacing-md);
}

.info-alert {
    display: flex;
    gap: var(--spacing-md);
    padding: var(--spacing-md);
    border-radius: var(--border-radius-md);
    margin-bottom: var(--spacing-md);
}

.alert-info {
    background-color: rgba(67, 97, 238, 0.1);
    border-left: 4px solid var(--primary-color);
}

.alert-success {
    background-color: rgba(40, 167, 69, 0.1);
    border-left: 4px solid var(--success-color);
}

.alert-warning {
    background-color: rgba(255, 193, 7, 0.1);
    border-left: 4px solid var(--warning-color);
}

.info-alert i {
    font-size: 1.5rem;
    margin-top: 0.25rem;
}

.alert-info i {
    color: var(--primary-color);
}

.alert-success i {
    color: var(--success-color);
}

.alert-warning i {
    color: var(--warning-color);
}

.alert-content h5 {
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.alert-content p {
    margin: 0;
    color: var(--gray-700);
}

.next-steps, .preparation-tips {
    margin-top: var(--spacing-md);
}

.next-steps h5, .preparation-tips h5 {
    margin-bottom: var(--spacing-sm);
    color: var(--dark-color);
}

.next-steps ul, .preparation-tips ul {
    margin: 0;
    padding-left: 1.5rem;
}

.next-steps li, .preparation-tips li {
    margin-bottom: 0.5rem;
    color: var(--gray-700);
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

.contact-methods {
    margin: var(--spacing-md) 0;
}

.contact-method {
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

.contact-method:hover {
    background-color: var(--gray-100);
}

.contact-method i {
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

.contact-info {
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

.highlight-card {
    background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(67, 97, 238, 0.05));
    border: 2px solid var(--primary-color);
}

.bookmark-notice {
    text-align: center;
}

.bookmark-notice i {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.bookmark-notice h5 {
    margin-bottom: 0.5rem;
    color: var(--dark-color);
}

.bookmark-notice p {
    margin: 0;
    color: var(--gray-600);
    font-size: var(--font-size-sm);
}

/* Responsive */
@media (max-width: 992px) {
    .tracking-wrapper {
        grid-template-columns: 1fr;
        gap: var(--spacing-lg);
    }
    
    .booking-details-grid {
        grid-template-columns: 1fr;
        gap: var(--spacing-md);
    }
}

@media (max-width: 768px) {
    .quick-info {
        flex-direction: column;
        align-items: center;
        gap: var(--spacing-md);
    }
    
    .tour-preview {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .tour-image {
        width: 120px;
        height: 120px;
    }
    
    .detail-row {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .detail-row .value {
        text-align: left;
    }
    
    .timeline-item {
        padding-left: 60px;
    }
    
    .timeline::before {
        left: 20px;
    }
    
    .timeline-marker {
        width: 40px;
        height: 40px;
        left: 0;
    }
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(67, 97, 238, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(67, 97, 238, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(67, 97, 238, 0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy tracking link functionality
    window.copyTrackingLink = function() {
        const url = window.location.href;
        
        if (navigator.clipboard) {
            navigator.clipboard.writeText(url).then(function() {
                showCopySuccess();
            }).catch(function() {
                fallbackCopyToClipboard(url);
            });
        } else {
            fallbackCopyToClipboard(url);
        }
    };
    
    function fallbackCopyToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        textArea.style.top = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            showCopySuccess();
        } catch (err) {
            console.error('Failed to copy text: ', err);
        }
        
        document.body.removeChild(textArea);
    }
    
    function showCopySuccess() {
        const copyBtn = document.getElementById('copy-btn');
        const originalText = copyBtn.innerHTML;
        
        copyBtn.innerHTML = '<i class="material-icons">check</i><?php _e('link_copied'); ?>';
        copyBtn.style.backgroundColor = 'var(--success-color)';
        copyBtn.style.color = 'var(--white-color)';
        
        setTimeout(() => {
            copyBtn.innerHTML = originalText;
            copyBtn.style.backgroundColor = '';
            copyBtn.style.color = '';
        }, 2000);
    }
});
</script>