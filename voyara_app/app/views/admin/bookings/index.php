<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Bookings'; include VIEW_PATH . '/admin/layout_top.php'; ?>
<?php $currency = setting('currency_symbol','$'); ?>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>All Bookings <span class="badge bg-secondary ms-2"><?= number_format($total) ?></span></h5>
  </div>

  <!-- FILTERS -->
  <div class="p-3 border-bottom">
    <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
      <input type="text" name="keyword" class="form-control form-control-sm" style="width:200px"
             placeholder="Search ref / name / package" value="<?= e($filters['keyword'] ?? '') ?>">
      <select name="status" class="form-select form-select-sm" style="width:150px">
        <option value="">All Statuses</option>
        <?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?>
        <option value="<?= $s ?>" <?= (($filters['status'] ?? '') === $s) ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-sm btn-admin-primary">Filter</button>
      <a href="<?= APP_URL ?>/admin/bookings" class="btn btn-sm btn-outline-secondary">Clear</a>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table admin-table">
      <thead>
        <tr><th>Ref</th><th>Customer</th><th>Package</th><th>Travel Date</th><th>Persons</th><th>Total</th><th>Status</th><th>Payment</th><th>Agent</th><th></th></tr>
      </thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
          <td><a href="<?= APP_URL ?>/admin/bookings/<?= (int)$b['id'] ?>"><code><?= e($b['booking_ref']) ?></code></a></td>
          <td><?= e($b['user_name']) ?></td>
          <td><?= e(mb_strimwidth($b['package_title'],0,25,'…')) ?></td>
          <td><?= date('d M Y', strtotime($b['travel_date'])) ?></td>
          <td><?= (int)$b['persons'] ?></td>
          <td><?= $currency ?><?= number_format($b['total_price'],0) ?></td>
          <td><span class="badge-status badge-status-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
          <td><span class="badge-status badge-status-<?= $b['payment_status'] ?? 'pending' ?>"><?= ucfirst($b['payment_status'] ?? '—') ?></span></td>
          <td><?= $b['agent_name'] ? e($b['agent_name']) : '<span class="text-muted small">—</span>' ?></td>
          <td><a href="<?= APP_URL ?>/admin/bookings/<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($bookings)): ?>
        <tr><td colspan="10" class="text-center text-muted py-4">No bookings found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($total > 20): $pages = ceil($total/20); ?>
  <div class="p-3">
    <nav><ul class="pagination pagination-sm mb-0">
      <?php for($i=1;$i<=$pages;$i++): ?>
      <li class="page-item <?= $i===$page?'active':'' ?>">
        <a class="page-link" href="?<?= http_build_query(array_merge($filters,['page'=>$i])) ?>"><?= $i ?></a>
      </li>
      <?php endfor; ?>
    </ul></nav>
  </div>
  <?php endif; ?>
</div>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
