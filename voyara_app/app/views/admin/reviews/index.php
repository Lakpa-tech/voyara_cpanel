<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Reviews'; include VIEW_PATH . '/admin/layout_top.php'; ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Reviews</h5>
    <div class="d-flex gap-2">
      <?php foreach (['pending','approved','rejected'] as $s): ?>
      <a href="?status=<?= $s ?>" class="btn btn-sm <?= $status===$s ? 'btn-admin-primary' : 'btn-outline-secondary' ?>">
        <?= ucfirst($s) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="p-4">
    <?php if (empty($reviews)): ?>
    <p class="text-muted text-center py-3">No <?= $status ?> reviews.</p>
    <?php endif; ?>
    <?php foreach ($reviews as $r): ?>
    <div class="review-admin-item">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
          <strong><?= e($r['user_name']) ?></strong>
          <span class="text-muted small ms-2">on <?= e($r['package_title']) ?></span>
          <div class="small text-muted"><?= date('d M Y', strtotime($r['created_at'])) ?></div>
        </div>
        <div class="text-gold">
          <?php for($i=1;$i<=5;$i++): ?>
          <i class="bi bi-star<?= $i<=(int)$r['rating'] ? '-fill' : '' ?>"></i>
          <?php endfor; ?>
        </div>
      </div>
      <?php if ($r['title']): ?><h6 class="mb-1"><?= e($r['title']) ?></h6><?php endif; ?>
      <p class="text-muted mb-3"><?= nl2br(e($r['body'])) ?></p>
      <?php if ($status === 'pending'): ?>
      <form method="POST" action="<?= APP_URL ?>/admin/reviews/<?= (int)$r['id'] ?>/moderate" class="d-flex gap-2">
        <?= CSRF::field() ?>
        <button name="status" value="approved" class="btn btn-sm btn-success">Approve</button>
        <button name="status" value="rejected" class="btn btn-sm btn-danger">Reject</button>
      </form>
      <?php else: ?>
      <span class="badge bg-<?= $status==='approved' ? 'success' : 'danger' ?>"><?= ucfirst($status) ?></span>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
