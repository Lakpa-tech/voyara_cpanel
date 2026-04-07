<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Login';
include VIEW_PATH . '/partials/header.php';
?>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">Voy<span>ara</span></div>
    <h2 class="auth-title">Welcome Back</h2>
    <p class="auth-sub">Sign in to manage your bookings</p>
    <?php include VIEW_PATH . '/partials/flash.php'; ?>
    <form method="POST" action="<?= APP_URL ?>/login">
      <?= CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com" required autofocus>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn btn-gold w-100">Sign In</button>
      <p class="text-center text-muted small mt-3">
        Don't have an account? <a href="<?= APP_URL ?>/register">Register</a>
      </p>
    </form>
  </div>
</div>
<?php include VIEW_PATH . '/partials/footer.php'; ?>
