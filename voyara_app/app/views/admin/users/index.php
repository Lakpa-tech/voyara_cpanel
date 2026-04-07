<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Users'; include VIEW_PATH . '/admin/layout_top.php'; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>All Users <span class="badge bg-secondary ms-2"><?= number_format($total) ?></span></h5>
  </div>
  <div class="table-responsive">
    <table class="table admin-table">
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Joined</th><th>Actions</th></tr></thead>
      <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
          <td class="fw-500"><?= e($u['name']) ?></td>
          <td><?= e($u['email']) ?></td>
          <td><?= e($u['phone'] ?: '—') ?></td>
          <td><?= $u['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' ?></td>
          <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
          <td>
            <form method="POST" action="<?= APP_URL ?>/admin/users/<?= (int)$u['id'] ?>/toggle" class="d-inline">
              <?= CSRF::field() ?>
              <button class="btn btn-sm <?= $u['is_active'] ? 'btn-outline-warning' : 'btn-outline-success' ?>">
                <?= $u['is_active'] ? 'Disable' : 'Enable' ?>
              </button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
