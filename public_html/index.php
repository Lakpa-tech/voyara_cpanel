<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */

// public_html/index.php
// voyara_app lives one level above public_html inside the cPanel home directory
// Structure: /home/username/voyara_app/   <-- app code (private)
//            /home/username/public_html/  <-- web root (this file)

define('ROOT_PATH', dirname(__DIR__) . '/voyara_app');

require_once ROOT_PATH . '/config/bootstrap.php';

foreach (glob(APP_PATH . '/controllers/*.php') as $f) require_once $f;

$uri    = strtok($_SERVER['REQUEST_URI'], '?');
$uri    = rtrim(parse_url($uri, PHP_URL_PATH), '/') ?: '/';
$method = $_SERVER['REQUEST_METHOD'];

$routes = [
    ['GET',  '/',                      fn() => view('user.home', [
        'featured'   => PackageModel::featured(6),
        'categories' => PackageModel::categories(),
        'locations'  => PackageModel::locations(),
    ])],
    ['GET',  '/login',                 fn() => AuthController::loginForm()],
    ['POST', '/login',                 fn() => AuthController::login()],
    ['GET',  '/register',              fn() => AuthController::registerForm()],
    ['POST', '/register',              fn() => AuthController::register()],
    ['GET',  '/logout',                fn() => AuthController::logout()],
    ['GET',  '/dashboard',             fn() => UserController::dashboard()],
    ['GET',  '/profile',               fn() => UserController::profileForm()],
    ['POST', '/profile',               fn() => UserController::updateProfile()],
    ['POST', '/profile/password',      fn() => UserController::changePassword()],
    ['GET',  '/packages',              fn() => PackageController::index()],
    ['POST', '/book',                  fn() => BookingController::store()],
    ['POST', '/reviews',               fn() => ReviewController::store()],
    ['GET',  '/admin',                 fn() => redirect('/admin/dashboard')],
    ['GET',  '/admin/login',           fn() => AdminController::loginForm()],
    ['POST', '/admin/login',           fn() => AdminController::login()],
    ['GET',  '/admin/logout',          fn() => AdminController::logout()],
    ['GET',  '/admin/dashboard',       fn() => AdminController::dashboard()],
    ['GET',  '/admin/packages',        fn() => AdminController::packages()],
    ['GET',  '/admin/packages/create', fn() => AdminController::createPackageForm()],
    ['POST', '/admin/packages',        fn() => AdminController::storePackage()],
    ['GET',  '/admin/bookings',        fn() => AdminController::bookings()],
    ['GET',  '/admin/users',           fn() => AdminController::users()],
    ['GET',  '/admin/reviews',         fn() => AdminController::reviews()],
    ['GET',  '/admin/agents',          fn() => AdminController::agents()],
    ['GET',  '/admin/agents/create',   fn() => AdminController::createAgentForm()],
    ['POST', '/admin/agents',          fn() => AdminController::storeAgent()],
    ['GET',  '/admin/settings',        fn() => AdminController::settings()],
    ['POST', '/admin/settings',        fn() => AdminController::saveSettings()],
    ['GET',  '/agent/login',           fn() => AgentController::loginForm()],
    ['POST', '/agent/login',           fn() => AgentController::login()],
    ['GET',  '/agent/logout',          fn() => AgentController::logout()],
    ['GET',  '/agent/dashboard',       fn() => AgentController::dashboard()],
    ['GET',  '/agent/bookings',        fn() => AgentController::bookings()],
];

function matchDynamic(string $uri, string $method): bool {
    if ($method === 'GET'  && preg_match('#^/packages/([a-z0-9\-]+)$#', $uri, $m))       { PackageController::show($m[1]); return true; }
    if ($method === 'GET'  && preg_match('#^/book/(\d+)$#', $uri, $m))                   { BookingController::form((int)$m[1]); return true; }
    if ($method === 'GET'  && preg_match('#^/bookings/(\d+)$#', $uri, $m))               { BookingController::show((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/bookings/(\d+)/payment$#', $uri, $m))       { BookingController::uploadPayment((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/bookings/(\d+)/cancel$#', $uri, $m))        { BookingController::cancel((int)$m[1]); return true; }
    if ($method === 'GET'  && preg_match('#^/admin/packages/(\d+)/edit$#', $uri, $m))    { AdminController::editPackageForm((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/admin/packages/(\d+)$#', $uri, $m))         { AdminController::updatePackage((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/admin/packages/(\d+)/delete$#', $uri, $m))  { AdminController::deletePackage((int)$m[1]); return true; }
    if ($method === 'GET'  && preg_match('#^/admin/bookings/(\d+)$#', $uri, $m))         { AdminController::bookingDetail((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/admin/bookings/(\d+)/status$#', $uri, $m))  { AdminController::updateBookingStatus((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/admin/bookings/(\d+)/assign$#', $uri, $m))  { AdminController::assignAgent((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/admin/bookings/(\d+)/payment$#', $uri, $m)) { AdminController::verifyPayment((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/admin/users/(\d+)/toggle$#', $uri, $m))     { AdminController::toggleUser((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/admin/reviews/(\d+)/moderate$#', $uri, $m)) { AdminController::moderateReview((int)$m[1]); return true; }
    if ($method === 'GET'  && preg_match('#^/agent/bookings/(\d+)$#', $uri, $m))         { AgentController::bookingDetail((int)$m[1]); return true; }
    if ($method === 'POST' && preg_match('#^/agent/bookings/(\d+)/status$#', $uri, $m))  { AgentController::updateStatus((int)$m[1]); return true; }
    return false;
}

foreach ($routes as [$rMethod, $rPath, $handler]) {
    if ($method === $rMethod && $uri === $rPath) { $handler(); exit; }
}

if (matchDynamic($uri, $method)) exit;

http_response_code(404);
view('partials.404');
