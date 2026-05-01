<?php
// Check if running on localhost
$isLocalhost = in_array($_SERVER['HTTP_HOST'], ['localhost', '127.0.0.1']);

if ($isLocalhost) {
    // Localhost Database Params
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'dms_db');
    
    // URL Root for Localhost
    define('URLROOT', "http://localhost/dms");
} else {
    // Live Server Database Params
    define('DB_HOST', 'localhost');
    define('DB_USER', 'rasedwwq_dms');
    define('DB_PASS', 'Z2Xt^3%W?M=m');
    define('DB_NAME', 'rasedwwq_dms');
    
    // URL Root for Live Server
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https" : "http";
    define('URLROOT', $protocol . "://" . $_SERVER['HTTP_HOST']);

    // Enable error reporting for debugging on live server (Remove this after fixing)
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// App Root
define('APPROOT', dirname(dirname(__FILE__)) . '/app');

// Site Name
define('SITENAME', 'PararBazar');
