<?php
/**
 * Admin Dashboard View
 */
?>

<!-- Dashboard Stats -->
<div class="dashboard-stats">
    <div class="stats-grid">
        <div class="stat-card" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-icon">
                <i class="material-icons">explore</i>
            </div>
            <div class="stat-content">
                <h2 class="stat-value"><?php echo number_format($totalTours); ?></h2>
                <p class="stat-label"><?php _e('total_tours'); ?></p>
            </div>
            <a href="<?php echo $adminUrl; ?>/tours" class="stat-link">
                <i class="material-icons">arrow_forward</i>
            </a>
        </div>
        
        <div class="stat-card" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-icon">
                <i class="material-icons">book_online</i>
            </div>
            <div class="stat-content">
                <h2 class="stat-value"><?php echo number_format($totalBookings); ?></h2>
                <p class="stat-label"><?php _e('total_bookings'); ?></p>
            </div>
            <a href="<?php echo $adminUrl; ?>/bookings" class="stat-link">
                <i class="material-icons">arrow_forward</i>
            </a>
        </div>
        
        <div class="stat-card" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-icon">
                <i class="material-icons">category</i>
            </div>
            <div class="stat-content">
                <h2 class="stat-value"><?php echo number_format($totalCategories); ?></h2>
                <p class="stat-label"><?php _e('total_categories'); ?></p>
            </div>
            <a href="<?php echo $adminUrl; ?>/categories" class="stat-link">
                <i class="material-icons">arrow_forward</i>
            </a>
        </div>
        
        <div class="stat-card" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-icon">
                <i class="material-icons">people</i>
            </div>
            <div class="stat-content">
                <h2 class="stat-value"><?php echo number_format($totalUsers); ?></h2>
                <p class="stat-label"><?php _e('total_users'); ?></p>
            </div>
            <a href="<?php echo $adminUrl; ?>/users" class="stat-link">
                <i class="material-icons">arrow_forward</i>
            </a>
        </div>
    </div>
</div>

<!-- Dashboard Widgets -->
<div class="dashboard-widgets">
    <div class="dashboard-row">
        <!-- Sales Chart -->
        <div class="dashboard-widget widget-large" data-aos="fade-up">
            <div class="widget-header">
                <h3 class="widget-title"><?php _e('sales_last_30_days'); ?></h3>
                <div class="widget-actions">
                    <button type="button" class="widget-action">
                        <i class="material-icons">more_vert</i>
                    </button>
                </div>
            </div>
            <div class="widget-content">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        <!-- Recent Bookings -->
        <div class="dashboard-widget" data-aos="fade-up">
            <div class="widget-header">
                <h3 class="widget-title"><?php _e('recent_bookings'); ?></h3>
                <div class="widget-actions">
                    <a href="<?php echo $adminUrl; ?>/bookings" class="widget-action">
                        <i class="material-icons">visibility</i>
                    </a>
                </div>
            </div>
            <div class="widget-content">
                <div class="recent-bookings">
                    <?php if (empty($recentBookings)): ?>
                        <div class="empty-state">
                            <i class="material-icons">event_busy</i>
                            <p><?php _e('no_recent_bookings'); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="bookings-list">
                            <?php foreach ($recentBookings as $booking): ?>
                                <div class="booking-item">
                                    <div class="booking-icon">
                                        <i class="material-icons">event</i>
                                    </div>
                                    <div class="booking-content">
                                        <h4 class="booking-title">
                                            <?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?>
                                            <span class="booking-tour"><?php echo $booking['tour_name']; ?></span>
                                        </h4>
                                        <div class="booking-meta">
                                            <span class="booking-date">
                                                <i class="material-icons">calendar_today</i>
                                                <?php echo date('M d, Y', strtotime($booking['booking_date'])); ?>
                                            </span>
                                            <span class="booking-price">
                                                <i class="material-icons">monetization_on</i>
                                                <?php echo $settings['currency_symbol'] . number_format($booking['total_price'], 2); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="booking-status booking-status-<?php echo $booking['status']; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="dashboard-row">
        <!-- Quick Actions -->
        <div class="dashboard-widget" data-aos="fade-up">
            <div class="widget-header">
                <h3 class="widget-title"><?php _e('quick_actions'); ?></h3>
            </div>
            <div class="widget-content">
                <div class="quick-actions">
                    <a href="<?php echo $adminUrl; ?>/tours/create" class="quick-action">
                        <i class="material-icons">add_circle</i>
                        <span><?php _e('add_tour'); ?></span>
                    </a>
                    <a href="<?php echo $adminUrl; ?>/categories/create" class="quick-action">
                        <i class="material-icons">create_new_folder</i>
                        <span><?php _e('add_category'); ?></span>
                    </a>
                    <a href="<?php echo $adminUrl; ?>/gallery/create" class="quick-action">
                        <i class="material-icons">add_photo_alternate</i>
                        <span><?php _e('add_gallery'); ?></span>
                    </a>
                    <a href="<?php echo $adminUrl; ?>/users/create" class="quick-action">
                        <i class="material-icons">person_add</i>
                        <span><?php _e('add_user'); ?></span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- System Info -->
        <div class="dashboard-widget" data-aos="fade-up">
            <div class="widget-header">
                <h3 class="widget-title"><?php _e('system_info'); ?></h3>
            </div>
            <div class="widget-content">
                <div class="system-info">
                    <div class="info-item">
                        <div class="info-label"><?php _e('php_version'); ?></div>
                        <div class="info-value"><?php echo phpversion(); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><?php _e('database'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><?php _e('server'); ?></div>
                        <div class="info-value"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><?php _e('memory_limit'); ?></div>
                        <div class="info-value"><?php echo ini_get('memory_limit'); ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label"><?php _e('max_upload_size'); ?></div>
                        <div class="info-value"><?php echo ini_get('upload_max_filesize'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart
    const salesChart = document.getElementById('salesChart');
    
    if (salesChart) {
        const salesData = <?php echo json_encode($salesData); ?>;
        
        const labels = salesData.map(item => item.date);
        const data = salesData.map(item => item.total);
        
        new Chart(salesChart, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: '<?php _e('daily_sales'); ?>',
                    data: data,
                    backgroundColor: 'rgba(255, 107, 107, 0.2)',
                    borderColor: 'rgba(255, 107, 107, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    pointBackgroundColor: 'rgba(255, 107, 107, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '<?php echo $settings['currency_symbol']; ?>' + value;
                            }
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '<?php _e('sales'); ?>: <?php echo $settings['currency_symbol']; ?>' + context.raw;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<style>
/* Dashboard Stats */
.dashboard-stats {
    margin-bottom: var(--spacing-xl);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: var(--spacing-lg);
}

.stat-card {
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    padding: var(--spacing-lg);
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    transition: transform var(--transition-medium), box-shadow var(--transition-medium);
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
}

.stat-card:nth-child(1)::before {
    background-color: #FF6B6B;
}

.stat-card:nth-child(2)::before {
    background-color: #4ECDC4;
}

.stat-card:nth-child(3)::before {
    background-color: #FFE66D;
}

.stat-card:nth-child(4)::before {
    background-color: #6A8FFF;
}

.stat-icon {
    background-color: rgba(255, 107, 107, 0.1);
    color: #FF6B6B;
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: var(--spacing-md);
    font-size: 2rem;
}

.stat-card:nth-child(1) .stat-icon {
    background-color: rgba(255, 107, 107, 0.1);
    color: #FF6B6B;
}

.stat-card:nth-child(2) .stat-icon {
    background-color: rgba(78, 205, 196, 0.1);
    color: #4ECDC4;
}

.stat-card:nth-child(3) .stat-icon {
    background-color: rgba(255, 230, 109, 0.1);
    color: #FFD700;
}

.stat-card:nth-child(4) .stat-icon {
    background-color: rgba(106, 143, 255, 0.1);
    color: #6A8FFF;
}

.stat-content {
    flex: 1;
}

.stat-value {
    font-size: var(--font-size-xl);
    font-weight: var(--font-weight-bold);
    margin-bottom: 0;
    line-height: 1;
}

.stat-label {
    font-size: var(--font-size-sm);
    color: #777;
    margin-bottom: 0;
}

.stat-link {
    width: 30px;
    height: 30px;
    border-radius: var(--border-radius-circle);
    background-color: #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color var(--transition-fast), color var(--transition-fast);
}

.stat-link:hover {
    background-color: var(--primary-color);
    color: var(--white-color);
}

/* Dashboard Widgets */
.dashboard-widgets {
    margin-bottom: var(--spacing-xl);
}

.dashboard-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-lg);
    margin-bottom: var(--spacing-lg);
}

.dashboard-widget {
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-md);
    overflow: hidden;
}

.widget-large {
    grid-column: span 1;
}

.widget-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-md) var(--spacing-lg);
    border-bottom: 1px solid #eee;
}

.widget-title {
    font-size: var(--font-size-lg);
    margin-bottom: 0;
}

.widget-actions {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.widget-action {
    width: 36px;
    height: 36px;
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #777;
    transition: background-color var(--transition-fast), color var(--transition-fast);
}

.widget-action:hover {
    background-color: #f0f0f0;
    color: var(--dark-color);
}

.widget-content {
    padding: var(--spacing-lg);
}

/* Recent Bookings */
.empty-state {
    text-align: center;
    padding: var(--spacing-xl) 0;
    color: #aaa;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: var(--spacing-md);
}

.bookings-list {
    max-height: 350px;
    overflow-y: auto;
}

.booking-item {
    display: flex;
    align-items: center;
    padding: var(--spacing-md);
    border-bottom: 1px solid #eee;
    transition: background-color var(--transition-fast);
}

.booking-item:last-child {
    border-bottom: none;
}

.booking-item:hover {
    background-color: #f9f9f9;
}

.booking-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius-circle);
    background-color: rgba(78, 205, 196, 0.1);
    color: #4ECDC4;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: var(--spacing-md);
}

.booking-content {
    flex: 1;
}

.booking-title {
    font-size: var(--font-size-md);
    margin-bottom: 0.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.booking-tour {
    font-size: var(--font-size-sm);
    color: #777;
    font-weight: var(--font-weight-regular);
}

.booking-meta {
    display: flex;
    gap: 1rem;
    font-size: var(--font-size-sm);
    color: #777;
}

.booking-meta span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.booking-meta i {
    font-size: 1rem;
}

.booking-status {
    padding: 0.25rem 0.75rem;
    border-radius: var(--border-radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
}

.booking-status-pending {
    background-color: rgba(255, 193, 7, 0.1);
    color: #FFC107;
}

.booking-status-confirmed {
    background-color: rgba(40, 167, 69, 0.1);
    color: #28A745;
}

.booking-status-cancelled {
    background-color: rgba(220, 53, 69, 0.1);
    color: #DC3545;
}

/* Quick Actions */
.quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
}

.quick-action {
    background-color: #f8f8f8;
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: background-color var(--transition-fast), transform var(--transition-fast);
}

.quick-action:hover {
    background-color: #f0f0f0;
    transform: translateY(-3px);
}

.quick-action i {
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius-circle);
    background-color: var(--primary-color);
    color: var(--white-color);
    display: flex;
    align-items: center;
    justify-content: center;
}

.quick-action span {
    font-weight: var(--font-weight-medium);
    color: var(--dark-color);
}

/* System Info */
.system-info {
    display: grid;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #eee;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: var(--font-weight-medium);
}

.info-value {
    color: #777;
}

/* Responsive Styles */
@media (max-width: 1200px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 992px) {
    .dashboard-row {
        grid-template-columns: 1fr;
    }
    
    .widget-large {
        grid-column: span 1;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .quick-actions {
        grid-template-columns: 1fr;
    }
}
</style>