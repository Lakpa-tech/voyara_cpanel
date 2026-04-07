<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Packages'; include VIEW_PATH . '/admin/layout_top.php'; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>All Packages <span class="badge bg-secondary ms-2"><?= number_format($total) ?></span></h5>
    <a href="<?= APP_URL ?>/admin/packages/create" class="btn btn-admin-primary btn-sm">
      <i class="bi bi-plus-lg me-1"></i>New Package
    </a>
  </div>
  <div class="table-responsive">
    <table class="table admin-table">
      <thead>
        <tr><th>Cover</th><th>Title</th><th>Category</th><th>Location</th><th>Price</th><th>Days</th><th>Featured</th><th>Active</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php foreach ($packages as $pkg): ?>
        <tr>
          <td>
            <?php if ($pkg['cover_image']): ?>
            <img src="<?= UPLOAD_URL ?>/packages/<?= e($pkg['cover_image']) ?>" class="admin-thumb" alt="">
            <?php else: ?>
            <div class="admin-thumb-placeholder"><i class="bi bi-image"></i></div>
            <?php endif; ?>
          </td>
          <td class="fw-500"><?= e($pkg['title']) ?></td>
          <td><?= e($pkg['category_name']) ?></td>
          <td><?= e($pkg['location_name']) ?></td>
          <td><?= setting('currency_symbol','$') ?><?= number_format($pkg['price'],0) ?></td>
          <td><?= (int)$pkg['duration_days'] ?>d</td>
          <td><?= $pkg['is_featured'] ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-dash text-muted"></i>' ?></td>
          <td><?= $pkg['is_active']  ? '<i class="bi bi-check-circle-fill text-success"></i>' : '<i class="bi bi-x-circle-fill text-danger"></i>' ?></td>
          <td>
            <a href="<?= APP_URL ?>/packages/<?= e($pkg['slug']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="View"><i class="bi bi-eye"></i></a>
            <a href="<?= APP_URL ?>/admin/packages/<?= (int)$pkg['id'] ?>/edit" class="btn btn-sm btn-outline-primary" title="Edit"><i class="bi bi-pencil"></i></a>
            <form method="POST" action="<?= APP_URL ?>/admin/packages/<?= (int)$pkg['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Delete this package?')">
              <?= CSRF::field() ?>
              <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- PAGINATION -->
  <?php if ($total > 20): $pages = ceil($total/20); ?>
  <div class="p-3">
    <nav><ul class="pagination pagination-sm mb-0">
      <?php for($i=1;$i<=$pages;$i++): ?>
      <li class="page-item <?= $i===$page?'active':'' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
      <?php endfor; ?>
    </ul></nav>
  </div>
  <?php endif; ?>
</div>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
