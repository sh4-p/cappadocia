<?php
/**
 * Admin Settings View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('settings'); ?></h1>
</div>

<form action="<?php echo $adminUrl; ?>/settings/update" method="post" enctype="multipart/form-data" class="settings-form">
    <div class="row">
        <!-- Settings Tabs -->
        <div class="col-md-3">
            <div class="nav-tabs-container">
                <div class="settings-nav-tabs">
                    <a href="#settings-general" class="settings-nav-tab active" data-tab="general">
                        <i class="material-icons">settings</i>
                        <span><?php _e('general'); ?></span>
                    </a>
                    <a href="#settings-website" class="settings-nav-tab" data-tab="website">
                        <i class="material-icons">language</i>
                        <span><?php _e('website'); ?></span>
                    </a>
                    <a href="#settings-contact" class="settings-nav-tab" data-tab="contact">
                        <i class="material-icons">contact_mail</i>
                        <span><?php _e('contact'); ?></span>
                    </a>
                    <a href="#settings-social" class="settings-nav-tab" data-tab="social">
                        <i class="material-icons">share</i>
                        <span><?php _e('social_media'); ?></span>
                    </a>
                    <a href="#settings-payments" class="settings-nav-tab" data-tab="payments">
                        <i class="material-icons">payment</i>
                        <span><?php _e('payments'); ?></span>
                    </a>
                    <a href="#settings-email" class="settings-nav-tab" data-tab="email">
                        <i class="material-icons">email</i>
                        <span><?php _e('email'); ?></span>
                    </a>
                    <a href="#settings-advanced" class="settings-nav-tab" data-tab="advanced">
                        <i class="material-icons">code</i>
                        <span><?php _e('advanced'); ?></span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Settings Content -->
        <div class="col-md-9">
            <div class="settings-tab-content">
                <!-- General Settings -->
                <div id="settings-general" class="settings-tab-pane active">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('general_settings'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="site_title" class="form-label"><?php _e('site_title'); ?> <span class="required">*</span></label>
                                <input type="text" id="site_title" name="settings[site_title]" class="form-control" value="<?php echo htmlspecialchars($settings['site_title'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="site_description" class="form-label"><?php _e('site_description'); ?></label>
                                <textarea id="site_description" name="settings[site_description]" class="form-control" rows="3"><?php echo htmlspecialchars($settings['site_description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="default_language" class="form-label"><?php _e('default_language'); ?></label>
                                <select id="default_language" name="settings[default_language]" class="form-select">
                                    <?php foreach ($languages as $language): ?>
                                        <option value="<?php echo $language['code']; ?>" <?php echo ($settings['default_language'] ?? DEFAULT_LANGUAGE) == $language['code'] ? 'selected' : ''; ?>>
                                            <?php echo $language['name']; ?> (<?php echo $language['code']; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="timezone" class="form-label"><?php _e('timezone'); ?></label>
                                <select id="timezone" name="settings[timezone]" class="form-select">
                                    <?php
                                    $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
                                    $current_timezone = $settings['timezone'] ?? date_default_timezone_get();
                                    foreach ($timezones as $timezone):
                                    ?>
                                        <option value="<?php echo $timezone; ?>" <?php echo $current_timezone == $timezone ? 'selected' : ''; ?>>
                                            <?php echo $timezone; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="date_format" class="form-label"><?php _e('date_format'); ?></label>
                                <select id="date_format" name="settings[date_format]" class="form-select">
                                    <option value="F j, Y" <?php echo ($settings['date_format'] ?? 'F j, Y') == 'F j, Y' ? 'selected' : ''; ?>><?php echo date('F j, Y'); ?></option>
                                    <option value="Y-m-d" <?php echo ($settings['date_format'] ?? '') == 'Y-m-d' ? 'selected' : ''; ?>><?php echo date('Y-m-d'); ?></option>
                                    <option value="m/d/Y" <?php echo ($settings['date_format'] ?? '') == 'm/d/Y' ? 'selected' : ''; ?>><?php echo date('m/d/Y'); ?></option>
                                    <option value="d/m/Y" <?php echo ($settings['date_format'] ?? '') == 'd/m/Y' ? 'selected' : ''; ?>><?php echo date('d/m/Y'); ?></option>
                                    <option value="d.m.Y" <?php echo ($settings['date_format'] ?? '') == 'd.m.Y' ? 'selected' : ''; ?>><?php echo date('d.m.Y'); ?></option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="currency_code" class="form-label"><?php _e('currency_code'); ?></label>
                                <select id="currency_code" name="settings[currency_code]" class="form-select">
                                    <option value="USD" <?php echo ($settings['currency_code'] ?? 'USD') == 'USD' ? 'selected' : ''; ?>>USD - US Dollar</option>
                                    <option value="EUR" <?php echo ($settings['currency_code'] ?? '') == 'EUR' ? 'selected' : ''; ?>>EUR - Euro</option>
                                    <option value="GBP" <?php echo ($settings['currency_code'] ?? '') == 'GBP' ? 'selected' : ''; ?>>GBP - British Pound</option>
                                    <option value="TRY" <?php echo ($settings['currency_code'] ?? '') == 'TRY' ? 'selected' : ''; ?>>TRY - Turkish Lira</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="currency_symbol" class="form-label"><?php _e('currency_symbol'); ?></label>
                                <input type="text" id="currency_symbol" name="settings[currency_symbol]" class="form-control" value="<?php echo htmlspecialchars($settings['currency_symbol'] ?? '$'); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Website Settings -->
                <div id="settings-website" class="settings-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('website_settings'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="logo" class="form-label"><?php _e('logo'); ?></label>
                                        <div class="image-preview">
                                            <img src="<?php echo $imgUrl; ?>/logo.png" alt="<?php _e('logo'); ?>" id="logo_preview">
                                        </div>
                                        <div class="mt-3">
                                            <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                                            <small class="form-text"><?php _e('logo_help'); ?></small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="favicon" class="form-label"><?php _e('favicon'); ?></label>
                                        <div class="image-preview">
                                            <img src="<?php echo $imgUrl; ?>/favicon.ico" alt="<?php _e('favicon'); ?>" id="favicon_preview">
                                        </div>
                                        <div class="mt-3">
                                            <input type="file" id="favicon" name="favicon" class="form-control" accept="image/x-icon,image/png">
                                            <small class="form-text"><?php _e('favicon_help'); ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="google_analytics" class="form-label"><?php _e('google_analytics'); ?></label>
                                <input type="text" id="google_analytics" name="settings[google_analytics]" class="form-control" value="<?php echo htmlspecialchars($settings['google_analytics'] ?? ''); ?>" placeholder="UA-XXXXXXXXX-X">
                                <small class="form-text"><?php _e('google_analytics_help'); ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="google_maps_api_key" class="form-label"><?php _e('google_maps_api_key'); ?></label>
                                <input type="text" id="google_maps_api_key" name="settings[google_maps_api_key]" class="form-control" value="<?php echo htmlspecialchars($settings['google_maps_api_key'] ?? ''); ?>">
                                <small class="form-text"><?php _e('google_maps_api_key_help'); ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="header_scripts" class="form-label"><?php _e('header_scripts'); ?></label>
                                <textarea id="header_scripts" name="settings[header_scripts]" class="form-control" rows="5"><?php echo htmlspecialchars($settings['header_scripts'] ?? ''); ?></textarea>
                                <small class="form-text"><?php _e('header_scripts_help'); ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="footer_scripts" class="form-label"><?php _e('footer_scripts'); ?></label>
                                <textarea id="footer_scripts" name="settings[footer_scripts]" class="form-control" rows="5"><?php echo htmlspecialchars($settings['footer_scripts'] ?? ''); ?></textarea>
                                <small class="form-text"><?php _e('footer_scripts_help'); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Settings -->
                <div id="settings-contact" class="settings-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('contact_settings'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="contact_email" class="form-label"><?php _e('contact_email'); ?> <span class="required">*</span></label>
                                <input type="email" id="contact_email" name="settings[contact_email]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_email'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact_phone" class="form-label"><?php _e('contact_phone'); ?></label>
                                <input type="text" id="contact_phone" name="settings[contact_phone]" class="form-control" value="<?php echo htmlspecialchars($settings['contact_phone'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="address" class="form-label"><?php _e('address'); ?></label>
                                <textarea id="address" name="settings[address]" class="form-control" rows="3"><?php echo htmlspecialchars($settings['address'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="google_maps_lat" class="form-label"><?php _e('google_maps_lat'); ?></label>
                                <input type="text" id="google_maps_lat" name="settings[google_maps_lat]" class="form-control" value="<?php echo htmlspecialchars($settings['google_maps_lat'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="google_maps_lng" class="form-label"><?php _e('google_maps_lng'); ?></label>
                                <input type="text" id="google_maps_lng" name="settings[google_maps_lng]" class="form-control" value="<?php echo htmlspecialchars($settings['google_maps_lng'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="working_hours" class="form-label"><?php _e('working_hours'); ?></label>
                                <textarea id="working_hours" name="settings[working_hours]" class="form-control" rows="3"><?php echo htmlspecialchars($settings['working_hours'] ?? ''); ?></textarea>
                                <small class="form-text"><?php _e('working_hours_help'); ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Social Media Settings -->
                <div id="settings-social" class="settings-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('social_media_settings'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="facebook" class="form-label">Facebook</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-facebook-f"></i></span>
                                    <input type="url" id="facebook" name="settings[facebook]" class="form-control" value="<?php echo htmlspecialchars($settings['facebook'] ?? ''); ?>" placeholder="https://facebook.com/yourpage">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="instagram" class="form-label">Instagram</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-instagram"></i></span>
                                    <input type="url" id="instagram" name="settings[instagram]" class="form-control" value="<?php echo htmlspecialchars($settings['instagram'] ?? ''); ?>" placeholder="https://instagram.com/youraccount">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="twitter" class="form-label">Twitter</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-twitter"></i></span>
                                    <input type="url" id="twitter" name="settings[twitter]" class="form-control" value="<?php echo htmlspecialchars($settings['twitter'] ?? ''); ?>" placeholder="https://twitter.com/youraccount">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="youtube" class="form-label">YouTube</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-youtube"></i></span>
                                    <input type="url" id="youtube" name="settings[youtube]" class="form-control" value="<?php echo htmlspecialchars($settings['youtube'] ?? ''); ?>" placeholder="https://youtube.com/channel/yourchannel">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="linkedin" class="form-label">LinkedIn</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-linkedin-in"></i></span>
                                    <input type="url" id="linkedin" name="settings[linkedin]" class="form-control" value="<?php echo htmlspecialchars($settings['linkedin'] ?? ''); ?>" placeholder="https://linkedin.com/company/yourcompany">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="tripadvisor" class="form-label">TripAdvisor</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fab fa-tripadvisor"></i></span>
                                    <input type="url" id="tripadvisor" name="settings[tripadvisor]" class="form-control" value="<?php echo htmlspecialchars($settings['tripadvisor'] ?? ''); ?>" placeholder="https://tripadvisor.com/...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Settings -->
                <div id="settings-payments" class="settings-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('payment_settings'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="payment_card" name="settings[payment_card]" value="1" class="form-check-input" <?php echo ($settings['payment_card'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                    <label for="payment_card" class="form-check-label"><?php _e('enable_credit_card'); ?></label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="payment_paypal" name="settings[payment_paypal]" value="1" class="form-check-input" <?php echo ($settings['payment_paypal'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                    <label for="payment_paypal" class="form-check-label"><?php _e('enable_paypal'); ?></label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="payment_bank" name="settings[payment_bank]" value="1" class="form-check-input" <?php echo ($settings['payment_bank'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                    <label for="payment_bank" class="form-check-label"><?php _e('enable_bank_transfer'); ?></label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="payment_cash" name="settings[payment_cash]" value="1" class="form-check-input" <?php echo ($settings['payment_cash'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                    <label for="payment_cash" class="form-check-label"><?php _e('enable_cash'); ?></label>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <h4><?php _e('bank_account_details'); ?></h4>
                            <p class="text-muted"><?php _e('bank_account_details_help'); ?></p>
                            
                            <div class="form-group">
                                <label for="bank_name" class="form-label"><?php _e('bank_name'); ?></label>
                                <input type="text" id="bank_name" name="settings[bank_name]" class="form-control" value="<?php echo htmlspecialchars($settings['bank_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="account_name" class="form-label"><?php _e('account_name'); ?></label>
                                <input type="text" id="account_name" name="settings[account_name]" class="form-control" value="<?php echo htmlspecialchars($settings['account_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="account_number" class="form-label"><?php _e('account_number'); ?></label>
                                <input type="text" id="account_number" name="settings[account_number]" class="form-control" value="<?php echo htmlspecialchars($settings['account_number'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="iban" class="form-label"><?php _e('iban'); ?></label>
                                <input type="text" id="iban" name="settings[iban]" class="form-control" value="<?php echo htmlspecialchars($settings['iban'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="swift_code" class="form-label"><?php _e('swift_code'); ?></label>
                                <input type="text" id="swift_code" name="settings[swift_code]" class="form-control" value="<?php echo htmlspecialchars($settings['swift_code'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Email Settings -->
                <div id="settings-email" class="settings-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('email_settings'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="email_from_name" class="form-label"><?php _e('email_from_name'); ?></label>
                                <input type="text" id="email_from_name" name="settings[email_from_name]" class="form-control" value="<?php echo htmlspecialchars($settings['email_from_name'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="email_from_address" class="form-label"><?php _e('email_from_address'); ?></label>
                                <input type="email" id="email_from_address" name="settings[email_from_address]" class="form-control" value="<?php echo htmlspecialchars($settings['email_from_address'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_host" class="form-label"><?php _e('smtp_host'); ?></label>
                                <input type="text" id="smtp_host" name="settings[smtp_host]" class="form-control" value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_port" class="form-label"><?php _e('smtp_port'); ?></label>
                                <input type="text" id="smtp_port" name="settings[smtp_port]" class="form-control" value="<?php echo htmlspecialchars($settings['smtp_port'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_username" class="form-label"><?php _e('smtp_username'); ?></label>
                                <input type="text" id="smtp_username" name="settings[smtp_username]" class="form-control" value="<?php echo htmlspecialchars($settings['smtp_username'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_password" class="form-label"><?php _e('smtp_password'); ?></label>
                                <input type="password" id="smtp_password" name="settings[smtp_password]" class="form-control" value="<?php echo htmlspecialchars($settings['smtp_password'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="smtp_encryption" class="form-label"><?php _e('smtp_encryption'); ?></label>
                                <select id="smtp_encryption" name="settings[smtp_encryption]" class="form-select">
                                    <option value=""><?php _e('none'); ?></option>
                                    <option value="tls" <?php echo ($settings['smtp_encryption'] ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                                    <option value="ssl" <?php echo ($settings['smtp_encryption'] ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="email_notification_booking" name="settings[email_notification_booking]" value="1" class="form-check-input" <?php echo ($settings['email_notification_booking'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                    <label for="email_notification_booking" class="form-check-label"><?php _e('email_notification_booking'); ?></label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="email_notification_contact" name="settings[email_notification_contact]" value="1" class="form-check-input" <?php echo ($settings['email_notification_contact'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                    <label for="email_notification_contact" class="form-check-label"><?php _e('email_notification_contact'); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Advanced Settings -->
                <div id="settings-advanced" class="settings-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('advanced_settings'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="maintenance_mode" class="form-label"><?php _e('maintenance_mode'); ?></label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="maintenance_mode" name="settings[maintenance_mode]" value="1" class="form-check-input" <?php echo ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                    <label for="maintenance_mode" class="form-check-label"><?php _e('enable_maintenance_mode'); ?></label>
                                </div>
                                <small class="form-text"><?php _e('maintenance_mode_help'); ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="maintenance_message" class="form-label"><?php _e('maintenance_message'); ?></label>
                                <textarea id="maintenance_message" name="settings[maintenance_message]" class="form-control" rows="3"><?php echo htmlspecialchars($settings['maintenance_message'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="form-group">
                                <label for="recaptcha_site_key" class="form-label"><?php _e('recaptcha_site_key'); ?></label>
                                <input type="text" id="recaptcha_site_key" name="settings[recaptcha_site_key]" class="form-control" value="<?php echo htmlspecialchars($settings['recaptcha_site_key'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="recaptcha_secret_key" class="form-label"><?php _e('recaptcha_secret_key'); ?></label>
                                <input type="text" id="recaptcha_secret_key" name="settings[recaptcha_secret_key]" class="form-control" value="<?php echo htmlspecialchars($settings['recaptcha_secret_key'] ?? ''); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="cache_time" class="form-label"><?php _e('cache_time'); ?></label>
                                <div class="input-group">
                                    <input type="number" id="cache_time" name="settings[cache_time]" class="form-control" value="<?php echo htmlspecialchars($settings['cache_time'] ?? '3600'); ?>" min="0">
                                    <span class="input-group-text"><?php _e('seconds'); ?></span>
                                </div>
                                <small class="form-text"><?php _e('cache_time_help'); ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="debug_mode" class="form-label"><?php _e('debug_mode'); ?></label>
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="debug_mode" name="settings[debug_mode]" value="1" class="form-check-input" <?php echo ($settings['debug_mode'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                    <label for="debug_mode" class="form-check-label"><?php _e('enable_debug_mode'); ?></label>
                                </div>
                                <small class="form-text"><?php _e('debug_mode_help'); ?></small>
                            </div>
                        </div>
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
        <a href="<?php echo $adminUrl; ?>/dashboard" class="btn btn-light">
            <i class="material-icons">cancel</i>
            <?php _e('cancel'); ?>
        </a>
    </div>
</form>

<style>
    /* Settings tabs styles - using unique class names to avoid conflicts */
    .settings-nav-tabs-container {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .settings-nav-tabs {
        display: flex;
        flex-direction: column;
        border-bottom: none;
    }

    .settings-nav-tab {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px 24px;
        color: #555;
        border-left: 3px solid transparent;
        transition: all 0.3s;
        cursor: pointer;
        text-decoration: none;
    }

    .settings-nav-tab:hover,
    .settings-nav-tab.active {
        background-color: #f5f5f5;
        color: #2196f3;
        border-left-color: #2196f3;
    }

    .settings-nav-tab i {
        font-size: 20px;
    }

    .settings-tab-content {
        margin-bottom: 30px;
    }

    /* Hide all tab panes by default */
    .settings-tab-pane {
        display: none;
    }

    /* Show active tab pane */
    .settings-tab-pane.active {
        display: block;
    }

    .image-preview {
        width: 100%;
        height: 150px;
        background-color: #f5f5f5;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .image-preview img {
        max-width: 100%;
        max-height: 100%;
    }

    .form-switch {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .form-switch input {
        margin-right: 8px;
        width: 48px;
        height: 24px;
    }

    .form-actions {
        display: flex;
        gap: 16px;
        margin-top: 24px;
    }
</style>

<script>
// Settings tabs manager with unique function name
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing settings tabs');
    
    // Use a completely separate namespace for our tab management
    const SettingsTabs = {
        init: function() {
            this.tabLinks = document.querySelectorAll('.settings-nav-tab');
            this.tabPanes = document.querySelectorAll('.settings-tab-pane');
            this.bindEvents();
            this.activateFirstTab();
            this.initImagePreviews();
        },
        
        bindEvents: function() {
            // Use arrow function to keep 'this' context
            this.tabLinks.forEach(tabLink => {
                tabLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    this.activateTab(tabLink);
                });
            });
        },
        
        activateTab: function(tabLink) {
            console.log('Activating tab:', tabLink.getAttribute('data-tab'));
            
            // Remove active class from all tabs and panes
            this.tabLinks.forEach(link => link.classList.remove('active'));
            this.tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Add active class to selected tab and its corresponding pane
            tabLink.classList.add('active');
            const tabId = 'settings-' + tabLink.getAttribute('data-tab');
            const tabPane = document.getElementById(tabId);
            
            if (tabPane) {
                tabPane.classList.add('active');
                console.log('Tab pane activated:', tabId);
            } else {
                console.error('Tab pane not found:', tabId);
            }
        },
        
        activateFirstTab: function() {
            if (this.tabLinks.length > 0) {
                console.log('Auto-activating first tab');
                this.activateTab(this.tabLinks[0]);
            }
        },
        
        initImagePreviews: function() {
            // Logo Preview
            const logoInput = document.getElementById('logo');
            const logoPreview = document.getElementById('logo_preview');
            
            if (logoInput && logoPreview) {
                logoInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            logoPreview.src = e.target.result;
                        }
                        
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
            
            // Favicon Preview
            const faviconInput = document.getElementById('favicon');
            const faviconPreview = document.getElementById('favicon_preview');
            
            if (faviconInput && faviconPreview) {
                faviconInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            faviconPreview.src = e.target.result;
                        }
                        
                        reader.readAsDataURL(this.files[0]);
                    }
                });
            }
        }
    };
    
    // Initialize settings tabs
    SettingsTabs.init();
});
</script>