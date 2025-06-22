<?php
/**
 * Admin Create Booking View - Updated with Optional Email Controls
 */

// Get form data for redisplay if there are errors
$formData = $formData ?? [];
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('add_booking'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/bookings" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_bookings'); ?></span>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php _e('booking_details'); ?></h3>
            </div>
            <div class="card-body">
                <form action="<?php echo $adminUrl; ?>/bookings/create" method="post" id="booking-form">
                    <div class="row">
                        <!-- Tour Selection -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="tour_id" class="form-label required"><?php _e('tour'); ?></label>
                                <select name="tour_id" id="tour_id" class="form-select" required>
                                    <option value=""><?php _e('select_tour'); ?></option>
                                    <?php foreach ($tours as $tour): ?>
                                        <option value="<?php echo $tour['id']; ?>" 
                                                data-price="<?php echo $tour['discount_price'] > 0 ? $tour['discount_price'] : $tour['price']; ?>"
                                                data-original-price="<?php echo $tour['price']; ?>"
                                                data-discount-price="<?php echo $tour['discount_price']; ?>"
                                                <?php echo (isset($formData['tour_id']) && $formData['tour_id'] == $tour['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($tour['name']); ?>
                                            <?php if ($tour['discount_price'] > 0): ?>
                                                (<?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?> 
                                                - <?php _e('was'); ?> <?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?>)
                                            <?php else: ?>
                                                (<?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="form-label required"><?php _e('first_name'); ?></label>
                                <input type="text" name="first_name" id="first_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['first_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" class="form-label required"><?php _e('last_name'); ?></label>
                                <input type="text" name="last_name" id="last_name" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['last_name'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-label required"><?php _e('email'); ?></label>
                                <input type="email" name="email" id="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="form-label required"><?php _e('phone'); ?></label>
                                <input type="tel" name="phone" id="phone" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['phone'] ?? ''); ?>" required>
                            </div>
                        </div>
                        
                        <!-- Booking Details -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="booking_date" class="form-label required"><?php _e('booking_date'); ?></label>
                                <input type="date" name="booking_date" id="booking_date" class="form-control" 
                                       value="<?php echo htmlspecialchars($formData['booking_date'] ?? ''); ?>" 
                                       min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="adults" class="form-label required"><?php _e('adults'); ?></label>
                                <select name="adults" id="adults" class="form-select" required>
                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?php echo $i; ?>" 
                                                <?php echo (isset($formData['adults']) && $formData['adults'] == $i) ? 'selected' : ($i == 1 ? 'selected' : ''); ?>>
                                            <?php echo $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="children" class="form-label"><?php _e('children'); ?></label>
                                <select name="children" id="children" class="form-select">
                                    <?php for ($i = 0; $i <= 10; $i++): ?>
                                        <option value="<?php echo $i; ?>" 
                                                <?php echo (isset($formData['children']) && $formData['children'] == $i) ? 'selected' : ($i == 0 ? 'selected' : ''); ?>>
                                            <?php echo $i; ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Payment and Status -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payment_method" class="form-label required"><?php _e('payment_method'); ?></label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value=""><?php _e('select_payment_method'); ?></option>
                                    <?php foreach ($activePaymentMethods as $method => $name): ?>
                                        <option value="<?php echo $method; ?>" 
                                                <?php echo (isset($formData['payment_method']) && $formData['payment_method'] == $method) ? 'selected' : ''; ?>>
                                            <?php echo $name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-label required"><?php _e('status'); ?></label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="pending" <?php echo (isset($formData['status']) && $formData['status'] == 'pending') ? 'selected' : 'selected'; ?>>
                                        <?php _e('pending'); ?>
                                    </option>
                                    <option value="confirmed" <?php echo (isset($formData['status']) && $formData['status'] == 'confirmed') ? 'selected' : ''; ?>>
                                        <?php _e('confirmed'); ?>
                                    </option>
                                    <option value="cancelled" <?php echo (isset($formData['status']) && $formData['status'] == 'cancelled') ? 'selected' : ''; ?>>
                                        <?php _e('cancelled'); ?>
                                    </option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Special Requests -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="special_requests" class="form-label"><?php _e('special_requests'); ?></label>
                                <textarea name="special_requests" id="special_requests" class="form-control" rows="2"><?php echo htmlspecialchars($formData['special_requests'] ?? ''); ?></textarea>
                                <small class="form-text text-muted"><?php _e('special_requests_help'); ?></small>
                            </div>
                        </div>
                        
                        <!-- Admin Notes -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="admin_notes" class="form-label"><?php _e('admin_notes'); ?></label>
                                <textarea name="admin_notes" id="admin_notes" class="form-control" rows="2"><?php echo htmlspecialchars($formData['admin_notes'] ?? ''); ?></textarea>
                                <small class="form-text text-muted"><?php _e('admin_notes_help'); ?></small>
                            </div>
                        </div>
                        
                        <!-- Email Notification Options -->
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label"><?php _e('email_notifications'); ?></label>
                                <div class="email-options">
                                    <div class="form-check">
                                        <input type="checkbox" name="send_customer_email" id="send_customer_email" 
                                               class="form-check-input" value="1" 
                                               <?php echo (isset($formData['send_customer_email']) && $formData['send_customer_email']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="send_customer_email">
                                            <i class="material-icons">email</i>
                                            <?php _e('send_confirmation_email_to_customer'); ?>
                                        </label>
                                        <small class="form-text text-muted d-block"><?php _e('send_customer_email_help'); ?></small>
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" name="send_admin_email" id="send_admin_email" 
                                               class="form-check-input" value="1"
                                               <?php echo (isset($formData['send_admin_email']) && $formData['send_admin_email']) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="send_admin_email">
                                            <i class="material-icons">notifications</i>
                                            <?php _e('send_notification_to_admin'); ?>
                                        </label>
                                        <small class="form-text text-muted d-block"><?php _e('send_admin_email_help'); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Hidden Total Price -->
                        <input type="hidden" name="total_price" id="total_price" value="<?php echo htmlspecialchars($formData['total_price'] ?? '0'); ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Price Summary Sidebar -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php _e('price_summary'); ?></h3>
            </div>
            <div class="card-body">
                <div id="price-summary">
                    <div class="price-row">
                        <span class="label"><?php _e('tour'); ?>:</span>
                        <span class="value" id="selected-tour"><?php _e('select_tour_first'); ?></span>
                    </div>
                    
                    <div class="price-row">
                        <span class="label"><?php _e('price_per_adult'); ?>:</span>
                        <span class="value" id="adult-price"><?php echo $settings['currency_symbol']; ?>0.00</span>
                    </div>
                    
                    <div class="price-row">
                        <span class="label"><?php _e('adults'); ?>:</span>
                        <span class="value"><span id="adult-count">1</span> × <span id="adult-unit-price"><?php echo $settings['currency_symbol']; ?>0.00</span></span>
                    </div>
                    
                    <div class="price-row">
                        <span class="label"><?php _e('children'); ?> (50%):</span>
                        <span class="value"><span id="children-count">0</span> × <span id="children-unit-price"><?php echo $settings['currency_symbol']; ?>0.00</span></span>
                    </div>
                    
                    <hr>
                    
                    <div class="price-row total">
                        <span class="label"><?php _e('total_price'); ?>:</span>
                        <span class="value" id="total-display"><?php echo $settings['currency_symbol']; ?>0.00</span>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" form="booking-form" class="btn btn-primary btn-block">
                        <i class="material-icons">save</i>
                        <span><?php _e('create_booking'); ?></span>
                    </button>
                    <a href="<?php echo $adminUrl; ?>/bookings" class="btn btn-light btn-block">
                        <?php _e('cancel'); ?>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Email Information -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title"><?php _e('email_information'); ?></h3>
            </div>
            <div class="card-body">
                <div class="email-info">
                    <div class="info-item">
                        <strong><?php _e('customer_email'); ?>:</strong>
                        <p><?php _e('customer_email_description'); ?></p>
                    </div>
                    <div class="info-item">
                        <strong><?php _e('admin_notification'); ?>:</strong>
                        <p><?php _e('admin_notification_description'); ?></p>
                    </div>
                    <div class="info-item">
                        <strong><?php _e('note'); ?>:</strong>
                        <p class="text-muted"><?php _e('emails_optional_admin_created'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status Information -->
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="card-title"><?php _e('status_information'); ?></h3>
            </div>
            <div class="card-body">
                <div class="status-info">
                    <div class="status-item">
                        <strong><?php _e('pending'); ?>:</strong>
                        <p><?php _e('status_pending_description'); ?></p>
                    </div>
                    <div class="status-item">
                        <strong><?php _e('confirmed'); ?>:</strong>
                        <p><?php _e('status_confirmed_description'); ?></p>
                    </div>
                    <div class="status-item">
                        <strong><?php _e('cancelled'); ?>:</strong>
                        <p><?php _e('status_cancelled_description'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tourSelect = document.getElementById('tour_id');
    const adultsSelect = document.getElementById('adults');
    const childrenSelect = document.getElementById('children');
    const totalPriceInput = document.getElementById('total_price');
    const statusSelect = document.getElementById('status');
    const customerEmailCheckbox = document.getElementById('send_customer_email');
    
    // Price display elements
    const selectedTourDisplay = document.getElementById('selected-tour');
    const adultPriceDisplay = document.getElementById('adult-price');
    const adultCountDisplay = document.getElementById('adult-count');
    const adultUnitPriceDisplay = document.getElementById('adult-unit-price');
    const childrenCountDisplay = document.getElementById('children-count');
    const childrenUnitPriceDisplay = document.getElementById('children-unit-price');
    const totalDisplay = document.getElementById('total-display');
    
    let currentTourPrice = 0;
    const currencySymbol = '<?php echo $settings['currency_symbol']; ?>';
    
    // Update price calculation
    function updatePricing() {
        const adults = parseInt(adultsSelect.value) || 1;
        const children = parseInt(childrenSelect.value) || 0;
        
        if (currentTourPrice > 0) {
            const adultTotal = adults * currentTourPrice;
            const childrenTotal = children * (currentTourPrice * 0.5);
            const totalPrice = adultTotal + childrenTotal;
            
            // Update displays
            adultCountDisplay.textContent = adults;
            adultUnitPriceDisplay.textContent = currencySymbol + currentTourPrice.toFixed(2);
            childrenCountDisplay.textContent = children;
            childrenUnitPriceDisplay.textContent = currencySymbol + (currentTourPrice * 0.5).toFixed(2);
            totalDisplay.textContent = currencySymbol + totalPrice.toFixed(2);
            
            // Update hidden input
            totalPriceInput.value = totalPrice.toFixed(2);
        }
    }
    
    // Handle tour selection
    tourSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const tourPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const tourName = selectedOption.textContent.split('(')[0].trim();
            
            currentTourPrice = tourPrice;
            selectedTourDisplay.textContent = tourName;
            adultPriceDisplay.textContent = currencySymbol + tourPrice.toFixed(2);
            
            updatePricing();
        } else {
            currentTourPrice = 0;
            selectedTourDisplay.textContent = '<?php _e('select_tour_first'); ?>';
            adultPriceDisplay.textContent = currencySymbol + '0.00';
            totalDisplay.textContent = currencySymbol + '0.00';
            totalPriceInput.value = '0';
        }
    });
    
    // Auto-check customer email if status is confirmed
    statusSelect.addEventListener('change', function() {
        if (this.value === 'confirmed') {
            customerEmailCheckbox.checked = true;
        }
    });
    
    // Handle adults/children change
    adultsSelect.addEventListener('change', updatePricing);
    childrenSelect.addEventListener('change', updatePricing);
    
    // Initialize if tour is pre-selected
    if (tourSelect.value) {
        tourSelect.dispatchEvent(new Event('change'));
    }
});
</script>

<style>
.required::after {
    content: ' *';
    color: #dc3545;
}

#price-summary {
    margin-bottom: 20px;
}

.price-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    padding: 5px 0;
}

.price-row.total {
    font-weight: bold;
    font-size: 1.1em;
    color: var(--primary-color);
}

.price-row .label {
    color: #6c757d;
}

.price-row .value {
    font-weight: 500;
}

.form-actions {
    margin-top: 20px;
}

.form-actions .btn {
    margin-bottom: 10px;
}

.email-options {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.form-check {
    margin-bottom: 15px;
}

.form-check:last-child {
    margin-bottom: 0;
}

.form-check-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
    cursor: pointer;
}

.form-check-label .material-icons {
    font-size: 18px;
    color: #007bff;
}

.email-info,
.status-info {
    font-size: 14px;
}

.info-item,
.status-item {
    margin-bottom: 15px;
}

.info-item:last-child,
.status-item:last-child {
    margin-bottom: 0;
}

.info-item strong,
.status-item strong {
    color: var(--primary-color);
}

.info-item p,
.status-item p {
    margin: 5px 0 0 0;
    color: #6c757d;
    font-size: 13px;
}

.card-title {
    margin: 0;
    font-size: 1.1em;
}

hr {
    margin: 15px 0;
    border-color: #dee2e6;
}

.form-text.text-muted {
    font-size: 12px;
    margin-top: 4px;
}
</style>