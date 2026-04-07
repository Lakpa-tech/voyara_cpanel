<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'My Bookings'; include VIEW_PATH . '/agent/layout_top.php'; ?>
<?php $currency = setting('currency_symbol','$'); ?>
<div class="admin-card">
  <div class="admin-card-header"><h5>Assigned Bookings <span class="badge bg-secondary"><?= $total ?></span></h5></div>
  <div class="table-responsive">
    <table class="table admin-table">
      <thead><tr><th>Ref</th><th>Customer</th><th>Package</th><th>Travel Date</th><th>Total</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
          <td><code><?= e($b['booking_ref']) ?></code></td>
          <td><?= e($b['user_name']) ?></td>
          <td><?= e(mb_strimwidth($b['package_title'],0,28,'…')) ?></td>
          <td><?= date('d M Y', strtotime($b['travel_date'])) ?></td>
          <td><?= $currency ?><?= number_format($b['total_price'],0) ?></td>
          <td><span class="badge-status badge-status-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
          <td><a href="<?= APP_URL ?>/agent/bookings/<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php include VIEW_PATH . '/agent/layout_bottom.php'; ?>
