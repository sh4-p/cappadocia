<?php
/**
 * Admin Settings View - Complete Updated Version with Dynamic Payment Configurations
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
                    <a href="#settings-homepage" class="settings-nav-tab" data-tab="homepage">
                        <i class="material-icons">image</i>
                        <span><?php _e('homepage_images'); ?></span>
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

                <!-- Homepage Images Settings -->
                <div id="settings-homepage" class="settings-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('homepage_images'); ?></h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Hero Background Image -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hero_bg" class="form-label"><?php _e('hero_bg_image'); ?></label>
                                        <div class="image-preview">
                                            <?php 
                                            $heroBgFile = $settings['hero_bg'] ?? 'hero-bg.jpg';
                                            $heroBgPath = $imgUrl . '/' . $heroBgFile;
                                            $heroBgSrc = file_exists(BASE_PATH . '/public/img/' . $heroBgFile) ? $heroBgPath : $imgUrl . '/hero-bg.jpg';
                                            ?>
                                            <img src="<?php echo $heroBgSrc; ?>" alt="<?php _e('hero_bg_image'); ?>" id="hero_bg_preview" onerror="this.src='<?php echo $imgUrl; ?>/hero-bg.jpg'">
                                        </div>
                                        <div class="mt-3">
                                            <input type="file" id="hero_bg" name="homepage_images[hero_bg]" class="form-control" accept="image/*">
                                            <small class="form-text"><?php _e('recommended_size'); ?>: 1920x1080px</small>
                                        </div>
                                        <?php if (!empty($settings['hero_bg']) && $settings['hero_bg'] !== 'hero-bg.jpg'): ?>
                                            <div class="mt-2">
                                                <small class="text-muted"><?php _e('current_file'); ?>: <?php echo htmlspecialchars($settings['hero_bg']); ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- About Section Background -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="about_bg" class="form-label"><?php _e('about_bg_image'); ?></label>
                                        <div class="image-preview">
                                            <?php 
                                            $aboutBgFile = $settings['about_bg'] ?? 'about-bg.jpg';
                                            $aboutBgPath = $imgUrl . '/' . $aboutBgFile;
                                            $aboutBgSrc = file_exists(BASE_PATH . '/public/img/' . $aboutBgFile) ? $aboutBgPath : $imgUrl . '/about-bg.jpg';
                                            ?>
                                            <img src="<?php echo $aboutBgSrc; ?>" alt="<?php _e('about_bg_image'); ?>" id="about_bg_preview" onerror="this.src='<?php echo $imgUrl; ?>/about-bg.jpg'">
                                        </div>
                                        <div class="mt-3">
                                            <input type="file" id="about_bg" name="homepage_images[about_bg]" class="form-control" accept="image/*">
                                            <small class="form-text"><?php _e('recommended_size'); ?>: 1920x1200px</small>
                                        </div>
                                        <?php if (!empty($settings['about_bg']) && $settings['about_bg'] !== 'about-bg.jpg'): ?>
                                            <div class="mt-2">
                                                <small class="text-muted"><?php _e('current_file'); ?>: <?php echo htmlspecialchars($settings['about_bg']); ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
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
                                            <?php 
                                            $logoFile = $settings['logo'] ?? 'logo.png';
                                            $logoPath = $imgUrl . '/' . $logoFile;
                                            // Check if file exists, if not use default
                                            $logoSrc = file_exists(BASE_PATH . '/public/img/' . $logoFile) ? $logoPath : $imgUrl . '/logo.png';
                                            ?>
                                            <img src="<?php echo $logoSrc; ?>" alt="<?php _e('logo'); ?>" id="logo_preview" onerror="this.src='<?php echo $imgUrl; ?>/logo.png'">
                                        </div>
                                        <div class="mt-3">
                                            <input type="file" id="logo" name="logo" class="form-control" accept="image/*">
                                            <small class="form-text"><?php _e('logo_help'); ?></small>
                                        </div>
                                        <?php if (!empty($settings['logo']) && $settings['logo'] !== 'logo.png'): ?>
                                            <div class="mt-2">
                                                <small class="text-muted"><?php _e('current_file'); ?>: <?php echo htmlspecialchars($settings['logo']); ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="favicon" class="form-label"><?php _e('favicon'); ?></label>
                                        <div class="image-preview">
                                            <?php 
                                            $faviconFile = $settings['favicon'] ?? 'favicon.ico';
                                            $faviconPath = $imgUrl . '/' . $faviconFile;
                                            // Check if file exists, if not use default
                                            $faviconSrc = file_exists(BASE_PATH . '/public/img/' . $faviconFile) ? $faviconPath : $imgUrl . '/favicon.ico';
                                            ?>
                                            <img src="<?php echo $faviconSrc; ?>" alt="<?php _e('favicon'); ?>" id="favicon_preview" onerror="this.src='<?php echo $imgUrl; ?>/favicon.ico'">
                                        </div>
                                        <div class="mt-3">
                                            <input type="file" id="favicon" name="favicon" class="form-control" accept="image/x-icon,image/png">
                                            <small class="form-text"><?php _e('favicon_help'); ?></small>
                                        </div>
                                        <?php if (!empty($settings['favicon']) && $settings['favicon'] !== 'favicon.ico'): ?>
                                            <div class="mt-2">
                                                <small class="text-muted"><?php _e('current_file'); ?>: <?php echo htmlspecialchars($settings['favicon']); ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="google_analytics" class="form-label"><?php _e('google_analytics'); ?></label>
                                <input type="text" id="google_analytics" name="settings[google_analytics]" class="form-control" value="<?php echo htmlspecialchars($settings['google_analytics'] ?? ''); ?>" placeholder="UA-XXXXXXXXX-X">
                                <small class="form-text"><?php _e('google_analytics_help'); ?></small>
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
                        </div>
                    </div>
                </div>
                
                <!-- Payment Settings - ENHANCED WITH DYNAMIC CONFIGURATIONS -->
                <div id="settings-payments" class="settings-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('payment_settings'); ?></h3>
                            <p class="card-description"><?php _e('payment_settings_description'); ?></p>
                        </div>
                        <div class="card-body">
                            <!-- Payment Methods Section -->
                            <div class="settings-section">
                                <h4 class="section-title">
                                    <i class="material-icons">payment</i>
                                    <?php _e('payment_methods'); ?>
                                </h4>
                                <p class="section-description"><?php _e('payment_methods_description'); ?></p>
                                
                                <div class="payment-methods-grid">
                                    <!-- Credit Card -->
                                    <div class="payment-method-card">
                                        <div class="payment-method-header">
                                            <div class="payment-method-icon">
                                                <i class="material-icons">credit_card</i>
                                            </div>
                                            <div class="payment-method-info">
                                                <h5><?php _e('credit_card'); ?></h5>
                                                <p><?php _e('credit_card_admin_description'); ?></p>
                                            </div>
                                            <div class="payment-method-toggle">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="settings[payment_card]" value="0">
                                                    <input type="checkbox" id="payment_card" name="settings[payment_card]" value="1" class="form-check-input" <?php echo ($settings['payment_card'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                    <label for="payment_card" class="form-check-label"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- PayPal -->
                                    <div class="payment-method-card">
                                        <div class="payment-method-header">
                                            <div class="payment-method-icon">
                                                <i class="material-icons">account_balance_wallet</i>
                                            </div>
                                            <div class="payment-method-info">
                                                <h5><?php _e('paypal'); ?></h5>
                                                <p><?php _e('paypal_admin_description'); ?></p>
                                            </div>
                                            <div class="payment-method-toggle">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="settings[payment_paypal]" value="0">
                                                    <input type="checkbox" id="payment_paypal" name="settings[payment_paypal]" value="1" class="form-check-input" <?php echo ($settings['payment_paypal'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                                    <label for="payment_paypal" class="form-check-label"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Bank Transfer -->
                                    <div class="payment-method-card">
                                        <div class="payment-method-header">
                                            <div class="payment-method-icon">
                                                <i class="material-icons">account_balance</i>
                                            </div>
                                            <div class="payment-method-info">
                                                <h5><?php _e('bank_transfer'); ?></h5>
                                                <p><?php _e('bank_transfer_admin_description'); ?></p>
                                            </div>
                                            <div class="payment-method-toggle">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="settings[payment_bank]" value="0">
                                                    <input type="checkbox" id="payment_bank" name="settings[payment_bank]" value="1" class="form-check-input" <?php echo ($settings['payment_bank'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                                    <label for="payment_bank" class="form-check-label"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Cash Payment -->
                                    <div class="payment-method-card">
                                        <div class="payment-method-header">
                                            <div class="payment-method-icon">
                                                <i class="material-icons">money</i>
                                            </div>
                                            <div class="payment-method-info">
                                                <h5><?php _e('cash_payment'); ?></h5>
                                                <p><?php _e('cash_payment_admin_description'); ?></p>
                                            </div>
                                            <div class="payment-method-toggle">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="settings[payment_cash]" value="0">
                                                    <input type="checkbox" id="payment_cash" name="settings[payment_cash]" value="1" class="form-check-input" <?php echo ($settings['payment_cash'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                                    <label for="payment_cash" class="form-check-label"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="settings-divider">
                            
                            <!-- Payment Method Configurations -->
                            <div class="settings-section">
                                <h4 class="section-title">
                                    <i class="material-icons">tune</i>
                                    <?php _e('payment_configurations'); ?>
                                </h4>
                                <p class="section-description"><?php _e('payment_configurations_description'); ?></p>
                                
                                <!-- Credit Card Configuration -->
                                <div id="card-config" class="payment-config-section" style="display: none;">
                                    <div class="config-header">
                                        <h5>
                                            <i class="material-icons">credit_card</i>
                                            <?php _e('credit_card_configuration'); ?>
                                        </h5>
                                        <p><?php _e('stripe_settings_description'); ?></p>
                                    </div>
                                    <div class="config-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="stripe_public_key" class="form-label"><?php _e('stripe_public_key'); ?></label>
                                                <input type="text" id="stripe_public_key" name="settings[stripe_public_key]" class="form-control" value="<?php echo htmlspecialchars($settings['stripe_public_key'] ?? ''); ?>" placeholder="pk_...">
                                                <small class="form-text"><?php _e('stripe_public_key_help'); ?></small>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="stripe_secret_key" class="form-label"><?php _e('stripe_secret_key'); ?></label>
                                                <input type="password" id="stripe_secret_key" name="settings[stripe_secret_key]" class="form-control" value="<?php echo htmlspecialchars($settings['stripe_secret_key'] ?? ''); ?>" placeholder="sk_...">
                                                <small class="form-text"><?php _e('stripe_secret_key_help'); ?></small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="hidden" name="settings[stripe_test_mode]" value="0">
                                                <input type="checkbox" id="stripe_test_mode" name="settings[stripe_test_mode]" value="1" class="form-check-input" <?php echo ($settings['stripe_test_mode'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                <label for="stripe_test_mode" class="form-check-label"><?php _e('stripe_test_mode'); ?></label>
                                            </div>
                                            <small class="form-text"><?php _e('stripe_test_mode_help'); ?></small>
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <i class="material-icons">info</i>
                                            <div>
                                                <strong><?php _e('stripe_setup_guide'); ?></strong>
                                                <p><?php _e('stripe_setup_instructions'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- PayPal Configuration -->
                                <div id="paypal-config" class="payment-config-section" style="display: none;">
                                    <div class="config-header">
                                        <h5>
                                            <i class="material-icons">account_balance_wallet</i>
                                            <?php _e('paypal_configuration'); ?>
                                        </h5>
                                        <p><?php _e('paypal_settings_description'); ?></p>
                                    </div>
                                    <div class="config-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="paypal_client_id" class="form-label"><?php _e('paypal_client_id'); ?></label>
                                                <input type="text" id="paypal_client_id" name="settings[paypal_client_id]" class="form-control" value="<?php echo htmlspecialchars($settings['paypal_client_id'] ?? ''); ?>" placeholder="AXXXXxxxxx...">
                                                <small class="form-text"><?php _e('paypal_client_id_help'); ?></small>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="paypal_client_secret" class="form-label"><?php _e('paypal_client_secret'); ?></label>
                                                <input type="password" id="paypal_client_secret" name="settings[paypal_client_secret]" class="form-control" value="<?php echo htmlspecialchars($settings['paypal_client_secret'] ?? ''); ?>" placeholder="EXXXXxxxxx...">
                                                <small class="form-text"><?php _e('paypal_client_secret_help'); ?></small>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <div class="form-check">
                                                <input type="hidden" name="settings[paypal_sandbox]" value="0">
                                                <input type="checkbox" id="paypal_sandbox" name="settings[paypal_sandbox]" value="1" class="form-check-input" <?php echo ($settings['paypal_sandbox'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                <label for="paypal_sandbox" class="form-check-label"><?php _e('paypal_sandbox_mode'); ?></label>
                                            </div>
                                            <small class="form-text"><?php _e('paypal_sandbox_help'); ?></small>
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <i class="material-icons">info</i>
                                            <div>
                                                <strong><?php _e('paypal_setup_guide'); ?></strong>
                                                <p><?php _e('paypal_setup_instructions'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Bank Transfer Configuration -->
                                <div id="bank-config" class="payment-config-section" style="display: none;">
                                    <div class="config-header">
                                        <h5>
                                            <i class="material-icons">account_balance</i>
                                            <?php _e('bank_transfer_configuration'); ?>
                                        </h5>
                                        <p><?php _e('bank_account_details_help'); ?></p>
                                    </div>
                                    <div class="config-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="bank_name" class="form-label"><?php _e('bank_name'); ?></label>
                                                <input type="text" id="bank_name" name="settings[bank_name]" class="form-control" value="<?php echo htmlspecialchars($settings['bank_name'] ?? ''); ?>" placeholder="<?php _e('enter_bank_name'); ?>">
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="account_name" class="form-label"><?php _e('account_name'); ?></label>
                                                <input type="text" id="account_name" name="settings[account_name]" class="form-control" value="<?php echo htmlspecialchars($settings['account_name'] ?? ''); ?>" placeholder="<?php _e('enter_account_holder_name'); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="account_number" class="form-label"><?php _e('account_number'); ?></label>
                                                <input type="text" id="account_number" name="settings[account_number]" class="form-control" value="<?php echo htmlspecialchars($settings['account_number'] ?? ''); ?>" placeholder="<?php _e('enter_account_number'); ?>">
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="swift_code" class="form-label"><?php _e('swift_code'); ?></label>
                                                <input type="text" id="swift_code" name="settings[swift_code]" class="form-control" value="<?php echo htmlspecialchars($settings['swift_code'] ?? ''); ?>" placeholder="<?php _e('enter_swift_code'); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="iban" class="form-label"><?php _e('iban'); ?></label>
                                            <input type="text" id="iban" name="settings[iban]" class="form-control" value="<?php echo htmlspecialchars($settings['iban'] ?? ''); ?>" placeholder="<?php _e('enter_iban'); ?>">
                                            <small class="form-text"><?php _e('iban_help'); ?></small>
                                        </div>
                                        
                                        <div class="alert alert-warning">
                                            <i class="material-icons">warning</i>
                                            <div>
                                                <strong><?php _e('bank_transfer_note'); ?></strong>
                                                <p><?php _e('bank_transfer_instructions'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Cash Payment Configuration -->
                                <div id="cash-config" class="payment-config-section" style="display: none;">
                                    <div class="config-header">
                                        <h5>
                                            <i class="material-icons">money</i>
                                            <?php _e('cash_payment_configuration'); ?>
                                        </h5>
                                        <p><?php _e('cash_payment_settings_description'); ?></p>
                                    </div>
                                    <div class="config-body">
                                        <div class="form-group">
                                            <label for="cash_instructions" class="form-label"><?php _e('cash_payment_instructions'); ?></label>
                                            <textarea id="cash_instructions" name="settings[cash_instructions]" class="form-control" rows="4" placeholder="<?php _e('cash_instructions_placeholder'); ?>"><?php echo htmlspecialchars($settings['cash_instructions'] ?? ''); ?></textarea>
                                            <small class="form-text"><?php _e('cash_instructions_help'); ?></small>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="meeting_point" class="form-label"><?php _e('meeting_point'); ?></label>
                                            <input type="text" id="meeting_point" name="settings[meeting_point]" class="form-control" value="<?php echo htmlspecialchars($settings['meeting_point'] ?? ''); ?>" placeholder="<?php _e('meeting_point_placeholder'); ?>">
                                            <small class="form-text"><?php _e('meeting_point_help'); ?></small>
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <i class="material-icons">info</i>
                                            <div>
                                                <strong><?php _e('cash_payment_note'); ?></strong>
                                                <p><?php _e('cash_payment_description'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Email Settings -->
                <div id="settings-email" class="settings-tab-pane">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><?php _e('email_settings'); ?></h3>
                            <p class="card-description"><?php _e('email_settings_description'); ?></p>
                        </div>
                        <div class="card-body">
                            <!-- General Email Settings -->
                            <div class="settings-section">
                                <h4 class="section-title">
                                    <i class="material-icons">settings</i>
                                    <?php _e('general_email_settings'); ?>
                                </h4>
                                <p class="section-description"><?php _e('general_email_settings_description'); ?></p>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="email_from_name" class="form-label"><?php _e('email_from_name'); ?> <span class="required">*</span></label>
                                        <input type="text" id="email_from_name" name="settings[email_from_name]" class="form-control" value="<?php echo htmlspecialchars($settings['email_from_name'] ?? ''); ?>" required>
                                        <small class="form-text"><?php _e('email_from_name_help'); ?></small>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label for="email_from_address" class="form-label"><?php _e('email_from_address'); ?> <span class="required">*</span></label>
                                        <input type="email" id="email_from_address" name="settings[email_from_address]" class="form-control" value="<?php echo htmlspecialchars($settings['email_from_address'] ?? ''); ?>" required>
                                        <small class="form-text"><?php _e('email_from_address_help'); ?></small>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="email_reply_to" class="form-label"><?php _e('email_reply_to'); ?></label>
                                        <input type="email" id="email_reply_to" name="settings[email_reply_to]" class="form-control" value="<?php echo htmlspecialchars($settings['email_reply_to'] ?? ''); ?>">
                                        <small class="form-text"><?php _e('email_reply_to_help'); ?></small>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label for="email_admin" class="form-label"><?php _e('email_admin'); ?></label>
                                        <input type="email" id="email_admin" name="settings[email_admin]" class="form-control" value="<?php echo htmlspecialchars($settings['email_admin'] ?? ''); ?>">
                                        <small class="form-text"><?php _e('email_admin_help'); ?></small>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="settings-divider">
                            
                            <!-- SMTP Configuration -->
                            <div class="settings-section">
                                <h4 class="section-title">
                                    <i class="material-icons">cloud</i>
                                    <?php _e('smtp_configuration'); ?>
                                </h4>
                                <p class="section-description"><?php _e('smtp_configuration_description'); ?></p>
                                
                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="settings[smtp_enabled]" value="0">
                                        <input type="checkbox" id="smtp_enabled" name="settings[smtp_enabled]" value="1" class="form-check-input smtp-toggle" <?php echo ($settings['smtp_enabled'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                        <label for="smtp_enabled" class="form-check-label"><?php _e('enable_smtp'); ?></label>
                                    </div>
                                    <small class="form-text"><?php _e('smtp_enabled_help'); ?></small>
                                </div>
                                
                                <div id="smtp-config" class="smtp-config-section" style="display: none;">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="smtp_host" class="form-label"><?php _e('smtp_host'); ?> <span class="required">*</span></label>
                                            <input type="text" id="smtp_host" name="settings[smtp_host]" class="form-control" value="<?php echo htmlspecialchars($settings['smtp_host'] ?? ''); ?>" placeholder="smtp.gmail.com">
                                            <small class="form-text"><?php _e('smtp_host_help'); ?></small>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="smtp_port" class="form-label"><?php _e('smtp_port'); ?> <span class="required">*</span></label>
                                            <select id="smtp_port" name="settings[smtp_port]" class="form-select">
                                                <option value="25" <?php echo ($settings['smtp_port'] ?? '587') == '25' ? 'selected' : ''; ?>>25 (No encryption)</option>
                                                <option value="587" <?php echo ($settings['smtp_port'] ?? '587') == '587' ? 'selected' : ''; ?>>587 (TLS/STARTTLS)</option>
                                                <option value="465" <?php echo ($settings['smtp_port'] ?? '587') == '465' ? 'selected' : ''; ?>>465 (SSL)</option>
                                                <option value="2525" <?php echo ($settings['smtp_port'] ?? '587') == '2525' ? 'selected' : ''; ?>>2525 (Alternative)</option>
                                            </select>
                                            <small class="form-text"><?php _e('smtp_port_help'); ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="smtp_security" class="form-label"><?php _e('smtp_security'); ?></label>
                                            <select id="smtp_security" name="settings[smtp_security]" class="form-select">
                                                <option value="none" <?php echo ($settings['smtp_security'] ?? 'tls') == 'none' ? 'selected' : ''; ?>><?php _e('none'); ?></option>
                                                <option value="ssl" <?php echo ($settings['smtp_security'] ?? 'tls') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                                <option value="tls" <?php echo ($settings['smtp_security'] ?? 'tls') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                                            </select>
                                            <small class="form-text"><?php _e('smtp_security_help'); ?></small>
                                        </div>
                                        
                                        <div class="form-group col-md-6">
                                            <label for="smtp_timeout" class="form-label"><?php _e('smtp_timeout'); ?></label>
                                            <input type="number" id="smtp_timeout" name="settings[smtp_timeout]" class="form-control" value="<?php echo htmlspecialchars($settings['smtp_timeout'] ?? '30'); ?>" min="10" max="300">
                                            <small class="form-text"><?php _e('smtp_timeout_help'); ?></small>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="hidden" name="settings[smtp_auth]" value="0">
                                            <input type="checkbox" id="smtp_auth" name="settings[smtp_auth]" value="1" class="form-check-input" <?php echo ($settings['smtp_auth'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                            <label for="smtp_auth" class="form-check-label"><?php _e('smtp_authentication'); ?></label>
                                        </div>
                                        <small class="form-text"><?php _e('smtp_auth_help'); ?></small>
                                    </div>
                                    
                                    <div id="smtp-auth-fields">
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label for="smtp_username" class="form-label"><?php _e('smtp_username'); ?></label>
                                                <input type="text" id="smtp_username" name="settings[smtp_username]" class="form-control" value="<?php echo htmlspecialchars($settings['smtp_username'] ?? ''); ?>" autocomplete="username">
                                                <small class="form-text"><?php _e('smtp_username_help'); ?></small>
                                            </div>
                                            
                                            <div class="form-group col-md-6">
                                                <label for="smtp_password" class="form-label"><?php _e('smtp_password'); ?></label>
                                                <div class="input-group">
                                                    <input type="password" id="smtp_password" name="settings[smtp_password]" class="form-control" value="<?php echo htmlspecialchars($settings['smtp_password'] ?? ''); ?>" autocomplete="current-password">
                                                    <button type="button" class="btn btn-outline-secondary toggle-password" data-target="smtp_password">
                                                        <i class="material-icons">visibility</i>
                                                    </button>
                                                </div>
                                                <small class="form-text"><?php _e('smtp_password_help'); ?></small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="smtp-test-section">
                                        <div class="form-group">
                                            <label for="test_email" class="form-label"><?php _e('test_email_address'); ?></label>
                                            <div class="input-group">
                                                <input type="email" id="test_email" class="form-control" placeholder="<?php _e('enter_test_email'); ?>">
                                                <button type="button" class="btn btn-info test-smtp-btn">
                                                    <i class="material-icons">send</i>
                                                    <?php _e('send_test_email'); ?>
                                                </button>
                                            </div>
                                            <small class="form-text"><?php _e('test_email_help'); ?></small>
                                        </div>
                                        <div id="smtp-test-result" class="smtp-test-result"></div>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <i class="material-icons">info</i>
                                        <div>
                                            <strong><?php _e('smtp_common_providers'); ?></strong>
                                            <ul class="smtp-providers-list">
                                                <li><strong>Gmail:</strong> smtp.gmail.com, Port: 587, TLS</li>
                                                <li><strong>Outlook/Hotmail:</strong> smtp.live.com, Port: 587, TLS</li>
                                                <li><strong>Yahoo:</strong> smtp.mail.yahoo.com, Port: 587, TLS</li>
                                                <li><strong>SendGrid:</strong> smtp.sendgrid.net, Port: 587, TLS</li>
                                                <li><strong>Mailgun:</strong> smtp.mailgun.org, Port: 587, TLS</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="settings-divider">
                            
                            <!-- Email Notification Settings -->
                            <div class="settings-section">
                                <h4 class="section-title">
                                    <i class="material-icons">notifications</i>
                                    <?php _e('email_notifications'); ?>
                                </h4>
                                <p class="section-description"><?php _e('email_notifications_description'); ?></p>
                                
                                <div class="email-notifications-grid">
                                    <div class="notification-card">
                                        <div class="notification-header">
                                            <div class="notification-icon">
                                                <i class="material-icons">event_available</i>
                                            </div>
                                            <div class="notification-info">
                                                <h5><?php _e('booking_notifications'); ?></h5>
                                                <p><?php _e('booking_notifications_description'); ?></p>
                                            </div>
                                            <div class="notification-toggle">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="settings[email_notification_booking]" value="0">
                                                    <input type="checkbox" id="email_notification_booking" name="settings[email_notification_booking]" value="1" class="form-check-input" <?php echo ($settings['email_notification_booking'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                    <label for="email_notification_booking" class="form-check-label"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="notification-card">
                                        <div class="notification-header">
                                            <div class="notification-icon">
                                                <i class="material-icons">contact_mail</i>
                                            </div>
                                            <div class="notification-info">
                                                <h5><?php _e('contact_notifications'); ?></h5>
                                                <p><?php _e('contact_notifications_description'); ?></p>
                                            </div>
                                            <div class="notification-toggle">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="settings[email_notification_contact]" value="0">
                                                    <input type="checkbox" id="email_notification_contact" name="settings[email_notification_contact]" value="1" class="form-check-input" <?php echo ($settings['email_notification_contact'] ?? '1') == '1' ? 'checked' : ''; ?>>
                                                    <label for="email_notification_contact" class="form-check-label"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="notification-card">
                                        <div class="notification-header">
                                            <div class="notification-icon">
                                                <i class="material-icons">person_add</i>
                                            </div>
                                            <div class="notification-info">
                                                <h5><?php _e('user_registration_notifications'); ?></h5>
                                                <p><?php _e('user_registration_notifications_description'); ?></p>
                                            </div>
                                            <div class="notification-toggle">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="settings[email_notification_registration]" value="0">
                                                    <input type="checkbox" id="email_notification_registration" name="settings[email_notification_registration]" value="1" class="form-check-input" <?php echo ($settings['email_notification_registration'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                                    <label for="email_notification_registration" class="form-check-label"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="notification-card">
                                        <div class="notification-header">
                                            <div class="notification-icon">
                                                <i class="material-icons">campaign</i>
                                            </div>
                                            <div class="notification-info">
                                                <h5><?php _e('newsletter_notifications'); ?></h5>
                                                <p><?php _e('newsletter_notifications_description'); ?></p>
                                            </div>
                                            <div class="notification-toggle">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="settings[email_notification_newsletter]" value="0">
                                                    <input type="checkbox" id="email_notification_newsletter" name="settings[email_notification_newsletter]" value="1" class="form-check-input" <?php echo ($settings['email_notification_newsletter'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                                    <label for="email_notification_newsletter" class="form-check-label"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <hr class="settings-divider">
                            
                            <!-- Email Templates -->
                            <div class="settings-section">
                                <h4 class="section-title">
                                    <i class="material-icons">description</i>
                                    <?php _e('email_templates'); ?>
                                </h4>
                                <p class="section-description"><?php _e('email_templates_description'); ?></p>
                                
                                <div class="form-group">
                                    <label for="email_signature" class="form-label"><?php _e('email_signature'); ?></label>
                                    <textarea id="email_signature" name="settings[email_signature]" class="form-control" rows="4" placeholder="<?php _e('email_signature_placeholder'); ?>"><?php echo htmlspecialchars($settings['email_signature'] ?? ''); ?></textarea>
                                    <small class="form-text"><?php _e('email_signature_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email_header" class="form-label"><?php _e('email_header_html'); ?></label>
                                    <textarea id="email_header" name="settings[email_header]" class="form-control" rows="3" placeholder="<?php _e('email_header_placeholder'); ?>"><?php echo htmlspecialchars($settings['email_header'] ?? ''); ?></textarea>
                                    <small class="form-text"><?php _e('email_header_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email_footer" class="form-label"><?php _e('email_footer_html'); ?></label>
                                    <textarea id="email_footer" name="settings[email_footer]" class="form-control" rows="3" placeholder="<?php _e('email_footer_placeholder'); ?>"><?php echo htmlspecialchars($settings['email_footer'] ?? ''); ?></textarea>
                                    <small class="form-text"><?php _e('email_footer_help'); ?></small>
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
                                    <input type="hidden" name="settings[maintenance_mode]" value="0">
                                    <input type="checkbox" id="maintenance_mode" name="settings[maintenance_mode]" value="1" class="form-check-input" <?php echo ($settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : ''; ?>>
                                    <label for="maintenance_mode" class="form-check-label"><?php _e('enable_maintenance_mode'); ?></label>
                                </div>
                                <small class="form-text"><?php _e('maintenance_mode_help'); ?></small>
                            </div>
                            
                            <div class="form-group">
                                <label for="debug_mode" class="form-label"><?php _e('debug_mode'); ?></label>
                                <div class="form-check form-switch">
                                    <input type="hidden" name="settings[debug_mode]" value="0">
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

    /* SMTP Configuration için ek stiller */
    .smtp-config-section {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .smtp-config-section:not(.active) {
        max-height: 0;
        padding-top: 0;
        padding-bottom: 0;
        margin-top: 0;
        opacity: 0;
    }

    .smtp-config-section.active {
        max-height: none;
        opacity: 1;
    }

    #smtp-auth-fields {
        transition: all 0.3s ease;
        overflow: hidden;
    }

    #smtp-auth-fields:not(.show) {
        max-height: 0;
        padding-top: 0;
        padding-bottom: 0;
        margin-top: 0;
        opacity: 0;
    }

    #smtp-auth-fields.show {
        max-height: none;
        opacity: 1;
    }

    /* Form elemanları için daha iyi spacing */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        margin-bottom: 0.5rem;
        display: block;
        font-weight: 500;
        line-height: 1.4;
    }

    .form-text {
        margin-top: 0.25rem;
        margin-bottom: 0;
        font-size: 0.875rem;
        line-height: 1.4;
    }

    /* Text overlap prevention */
    .card-description,
    .section-description {
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .alert {
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .form-row .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .settings-nav-tabs {
            flex-direction: row;
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .settings-nav-tab {
            flex-shrink: 0;
            min-width: 150px;
        }
    }
    /* Payment Settings Specific Styles */
    .card-description {
        margin: 0;
        color: var(--gray-600);
        font-size: var(--font-size-sm);
        margin-top: 0.5rem;
    }

    .settings-section {
        margin-bottom: 3rem;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        font-size: 1.125rem;
        color: var(--dark-color);
    }

    .section-title i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .section-description {
        margin-bottom: 1.5rem;
        color: var(--gray-600);
        font-size: var(--font-size-sm);
        line-height: 1.5;
    }

    .settings-divider {
        border: none;
        height: 1px;
        background-color: var(--gray-300);
        margin: 2rem 0;
    }

    /* Payment Methods Grid */
    .payment-methods-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .payment-method-card {
        background-color: var(--white-color);
        border: 2px solid var(--gray-200);
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
        transition: all var(--transition-fast);
        position: relative;
    }

    .payment-method-card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-md);
    }

    .payment-method-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .payment-method-icon {
        width: 50px;
        height: 50px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .payment-method-icon i {
        font-size: 1.5rem;
    }

    .payment-method-info {
        flex: 1;
    }

    .payment-method-info h5 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        color: var(--dark-color);
    }

    .payment-method-info p {
        margin: 0;
        font-size: var(--font-size-sm);
        color: var(--gray-600);
        line-height: 1.4;
    }

    .payment-method-toggle {
        flex-shrink: 0;
    }

    /* Enhanced Form Switch */
    .form-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .form-switch input[type="checkbox"] {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .form-switch .form-check-label {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }

    .form-switch .form-check-label:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    .form-switch input:checked + .form-check-label {
        background-color: var(--primary-color);
    }

    .form-switch input:checked + .form-check-label:before {
        transform: translateX(26px);
    }

    /* Payment Configuration Sections */
    .payment-config-section {
        background-color: var(--gray-50);
        border-radius: var(--border-radius-lg);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 4px solid var(--primary-color);
        transition: all var(--transition-fast);
    }

    .payment-config-section.active {
        background-color: rgba(67, 97, 238, 0.05);
        border-left-color: var(--primary-color);
    }

    .config-header {
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--gray-300);
    }

    .config-header h5 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin: 0 0 0.5rem 0;
        font-size: 1.125rem;
        color: var(--dark-color);
    }

    .config-header h5 i {
        color: var(--primary-color);
        font-size: 1.25rem;
    }

    .config-header p {
        margin: 0;
        font-size: var(--font-size-sm);
        color: var(--gray-600);
        line-height: 1.4;
    }

    .config-body {
        /* No additional styles needed */
    }

    /* Alert Styles */
    .alert {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 1rem;
        border-radius: var(--border-radius-md);
        margin-top: 1rem;
        font-size: var(--font-size-sm);
        line-height: 1.5;
    }

    .alert i {
        flex-shrink: 0;
        font-size: 1.25rem;
        margin-top: 0.125rem;
    }

    .alert-info {
        background-color: #e7f3ff;
        border: 1px solid #b3d9ff;
        color: #0066cc;
    }

    .alert-info i {
        color: #0066cc;
    }

    .alert-warning {
        background-color: #fff3cd;
        border: 1px solid #ffeaa7;
        color: #856404;
    }

    .alert-warning i {
        color: #f39c12;
    }

    .alert strong {
        display: block;
        margin-bottom: 0.25rem;
        font-weight: var(--font-weight-semibold);
    }

    .alert p {
        margin: 0;
    }

    /* Form Enhancements */
    .form-row {
        display: flex;
        margin: 0 -10px;
        flex-wrap: wrap;
    }

    .form-group {
        padding: 0 10px;
        margin-bottom: 1rem;
    }

    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: var(--font-weight-medium);
        color: var(--dark-color);
    }

    .form-control,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        font-size: 1rem;
        transition: border-color var(--transition-fast), box-shadow var(--transition-fast);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        outline: none;
    }

    .form-text {
        font-size: var(--font-size-sm);
        color: var(--gray-600);
        margin-top: 0.25rem;
        line-height: 1.4;
    }

    /* Settings tabs styles */
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

    .form-actions {
        display: flex;
        gap: 16px;
        margin-top: 24px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .payment-methods-grid {
            grid-template-columns: 1fr;
        }
        
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .payment-config-section {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        .payment-method-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1rem;
        }
        
        .payment-method-toggle {
            align-self: stretch;
            display: flex;
            justify-content: center;
        }
    }
</style>

<script>
// Settings Tabs ve File Upload için geliştirilmiş JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Settings page initialized');
    
    // Settings Tabs Functionality
    const SettingsTabs = {
        init: function() {
            this.tabLinks = document.querySelectorAll('.settings-nav-tab');
            this.tabPanes = document.querySelectorAll('.settings-tab-pane');
            this.bindEvents();
            this.activateFirstTab();
            this.initImagePreviews();
            this.initPaymentToggles();
            this.initSMTPToggle();
            this.initFileUploadValidation();
        },
        
        bindEvents: function() {
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
                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml'];
                        if (!allowedTypes.includes(this.files[0].type)) {
                            alert('Please select a valid image file (JPEG, PNG, GIF, or SVG)');
                            this.value = '';
                            return;
                        }
                        
                        // Validate file size (max 5MB)
                        if (this.files[0].size > 5 * 1024 * 1024) {
                            alert('File size should be less than 5MB');
                            this.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            logoPreview.src = e.target.result;
                        }
                        reader.readAsDataURL(this.files[0]);
                        
                        console.log('Logo file selected:', this.files[0].name);
                    }
                });
            }
            
            // Favicon Preview
            const faviconInput = document.getElementById('favicon');
            const faviconPreview = document.getElementById('favicon_preview');
            
            if (faviconInput && faviconPreview) {
                faviconInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        // Validate file type
                        const allowedTypes = ['image/x-icon', 'image/png'];
                        if (!allowedTypes.includes(this.files[0].type)) {
                            alert('Please select a valid favicon file (ICO or PNG)');
                            this.value = '';
                            return;
                        }
                        
                        // Validate file size (max 1MB)
                        if (this.files[0].size > 1024 * 1024) {
                            alert('Favicon file size should be less than 1MB');
                            this.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            faviconPreview.src = e.target.result;
                        }
                        reader.readAsDataURL(this.files[0]);
                        
                        console.log('Favicon file selected:', this.files[0].name);
                    }
                });
            }
            
            // Homepage Images Previews
            const homepageImageInputs = [
                'hero_bg', 'about_bg'
            ];
            
            homepageImageInputs.forEach(function(id) {
                const input = document.getElementById(id);
                const preview = document.getElementById(id + '_preview');
                
                if (input && preview) {
                    input.addEventListener('change', function() {
                        if (this.files && this.files[0]) {
                            // Validate file type
                            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                            if (!allowedTypes.includes(this.files[0].type)) {
                                alert('Please select a valid image file (JPEG, PNG, or GIF)');
                                this.value = '';
                                return;
                            }
                            
                            // Validate file size (max 10MB for background images)
                            if (this.files[0].size > 10 * 1024 * 1024) {
                                alert('Image file size should be less than 10MB');
                                this.value = '';
                                return;
                            }
                            
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                preview.src = e.target.result;
                            }
                            reader.readAsDataURL(this.files[0]);
                            
                            console.log('Homepage image selected:', id, this.files[0].name);
                        }
                    });
                }
            });
        },
        
        initFileUploadValidation: function() {
            // Add form submission validation
            const settingsForm = document.querySelector('.settings-form');
            if (settingsForm) {
                settingsForm.addEventListener('submit', function(e) {
                    // Show loading state
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<i class="material-icons">hourglass_empty</i> Saving...';
                        submitBtn.disabled = true;
                        
                        // Re-enable button after form submission (fallback)
                        setTimeout(() => {
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }, 5000);
                    }
                });
            }
        },
        
        initPaymentToggles: function() {
            // Add visual feedback for payment method toggles
            const paymentToggles = document.querySelectorAll('.payment-method-toggle input[type="checkbox"]');
            
            paymentToggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const card = this.closest('.payment-method-card');
                    if (this.checked) {
                        card.style.borderColor = '#4361ee';
                        card.style.backgroundColor = '#f8f9ff';
                    } else {
                        card.style.borderColor = '#e5e7eb';
                        card.style.backgroundColor = '#ffffff';
                    }
                    
                    // Show/hide configuration sections
                    SettingsTabs.updatePaymentConfigs();
                });
                
                // Set initial state
                const card = toggle.closest('.payment-method-card');
                if (toggle.checked) {
                    card.style.borderColor = '#4361ee';
                    card.style.backgroundColor = '#f8f9ff';
                }
            });
            
            // Initial configuration update
            this.updatePaymentConfigs();
        },
        
        updatePaymentConfigs: function() {
            // Get all payment method toggles
            const cardToggle = document.getElementById('payment_card');
            const paypalToggle = document.getElementById('payment_paypal');
            const bankToggle = document.getElementById('payment_bank');
            const cashToggle = document.getElementById('payment_cash');
            
            // Get all config sections
            const cardConfig = document.getElementById('card-config');
            const paypalConfig = document.getElementById('paypal-config');
            const bankConfig = document.getElementById('bank-config');
            const cashConfig = document.getElementById('cash-config');
            
            // Show/hide configurations based on toggle states
            if (cardConfig) {
                if (cardToggle && cardToggle.checked) {
                    cardConfig.style.display = 'block';
                    cardConfig.classList.add('active');
                } else {
                    cardConfig.style.display = 'none';
                    cardConfig.classList.remove('active');
                }
            }
            
            if (paypalConfig) {
                if (paypalToggle && paypalToggle.checked) {
                    paypalConfig.style.display = 'block';
                    paypalConfig.classList.add('active');
                } else {
                    paypalConfig.style.display = 'none';
                    paypalConfig.classList.remove('active');
                }
            }
            
            if (bankConfig) {
                if (bankToggle && bankToggle.checked) {
                    bankConfig.style.display = 'block';
                    bankConfig.classList.add('active');
                } else {
                    bankConfig.style.display = 'none';
                    bankConfig.classList.remove('active');
                }
            }
            
            if (cashConfig) {
                if (cashToggle && cashToggle.checked) {
                    cashConfig.style.display = 'block';
                    cashConfig.classList.add('active');
                } else {
                    cashConfig.style.display = 'none';
                    cashConfig.classList.remove('active');
                }
            }
            
            // Show a message if no payment methods are selected
            this.checkNoPaymentMethods();
        },
        
        checkNoPaymentMethods: function() {
            const toggles = document.querySelectorAll('.payment-method-toggle input[type="checkbox"]');
            const anyChecked = Array.from(toggles).some(toggle => toggle.checked);
            
            // Find or create no-payment warning
            let noPaymentWarning = document.getElementById('no-payment-warning');
            
            if (!anyChecked) {
                if (!noPaymentWarning) {
                    noPaymentWarning = document.createElement('div');
                    noPaymentWarning.id = 'no-payment-warning';
                    noPaymentWarning.className = 'alert alert-warning';
                    noPaymentWarning.innerHTML = `
                        <i class="material-icons">warning</i>
                        <div>
                            <strong>No Payment Methods Selected</strong>
                            <p>Please enable at least one payment method to accept bookings</p>
                        </div>
                    `;
                    
                    const configSection = document.querySelector('.payment-config-section') && document.querySelector('.payment-config-section').parentNode;
                    if (configSection) {
                        configSection.insertBefore(noPaymentWarning, configSection.firstChild);
                    }
                }
            } else {
                if (noPaymentWarning) {
                    noPaymentWarning.remove();
                }
            }
        },
        
        // SMTP Toggle Functionality
        initSMTPToggle: function() {
            const smtpToggle = document.getElementById('smtp_enabled');
            const smtpConfig = document.getElementById('smtp-config');
            const smtpAuthToggle = document.getElementById('smtp_auth');
            const smtpAuthFields = document.getElementById('smtp-auth-fields');
            
            if (smtpToggle && smtpConfig) {
                // Initial state check
                this.toggleSMTPConfig();
                
                // Toggle SMTP configuration visibility
                smtpToggle.addEventListener('change', () => {
                    this.toggleSMTPConfig();
                });
            }
            
            if (smtpAuthToggle && smtpAuthFields) {
                // Initial state check
                this.toggleSMTPAuth();
                
                smtpAuthToggle.addEventListener('change', () => {
                    this.toggleSMTPAuth();
                });
            }
            
            // Test SMTP functionality
            this.initSMTPTest();
            
            // Password visibility toggles
            this.initPasswordToggles();
        },
        
        toggleSMTPConfig: function() {
            const smtpToggle = document.getElementById('smtp_enabled');
            const smtpConfig = document.getElementById('smtp-config');
            
            if (smtpToggle && smtpConfig) {
                if (smtpToggle.checked) {
                    smtpConfig.style.display = 'block';
                    smtpConfig.classList.add('active');
                    console.log('SMTP configuration shown');
                } else {
                    smtpConfig.style.display = 'none';
                    smtpConfig.classList.remove('active');
                    console.log('SMTP configuration hidden');
                }
            }
        },
        
        toggleSMTPAuth: function() {
            const smtpAuthToggle = document.getElementById('smtp_auth');
            const smtpAuthFields = document.getElementById('smtp-auth-fields');
            
            if (smtpAuthToggle && smtpAuthFields) {
                if (smtpAuthToggle.checked) {
                    smtpAuthFields.style.display = 'block';
                    smtpAuthFields.classList.add('show');
                    console.log('SMTP auth fields shown');
                } else {
                    smtpAuthFields.style.display = 'none';
                    smtpAuthFields.classList.remove('show');
                    console.log('SMTP auth fields hidden');
                }
            }
        },
        
        initSMTPTest: function() {
            const testSmtpBtn = document.querySelector('.test-smtp-btn');
            const testEmailInput = document.getElementById('test_email');
            const testResult = document.getElementById('smtp-test-result');
            
            if (testSmtpBtn && testEmailInput && testResult) {
                testSmtpBtn.addEventListener('click', function() {
                    const email = testEmailInput.value.trim();
                    
                    if (!email) {
                        testResult.innerHTML = '<div class="alert alert-warning"><i class="material-icons">warning</i>Please enter a test email address</div>';
                        return;
                    }
                    
                    if (!isValidEmail(email)) {
                        testResult.innerHTML = '<div class="alert alert-warning"><i class="material-icons">warning</i>Please enter a valid email address</div>';
                        return;
                    }
                    
                    // Show loading state
                    const originalText = testSmtpBtn.innerHTML;
                    testSmtpBtn.innerHTML = '<i class="material-icons">hourglass_empty</i> Sending...';
                    testSmtpBtn.disabled = true;
                    
                    // Get SMTP settings from form
                    const smtpSettings = {
                        test_email: email,
                        smtp_enabled: document.getElementById('smtp_enabled').checked ? '1' : '0',
                        smtp_host: document.getElementById('smtp_host').value,
                        smtp_port: document.getElementById('smtp_port').value,
                        smtp_security: document.getElementById('smtp_security').value,
                        smtp_auth: document.getElementById('smtp_auth').checked ? '1' : '0',
                        smtp_username: document.getElementById('smtp_username').value,
                        smtp_password: document.getElementById('smtp_password').value,
                        smtp_timeout: document.getElementById('smtp_timeout').value,
                        email_from_address: document.getElementById('email_from_address').value,
                        email_from_name: document.getElementById('email_from_name').value,
                        email_reply_to: document.getElementById('email_reply_to').value
                    };
                    
                    // Send AJAX request to test email endpoint
                    fetch(window.location.origin + '/admin/settings/testEmail', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(smtpSettings)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            testResult.innerHTML = '<div class="alert alert-success"><i class="material-icons">check_circle</i>' + data.message + '</div>';
                        } else {
                            testResult.innerHTML = '<div class="alert alert-danger"><i class="material-icons">error</i>' + data.message + '</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        testResult.innerHTML = '<div class="alert alert-danger"><i class="material-icons">error</i>Failed to send test email. Please try again.</div>';
                    })
                    .finally(() => {
                        testSmtpBtn.innerHTML = originalText;
                        testSmtpBtn.disabled = false;
                    });
                });
            }
            
            function isValidEmail(email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }
        },
        
        initPasswordToggles: function() {
            const passwordToggles = document.querySelectorAll('.toggle-password');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const targetId = this.dataset.target;
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (input && icon) {
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.textContent = 'visibility_off';
                        } else {
                            input.type = 'password';
                            icon.textContent = 'visibility';
                        }
                    }
                });
            });
        }
    };
    
    // Initialize settings tabs
    SettingsTabs.init();
    
    // Show success/error messages if they exist
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(message => {
        setTimeout(() => {
            message.style.opacity = '0';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);
    });
});
</script>