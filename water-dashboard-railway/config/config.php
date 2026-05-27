<?php
// ============================================================
// config/config.php - Application Configuration
// ============================================================

// Application Settings
define('APP_NAME', 'Water Dashboard');
define('APP_VERSION', '1.0.0');
$_detected_url = getenv('APP_URL'); if (!$_detected_url) {     $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';     $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';     $_detected_url = $scheme . '://' . $host; } define('APP_URL', rtrim($_detected_url, '/'));
define('BASE_PATH', dirname(__DIR__));

// Database Configuration
define('DB_HOST', 'sql12.freesqldatabase.com');
define('DB_NAME', 'sql12827461');
define('DB_USER', 'sql12827461');
define('DB_PASS', 'FQX2VLXPqk');
define('DB_PORT', '3306');
define('DB_CHARSET', 'utf8mb4');
// Session Configuration
define('SESSION_NAME', 'water_dash_session');
define('SESSION_LIFETIME', 3600);

// Pagination
define('RECORDS_PER_PAGE', 10);

// Water Thresholds (liters per acre per day)
define('WATER_THRESHOLD_LOW', 300);
define('WATER_THRESHOLD_HIGH', 1000);
define('WATER_THRESHOLD_CRITICAL', 1500);

// Alert types
define('ALERT_SUCCESS', 'success');
define('ALERT_DANGER', 'danger');
define('ALERT_WARNING', 'warning');
define('ALERT_INFO', 'info');

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error reporting (0 in production)
error_reporting(0);
ini_set('display_errors', 0);
