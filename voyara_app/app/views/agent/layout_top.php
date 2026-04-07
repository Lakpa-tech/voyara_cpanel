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
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Agent') ?> — Voyara Agent</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/admin.css">
</head>
<body class="admin-body">
<div class="admin-wrapper">
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand" style="background:#2a6b6e">Voy<span>ara</span> <small>Agent</small></div>
    <nav class="sidebar-nav">
      <?php $cur = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>
      <a href="<?= APP_URL ?>/agent/dashboard" class="sidebar-link <?= str_starts_with($cur,'/agent/dashboard')?'active':'' ?>">
        <i class="bi bi-speedometer2"></i><span>Dashboard</span>
      </a>
      <a href="<?= APP_URL ?>/agent/bookings" class="sidebar-link <?= str_starts_with($cur,'/agent/bookings')?'active':'' ?>">
        <i class="bi bi-calendar-check"></i><span>My Bookings</span>
      </a>
    </nav>
    <div class="sidebar-footer">
      <a href="<?= APP_URL ?>/agent/logout" class="sidebar-link text-danger">
        <i class="bi bi-box-arrow-left"></i><span>Logout</span>
      </a>
    </div>
  </aside>
  <div class="admin-main">
    <header class="admin-topbar">
      <button class="sidebar-toggle" id="sidebarToggle"><i class="bi bi-list"></i></button>
      <div class="topbar-title"><?= e($pageTitle ?? '') ?></div>
      <div class="topbar-right">
        <span class="small text-muted"><i class="bi bi-person-badge me-1"></i><?= e(Session::get('agent_name','Agent')) ?></span>
      </div>
    </header>
    <div class="admin-content">
      <?php $fe = Session::getFlash('error'); $fs = Session::getFlash('success');
      if ($error ?? $fe): ?><div class="alert alert-danger"><?= e($error ?? $fe) ?></div><?php endif;
      if ($success ?? $fs): ?><div class="alert alert-success"><?= e($success ?? $fs) ?></div><?php endif; ?>
