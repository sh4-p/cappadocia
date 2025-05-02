<?php
/**
 * Admin Bookings List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('bookings'); ?></h1>
    <div class="page-actions">
        <div class="filter-dropdown dropdown">
            <button class="btn btn-light dropdown-toggle">
                <i class="material-icons">filter_list</i>
                <span><?php _e('filter'); ?></span>
            </button>
            <div class="dropdown-menu">
                <form action="<?php echo $adminUrl; ?>/bookings" method="get">
                    <div class="form-group">
                        <label for="status" class="form-label"><?php _e('status'); ?></label>
                        <select name="status" id="status" class="form-select">
                            <option value=""><?php _e('all_statuses'); ?></option>
                            <option value="pending" <?php echo isset($_GET['status']) && $_GET['status'] === 'pending' ? 'selected' : ''; ?>><?php _e('pending'); ?></option>
                            <option value="confirmed" <?php echo isset($_GET['status']) && $_GET['status'] === 'confirmed' ? 'selected' : ''; ?>><?php _e('confirmed'); ?></option>
                            <option value="cancelled" <?php echo isset($_GET['status']) && $_GET['status'] === 'cancelled' ? 'selected' : ''; ?>><?php _e('cancelled'); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_from" class="form-label"><?php _e('date_from'); ?></label>
                        <input type="date" name="date_from" id="date_from" class="form-control datepicker" value="<?php echo isset($_GET['date_from']) ? $_GET['date_from'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="date_to" class="form-label"><?php _e('date_to'); ?></label>
                        <input type="date" name="date_to" id="date_to" class="form-control datepicker" value="<?php echo isset($_GET['date_to']) ? $_GET['date_to'] : ''; ?>">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="material-icons">search</i>
                            <?php _e('apply_filters'); ?>
                        </button>
                    </div>
                    <div class="form-group">
                        <a href="<?php echo $adminUrl; ?>/bookings" class="btn btn-light btn-block">
                            <i class="material-icons">clear</i>
                            <?php _e('clear_filters'); ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        <a href="<?php echo $adminUrl; ?>/bookings/export<?php echo isset($_GET['status']) || isset($_GET['date_from']) || isset($_GET['date_to']) ? '?' . http_build_query($_GET) : ''; ?>" class="btn btn-success">
            <i class="material-icons">file_download</i>
            <span><?php _e('export'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($bookings)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">event_busy</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_bookings_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_bookings_description'); ?></p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th><?php _e('id'); ?></th>
                            <th><?php _e('tour'); ?></th>
                            <th><?php _e('customer'); ?></th>
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
                                        <?php echo $booking['tour_name']; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?>
                                    <br>
                                    <small><?php echo $booking['email']; ?></small>
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

<!-- Booking Statistics -->
<div class="card">
    <div class="card-header">
        <h2 class="card-title"><?php _e('booking_statistics'); ?></h2>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <canvas id="bookingsChart" width="400" height="200"></canvas>
            </div>
            <div class="col-md-6">
                <div class="stats-summary">
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('total_bookings'); ?></div>
                        <div class="stat-value"><?php echo $totalBookings; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('confirmed_bookings'); ?></div>
                        <div class="stat-value"><?php echo $confirmedBookings; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('pending_bookings'); ?></div>
                        <div class="stat-value"><?php echo $pendingBookings; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('cancelled_bookings'); ?></div>
                        <div class="stat-value"><?php echo $cancelledBookings; ?></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label"><?php _e('total_revenue'); ?></div>
                        <div class="stat-value"><?php echo $settings['currency_symbol'] . number_format($totalRevenue, 2); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize charts
    const bookingsCtx = document.getElementById('bookingsChart');
    
    if (bookingsCtx) {
        const bookingsData = <?php echo json_encode($bookingsChartData); ?>;
        
        new Chart(bookingsCtx, {
            type: 'line',
            data: {
                labels: bookingsData.labels,
                datasets: [{
                    label: '<?php _e('bookings'); ?>',
                    data: bookingsData.values,
                    backgroundColor: 'rgba(67, 97, 238, 0.2)',
                    borderColor: 'rgba(67, 97, 238, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    }
});
</script>

<style>
.stats-summary {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-md);
}

.stat-item {
    background-color: var(--white-color);
    border-radius: var(--border-radius-md);
    box-shadow: var(--shadow-sm);
    padding: var(--spacing-md);
    border-left: 3px solid var(--primary-color);
}

.stat-label {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    margin-bottom: var(--spacing-xs);
}

.stat-value {
    font-size: var(--font-size-lg);
    font-weight: var(--font-weight-bold);
    color: var(--dark-color);
}

.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-xxl) 0;
    text-align: center;
}

.empty-state-icon {
    font-size: 4rem;
    color: var(--gray-400);
    margin-bottom: var(--spacing-lg);
}

.empty-state-title {
    font-size: var(--font-size-xl);
    color: var(--gray-800);
    margin-bottom: var(--spacing-md);
}

.empty-state-description {
    color: var(--gray-600);
    max-width: 500px;
}
</style>