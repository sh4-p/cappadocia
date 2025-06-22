<?php
/**
 * Admin Customer Bookings List View
 */
?>

<div class="page-header">
    <h1 class="page-title">
        <?php echo sprintf(__('bookings_by_customer'), htmlspecialchars($customerName)); ?>
    </h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/bookings" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_all_bookings'); ?></span>
        </a>
        <a href="mailto:<?php echo htmlspecialchars($customerEmail); ?>" class="btn btn-primary">
            <i class="material-icons">email</i>
            <span><?php _e('send_email'); ?></span>
        </a>
    </div>
</div>

<!-- Customer Summary -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php _e('customer_summary'); ?></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="customer-info">
                            <h4><?php echo htmlspecialchars($customerName); ?></h4>
                            <p class="text-muted"><?php echo htmlspecialchars($customerEmail); ?></p>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $totalBookings; ?></div>
                                <div class="stat-label"><?php _e('total_bookings'); ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $confirmedBookings; ?></div>
                                <div class="stat-label"><?php _e('confirmed_bookings'); ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $pendingBookings; ?></div>
                                <div class="stat-label"><?php _e('pending_bookings'); ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $cancelledBookings; ?></div>
                                <div class="stat-label"><?php _e('cancelled_bookings'); ?></div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value"><?php echo $settings['currency_symbol'] . number_format($totalSpent, 2); ?></div>
                                <div class="stat-label"><?php _e('total_spent'); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bookings List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php _e('booking_history'); ?></h3>
    </div>
    <div class="card-body">
        <?php if (empty($bookings)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">event_busy</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_bookings_found'); ?></h3>
                <p class="empty-state-description"><?php _e('this_customer_has_no_bookings'); ?></p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><?php _e('id'); ?></th>
                            <th><?php _e('tour'); ?></th>
                            <th><?php _e('booking_date'); ?></th>
                            <th><?php _e('people'); ?></th>
                            <th><?php _e('total_price'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('created_at'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>#<?php echo $booking['id']; ?></td>
                                <td>
                                    <a href="<?php echo $adminUrl; ?>/tours/edit/<?php echo $booking['tour_id']; ?>" target="_blank">
                                        <?php echo htmlspecialchars($booking['tour_name']); ?>
                                    </a>
                                </td>
                                <td><?php echo DateHelper::format($booking['booking_date'], 'd M Y'); ?></td>
                                <td>
                                    <span title="<?php _e('adults'); ?>"><?php echo $booking['adults']; ?></span> / 
                                    <span title="<?php _e('children'); ?>"><?php echo $booking['children']; ?></span>
                                </td>
                                <td><?php echo $settings['currency_symbol'] . number_format($booking['total_price'], 2); ?></td>
                                <td>
                                    <span class="status status-<?php echo $booking['status']; ?>">
                                        <?php echo _e($booking['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo DateHelper::format($booking['created_at'], 'd M Y, H:i'); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="<?php echo $adminUrl; ?>/bookings/view/<?php echo $booking['id']; ?>" class="action-btn" title="<?php _e('view'); ?>">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                        <div class="dropdown">
                                            <button class="action-btn dropdown-toggle" title="<?php _e('change_status'); ?>">
                                                <i class="material-icons">more_vert</i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <ul>
                                                    <li>
                                                        <a href="<?php echo $adminUrl; ?>/bookings/status/<?php echo $booking['id']; ?>/pending" class="<?php echo $booking['status'] === 'pending' ? 'active' : ''; ?>">
                                                            <i class="material-icons">schedule</i>
                                                            <span><?php _e('pending'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo $adminUrl; ?>/bookings/status/<?php echo $booking['id']; ?>/confirmed" class="<?php echo $booking['status'] === 'confirmed' ? 'active' : ''; ?>">
                                                            <i class="material-icons">check_circle</i>
                                                            <span><?php _e('confirmed'); ?></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="<?php echo $adminUrl; ?>/bookings/status/<?php echo $booking['id']; ?>/cancelled" class="<?php echo $booking['status'] === 'cancelled' ? 'active' : ''; ?>">
                                                            <i class="material-icons">cancel</i>
                                                            <span><?php _e('cancelled'); ?></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.customer-info h4 {
    margin-bottom: 5px;
    font-weight: 600;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.stat-item {
    text-align: center;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border-left: 3px solid var(--primary-color);
}

.stat-value {
    font-size: 24px;
    font-weight: bold;
    color: var(--primary-color);
    margin-bottom: 5px;
}

.stat-label {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
    text-align: center;
}

.empty-state-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state-title {
    font-size: 1.5rem;
    color: #6c757d;
    margin-bottom: 10px;
}

.empty-state-description {
    color: #adb5bd;
    max-width: 400px;
}

.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    text-transform: uppercase;
}

.status-pending {
    background-color: rgba(255, 193, 7, 0.1);
    color: #856404;
}

.status-confirmed {
    background-color: rgba(40, 167, 69, 0.1);
    color: #155724;
}

.status-cancelled {
    background-color: rgba(220, 53, 69, 0.1);
    color: #721c24;
}

.actions {
    display: flex;
    gap: 5px;
    align-items: center;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    text-decoration: none;
    transition: all 0.2s;
}

.action-btn:hover {
    background-color: #f8f9fa;
    color: var(--primary-color);
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    min-width: 150px;
    z-index: 1000;
    display: none;
}

.dropdown:hover .dropdown-menu {
    display: block;
}

.dropdown-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
}

.dropdown-menu li a {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    text-decoration: none;
    color: #495057;
    font-size: 14px;
}

.dropdown-menu li a:hover {
    background-color: #f8f9fa;
}

.dropdown-menu li a.active {
    background-color: #e3f2fd;
    color: var(--primary-color);
}
</style>