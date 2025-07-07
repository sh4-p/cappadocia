
<?php
/**
 * Main entry point for the Cappadocia Travel Agency website
 * 
 * This file initializes the application and loads core components
 */

// Define base path
define('BASE_PATH', __DIR__);

// Load configuration
require_once BASE_PATH . '/config/config.php';

// Load core files
require_once BASE_PATH . '/core/App.php';
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/View.php';
require_once BASE_PATH . '/core/Database.php';
require_once BASE_PATH . '/core/Session.php';
require_once BASE_PATH . '/core/Language.php';
require_once BASE_PATH . '/core/Validator.php';
require_once BASE_PATH . '/core/Logger.php';

// Load helper files
require_once BASE_PATH . '/app/helpers/LanguageHelper.php';
require_once BASE_PATH . '/app/helpers/FormHelper.php';
require_once BASE_PATH . '/app/helpers/ImageHelper.php';
require_once BASE_PATH . '/app/helpers/DateHelper.php';



// Initialize the application
$app = new App();

// Run the application
$app->run();