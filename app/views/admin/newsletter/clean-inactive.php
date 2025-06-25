<div class="page-header">
    <div class="page-title">
        <h1><i class="material-icons">cleaning_services</i> <?php _e('clean_inactive_subscribers'); ?></h1>
        <p><?php _e('remove_inactive_pending_subscribers'); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-outline-secondary">
            <i class="material-icons">arrow_back</i>
            <?php _e('back_to_subscribers'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?php _e('cleanup_configuration'); ?></h4>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <h5><i class="material-icons">warning</i> <?php _e('important_warning'); ?></h5>
                    <p><?php _e('cleanup_warning_message'); ?></p>
                </div>
                
                <form method="POST" action="<?php echo $adminUrl; ?>/newsletter/clean-inactive" id="cleanup-form">
                    <div class="mb-4">
                        <label for="days" class="form-label"><?php _e('cleanup_period'); ?></label>
                        <div class="input-group">
                            <input type="number" name="days" id="days" class="form-control" 
                                   value="365" min="30" max="1095" required>
                            <span class="input-group-text"><?php _e('days'); ?></span>
                        </div>
                        <small class="form-text text-muted"><?php _e('cleanup_period_description'); ?></small>
                    </div>
                    
                    <div class="cleanup-preview" id="cleanup-preview">
                        <h5><?php _e('cleanup_preview'); ?></h5>
                        <div class="preview-stats">
                            <div class="stat-card stat-danger">
                                <div class="stat-icon">
                                    <i class="material-icons">delete_forever</i>
                                </div>
                                <div class="stat-content">
                                    <h3 id="subscribers-to-remove">-</h3>
                                    <p><?php _e('subscribers_to_remove'); ?></p>
                                </div>
                            </div>
                            
                            <div class="stat-card stat-success">
                                <div class="stat-icon">
                                    <i class="material-icons">check_circle</i>
                                </div>
                                <div class="stat-content">
                                    <h3 id="subscribers-to-keep">-</h3>
                                    <p><?php _e('subscribers_to_keep'); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="preview-details" id="preview-details">
                            <div class="details-card">
                                <h6><?php _e('cleanup_criteria'); ?></h6>
                                <ul class="criteria-list">
                                    <li><?php _e('criteria_pending_status'); ?></li>
                                    <li><?php _e('criteria_older_than'); ?> <strong id="cleanup-days">365</strong> <?php _e('days'); ?></li>
                                    <li><?php _e('criteria_never_confirmed'); ?></li>
                                </ul>
                            </div>
                            
                            <div class="details-card">
                                <h6><?php _e('will_be_preserved'); ?></h6>
                                <ul class="preservation-list">
                                    <li><?php _e('active_subscribers'); ?></li>
                                    <li><?php _e('unsubscribed_subscribers'); ?></li>
                                    <li><?php _e('recent_pending_subscribers'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="confirmation-section">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirm-cleanup" required>
                            <label class="form-check-label" for="confirm-cleanup">
                                <strong><?php _e('confirm_cleanup_action'); ?></strong>
                            </label>
                        </div>
                        <small class="form-text text-muted"><?php _e('confirm_cleanup_description'); ?></small>
                    </div>
                    
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-danger" id="cleanup-btn" disabled>
                            <i class="material-icons">cleaning_services</i>
                            <?php _e('clean_inactive_subscribers'); ?>
                        </button>
                        <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-secondary">
                            <?php _e('cancel'); ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Current Statistics -->
        <div class="card">
            <div class="card-header">
                <h5><i class="material-icons">analytics</i> <?php _e('current_statistics'); ?></h5>
            </div>
            <div class="card-body">
                <div class="current-stats">
                    <div class="stat-row">
                        <span class="stat-label"><?php _e('total_subscribers'); ?></span>
                        <span class="stat-value"><?php echo number_format($stats['total'] ?? 0); ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label"><?php _e('active_subscribers'); ?></span>
                        <span class="stat-value text-success"><?php echo number_format($stats['active'] ?? 0); ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label"><?php _e('pending_subscribers'); ?></span>
                        <span class="stat-value text-warning"><?php echo number_format($stats['pending'] ?? 0); ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label"><?php _e('unsubscribed'); ?></span>
                        <span class="stat-value text-muted"><?php echo number_format($stats['unsubscribed'] ?? 0); ?></span>
                    </div>
                    <div class="stat-row">
                        <span class="stat-label"><?php _e('inactive_subscribers'); ?></span>
                        <span class="stat-value text-danger"><?php echo number_format($stats['inactive'] ?? 0); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cleanup Benefits -->
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="material-icons">eco</i> <?php _e('cleanup_benefits'); ?></h5>
            </div>
            <div class="card-body">
                <ul class="benefits-list">
                    <li>
                        <i class="material-icons text-success">speed</i>
                        <div>
                            <strong><?php _e('improved_performance'); ?></strong>
                            <small><?php _e('improved_performance_description'); ?></small>
                        </div>
                    </li>
                    <li>
                        <i class="material-icons text-success">storage</i>
                        <div>
                            <strong><?php _e('reduced_storage'); ?></strong>
                            <small><?php _e('reduced_storage_description'); ?></small>
                        </div>
                    </li>
                    <li>
                        <i class="material-icons text-success">analytics</i>
                        <div>
                            <strong><?php _e('better_metrics'); ?></strong>
                            <small><?php _e('better_metrics_description'); ?></small>
                        </div>
                    </li>
                    <li>
                        <i class="material-icons text-success">security</i>
                        <div>
                            <strong><?php _e('gdpr_compliance'); ?></strong>
                            <small><?php _e('gdpr_compliance_description'); ?></small>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Best Practices -->
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="material-icons">tips_and_updates</i> <?php _e('best_practices'); ?></h5>
            </div>
            <div class="card-body">
                <h6><?php _e('recommended_cleanup_periods'); ?></h6>
                <div class="practice-item">
                    <strong>30-90 <?php _e('days'); ?></strong>
                    <small><?php _e('aggressive_cleanup_description'); ?></small>
                </div>
                <div class="practice-item">
                    <strong>365 <?php _e('days'); ?></strong>
                    <small><?php _e('standard_cleanup_description'); ?></small>
                </div>
                <div class="practice-item">
                    <strong>730 <?php _e('days'); ?></strong>
                    <small><?php _e('conservative_cleanup_description'); ?></small>
                </div>
                
                <h6 class="mt-3"><?php _e('cleanup_schedule'); ?></h6>
                <ul class="small">
                    <li><?php _e('monthly_cleanup_tip'); ?></li>
                    <li><?php _e('quarterly_review_tip'); ?></li>
                    <li><?php _e('backup_before_cleanup_tip'); ?></li>
                </ul>
            </div>
        </div>
        
        <!-- Safety Notice -->
        <div class="card mt-3 border-warning">
            <div class="card-header bg-warning">
                <h5><i class="material-icons">warning</i> <?php _e('safety_notice'); ?></h5>
            </div>
            <div class="card-body">
                <ul class="small mb-0">
                    <li><?php _e('safety_tip_1'); ?></li>
                    <li><?php _e('safety_tip_2'); ?></li>
                    <li><?php _e('safety_tip_3'); ?></li>
                    <li><?php _e('safety_tip_4'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 15px;
}

.stat-card.stat-danger {
    background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
    border: 1px solid #fca5a5;
}

.stat-card.stat-success {
    background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
    border: 1px solid #86efac;
}

.stat-icon {
    margin-right: 15px;
}

.stat-icon i {
    font-size: 36px;
}

.stat-card.stat-danger .stat-icon i {
    color: #dc2626;
}

.stat-card.stat-success .stat-icon i {
    color: #16a34a;
}

.stat-content h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.stat-card.stat-danger .stat-content h3 {
    color: #dc2626;
}

.stat-card.stat-success .stat-content h3 {
    color: #16a34a;
}

.stat-content p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.preview-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 25px;
}

.preview-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.details-card {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
}

.details-card h6 {
    color: #333;
    margin-bottom: 15px;
    font-weight: 600;
}

.criteria-list, .preservation-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.criteria-list li, .preservation-list li {
    padding: 8px 0;
    border-bottom: 1px solid #e9ecef;
    font-size: 14px;
    color: #666;
}

.criteria-list li:last-child, .preservation-list li:last-child {
    border-bottom: none;
}

.current-stats {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.stat-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f1f1f1;
}

.stat-row:last-child {
    border-bottom: none;
}

.stat-label {
    font-size: 14px;
    color: #666;
}

.stat-value {
    font-weight: 600;
    font-size: 16px;
}

.benefits-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.benefits-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f1f1f1;
}

.benefits-list li:last-child {
    border-bottom: none;
}

.benefits-list li i {
    font-size: 20px;
    margin-top: 2px;
}

.benefits-list li div {
    flex: 1;
}

.benefits-list li strong {
    display: block;
    color: #333;
    font-size: 14px;
    margin-bottom: 4px;
}

.benefits-list li small {
    color: #666;
    font-size: 12px;
    line-height: 1.4;
}

.practice-item {
    padding: 10px 0;
    border-bottom: 1px solid #f1f1f1;
}

.practice-item:last-child {
    border-bottom: none;
}

.practice-item strong {
    display: block;
    color: #4361ee;
    font-size: 14px;
    margin-bottom: 4px;
}

.practice-item small {
    color: #666;
    font-size: 12px;
}

.confirmation-section {
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    border-radius: 8px;
    padding: 20px;
    margin: 30px 0;
}

@media (max-width: 768px) {
    .preview-stats,
    .preview-details {
        grid-template-columns: 1fr;
    }
    
    .stat-card {
        flex-direction: column;
        text-align: center;
    }
    
    .stat-icon {
        margin-right: 0;
        margin-bottom: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const daysInput = document.getElementById('days');
    const confirmCheckbox = document.getElementById('confirm-cleanup');
    const cleanupBtn = document.getElementById('cleanup-btn');
    const cleanupDaysSpan = document.getElementById('cleanup-days');
    
    // Update preview when days change
    daysInput.addEventListener('input', updatePreview);
    
    // Enable/disable button based on confirmation
    confirmCheckbox.addEventListener('change', function() {
        cleanupBtn.disabled = !this.checked;
    });
    
    // Initial preview update
    updatePreview();
    
    function updatePreview() {
        const days = parseInt(daysInput.value) || 365;
        cleanupDaysSpan.textContent = days;
        
        // In a real implementation, this would be an AJAX call to get actual counts
        // For now, we'll simulate the calculation
        const totalPending = <?php echo $stats['pending'] ?? 0; ?>;
        const estimatedOld = Math.floor(totalPending * 0.3); // Estimate 30% are old
        const toKeep = totalPending - estimatedOld;
        
        document.getElementById('subscribers-to-remove').textContent = estimatedOld.toLocaleString();
        document.getElementById('subscribers-to-keep').textContent = toKeep.toLocaleString();
    }
});

// Form submission confirmation
document.getElementById('cleanup-form').addEventListener('submit', function(e) {
    const days = document.getElementById('days').value;
    const subscribersToRemove = document.getElementById('subscribers-to-remove').textContent;
    
    if (!confirm(`<?php _e("final_cleanup_confirmation"); ?>\n\n<?php _e("days"); ?>: ${days}\n<?php _e("subscribers_to_remove"); ?>: ${subscribersToRemove}\n\n<?php _e("this_action_cannot_be_undone"); ?>`)) {
        e.preventDefault();
        return;
    }
    
    // Show loading state
    const cleanupBtn = document.getElementById('cleanup-btn');
    cleanupBtn.disabled = true;
    cleanupBtn.innerHTML = '<i class="material-icons">hourglass_empty</i> <?php _e("cleaning"); ?>...';
});
</script>