<?php
/**
 * Prime Fleet Services - Configuration
 */

ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting - prevent inline error output from breaking HTML layout
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Database Driver: 'sqlite' or 'mysql'
define('DB_DRIVER', 'sqlite');

// SQLite Settings
define('SQLITE_PATH', __DIR__ . '/../data/pfs.db');

// MySQL Settings (used if DB_DRIVER is 'mysql')
define('DB_HOST', 'localhost');
define('DB_NAME', 'pfs_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_PORT', 3306);
define('DB_CHARSET', 'utf8mb4');

// Paths and Uploads
define('BASE_PATH', dirname(__DIR__));
define('UPLOADS_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);
define('UPLOADS_URL', 'uploads/');

// Site Defaults
define('APP_NAME', 'Prime Fleet Services');
define('ADMIN_NAME', 'PFS Administrator');
