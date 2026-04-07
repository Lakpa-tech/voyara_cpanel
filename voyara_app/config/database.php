<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */

// For Podman/local dev: Use 127.0.0.1 (TCP) not localhost (Unix socket)
// For production cPanel: Use 'localhost'
define('DB_HOST',    getenv('DB_HOST')    ?: '127.0.0.1');
define('DB_PORT',    getenv('DB_PORT')    ?: '3306');
define('DB_NAME',    getenv('DB_NAME')    ?: 'voyara_db');
define('DB_USER',    getenv('DB_USER')    ?: 'voyara_user');
define('DB_PASS',    getenv('DB_PASS')    ?: 'voyara_pass');
define('DB_CHARSET', 'utf8mb4');
