<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Agents'; include VIEW_PATH . '/admin/layout_top.php'; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Travel Agents</h5>
    <a href="<?= APP_URL ?>/admin/agents/create" class="btn btn-admin-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>New Agent
    </a>
  </div>
  <div class="table-responsive">
    <table class="table admin-table">
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Last Login</th></tr></thead>
      <tbody>
        <?php foreach ($agents as $a): ?>
        <tr>
          <td class="fw-500"><?= e($a['name']) ?></td>
          <td><?= e($a['email']) ?></td>
          <td><?= e($a['phone'] ?: '—') ?></td>
          <td><?= $a['is_active'] ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' ?></td>
          <td><?= $a['last_login'] ? date('d M Y H:i', strtotime($a['last_login'])) : '—' ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($agents)): ?>
        <tr><td colspan="5" class="text-center text-muted py-3">No agents yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
