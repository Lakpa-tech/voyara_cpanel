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
<title><?= e($pageTitle ?? 'Voyara Travel') ?> — <?= e(setting('site_name','Voyara')) ?></title>
<meta name="description" content="<?= e($metaDesc ?? 'Discover the world with Voyara Travel — curated journeys, expert guides.') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/main.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg voy-nav" id="mainNav">
  <div class="container">
    <a class="navbar-brand voy-brand" href="<?= APP_URL ?>/">
      Voy<span>ara</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <i class="bi bi-list"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav mx-auto gap-1">
        <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/packages">Packages</a></li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Destinations</a>
          <ul class="dropdown-menu">
            <?php foreach ($navLocations ?? [] as $loc): ?>
            <li><a class="dropdown-item" href="<?= APP_URL ?>/packages?location=<?= e($loc['slug']) ?>"><?= e($loc['name']) ?>, <?= e($loc['country']) ?></a></li>
            <?php endforeach; ?>
          </ul>
        </li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <?php if (Auth::check()): ?>
          <a href="<?= APP_URL ?>/dashboard" class="btn btn-outline-gold btn-sm">
            <i class="bi bi-person-circle me-1"></i><?= e(Session::get('user_name')) ?>
          </a>
          <a href="<?= APP_URL ?>/logout" class="btn btn-gold btn-sm">Logout</a>
        <?php else: ?>
          <a href="<?= APP_URL ?>/login"    class="btn btn-outline-gold btn-sm">Login</a>
          <a href="<?= APP_URL ?>/register" class="btn btn-gold btn-sm">Register</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
