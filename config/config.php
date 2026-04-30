<?php
// Database params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dms_db');

// App Root
define('APPROOT', dirname(dirname(__FILE__)) . '/app');
// URL Root
define('URLROOT', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/dms");
// Site Name
define('SITENAME', 'PararBazar');
