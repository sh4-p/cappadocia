<?php
/**
 * Admin Anti-Bot Dashboard View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('antibot_management'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/antibot/settings" class="btn btn-primary">
            <i class="material-icons">settings</i>
            <span><?php _e('antibot_settings'); ?></span>
        </a>
        <?php if ($isEnabled): ?>
            <button type="button" class="btn btn-light" onclick="cleanOldRecords()">
                <i class="material-icons">cleaning_services</i>
                <span><?php _e('clean_old_records'); ?></span>
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if (!$isEnabled): ?>
    <div class="alert alert-warning">
        <i class="material-icons">warning</i>
        <span><?php _e('antibot_system_disabled'); ?></span>
        <a href="<?php echo $adminUrl; ?>/antibot/settings" class="btn btn-sm btn-primary ml-3">
            <?php _e('enable_antibot'); ?>
        </a>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="material-icons">security</i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo number_format($overallStats['total_attempts']); ?></div>
            <div class="stat-label"><?php _e('total_form_attempts'); ?></div>
        </div>
    </div>
    
    <div class="stat-card stat-danger">
        <div class="stat-icon">
            <i class="material-icons">bug_report</i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo number_format($overallStats['total_bot_attempts']); ?></div>
            <div class="stat-label"><?php _e('bot_attempts_blocked'); ?></div>
        </div>
    </div>
    
    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <i class="material-icons">block</i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo number_format($overallStats['total_blocked_ips']); ?></div>
            <div class="stat-label"><?php _e('blocked_ips'); ?></div>
        </div>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-icon">
            <i class="material-icons">verified</i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo $overallStats['success_rate']; ?>%</div>
            <div class="stat-label"><?php _e('success_rate'); ?></div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Protection Status -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php _e('protection_status'); ?></h3>
            </div>
            <div class="card-body">
                <div class="protection-status">
                    <div class="protection-item">
                        <div class="protection-name">
                            <i class="material-icons">shield</i>
                            <?php _e('general_protection'); ?>
                        </div>
                        <div class="protection-status-badge">
                            <?php if ($isEnabled): ?>
                                <span class="badge badge-success"><?php _e('enabled'); ?></span>
                            <?php else: ?>
                                <span class="badge badge-danger"><?php _e('disabled'); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="protection-item">
                        <div class="protection-name">
                            <i class="material-icons">verified_user</i>
                            reCAPTCHA v2
                        </div>
                        <div class="protection-status-badge">
                            <?php if (isset($settings['antibot_recaptcha_v2_enabled']) && $settings['antibot_recaptcha_v2_enabled'] == '1'): ?>
                                <span class="badge badge-success"><?php _e('enabled'); ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php _e('disabled'); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="protection-item">
                        <div class="protection-name">
                            <i class="material-icons">smart_toy</i>
                            reCAPTCHA v3
                        </div>
                        <div class="protection-status-badge">
                            <?php if (isset($settings['antibot_recaptcha_v3_enabled']) && $settings['antibot_recaptcha_v3_enabled'] == '1'): ?>
                                <span class="badge badge-success"><?php _e('enabled'); ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php _e('disabled'); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="protection-item">
                        <div class="protection-name">
                            <i class="material-icons">cloud</i>
                            Cloudflare Turnstile
                        </div>
                        <div class="protection-status-badge">
                            <?php if (isset($settings['antibot_turnstile_enabled']) && $settings['antibot_turnstile_enabled'] == '1'): ?>
                                <span class="badge badge-success"><?php _e('enabled'); ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php _e('disabled'); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="protection-item">
                        <div class="protection-name">
                            <i class="material-icons">visibility_off</i>
                            <?php _e('honeypot_fields'); ?>
                        </div>
                        <div class="protection-status-badge">
                            <?php if (isset($settings['antibot_honeypot_enabled']) && $settings['antibot_honeypot_enabled'] == '1'): ?>
                                <span class="badge badge-success"><?php _e('enabled'); ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php _e('disabled'); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="protection-item">
                        <div class="protection-name">
                            <i class="material-icons">speed</i>
                            <?php _e('rate_limiting'); ?>
                        </div>
                        <div class="protection-status-badge">
                            <?php if (isset($settings['antibot_rate_limit_enabled']) && $settings['antibot_rate_limit_enabled'] == '1'): ?>
                                <span class="badge badge-success"><?php _e('enabled'); ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php _e('disabled'); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Bot Attempts -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php _e('recent_bot_attempts'); ?></h3>
                <div class="card-actions">
                    <a href="<?php echo $adminUrl; ?>/antibot/attempts" class="btn btn-sm btn-light">
                        <?php _e('view_all'); ?>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (empty($recentAttempts)): ?>
                    <div class="empty-state-small">
                        <i class="material-icons">security</i>
                        <p><?php _e('no_bot_attempts_found'); ?></p>
                    </div>
                <?php else: ?>
                    <div class="recent-attempts">
                        <?php foreach (array_slice($recentAttempts, 0, 8) as $attempt): ?>
                            <div class="attempt-item">
                                <div class="attempt-info">
                                    <div class="attempt-type">
                                        <span class="badge badge-danger"><?php echo ucfirst($attempt['protection_type']); ?></span>
                                        <span class="text-muted"><?php echo ucfirst($attempt['form_type']); ?></span>
                                    </div>
                                    <div class="attempt-ip"><?php echo $attempt['ip_address']; ?></div>
                                </div>
                                <div class="attempt-time">
                                    <?php echo date('M d, H:i', strtotime($attempt['created_at'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?php _e('quick_actions'); ?></h3>
            </div>
            <div class="card-body">
                <div class="quick-actions">
                    <a href="<?php echo $adminUrl; ?>/antibot/attempts" class="quick-action-btn">
                        <i class="material-icons">list</i>
                        <span><?php _e('view_bot_attempts'); ?></span>
                    </a>
                    
                    <a href="<?php echo $adminUrl; ?>/antibot/blocks" class="quick-action-btn">
                        <i class="material-icons">block</i>
                        <span><?php _e('manage_ip_blocks'); ?></span>
                    </a>
                    
                    <a href="<?php echo $adminUrl; ?>/antibot/statistics" class="quick-action-btn">
                        <i class="material-icons">analytics</i>
                        <span><?php _e('view_statistics'); ?></span>
                    </a>
                    
                    <button type="button" class="quick-action-btn" onclick="addIPBlock()">
                        <i class="material-icons">add_moderator</i>
                        <span><?php _e('block_ip_address'); ?></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add IP Block Modal -->
<div class="modal fade" id="addIPBlockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('block_ip_address'); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?php echo $adminUrl; ?>/antibot/add-block" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ip_address"><?php _e('ip_address'); ?> <span class="required">*</span></label>
                        <input type="text" id="ip_address" name="ip_address" class="form-control" required placeholder="192.168.1.1">
                    </div>
                    
                    <div class="form-group">
                        <label for="reason"><?php _e('reason'); ?></label>
                        <input type="text" id="reason" name="reason" class="form-control" placeholder="<?php _e('manual_block'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="hours"><?php _e('block_duration_hours'); ?></label>
                        <input type="number" id="hours" name="hours" class="form-control" placeholder="0" min="0">
                        <small class="form-text"><?php _e('leave_empty_permanent_block'); ?></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><?php _e('cancel'); ?></button>
                    <button type="submit" class="btn btn-danger"><?php _e('block_ip'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 4px solid var(--primary-color);
    margin-top: 1rem;
}

.stat-card.stat-danger {
    border-left-color: #dc3545;
}

.stat-card.stat-warning {
    border-left-color: #ffc107;
}

.stat-card.stat-success {
    border-left-color: #28a745;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.stat-card.stat-danger .stat-icon {
    background: #dc3545;
}

.stat-card.stat-warning .stat-icon {
    background: #ffc107;
}

.stat-card.stat-success .stat-icon {
    background: #28a745;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    line-height: 1;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.protection-status {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.protection-item {
    display: flex;
    justify-content: between;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    border-radius: 6px;
}

.protection-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex: 1;
}

.protection-name i {
    font-size: 20px;
    color: #666;
}

.recent-attempts {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.attempt-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    background: #f8f9fa;
}

.attempt-type {
    margin-bottom: 0.25rem;
}

.attempt-type .badge {
    font-size: 0.7rem;
}

.attempt-ip {
    font-family: monospace;
    font-size: 0.9rem;
    color: #666;
}

.attempt-time {
    font-size: 0.8rem;
    color: #999;
}

.quick-actions {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.quick-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.5rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    text-decoration: none;
    color: #666;
    transition: all 0.3s;
    background: white;
    cursor: pointer;
}

.quick-action-btn:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    text-decoration: none;
}

.quick-action-btn i {
    font-size: 2rem;
}

.empty-state-small {
    text-align: center;
    padding: 2rem;
    color: #666;
}

.empty-state-small i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.badge {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    border-radius: 0.25rem;
}

.badge-success {
    background-color: #28a745;
    color: white;
}

.badge-danger {
    background-color: #dc3545;
    color: white;
}

.badge-secondary {
    background-color: #6c757d;
    color: white;
}
</style>

<script>
function addIPBlock() {
    $('#addIPBlockModal').modal('show');
}

function cleanOldRecords() {
    if (confirm('<?php _e("clean_old_records_confirm"); ?>')) {
        fetch('<?php echo $adminUrl; ?>/antibot/clean', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
}

// Auto-refresh statistics every 30 seconds
setInterval(function() {
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 30000);
</script>