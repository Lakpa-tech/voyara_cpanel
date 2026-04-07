<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */

require_once ROOT_PATH . '/config/app.php';
require_once ROOT_PATH . '/config/database.php';

spl_autoload_register(function (string $class): void {
    $map = [
        'DB'            => APP_PATH . '/models/DB.php',
        'Session'       => APP_PATH . '/models/Session.php',
        'Auth'          => APP_PATH . '/models/Auth.php',
        'CSRF'          => APP_PATH . '/models/CSRF.php',
        'Validator'     => APP_PATH . '/models/Validator.php',
        'FileUploader'  => APP_PATH . '/models/FileUploader.php',
        'UserModel'     => APP_PATH . '/models/UserModel.php',
        'PackageModel'  => APP_PATH . '/models/PackageModel.php',
        'BookingModel'  => APP_PATH . '/models/BookingModel.php',
        'ReviewModel'   => APP_PATH . '/models/ReviewModel.php',
        'SettingsModel' => APP_PATH . '/models/SettingsModel.php',
        'AdminModel'    => APP_PATH . '/models/AdminModel.php',
        'AgentModel'    => APP_PATH . '/models/AgentModel.php',
    ];
    if (isset($map[$class])) require_once $map[$class];
});

Session::start();

function redirect(string $url): void {
    header('Location: ' . APP_URL . $url);
    exit;
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function view(string $template, array $data = []): void {
    extract($data, EXTR_SKIP);
    $file = VIEW_PATH . '/' . str_replace('.', '/', $template) . '.php';
    if (!file_exists($file)) {
        throw new RuntimeException("View not found: {$template}");
    }
    require $file;
}

function setting(string $key, string $default = ''): string {
    static $cache = null;
    if ($cache === null) $cache = SettingsModel::all();
    return $cache[$key] ?? $default;
}
