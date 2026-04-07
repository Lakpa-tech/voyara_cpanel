<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Agent Dashboard'; include VIEW_PATH . '/agent/layout_top.php'; ?>
<?php $currency = setting('currency_symbol','$'); ?>

<div class="row g-3 mb-4">
  <?php foreach ([
    ['Total Assigned', $stats['total'],    'bi-calendar-check','#3d9194'],
    ['Pending',        $stats['pending'],  'bi-clock-history', '#e8a838'],
    ['Confirmed',      $stats['confirmed'],'bi-check-circle',  '#2d8a4e'],
  ] as [$label,$val,$icon,$color]): ?>
  <div class="col-md-4">
    <div class="admin-stat-card" style="border-top-color:<?= $color ?>">
      <div class="d-flex align-items-center justify-content-between">
        <div><div class="stat-big"><?= number_format((int)$val) ?></div><div class="stat-lbl-sm"><?= $label ?></div></div>
        <i class="bi <?= $icon ?> admin-stat-icon" style="color:<?= $color ?>"></i>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="admin-card">
  <div class="admin-card-header">
    <h5>Recent Bookings</h5>
    <a href="<?= APP_URL ?>/agent/bookings" class="btn btn-sm btn-outline-secondary">View All</a>
  </div>
  <div class="table-responsive">
    <table class="table admin-table">
      <thead><tr><th>Ref</th><th>Customer</th><th>Package</th><th>Travel Date</th><th>Total</th><th>Status</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
          <td><code><?= e($b['booking_ref']) ?></code></td>
          <td><?= e($b['user_name']) ?></td>
          <td><?= e(mb_strimwidth($b['package_title'],0,25,'…')) ?></td>
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
