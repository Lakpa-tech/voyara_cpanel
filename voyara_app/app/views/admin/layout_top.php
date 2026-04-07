<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Admin') ?> — Voyara Admin</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-wrapper">
  <!-- SIDEBAR -->
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">Voy<span>ara</span> <small>Admin</small></div>
    <nav class="sidebar-nav">
      <?php
      $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      $navItems = [
        ['/admin/dashboard', 'bi-speedometer2', 'Dashboard'],
        ['/admin/packages',  'bi-suitcase',      'Packages'],
        ['/admin/bookings',  'bi-calendar-check','Bookings'],
        ['/admin/users',     'bi-people',        'Users'],
        ['/admin/agents',    'bi-person-badge',  'Agents'],
        ['/admin/reviews',   'bi-star',          'Reviews'],
        ['/admin/settings',  'bi-gear',          'Settings'],
      ];
      foreach ($navItems as [$path, $icon, $label]):
        $active = str_starts_with($currentUri, $path) ? 'active' : '';
      ?>
      <a href="<?= APP_URL . $path ?>" class="sidebar-link <?= $active ?>">
        <i class="bi <?= $icon ?>"></i><span><?= $label ?></span>
      </a>
      <?php endforeach; ?>
    </nav>
    <div class="sidebar-footer">
      <a href="<?= APP_URL ?>/admin/logout" class="sidebar-link text-danger">
        <i class="bi bi-box-arrow-left"></i><span>Logout</span>
      </a>
    </div>
  </aside>

  <!-- MAIN -->
  <div class="admin-main">
    <header class="admin-topbar">
      <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
      <div class="topbar-title"><?= e($pageTitle ?? 'Dashboard') ?></div>
      <div class="topbar-right">
        <span class="small text-muted">
          <i class="bi bi-person-circle me-1"></i><?= e(Session::get('admin_name','Admin')) ?>
        </span>
      </div>
    </header>

    <div class="admin-content">
      <?php
      $flashError   = Session::getFlash('error');
      $flashSuccess = Session::getFlash('success');
      if ($error   ?? $flashError):   ?>
      <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i><?= e($error ?? $flashError) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php endif;
      if ($success ?? $flashSuccess): ?>
      <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i><?= e($success ?? $flashSuccess) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php endif; ?>
