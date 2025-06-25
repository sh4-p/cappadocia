<div class="newsletter-confirmation">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="confirmation-card">
                    <div class="confirmation-icon">
                        <?php if ($type === 'success'): ?>
                            <i class="material-icons text-success">check_circle</i>
                        <?php elseif ($type === 'error'): ?>
                            <i class="material-icons text-danger">error</i>
                        <?php else: ?>
                            <i class="material-icons text-info">info</i>
                        <?php endif; ?>
                    </div>
                    
                    <div class="confirmation-content">
                        <h1 class="confirmation-title">
                            <?php if ($type === 'success'): ?>
                                <?php _e('subscription_confirmed_title'); ?>
                            <?php elseif ($type === 'error'): ?>
                                <?php _e('confirmation_error_title'); ?>
                            <?php else: ?>
                                <?php _e('subscription_status_title'); ?>
                            <?php endif; ?>
                        </h1>
                        
                        <div class="confirmation-message">
                            <p class="lead"><?php echo $message; ?></p>
                        </div>
                        
                        <?php if (isset($subscriber) && $subscriber): ?>
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
                                    <span class="info-label"><?php _e('status'); ?>:</span>
                                    <span class="info-value">
                                        <span class="status-badge status-<?php echo $subscriber['status']; ?>">
                                            <?php _e('status_' . $subscriber['status']); ?>
                                        </span>
                                    </span>
                                </div>
                                <?php if ($subscriber['subscribed_at']): ?>
                                <div class="info-row">
                                    <span class="info-label"><?php _e('subscribed_at'); ?>:</span>
                                    <span class="info-value"><?php echo date('d/m/Y H:i', strtotime($subscriber['subscribed_at'])); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($type === 'success'): ?>
                        <div class="success-info">
                            <div class="alert alert-success">
                                <h4><?php _e('what_happens_next'); ?></h4>
                                <ul>
                                    <li><?php _e('newsletter_benefit_1'); ?></li>
                                    <li><?php _e('newsletter_benefit_2'); ?></li>
                                    <li><?php _e('newsletter_benefit_3'); ?></li>
                                    <li><?php _e('newsletter_benefit_4'); ?></li>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="confirmation-actions">
                            <a href="<?php echo $appUrl . '/' . $currentLang; ?>" class="btn btn-primary">
                                <i class="material-icons">home</i>
                                <?php _e('back_to_homepage'); ?>
                            </a>
                            
                            <?php if ($type === 'success' || ($subscriber && $subscriber['status'] === 'active')): ?>
                            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours" class="btn btn-outline-primary">
                                <i class="material-icons">explore</i>
                                <?php _e('browse_tours'); ?>
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($subscriber && $subscriber['status'] !== 'unsubscribed'): ?>
                            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/newsletter/unsubscribe/<?php echo $subscriber['token']; ?>" 
                               class="btn btn-outline-secondary">
                                <i class="material-icons">unsubscribe</i>
                                <?php _e('unsubscribe'); ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php if ($type === 'success'): ?>
                <!-- Newsletter Preview -->
                <div class="newsletter-preview mt-5">
                    <div class="preview-card">
                        <h3><?php _e('newsletter_preview_title'); ?></h3>
                        <p><?php _e('newsletter_preview_description'); ?></p>
                        
                        <div class="preview-content">
                            <div class="preview-item">
                                <i class="material-icons">explore</i>
                                <div>
                                    <h4><?php _e('exclusive_tours'); ?></h4>
                                    <p><?php _e('exclusive_tours_description'); ?></p>
                                </div>
                            </div>
                            
                            <div class="preview-item">
                                <i class="material-icons">local_offer</i>
                                <div>
                                    <h4><?php _e('special_offers'); ?></h4>
                                    <p><?php _e('special_offers_description'); ?></p>
                                </div>
                            </div>
                            
                            <div class="preview-item">
                                <i class="material-icons">place</i>
                                <div>
                                    <h4><?php _e('travel_tips'); ?></h4>
                                    <p><?php _e('travel_tips_description'); ?></p>
                                </div>
                            </div>
                            
                            <div class="preview-item">
                                <i class="material-icons">event</i>
                                <div>
                                    <h4><?php _e('early_access'); ?></h4>
                                    <p><?php _e('early_access_description'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.newsletter-confirmation {
    min-height: 80vh;
    display: flex;
    align-items: center;
    padding: 40px 0;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.confirmation-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    padding: 60px 40px;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.confirmation-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #4361ee, #7c3aed, #ec4899);
}

.confirmation-icon {
    margin-bottom: 30px;
}

.confirmation-icon i {
    font-size: 80px;
}

.confirmation-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 20px;
}

.confirmation-message .lead {
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

.success-info {
    margin: 30px 0;
}

.success-info .alert {
    text-align: left;
    border: none;
    background: #d4edda;
    border-left: 4px solid #28a745;
}

.success-info h4 {
    color: #155724;
    margin-bottom: 15px;
    font-size: 1.1rem;
    font-weight: 600;
}

.success-info ul {
    margin: 0;
    color: #155724;
}

.success-info li {
    margin-bottom: 8px;
}

.confirmation-actions {
    margin-top: 40px;
}

.confirmation-actions .btn {
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

.newsletter-preview {
    background: white;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    overflow: hidden;
}

.preview-card {
    padding: 40px;
}

.preview-card h3 {
    color: #333;
    margin-bottom: 15px;
    font-size: 1.5rem;
    font-weight: 600;
}

.preview-card > p {
    color: #666;
    margin-bottom: 30px;
}

.preview-content {
    display: grid;
    gap: 25px;
}

.preview-item {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.preview-item i {
    font-size: 32px;
    color: #4361ee;
    margin-top: 5px;
}

.preview-item h4 {
    color: #333;
    margin-bottom: 8px;
    font-size: 1.1rem;
    font-weight: 600;
}

.preview-item p {
    color: #666;
    margin: 0;
    line-height: 1.5;
}

@media (max-width: 768px) {
    .confirmation-card {
        padding: 40px 20px;
        margin: 20px;
    }
    
    .confirmation-title {
        font-size: 2rem;
    }
    
    .confirmation-icon i {
        font-size: 60px;
    }
    
    .info-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
    }
    
    .confirmation-actions .btn {
        display: block;
        width: 100%;
        margin: 10px 0;
    }
    
    .preview-item {
        flex-direction: column;
        text-align: center;
    }
}
</style>