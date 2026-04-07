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
<title>Agent Login — Voyara</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= ASSETS_URL ?>/css/admin.css">
</head>
<body class="admin-login-body">
<div class="admin-login-wrap">
  <div class="admin-login-card">
    <div class="admin-login-brand">Voy<span>ara</span> <small>Agent</small></div>
    <h4 class="mb-1">Agent Sign In</h4>
    <p class="text-muted small mb-4">Manage your assigned bookings</p>
    <?php if ($error ?? null): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="<?= APP_URL ?>/agent/login">
      <?= CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-admin-primary w-100">Sign In</button>
    </form>
    <div class="mt-3 text-center"><a href="<?= APP_URL ?>/" class="small text-muted">← Back to Website</a></div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
