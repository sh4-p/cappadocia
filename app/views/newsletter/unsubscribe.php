<div class="newsletter-unsubscribe">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="unsubscribe-card">
                    <div class="unsubscribe-icon">
                        <?php if ($type === 'success'): ?>
                            <i class="material-icons text-success">check_circle</i>
                        <?php elseif ($type === 'error'): ?>
                            <i class="material-icons text-danger">error</i>
                        <?php else: ?>
                            <i class="material-icons text-warning">unsubscribe</i>
                        <?php endif; ?>
                    </div>
                    
                    <div class="unsubscribe-content">
                        <h1 class="unsubscribe-title">
                            <?php if ($type === 'success'): ?>
                                <?php _e('unsubscribed_successfully_title'); ?>
                            <?php elseif ($type === 'error'): ?>
                                <?php _e('unsubscribe_error_title'); ?>
                            <?php else: ?>
                                <?php _e('unsubscribe_confirmation_title'); ?>
                            <?php endif; ?>
                        </h1>
                        
                        <div class="unsubscribe-message">
                            <p class="lead"><?php echo $message; ?></p>
                        </div>
                        
                        <?php if (isset($subscriber) && $subscriber && empty($message)): ?>
                        <div class="subscriber-info">
                            <div class="info-card">
                                <h3><?php _e('subscription_details'); ?></h3>
                                <div class="info-row">
                                    <span class="info-label"><?php _e('email'); ?>:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($subscriber['email']); ?></span>
                                </div>
                                <?php if ($subscriber['name']): ?>
                                <div class="info-row">
                                    <span class="info-label"><?php _e('name'); ?>:</span>
                                    <span class="info-value"><?php echo htmlspecialchars($subscriber['name']); ?></span>
                                </div>
                                <?php endif; ?>
                                <div class="info-row">
                                    <span class="info-label"><?php _e('current_status'); ?>:</span>
                                    <span class="info-value">
                                        <span class="status-badge status-<?php echo $subscriber['status']; ?>">
                                            <?php _e('status_' . $subscriber['status']); ?>
                                        </span>
                                    </span>
                                </div>
                                <?php if ($subscriber['subscribed_at']): ?>
                                <div class="info-row">
                                    <span class="info-label"><?php _e('subscribed_since'); ?>:</span>
                                    <span class="info-value"><?php echo date('d/m/Y', strtotime($subscriber['subscribed_at'])); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($subscriber['status'] !== 'unsubscribed'): ?>
                        <!-- Unsubscribe Form -->
                        <div class="unsubscribe-form">
                            <div class="warning-box">
                                <h4><?php _e('are_you_sure_unsubscribe'); ?></h4>
                                <p><?php _e('unsubscribe_warning_message'); ?></p>
                                
                                <div class="benefits-reminder">
                                    <h5><?php _e('you_will_miss_out_on'); ?></h5>
                                    <ul>
                                        <li><?php _e('exclusive_tour_offers'); ?></li>
                                        <li><?php _e('early_booking_discounts'); ?></li>
                                        <li><?php _e('travel_tips_guides'); ?></li>
                                        <li><?php _e('new_destination_updates'); ?></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <form method="POST" action="<?php echo $appUrl . '/' . $currentLang; ?>/newsletter/unsubscribe/<?php echo $subscriber['token']; ?>">
                                <div class="form-actions">
                                    <button type="submit" name="confirm" value="1" class="btn btn-danger">
                                        <i class="material-icons">unsubscribe</i>
                                        <?php _e('yes_unsubscribe_me'); ?>
                                    </button>
                                    <button type="submit" name="confirm" value="0" class="btn btn-primary">
                                        <i class="material-icons">arrow_back</i>
                                        <?php _e('keep_my_subscription'); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php if ($type === 'success'): ?>
                        <div class="success-info">
                            <div class="alert alert-info">
                                <h4><?php _e('what_happens_now'); ?></h4>
                                <ul>
                                    <li><?php _e('no_more_newsletters'); ?></li>
                                    <li><?php _e('data_kept_securely'); ?></li>
                                    <li><?php _e('can_resubscribe_anytime'); ?></li>
                                    <li><?php _e('thank_you_for_interest'); ?></li>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="unsubscribe-actions">
                            <a href="<?php echo $appUrl . '/' . $currentLang; ?>" class="btn btn-primary">
                                <i class="material-icons">home</i>
                                <?php _e('back_to_homepage'); ?>
                            </a>
                            
                            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-outline-primary">
                                <i class="material-icons">explore</i>
                                <?php _e('browse_tours'); ?>
                            </a>
                            
                            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-outline-secondary">
                                <i class="material-icons">contact_support</i>
                                <?php _e('contact_us'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <?php if ($type !== 'success'): ?>
                <!-- Alternatives -->
                <div class="alternatives-section mt-5">
                    <div class="alternatives-card">
                        <h3><?php _e('before_you_go'); ?></h3>
                        <p><?php _e('consider_these_alternatives'); ?></p>
                        
                        <div class="alternatives-grid">
                            <div class="alternative-item">
                                <i class="material-icons">tune</i>
                                <div>
                                    <h4><?php _e('adjust_preferences'); ?></h4>
                                    <p><?php _e('adjust_preferences_description'); ?></p>
                                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-sm btn-outline-primary">
                                        <?php _e('contact_us'); ?>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="alternative-item">
                                <i class="material-icons">schedule</i>
                                <div>
                                    <h4><?php _e('reduce_frequency'); ?></h4>
                                    <p><?php _e('reduce_frequency_description'); ?></p>
                                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-sm btn-outline-primary">
                                        <?php _e('learn_more'); ?>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="alternative-item">
                                <i class="material-icons">pause</i>
                                <div>
                                    <h4><?php _e('temporary_pause'); ?></h4>
                                    <p><?php _e('temporary_pause_description'); ?></p>
                                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/contact" class="btn btn-sm btn-outline-primary">
                                        <?php _e('pause_subscription'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($type === 'success'): ?>
                <!-- Resubscribe Option -->
                <div class="resubscribe-section mt-5">
                    <div class="resubscribe-card">
                        <h3><?php _e('changed_your_mind'); ?></h3>
                        <p><?php _e('resubscribe_anytime_message'); ?></p>
                        
                        <div class="newsletter-form">
                            <form class="resubscribe-form" action="<?php echo $appUrl . '/' . $currentLang; ?>/newsletter/subscribe" method="POST">
                                <div class="form-group">
                                    <div class="input-group">
                                        <input type="email" name="email" class="form-control" 
                                               placeholder="<?php _e('enter_email_address'); ?>" 
                                               value="<?php echo htmlspecialchars($subscriber['email'] ?? ''); ?>" required>
                                        <input type="text" name="name" class="form-control" 
                                               placeholder="<?php _e('enter_your_name'); ?>" 
                                               value="<?php echo htmlspecialchars($subscriber['name'] ?? ''); ?>">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="material-icons">email</i>
                                            <?php _e('resubscribe'); ?>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.newsletter-unsubscribe {
    min-height: 80vh;
    display: flex;
    align-items: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #fef7f0 0%, #fed7d7 100%);
}

.unsubscribe-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    padding: 60px 40px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.unsubscribe-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f56565, #ed8936, #ecc94b);
}

.unsubscribe-icon {
    margin-bottom: 30px;
}

.unsubscribe-icon i {
    font-size: 80px;
}

.unsubscribe-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
}

.unsubscribe-message .lead {
    font-size: 1.25rem;
    color: #666;
    margin-bottom: 30px;
}

.subscriber-info {
    margin: 40px 0;
}

.info-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 30px;
    text-align: left;
}

.info-card h3 {
    color: #333;
    margin-bottom: 20px;
    font-size: 1.25rem;
    font-weight: 600;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #666;
}

.info-value {
    color: #333;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.status-active {
    background: #d4edda;
    color: #155724;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-unsubscribed {
    background: #f8d7da;
    color: #721c24;
}

.status-inactive {
    background: #d1ecf1;
    color: #0c5460;
}

.unsubscribe-form {
    margin: 40px 0;
}

.warning-box {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 12px;
    padding: 30px;
    margin-bottom: 30px;
    text-align: left;
}

.warning-box h4 {
    color: #856404;
    margin-bottom: 15px;
    font-size: 1.2rem;
    font-weight: 600;
}

.warning-box > p {
    color: #856404;
    margin-bottom: 20px;
}

.benefits-reminder {
    margin-top: 20px;
}

.benefits-reminder h5 {
    color: #856404;
    margin-bottom: 10px;
    font-size: 1rem;
    font-weight: 600;
}

.benefits-reminder ul {
    color: #856404;
    margin: 0;
    padding-left: 20px;
}

.benefits-reminder li {
    margin-bottom: 5px;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.success-info {
    margin: 30px 0;
}

.success-info .alert {
    text-align: left;
    border: none;
    background: #d1ecf1;
    border-left: 4px solid #17a2b8;
}

.success-info h4 {
    color: #0c5460;
    margin-bottom: 15px;
    font-size: 1.1rem;
    font-weight: 600;
}

.success-info ul {
    margin: 0;
    color: #0c5460;
}

.success-info li {
    margin-bottom: 8px;
}

.unsubscribe-actions {
    margin-top: 40px;
}

.unsubscribe-actions .btn {
    margin: 5px;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: #4361ee;
    color: white;
    border: none;
}

.btn-primary:hover {
    background: #3651db;
    color: white;
    text-decoration: none;
}

.btn-outline-primary {
    border: 2px solid #4361ee;
    color: #4361ee;
    background: transparent;
}

.btn-outline-primary:hover {
    background: #4361ee;
    color: white;
    text-decoration: none;
}

.btn-outline-secondary {
    border: 2px solid #6c757d;
    color: #6c757d;
    background: transparent;
}

.btn-outline-secondary:hover {
    background: #6c757d;
    color: white;
    text-decoration: none;
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
}

.btn-danger:hover {
    background: #c82333;
    color: white;
    text-decoration: none;
}

.alternatives-section, .resubscribe-section {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.alternatives-card, .resubscribe-card {
    padding: 40px;
}

.alternatives-card h3, .resubscribe-card h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.5rem;
    font-weight: 600;
    text-align: center;
}

.alternatives-card > p, .resubscribe-card > p {
    color: #666;
    margin-bottom: 30px;
    text-align: center;
}

.alternatives-grid {
    display: grid;
    gap: 25px;
}

.alternative-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.alternative-item i {
    font-size: 32px;
    color: #4361ee;
    margin-top: 5px;
}

.alternative-item h4 {
    color: #333;
    margin-bottom: 8px;
    font-size: 1.1rem;
    font-weight: 600;
}

.alternative-item p {
    color: #666;
    margin-bottom: 15px;
    line-height: 1.5;
}

.resubscribe-form {
    max-width: 600px;
    margin: 0 auto;
}

.input-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.input-group .form-control {
    flex: 1;
    min-width: 200px;
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 16px;
}

.input-group .form-control:focus {
    border-color: #4361ee;
    outline: none;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
}

.input-group .btn {
    white-space: nowrap;
}

@media (max-width: 768px) {
    .unsubscribe-card {
        padding: 40px 20px;
        margin: 20px;
    }
    
    .unsubscribe-title {
        font-size: 2rem;
    }
    
    .unsubscribe-icon i {
        font-size: 60px;
    }
    
    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .form-actions .btn {
        width: 100%;
        margin: 5px 0;
    }
    
    .unsubscribe-actions .btn {
        display: block;
        width: 100%;
        margin: 10px 0;
    }
    
    .alternative-item {
        flex-direction: column;
        text-align: center;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control,
    .input-group .btn {
        width: 100%;
    }
}
</style>