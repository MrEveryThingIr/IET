<?php
// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['registered'])){
    header('Location: index.php?current_page=admin');
}

// Define Global Constants
define('APP_NAME', 'IET');
define('PROJECT_DIR', 'prIET_withMamadBeheshti'); // Adjust based on your project directory
define('BASE_DIR', __DIR__); // Base directory of the application
define('BASE_URL', isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' 
    ? "https://" . $_SERVER['HTTP_HOST'] . '/' . trim(PROJECT_DIR, '/') . '/'
    : "http://" . $_SERVER['HTTP_HOST'] . '/' . trim(PROJECT_DIR, '/') . '/'
);

// Include database configuration
require_once BASE_DIR . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "databaseConfig.php";

// Include autoloader for class files
require_once BASE_DIR . DIRECTORY_SEPARATOR . "autoloader.php";

// Include helper functions
require_once BASE_DIR . DIRECTORY_SEPARATOR . "helpers.php";

// Error reporting and logging (Production and Development Modes)
define('DEBUG_MODE', true); // Set to `false` in production
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', BASE_DIR . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "error.log");
    error_reporting(E_ALL); // Suppress display, but log errors
}

// Sanitize global input arrays (GET, POST)
if (!function_exists('sanitize_globals')) {
    function sanitize_globals() {
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? [];
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? [];
    }
}
sanitize_globals();

// CSRF Token Initialization
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Set default timezone
date_default_timezone_set('Asia/Tehran'); // Adjust based on your location

// Function to autoload classes (if not handled by autoloader.php)
spl_autoload_register(function ($class) {
    $file = BASE_DIR . DIRECTORY_SEPARATOR . "classes" . DIRECTORY_SEPARATOR . $class . ".php";
    if (file_exists($file)) {
        require_once $file;
    }
});

// Asset URL helper
if (!function_exists('asset')) {
    function asset($path) {
        return BASE_URL . "assets/" . ltrim($path, '/');
    }
}

// Upload URL helper
if (!function_exists('upload')) {
    function upload($path) {
        return BASE_URL . "uploads/" . ltrim($path, '/');
    }
}

// Logs Directory Initialization
$logDir = BASE_DIR . DIRECTORY_SEPARATOR . "logs";
if (!file_exists($logDir)) {
    mkdir($logDir, 0755, true);
}
