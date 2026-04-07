<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */

define('APP_NAME',    'Voyara Travel');
define('APP_URL',     getenv('APP_URL') ?: 'http://localhost:8000');
define('APP_ENV',     getenv('APP_ENV') ?: 'development');
define('APP_DEBUG',   APP_ENV === 'development');

// ROOT_PATH is already defined in public_html/index.php
// Do not redefine it here to avoid the "already defined" warning

// public_html is the sibling of voyara_app
define('PUBLIC_HTML',  dirname(ROOT_PATH) . '/public_html');

define('APP_PATH',     ROOT_PATH . '/app');
define('VIEW_PATH',    APP_PATH  . '/views');
define('UPLOAD_PATH',  PUBLIC_HTML . '/uploads');
define('PUBLIC_PATH',  PUBLIC_HTML);

define('UPLOAD_URL',   APP_URL . '/uploads');
define('ASSETS_URL',   APP_URL . '/assets');

define('MAX_FILE_SIZE',     5 * 1024 * 1024);
define('ALLOWED_IMG_TYPES', ['image/jpeg', 'image/png', 'image/webp']);
define('SESSION_LIFETIME',  7200);

if (APP_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
    ini_set('log_errors', 1);
    ini_set('error_log', ROOT_PATH . '/logs/error.log');
}
