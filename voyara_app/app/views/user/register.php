<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Register';
include VIEW_PATH . '/partials/header.php';
?>
<div class="auth-page">
  <div class="auth-card">
    <div class="auth-logo">Voy<span>ara</span></div>
    <h2 class="auth-title">Create Account</h2>
    <p class="auth-sub">Join Voyara and start your journey</p>
    <?php include VIEW_PATH . '/partials/flash.php'; ?>
    <form method="POST" action="<?= APP_URL ?>/register">
      <?= CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" placeholder="Your name" required minlength="2" maxlength="100">
      </div>
      <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="you@example.com" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone (optional)</label>
        <input type="tel" name="phone" class="form-control" placeholder="+1 234 567 8900">
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Min. 8 characters" required minlength="8">
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirm" class="form-control" placeholder="Repeat password" required>
      </div>
      <button type="submit" class="btn btn-gold w-100">Create Account</button>
      <p class="text-center text-muted small mt-3">
        Already have an account? <a href="<?= APP_URL ?>/login">Sign in</a>
      </p>
    </form>
  </div>
</div>
<?php include VIEW_PATH . '/partials/footer.php'; ?>
