<!-- Newsletter Subscription Widget -->
<div class="newsletter-widget" id="newsletter-widget">
    <div class="newsletter-container">
        <div class="newsletter-content">
            <div class="newsletter-icon">
                <i class="material-icons">email</i>
            </div>
            <div class="newsletter-text">
                <h3><?php _e('subscribe_to_newsletter'); ?></h3>
                <p><?php _e('newsletter_subscription_description'); ?></p>
            </div>
        </div>
        
        <div class="newsletter-form">
            <!-- Regular Form (fallback) -->
            <form class="subscription-form" id="newsletter-form" action="<?php echo $appUrl . '/' . $currentLang; ?>/newsletter/subscribe" method="POST">
                <div class="form-group">
                    <div class="input-group">
                        <input type="email" name="email" id="newsletter-email" class="form-control" 
                               placeholder="<?php _e('enter_your_email'); ?>" required>
                        <input type="text" name="name" id="newsletter-name" class="form-control" 
                               placeholder="<?php _e('your_name_optional'); ?>">
                        <button type="submit" class="btn btn-primary" id="newsletter-submit">
                            <span class="btn-text"><?php _e('subscribe'); ?></span>
                            <span class="btn-loading" style="display: none;">
                                <i class="material-icons">hourglass_empty</i>
                            </span>
                        </button>
                    </div>
                </div>
                
                <div class="form-footer">
                    <div class="privacy-notice">
                        <small>
                            <i class="material-icons">lock</i>
                            <?php _e('newsletter_privacy_notice'); ?>
                            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/page/privacy-policy" target="_blank">
                                <?php _e('privacy_policy'); ?>
                            </a>
                        </small>
                    </div>
                </div>
            </form>
            
            <!-- Success Message -->
            <div class="subscription-success" id="subscription-success" style="display: none;">
                <div class="success-content">
                    <i class="material-icons">check_circle</i>
                    <h4><?php _e('subscription_successful'); ?></h4>
                    <p><?php _e('check_email_for_confirmation'); ?></p>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="resetForm()">
                    <?php _e('subscribe_another'); ?>
                </button>
            </div>
            
            <!-- Error Message -->
            <div class="subscription-error" id="subscription-error" style="display: none;">
                <div class="error-content">
                    <i class="material-icons">error</i>
                    <h4><?php _e('subscription_failed'); ?></h4>
                    <p id="error-message"><?php _e('please_try_again'); ?></p>
                </div>
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="resetForm()">
                    <?php _e('try_again'); ?>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Benefits Section (optional, can be shown/hidden) -->
    <div class="newsletter-benefits" id="newsletter-benefits">
        <div class="benefits-grid">
            <div class="benefit-item">
                <i class="material-icons">explore</i>
                <span><?php _e('exclusive_tours'); ?></span>
            </div>
            <div class="benefit-item">
                <i class="material-icons">local_offer</i>
                <span><?php _e('special_discounts'); ?></span>
            </div>
            <div class="benefit-item">
                <i class="material-icons">tips_and_updates</i>
                <span><?php _e('travel_tips'); ?></span>
            </div>
            <div class="benefit-item">
                <i class="material-icons">event</i>
                <span><?php _e('early_access'); ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Newsletter Widget Styles -->
<style>
.newsletter-widget {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 40px;
    color: white;
    position: relative;
    overflow: hidden;
    margin: 40px 0;
}

.newsletter-widget::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: rotate(45deg);
}

.newsletter-container {
    position: relative;
    z-index: 2;
}

.newsletter-content {
    display: flex;
    align-items: center;
    margin-bottom: 30px;
    gap: 20px;
}

.newsletter-icon {
    flex-shrink: 0;
}

.newsletter-icon i {
    font-size: 48px;
    color: rgba(255, 255, 255, 0.9);
}

.newsletter-text h3 {
    margin: 0 0 10px 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
}

.newsletter-text p {
    margin: 0;
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.9);
    line-height: 1.5;
}

.newsletter-form {
    position: relative;
}

.subscription-form {
    transition: all 0.3s ease;
}

.input-group {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.input-group .form-control {
    flex: 1;
    min-width: 200px;
    padding: 12px 16px;
    border: 2px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    font-size: 16px;
    backdrop-filter: blur(10px);
}

.input-group .form-control::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.input-group .form-control:focus {
    outline: none;
    border-color: rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.15);
    box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
}

.input-group .btn {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    white-space: nowrap;
}

.input-group .btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-2px);
}

.input-group .btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.form-footer {
    margin-top: 15px;
}

.privacy-notice {
    display: flex;
    align-items: center;
    gap: 8px;
    color: rgba(255, 255, 255, 0.8);
}

.privacy-notice i {
    font-size: 16px;
}

.privacy-notice a {
    color: rgba(255, 255, 255, 0.9);
    text-decoration: underline;
}

.privacy-notice a:hover {
    color: white;
}

.subscription-success,
.subscription-error {
    text-align: center;
    padding: 30px 20px;
    border-radius: 12px;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
}

.subscription-success {
    border: 2px solid rgba(46, 204, 113, 0.5);
}

.subscription-error {
    border: 2px solid rgba(231, 76, 60, 0.5);
}

.success-content i,
.error-content i {
    font-size: 48px;
    margin-bottom: 15px;
}

.success-content i {
    color: #2ecc71;
}

.error-content i {
    color: #e74c3c;
}

.success-content h4,
.error-content h4 {
    margin: 0 0 10px 0;
    color: white;
    font-size: 1.25rem;
    font-weight: 600;
}

.success-content p,
.error-content p {
    margin: 0 0 20px 0;
    color: rgba(255, 255, 255, 0.9);
}

.newsletter-benefits {
    margin-top: 30px;
    padding-top: 30px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 20px;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: rgba(255, 255, 255, 0.9);
    font-size: 14px;
}

.benefit-item i {
    font-size: 20px;
    color: rgba(255, 255, 255, 0.8);
}

/* Responsive Design */
@media (max-width: 768px) {
    .newsletter-widget {
        padding: 30px 20px;
    }
    
    .newsletter-content {
        flex-direction: column;
        text-align: center;
        margin-bottom: 25px;
    }
    
    .newsletter-text h3 {
        font-size: 1.5rem;
    }
    
    .newsletter-text p {
        font-size: 1rem;
    }
    
    .input-group {
        flex-direction: column;
    }
    
    .input-group .form-control,
    .input-group .btn {
        width: 100%;
        min-width: auto;
    }
    
    .benefits-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .benefit-item {
        font-size: 13px;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .newsletter-widget {
        padding: 25px 15px;
        margin: 30px 0;
    }
    
    .newsletter-icon i {
        font-size: 36px;
    }
    
    .newsletter-text h3 {
        font-size: 1.25rem;
    }
    
    .benefits-grid {
        grid-template-columns: 1fr;
    }
}

/* Animation Classes */
.fade-out {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-in {
    opacity: 1;
    transform: translateY(0);
}
</style>

<!-- Newsletter Widget JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('newsletter-form');
    const submitBtn = document.getElementById('newsletter-submit');
    const successDiv = document.getElementById('subscription-success');
    const errorDiv = document.getElementById('subscription-error');
    const errorMessage = document.getElementById('error-message');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const email = formData.get('email');
        const name = formData.get('name');
        
        // Validate email
        if (!validateEmail(email)) {
            showError('<?php _e("please_enter_valid_email"); ?>');
            return;
        }
        
        // Show loading state
        showLoading();
        
        // Make AJAX request
        fetch('<?php echo $appUrl . '/' . $currentLang; ?>/newsletter/ajax-subscribe', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            
            if (data.success) {
                showSuccess();
                
                // Track subscription event (if analytics are enabled)
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'newsletter_subscribe', {
                        'event_category': 'engagement',
                        'event_label': 'newsletter_widget'
                    });
                }
            } else {
                showError(data.message || '<?php _e("subscription_failed_try_again"); ?>');
            }
        })
        .catch(error => {
            hideLoading();
            showError('<?php _e("network_error_try_again"); ?>');
            console.error('Newsletter subscription error:', error);
        });
    });
    
    function showLoading() {
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnLoading.style.display = 'inline-block';
    }
    
    function hideLoading() {
        submitBtn.disabled = false;
        btnText.style.display = 'inline-block';
        btnLoading.style.display = 'none';
    }
    
    function showSuccess() {
        form.style.display = 'none';
        successDiv.style.display = 'block';
        
        // Reset form after delay
        setTimeout(() => {
            resetForm();
        }, 10000); // Auto-reset after 10 seconds
    }
    
    function showError(message) {
        errorMessage.textContent = message;
        
        form.style.display = 'none';
        errorDiv.style.display = 'block';
        
        // Auto-hide error after delay
        setTimeout(() => {
            resetForm();
        }, 5000);
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Global function for reset button
    window.resetForm = function() {
        form.style.display = 'block';
        successDiv.style.display = 'none';
        errorDiv.style.display = 'none';
        
        // Clear form
        form.reset();
        hideLoading();
    };
});

// Optional: Auto-hide benefits section after subscription
function hideBenefitsAfterSubscription() {
    const benefits = document.getElementById('newsletter-benefits');
    if (benefits) {
        benefits.style.display = 'none';
    }
}
</script>

<?php
// Display any flash messages from session
if (isset($_SESSION['flash_newsletter_success'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success message if subscription was successful
    document.getElementById('newsletter-form').style.display = 'none';
    document.getElementById('subscription-success').style.display = 'block';
    
    // Auto-reset after delay
    setTimeout(() => {
        resetForm();
    }, 8000);
});
</script>
<?php unset($_SESSION['flash_newsletter_success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['flash_newsletter_error'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const errorMessage = '<?php echo addslashes($_SESSION['flash_newsletter_error']); ?>';
    document.getElementById('error-message').textContent = errorMessage;
    document.getElementById('newsletter-form').style.display = 'none';
    document.getElementById('subscription-error').style.display = 'block';
    
    // Auto-hide error after delay
    setTimeout(() => {
        resetForm();
    }, 5000);
});
</script>
<?php unset($_SESSION['flash_newsletter_error']); ?>
<?php endif; ?>