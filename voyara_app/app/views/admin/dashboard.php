<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Dashboard'; include VIEW_PATH . '/admin/layout_top.php'; ?>
<?php $currency = setting('currency_symbol','$'); ?>

<!-- STAT CARDS -->
<div class="row g-4 mb-4">
  <?php $cards = [
    ['Total Bookings', $stats['total'],     'bi-calendar-check','#3d9194'],
    ['Pending',        $stats['pending'],   'bi-clock-history', '#e8a838'],
    ['Confirmed',      $stats['confirmed'], 'bi-check-circle',  '#2d8a4e'],
    ['Revenue',        $currency . number_format($stats['revenue'],0), 'bi-currency-dollar','#c49a3c'],
    ['Users',          $stats['users'],     'bi-people',        '#6c63ff'],
    ['Packages',       $stats['packages'],  'bi-suitcase',      '#3d9194'],
    ['Pending Reviews',$stats['reviews'],   'bi-star-half',     '#e06030'],
  ];
  foreach ($cards as [$label,$val,$icon,$color]): ?>
  <div class="col-6 col-md-4 col-xl-3">
    <div class="admin-stat-card" style="border-top-color:<?= $color ?>">
      <div class="d-flex align-items-center justify-content-between">
        <div>
          <div class="stat-big"><?= is_numeric($val) ? number_format((int)$val) : $val ?></div>
          <div class="stat-lbl-sm"><?= $label ?></div>
        </div>
        <i class="bi <?= $icon ?> admin-stat-icon" style="color:<?= $color ?>"></i>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<div class="row g-4">
  <!-- RECENT BOOKINGS -->
  <div class="col-lg-8">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5>Recent Bookings</h5>
        <a href="<?= APP_URL ?>/admin/bookings" class="btn btn-sm btn-outline-secondary">View All</a>
      </div>
      <div class="table-responsive">
        <table class="table admin-table">
          <thead><tr><th>Ref</th><th>Customer</th><th>Package</th><th>Date</th><th>Amount</th><th>Status</th></tr></thead>
          <tbody>
            <?php foreach ($recentBookings as $b): ?>
            <tr>
              <td><a href="<?= APP_URL ?>/admin/bookings/<?= (int)$b['id'] ?>"><code><?= e($b['booking_ref']) ?></code></a></td>
              <td><?= e($b['user_name']) ?></td>
              <td><?= e(mb_strimwidth($b['package_title'],0,30,'…')) ?></td>
              <td><?= date('d M Y', strtotime($b['travel_date'])) ?></td>
              <td><?= $currency ?><?= number_format($b['total_price'],0) ?></td>
              <td><span class="badge-status badge-status-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- PENDING PAYMENTS -->
  <div class="col-lg-4">
    <div class="admin-card">
      <div class="admin-card-header">
        <h5>Pending Payments</h5>
      </div>
      <?php foreach ($pendingPayments as $b): ?>
      <a href="<?= APP_URL ?>/admin/bookings/<?= (int)$b['id'] ?>" class="pending-payment-item">
        <div>
          <div class="fw-500 small"><?= e($b['booking_ref']) ?></div>
          <div class="text-muted small"><?= e($b['user_name']) ?></div>
        </div>
        <div class="text-end">
          <div class="fw-600 text-gold"><?= $currency ?><?= number_format($b['total_price'],0) ?></div>
          <div class="small text-muted"><?= $b['payment_status'] ?? 'pending' ?></div>
        </div>
      </a>
      <?php endforeach; ?>
      <?php if (empty($pendingPayments)): ?>
      <p class="text-muted small text-center py-3">No pending payments</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
