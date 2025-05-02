<?php
/**
 * Admin Booking Detail View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('booking_details'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/bookings" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_bookings'); ?></span>
        </a>
        <a href="<?php echo $adminUrl; ?>/bookings/print/<?php echo $booking['id']; ?>" class="btn btn-light" target="_blank">
            <i class="material-icons">print</i>
            <span><?php _e('print'); ?></span>
        </a>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle">
                <i class="material-icons">more_vert</i>
                <span><?php _e('actions'); ?></span>
            </button>
            <div class="dropdown-menu">
                <ul>
                    <li>
                        <a href="<?php echo $adminUrl; ?>/bookings/status/<?php echo $booking['id']; ?>/pending" class="<?php echo $booking['status'] === 'pending' ? 'active' : ''; ?>">
                            <i class="material-icons">schedule</i>
                            <span><?php _e('mark_as_pending'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $adminUrl; ?>/bookings/status/<?php echo $booking['id']; ?>/confirmed" class="<?php echo $booking['status'] === 'confirmed' ? 'active' : ''; ?>">
                            <i class="material-icons">check_circle</i>
                            <span><?php _e('mark_as_confirmed'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $adminUrl; ?>/bookings/status/<?php echo $booking['id']; ?>/cancelled" class="<?php echo $booking['status'] === 'cancelled' ? 'active' : ''; ?>">
                            <i class="material-icons">cancel</i>
                            <span><?php _e('mark_as_cancelled'); ?></span>
                        </a>
                    </li>
                    <li class="dropdown-divider"></li>
                    <li>
                        <a href="<?php echo $adminUrl; ?>/bookings/resend-email/<?php echo $booking['id']; ?>">
                            <i class="material-icons">email</i>
                            <span><?php _e('resend_confirmation_email'); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="booking-detail">
    <div class="booking-header">
        <div class="booking-status booking-status-<?php echo $booking['status']; ?>">
            <?php _e($booking['status']); ?>
        </div>
        <div class="booking-id">
            <?php echo sprintf(__('booking_number'), $booking['id']); ?>
        </div>
        <div class="booking-date">
            <?php echo sprintf(__('created_on'), DateHelper::format($booking['created_at'], 'd M Y, H:i')); ?>
        </div>
    </div>

    <div class="booking-content">
        <div class="row">
            <!-- Tour Details Column -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php _e('tour_details'); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="tour-image">
                            <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                        </div>
                        <h4 class="tour-name">
                            <a href="<?php echo $adminUrl; ?>/tours/edit/<?php echo $tour['id']; ?>" target="_blank">
                                <?php echo $tour['name']; ?>
                            </a>
                        </h4>
                        <div class="detail-item">
                            <div class="detail-label"><?php _e('tour_date'); ?></div>
                            <div class="detail-value"><?php echo DateHelper::format($booking['booking_date'], 'd M Y'); ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><?php _e('adults'); ?></div>
                            <div class="detail-value"><?php echo $booking['adults']; ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><?php _e('children'); ?></div>
                            <div class="detail-value"><?php echo $booking['children']; ?></div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label"><?php _e('price_per_adult'); ?></div>
                            <div class="detail-value">
                                <?php if ($tour['discount_price'] > 0): ?>
                                    <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                                    <?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?>
                                <?php else: ?>
                                    <?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="detail-item total-price">
                            <div class="detail-label"><?php _e('total_price'); ?></div>
                            <div class="detail-value"><?php echo $settings['currency_symbol'] . number_format($booking['total_price'], 2); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Details Column -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php _e('customer_details'); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="customer-info">
                            <h4 class="customer-name"><?php echo $booking['first_name'] . ' ' . $booking['last_name']; ?></h4>
                            
                            <div class="detail-item">
                                <div class="detail-label"><?php _e('email'); ?></div>
                                <div class="detail-value">
                                    <a href="mailto:<?php echo $booking['email']; ?>"><?php echo $booking['email']; ?></a>
                                </div>
                            </div>
                            
                            <div class="detail-item">
                                <div class="detail-label"><?php _e('phone'); ?></div>
                                <div class="detail-value">
                                    <a href="tel:<?php echo $booking['phone']; ?>"><?php echo $booking['phone']; ?></a>
                                </div>
                            </div>
                            
                            <?php if (!empty($booking['special_requests'])): ?>
                                <div class="detail-item">
                                    <div class="detail-label"><?php _e('special_requests'); ?></div>
                                    <div class="detail-value special-requests">
                                        <?php echo nl2br(htmlspecialchars($booking['special_requests'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <hr>
                            
                            <div class="customer-actions">
                                <a href="mailto:<?php echo $booking['email']; ?>" class="btn btn-light btn-sm">
                                    <i class="material-icons">email</i>
                                    <span><?php _e('send_email'); ?></span>
                                </a>
                                <a href="<?php echo $adminUrl; ?>/bookings/list-by-customer/<?php echo urlencode($booking['email']); ?>" class="btn btn-light btn-sm">
                                    <i class="material-icons">list</i>
                                    <span><?php _e('all_bookings'); ?></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Status & History Column -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><?php _e('booking_status'); ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="status-info">
                            <div class="current-status status-<?php echo $booking['status']; ?>">
                                <div class="status-icon">
                                    <i class="material-icons">
                                        <?php
                                        switch ($booking['status']) {
                                            case 'pending':
                                                echo 'schedule';
                                                break;
                                            case 'confirmed':
                                                echo 'check_circle';
                                                break;
                                            case 'cancelled':
                                                echo 'cancel';
                                                break;
                                            default:
                                                echo 'help';
                                        }
                                        ?>
                                    </i>
                                </div>
                                <div class="status-details">
                                    <div class="status-name"><?php _e($booking['status']); ?></div>
                                    <div class="status-description">
                                        <?php
                                        switch ($booking['status']) {
                                            case 'pending':
                                                _e('status_pending_description');
                                                break;
                                            case 'confirmed':
                                                _e('status_confirmed_description');
                                                break;
                                            case 'cancelled':
                                                _e('status_cancelled_description');
                                                break;
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="status-actions">
                                <div class="form-group">
                                    <label for="booking_status" class="form-label"><?php _e('change_status'); ?></label>
                                    <div class="input-group">
                                        <select id="booking_status" class="form-select">
                                            <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>><?php _e('pending'); ?></option>
                                            <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>><?php _e('confirmed'); ?></option>
                                            <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>><?php _e('cancelled'); ?></option>
                                        </select>
                                        <button type="button" class="btn btn-primary" id="update-status-btn">
                                            <?php _e('update'); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h4 class="status-history-title"><?php _e('status_history'); ?></h4>
                            
                            <?php if (empty($statusHistory)): ?>
                                <p class="no-history"><?php _e('no_status_history'); ?></p>
                            <?php else: ?>
                                <div class="status-history">
                                    <?php foreach ($statusHistory as $history): ?>
                                        <div class="status-history-item">
                                            <div class="status-history-icon status-<?php echo $history['status']; ?>">
                                                <i class="material-icons">
                                                    <?php
                                                    switch ($history['status']) {
                                                        case 'pending':
                                                            echo 'schedule';
                                                            break;
                                                        case 'confirmed':
                                                            echo 'check_circle';
                                                            break;
                                                        case 'cancelled':
                                                            echo 'cancel';
                                                            break;
                                                        default:
                                                            echo 'help';
                                                    }
                                                    ?>
                                                </i>
                                            </div>
                                            <div class="status-history-details">
                                                <div class="status-history-text">
                                                    <?php echo sprintf(__('status_changed_to'), __($history['status'])); ?>
                                                </div>
                                                <div class="status-history-meta">
                                                    <span class="status-history-date">
                                                        <?php echo DateHelper::format($history['created_at'], 'd M Y, H:i'); ?>
                                                    </span>
                                                    <span class="status-history-user">
                                                        <?php echo sprintf(__('by_user'), $history['user_name']); ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($booking['notes'])): ?>
            <div class="card booking-notes">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('admin_notes'); ?></h3>
                </div>
                <div class="card-body">
                    <?php echo nl2br(htmlspecialchars($booking['notes'])); ?>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#edit-notes-modal">
                        <i class="material-icons">edit</i>
                        <span><?php _e('edit_notes'); ?></span>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="card booking-notes">
                <div class="card-body">
                    <div class="empty-notes">
                        <i class="material-icons">description</i>
                        <p><?php _e('no_notes_yet'); ?></p>
                        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#edit-notes-modal">
                            <i class="material-icons">add</i>
                            <span><?php _e('add_notes'); ?></span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit Notes Modal -->
<div class="modal" id="edit-notes-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><?php _e('edit_booking_notes'); ?></h3>
                <button type="button" class="close" data-dismiss="modal">
                    <i class="material-icons">close</i>
                </button>
            </div>
            <div class="modal-body">
                <form id="notes-form" action="<?php echo $adminUrl; ?>/bookings/update-notes/<?php echo $booking['id']; ?>" method="post">
                    <div class="form-group">
                        <label for="booking_notes" class="form-label"><?php _e('notes'); ?></label>
                        <textarea id="booking_notes" name="notes" class="form-control" rows="5"><?php echo htmlspecialchars($booking['notes']); ?></textarea>
                        <small class="form-text text-muted"><?php _e('notes_description'); ?></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">
                    <?php _e('cancel'); ?>
                </button>
                <button type="submit" class="btn btn-primary" form="notes-form">
                    <?php _e('save_notes'); ?>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update status functionality
    const statusSelect = document.getElementById('booking_status');
    const updateStatusBtn = document.getElementById('update-status-btn');
    
    if (statusSelect && updateStatusBtn) {
        updateStatusBtn.addEventListener('click', function() {
            const status = statusSelect.value;
            window.location.href = `<?php echo $adminUrl; ?>/bookings/status/<?php echo $booking['id']; ?>/${status}`;
        });
    }
    
    // Modal functionality
    const modals = document.querySelectorAll('.modal');
    const modalToggles = document.querySelectorAll('[data-toggle="modal"]');
    const modalCloses = document.querySelectorAll('[data-dismiss="modal"]');
    
    modalToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const modal = document.querySelector(targetId);
            
            if (modal) {
                modal.classList.add('show');
                document.body.classList.add('modal-open');
            }
        });
    });
    
    modalCloses.forEach(close => {
        close.addEventListener('click', function() {
            const modal = this.closest('.modal');
            
            if (modal) {
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
            }
        });
    });
    
    window.addEventListener('click', function(event) {
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
            }
        });
    });
});
</script>

<style>
.booking-detail {
    margin-bottom: var(--spacing-xl);
}

.booking-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: var(--spacing-lg);
    padding-bottom: var(--spacing-md);
    border-bottom: 1px solid var(--gray-200);
}

.booking-status {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius-md);
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
    font-size: var(--font-size-sm);
}

.booking-status-pending {
    background-color: rgba(255, 193, 7, 0.1);
    color: var(--warning-color);
}

.booking-status-confirmed {
    background-color: rgba(40, 167, 69, 0.1);
    color: var(--success-color);
}

.booking-status-cancelled {
    background-color: rgba(220, 53, 69, 0.1);
    color: var(--danger-color);
}

.booking-id, .booking-date {
    color: var(--gray-600);
    font-size: var(--font-size-sm);
}

.booking-content {
    margin-bottom: var(--spacing-xl);
}

.tour-image {
    width: 100%;
    height: 150px;
    border-radius: var(--border-radius-md);
    overflow: hidden;
    margin-bottom: var(--spacing-md);
}

.tour-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.tour-name {
    margin-bottom: var(--spacing-md);
}

.detail-item {
    margin-bottom: var(--spacing-sm);
    display: flex;
    justify-content: space-between;
}

.detail-label {
    color: var(--gray-600);
    font-size: var(--font-size-sm);
}

.detail-value {
    font-weight: var(--font-weight-medium);
}

.total-price {
    margin-top: var(--spacing-md);
    padding-top: var(--spacing-md);
    border-top: 1px solid var(--gray-200);
}

.total-price .detail-label,
.total-price .detail-value {
    font-weight: var(--font-weight-bold);
    font-size: var(--font-size-md);
}

.customer-name {
    margin-bottom: var(--spacing-md);
}

.customer-actions {
    display: flex;
    gap: var(--spacing-sm);
}

.special-requests {
    background-color: var(--gray-100);
    padding: var(--spacing-sm);
    border-radius: var(--border-radius-sm);
    font-size: var(--font-size-sm);
    max-height: 100px;
    overflow-y: auto;
}

.current-status {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-lg);
}

.status-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
}

.status-pending .status-icon {
    background-color: rgba(255, 193, 7, 0.1);
    color: var(--warning-color);
}

.status-confirmed .status-icon {
    background-color: rgba(40, 167, 69, 0.1);
    color: var(--success-color);
}

.status-cancelled .status-icon {
    background-color: rgba(220, 53, 69, 0.1);
    color: var(--danger-color);
}

.status-name {
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
    margin-bottom: var(--spacing-xs);
}

.status-description {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
}

.status-actions {
    margin: var(--spacing-lg) 0;
}

.input-group {
    display: flex;
    gap: 0;
}

.input-group .form-select {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

.status-history-title {
    margin-bottom: var(--spacing-md);
    font-size: var(--font-size-md);
}

.no-history {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    font-style: italic;
}

.status-history-item {
    display: flex;
    gap: var(--spacing-md);
    margin-bottom: var(--spacing-md);
}

.status-history-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.status-history-details {
    flex: 1;
}

.status-history-text {
    font-size: var(--font-size-sm);
    margin-bottom: var(--spacing-xs);
}

.status-history-meta {
    font-size: var(--font-size-xs);
    color: var(--gray-500);
}

.booking-notes {
    margin-top: var(--spacing-lg);
}

.empty-notes {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-lg) 0;
    color: var(--gray-500);
}

.empty-notes i {
    font-size: 3rem;
    margin-bottom: var(--spacing-md);
    opacity: 0.5;
}

/* Modal styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: var(--z-index-modal);
    opacity: 0;
    visibility: hidden;
    transition: opacity var(--transition-medium), visibility var(--transition-medium);
}

.modal.show {
    opacity: 1;
    visibility: visible;
}

.modal-dialog {
    width: 100%;
    max-width: 500px;
    margin: 30px auto;
}

.modal-content {
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    transform: translateY(-20px);
    transition: transform var(--transition-medium);
}

.modal.show .modal-content {
    transform: translateY(0);
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-md) var(--spacing-lg);
    border-bottom: 1px solid var(--gray-200);
}

.modal-title {
    margin: 0;
    font-size: var(--font-size-lg);
}

.modal-body {
    padding: var(--spacing-lg);
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: var(--spacing-md);
    padding: var(--spacing-md) var(--spacing-lg);
    border-top: 1px solid var(--gray-200);
}

.close {
    width: 36px;
    height: 36px;
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--gray-200);
    color: var(--gray-700);
    transition: background-color var(--transition-fast), color var(--transition-fast);
}

.close:hover {
    background-color: var(--gray-300);
    color: var(--dark-color);
}

body.modal-open {
    overflow: hidden;
}
</style>