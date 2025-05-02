<div class="error-container">
    <div class="error-code">404</div>
    <h1 class="error-title"><?php _e('page_not_found'); ?></h1>
    <p class="error-message"><?php _e('page_not_found_message'); ?></p>
    <a href="<?php echo $appUrl; ?>" class="btn btn-primary">
        <i class="material-icons">home</i>
        <span><?php _e('go_home'); ?></span>
    </a>
</div>

<style>
.error-container {
    text-align: center;
    padding: 80px 20px;
}

.error-code {
    font-size: 120px;
    font-weight: bold;
    color: #ddd;
    margin-bottom: 20px;
}

.error-title {
    font-size: 32px;
    margin-bottom: 20px;
}

.error-message {
    font-size: 18px;
    color: #777;
    max-width: 600px;
    margin: 0 auto 40px;
}
</style>