<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'New Agent'; include VIEW_PATH . '/admin/layout_top.php'; ?>

<div class="admin-card" style="max-width:560px">
  <div class="admin-card-header"><h5>Create Agent Account</h5></div>
  <div class="p-4">
    <form method="POST" action="<?= APP_URL ?>/admin/agents">
      <?= CSRF::field() ?>
      <div class="mb-3">
        <label class="form-label">Full Name <span class="text-danger">*</span></label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Phone</label>
        <input type="tel" name="phone" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Password <span class="text-danger">*</span></label>
        <input type="password" name="password" class="form-control" required minlength="8">
      </div>
      <div class="mb-4">
        <label class="form-label">Bio</label>
        <textarea name="bio" class="form-control" rows="3" placeholder="Short agent bio..."></textarea>
      </div>
      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-admin-primary">Create Agent</button>
        <a href="<?= APP_URL ?>/admin/agents" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
