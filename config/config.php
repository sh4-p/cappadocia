
<?php
/**
 * Main configuration file
 */

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Application configuration
define('APP_NAME', 'Cappadocia Travel Agency');
define('APP_URL', 'http://localhost/cappadocia');
define('ADMIN_URL', APP_URL . '/admin');

// Public paths
define('PUBLIC_PATH', BASE_PATH . '/public');
define('UPLOADS_PATH', PUBLIC_PATH . '/uploads');

// URL paths
define('CSS_URL', APP_URL . '/public/css');
define('JS_URL', APP_URL . '/public/js');
define('IMG_URL', APP_URL . '/public/img');
define('UPLOADS_URL', APP_URL . '/public/uploads');

// Default controller and action
define('DEFAULT_CONTROLLER', 'Home');
define('DEFAULT_ACTION', 'index');

// Session configuration
define('SESSION_PREFIX', 'cappadocia_');
define('SESSION_LIFETIME', 7200); // 2 hours

// Security configuration
define('LOG_ENCRYPTION_KEY', getenv('LOG_ENCRYPTION_KEY') ?: 'cappadocia_travel_log_encryption_key_2024');
// In production, set LOG_ENCRYPTION_KEY environment variable with a strong random key

// Include database configuration
require_once BASE_PATH . '/config/database.php';

// Language settings
define('DEFAULT_LANGUAGE', 'en');
define('AVAILABLE_LANGUAGES', json_encode([
    'en' => 'English',
    'tr' => 'Türkçe',
    'de' => 'Deutsch',
    'ru' => 'Русский'
]));

// Load database configuration
require_once 'database.php';

// Load environment-specific configuration if exists
$env_config = BASE_PATH . '/config/env.php';
if (file_exists($env_config)) {
    require_once $env_config;
}