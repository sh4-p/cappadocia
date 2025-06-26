<?php
/**
 * Admin Anti-Bot Settings View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('antibot_settings'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/antibot" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_antibot'); ?></span>
        </a>
    </div>
</div>

<form action="<?php echo $adminUrl; ?>/antibot/settings" method="post" class="antibot-settings-form">
    <div class="row">
        <!-- General Settings -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('general_settings'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="antibot_enabled" name="antibot_enabled" value="1" class="form-check-input" 
                                   <?php echo (isset($settings['antibot_enabled']) && $settings['antibot_enabled'] == '1') ? 'checked' : ''; ?>>
                            <label for="antibot_enabled" class="form-check-label">
                                <strong><?php _e('enable_antibot_system'); ?></strong>
                            </label>
                        </div>
                        <small class="form-text"><?php _e('enable_antibot_system_help'); ?></small>
                    </div>
                </div>
            </div>
            
            <!-- reCAPTCHA v2 Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="material-icons">verified_user</i>
                        Google reCAPTCHA v2
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="antibot_recaptcha_v2_enabled" name="antibot_recaptcha_v2_enabled" value="1" class="form-check-input" 
                                   <?php echo (isset($settings['antibot_recaptcha_v2_enabled']) && $settings['antibot_recaptcha_v2_enabled'] == '1') ? 'checked' : ''; ?>>
                            <label for="antibot_recaptcha_v2_enabled" class="form-check-label">
                                <?php _e('enable_recaptcha_v2'); ?>
                            </label>
                        </div>
                        <small class="form-text"><?php _e('enable_recaptcha_v2_help'); ?></small>
                    </div>
                    
                    <div class="recaptcha-v2-settings" style="display: <?php echo (isset($settings['antibot_recaptcha_v2_enabled']) && $settings['antibot_recaptcha_v2_enabled'] == '1') ? 'block' : 'none'; ?>;">
                        <div class="form-group">
                            <label for="recaptcha_v2_site_key"><?php _e('site_key'); ?> <span class="required">*</span></label>
                            <input type="text" id="recaptcha_v2_site_key" name="recaptcha_v2_site_key" class="form-control" 
                                   value="<?php echo htmlspecialchars($settings['recaptcha_v2_site_key'] ?? ''); ?>" placeholder="6Le...">
                            <small class="form-text"><?php _e('recaptcha_site_key_help'); ?></small>
                        </div>
                        
                        <div class="form-group">
                            <label for="recaptcha_v2_secret_key"><?php _e('secret_key'); ?> <span class="required">*</span></label>
                            <input type="password" id="recaptcha_v2_secret_key" name="recaptcha_v2_secret_key" class="form-control" 
                                   value="<?php echo htmlspecialchars($settings['recaptcha_v2_secret_key'] ?? ''); ?>" placeholder="6Le...">
                            <small class="form-text"><?php _e('recaptcha_secret_key_help'); ?></small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="material-icons">info</i>
                            <?php _e('recaptcha_v2_info'); ?>
                            <a href="https://www.google.com/recaptcha/admin" target="_blank"><?php _e('get_recaptcha_keys'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- reCAPTCHA v3 Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="material-icons">smart_toy</i>
                        Google reCAPTCHA v3
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="antibot_recaptcha_v3_enabled" name="antibot_recaptcha_v3_enabled" value="1" class="form-check-input" 
                                   <?php echo (isset($settings['antibot_recaptcha_v3_enabled']) && $settings['antibot_recaptcha_v3_enabled'] == '1') ? 'checked' : ''; ?>>
                            <label for="antibot_recaptcha_v3_enabled" class="form-check-label">
                                <?php _e('enable_recaptcha_v3'); ?>
                            </label>
                        </div>
                        <small class="form-text"><?php _e('enable_recaptcha_v3_help'); ?></small>
                    </div>
                    
                    <div class="recaptcha-v3-settings" style="display: <?php echo (isset($settings['antibot_recaptcha_v3_enabled']) && $settings['antibot_recaptcha_v3_enabled'] == '1') ? 'block' : 'none'; ?>;">
                        <div class="form-group">
                            <label for="recaptcha_v3_site_key"><?php _e('site_key'); ?> <span class="required">*</span></label>
                            <input type="text" id="recaptcha_v3_site_key" name="recaptcha_v3_site_key" class="form-control" 
                                   value="<?php echo htmlspecialchars($settings['recaptcha_v3_site_key'] ?? ''); ?>" placeholder="6Le...">
                        </div>
                        
                        <div class="form-group">
                            <label for="recaptcha_v3_secret_key"><?php _e('secret_key'); ?> <span class="required">*</span></label>
                            <input type="password" id="recaptcha_v3_secret_key" name="recaptcha_v3_secret_key" class="form-control" 
                                   value="<?php echo htmlspecialchars($settings['recaptcha_v3_secret_key'] ?? ''); ?>" placeholder="6Le...">
                        </div>
                        
                        <div class="form-group">
                            <label for="recaptcha_v3_min_score"><?php _e('minimum_score'); ?></label>
                            <input type="number" id="recaptcha_v3_min_score" name="recaptcha_v3_min_score" class="form-control" 
                                   value="<?php echo htmlspecialchars($settings['recaptcha_v3_min_score'] ?? '0.5'); ?>" 
                                   min="0" max="1" step="0.1">
                            <small class="form-text"><?php _e('recaptcha_v3_score_help'); ?></small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="material-icons">info</i>
                            <?php _e('recaptcha_v3_info'); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Cloudflare Turnstile Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="material-icons">cloud</i>
                        Cloudflare Turnstile
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="antibot_turnstile_enabled" name="antibot_turnstile_enabled" value="1" class="form-check-input" 
                                   <?php echo (isset($settings['antibot_turnstile_enabled']) && $settings['antibot_turnstile_enabled'] == '1') ? 'checked' : ''; ?>>
                            <label for="antibot_turnstile_enabled" class="form-check-label">
                                <?php _e('enable_turnstile'); ?>
                            </label>
                        </div>
                        <small class="form-text"><?php _e('enable_turnstile_help'); ?></small>
                    </div>
                    
                    <div class="turnstile-settings" style="display: <?php echo (isset($settings['antibot_turnstile_enabled']) && $settings['antibot_turnstile_enabled'] == '1') ? 'block' : 'none'; ?>;">
                        <div class="form-group">
                            <label for="turnstile_site_key"><?php _e('site_key'); ?> <span class="required">*</span></label>
                            <input type="text" id="turnstile_site_key" name="turnstile_site_key" class="form-control" 
                                   value="<?php echo htmlspecialchars($settings['turnstile_site_key'] ?? ''); ?>" placeholder="0x...">
                        </div>
                        
                        <div class="form-group">
                            <label for="turnstile_secret_key"><?php _e('secret_key'); ?> <span class="required">*</span></label>
                            <input type="password" id="turnstile_secret_key" name="turnstile_secret_key" class="form-control" 
                                   value="<?php echo htmlspecialchars($settings['turnstile_secret_key'] ?? ''); ?>" placeholder="0x...">
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="material-icons">info</i>
                            <?php _e('turnstile_info'); ?>
                            <a href="https://dash.cloudflare.com/" target="_blank"><?php _e('get_turnstile_keys'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Honeypot Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="material-icons">visibility_off</i>
                        <?php _e('honeypot_protection'); ?>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="antibot_honeypot_enabled" name="antibot_honeypot_enabled" value="1" class="form-check-input" 
                                   <?php echo (isset($settings['antibot_honeypot_enabled']) && $settings['antibot_honeypot_enabled'] == '1') ? 'checked' : ''; ?>>
                            <label for="antibot_honeypot_enabled" class="form-check-label">
                                <?php _e('enable_honeypot'); ?>
                            </label>
                        </div>
                        <small class="form-text"><?php _e('enable_honeypot_help'); ?></small>
                    </div>
                    
                    <div class="honeypot-settings" style="display: <?php echo (isset($settings['antibot_honeypot_enabled']) && $settings['antibot_honeypot_enabled'] == '1') ? 'block' : 'none'; ?>;">
                        <div class="form-group">
                            <label for="honeypot_field_name"><?php _e('honeypot_field_name'); ?></label>
                            <input type="text" id="honeypot_field_name" name="honeypot_field_name" class="form-control" 
                                   value="<?php echo htmlspecialchars($settings['honeypot_field_name'] ?? 'website'); ?>" placeholder="website">
                            <small class="form-text"><?php _e('honeypot_field_name_help'); ?></small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="material-icons">info</i>
                            <?php _e('honeypot_info'); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rate Limiting Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="material-icons">speed</i>
                        <?php _e('rate_limiting'); ?>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="antibot_rate_limit_enabled" name="antibot_rate_limit_enabled" value="1" class="form-check-input" 
                                   <?php echo (isset($settings['antibot_rate_limit_enabled']) && $settings['antibot_rate_limit_enabled'] == '1') ? 'checked' : ''; ?>>
                            <label for="antibot_rate_limit_enabled" class="form-check-label">
                                <?php _e('enable_rate_limiting'); ?>
                            </label>
                        </div>
                        <small class="form-text"><?php _e('enable_rate_limiting_help'); ?></small>
                    </div>
                    
                    <div class="rate-limit-settings" style="display: <?php echo (isset($settings['antibot_rate_limit_enabled']) && $settings['antibot_rate_limit_enabled'] == '1') ? 'block' : 'none'; ?>;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="antibot_rate_limit_contact"><?php _e('contact_form_limit'); ?></label>
                                    <input type="number" id="antibot_rate_limit_contact" name="antibot_rate_limit_contact" class="form-control" 
                                           value="<?php echo (int)($settings['antibot_rate_limit_contact'] ?? 3); ?>" min="1" max="100">
                                    <small class="form-text"><?php _e('attempts_per_hour'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="antibot_rate_limit_newsletter"><?php _e('newsletter_form_limit'); ?></label>
                                    <input type="number" id="antibot_rate_limit_newsletter" name="antibot_rate_limit_newsletter" class="form-control" 
                                           value="<?php echo (int)($settings['antibot_rate_limit_newsletter'] ?? 5); ?>" min="1" max="100">
                                    <small class="form-text"><?php _e('attempts_per_hour'); ?></small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="antibot_rate_limit_booking"><?php _e('booking_form_limit'); ?></label>
                                    <input type="number" id="antibot_rate_limit_booking" name="antibot_rate_limit_booking" class="form-control" 
                                           value="<?php echo (int)($settings['antibot_rate_limit_booking'] ?? 10); ?>" min="1" max="100">
                                    <small class="form-text"><?php _e('attempts_per_hour'); ?></small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="material-icons">info</i>
                            <?php _e('rate_limiting_info'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Auto-blocking Settings -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="material-icons">block</i>
                        <?php _e('auto_blocking'); ?>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="antibot_auto_block_threshold"><?php _e('auto_block_threshold'); ?></label>
                        <input type="number" id="antibot_auto_block_threshold" name="antibot_auto_block_threshold" class="form-control" 
                               value="<?php echo (int)($settings['antibot_auto_block_threshold'] ?? 5); ?>" min="0" max="100">
                        <small class="form-text"><?php _e('auto_block_threshold_help'); ?></small>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="material-icons">warning</i>
                        <?php _e('auto_block_warning'); ?>
                    </div>
                </div>
            </div>
            
            <!-- Test Protection -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="material-icons">science</i>
                        <?php _e('test_protection'); ?>
                    </h3>
                </div>
                <div class="card-body">
                    <p><?php _e('test_protection_description'); ?></p>
                    
                    <div class="test-buttons">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="testProtection('recaptcha_v2')">
                            <?php _e('test_recaptcha_v2'); ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="testProtection('recaptcha_v3')">
                            <?php _e('test_recaptcha_v3'); ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="testProtection('turnstile')">
                            <?php _e('test_turnstile'); ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="testProtection('honeypot')">
                            <?php _e('test_honeypot'); ?>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="testProtection('rate_limit')">
                            <?php _e('test_rate_limit'); ?>
                        </button>
                    </div>
                    
                    <div id="test-results" class="mt-3"></div>
                </div>
            </div>
            
            <!-- Documentation -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="material-icons">help</i>
                        <?php _e('documentation'); ?>
                    </h3>
                </div>
                <div class="card-body">
                    <div class="documentation-links">
                        <a href="https://developers.google.com/recaptcha" target="_blank" class="doc-link">
                            <i class="material-icons">open_in_new</i>
                            Google reCAPTCHA Documentation
                        </a>
                        <a href="https://developers.cloudflare.com/turnstile" target="_blank" class="doc-link">
                            <i class="material-icons">open_in_new</i>
                            Cloudflare Turnstile Documentation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="material-icons">save</i>
            <?php _e('save_settings'); ?>
        </button>
        <a href="<?php echo $adminUrl; ?>/antibot" class="btn btn-light">
            <i class="material-icons">cancel</i>
            <?php _e('cancel'); ?>
        </a>
    </div>
</form>

<style>
.form-actions {
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 1rem;
}

.card-title i {
    vertical-align: middle;
    margin-right: 0.5rem;
}

.test-buttons {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.test-buttons button {
    justify-content: flex-start;
}

.documentation-links {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.doc-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-color);
    text-decoration: none;
    padding: 0.5rem;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.doc-link:hover {
    background-color: rgba(var(--primary-color-rgb), 0.1);
    text-decoration: none;
}

.required {
    color: #dc3545;
}

.alert {
    padding: 0.75rem 1rem;
    margin: 1rem 0;
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.alert-info {
    color: #0c5460;
    background-color: #d1ecf1;
    border-color: #bee5eb;
}

.alert-warning {
    color: #856404;
    background-color: #fff3cd;
    border-color: #ffeaa7;
}

.alert i {
    vertical-align: middle;
    margin-right: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle settings sections based on checkbox state
    function toggleSettings(checkboxId, settingsClass) {
        const checkbox = document.getElementById(checkboxId);
        const settings = document.querySelector('.' + settingsClass);
        
        if (checkbox && settings) {
            checkbox.addEventListener('change', function() {
                settings.style.display = this.checked ? 'block' : 'none';
            });
        }
    }
    
    toggleSettings('antibot_recaptcha_v2_enabled', 'recaptcha-v2-settings');
    toggleSettings('antibot_recaptcha_v3_enabled', 'recaptcha-v3-settings');
    toggleSettings('antibot_turnstile_enabled', 'turnstile-settings');
    toggleSettings('antibot_honeypot_enabled', 'honeypot-settings');
    toggleSettings('antibot_rate_limit_enabled', 'rate-limit-settings');
});

function testProtection(type) {
    const resultsDiv = document.getElementById('test-results');
    resultsDiv.innerHTML = '<div class="loading">Testing...</div>';
    
    fetch('<?php echo $adminUrl; ?>/antibot/test', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'test_type=' + encodeURIComponent(type)
    })
    .then(response => response.json())
    .then(data => {
        const alertClass = data.success ? 'alert-success' : 'alert-danger';
        const icon = data.success ? 'check_circle' : 'error';
        
        resultsDiv.innerHTML = `
            <div class="alert ${alertClass}">
                <i class="material-icons">${icon}</i>
                ${data.message}
            </div>
        `;
    })
    .catch(error => {
        resultsDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="material-icons">error</i>
                Test failed: ${error.message}
            </div>
        `;
    });
}
</script>