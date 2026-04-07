<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle    = 'My Profile';
$navLocations = PackageModel::locations();
include VIEW_PATH . '/partials/header.php';
?>
<div class="page-header"><div class="container"><h1 class="page-header-title">My <em>Profile</em></h1></div></div>
<div class="container py-5">
  <?php include VIEW_PATH . '/partials/flash.php'; ?>
  <div class="row g-4">
    <div class="col-lg-3">
      <div class="dash-sidebar">
        <div class="dash-avatar"><?= strtoupper(substr($user['name'],0,1)) ?></div>
        <div class="fw-500 mt-2"><?= e($user['name']) ?></div>
        <div class="small text-muted"><?= e($user['email']) ?></div>
        <hr>
        <ul class="dash-nav">
          <li><a href="<?= APP_URL ?>/dashboard"><i class="bi bi-grid me-2"></i>Overview</a></li>
          <li><a href="<?= APP_URL ?>/profile" class="active"><i class="bi bi-person me-2"></i>Profile</a></li>
          <li><a href="<?= APP_URL ?>/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>
    <div class="col-lg-9">
      <!-- PROFILE FORM -->
      <div class="dash-card mb-4">
        <div class="dash-card-header"><h5>Personal Information</h5></div>
        <div class="p-4">
          <form method="POST" action="<?= APP_URL ?>/profile" enctype="multipart/form-data">
            <?= CSRF::field() ?>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required value="<?= e($user['name']) ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="tel" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled>
                <div class="form-text">Email cannot be changed.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label">Profile Photo</label>
                <input type="file" name="avatar" class="form-control" accept="image/*">
              </div>
            </div>
            <button type="submit" class="btn btn-gold mt-3">Save Changes</button>
          </form>
        </div>
      </div>

      <!-- PASSWORD FORM -->
      <div class="dash-card">
        <div class="dash-card-header"><h5>Change Password</h5></div>
        <div class="p-4">
          <form method="POST" action="<?= APP_URL ?>/profile/password">
            <?= CSRF::field() ?>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
              </div>
              <div class="col-md-4">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control" required minlength="8">
              </div>
              <div class="col-md-4">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control" required>
              </div>
            </div>
            <button type="submit" class="btn btn-outline-gold mt-3">Update Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include VIEW_PATH . '/partials/footer.php'; ?>
