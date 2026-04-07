<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle    = 'My Dashboard';
$navLocations = PackageModel::locations();
include VIEW_PATH . '/partials/header.php';
$currency = setting('currency_symbol','$');
?>
<div class="page-header">
  <div class="container">
    <h1 class="page-header-title">My <em>Dashboard</em></h1>
  </div>
</div>

<div class="container py-5">
  <?php include VIEW_PATH . '/partials/flash.php'; ?>

  <div class="row g-4">
    <!-- SIDEBAR -->
    <div class="col-lg-3">
      <div class="dash-sidebar">
        <div class="dash-avatar"><?= strtoupper(substr($user['name'],0,1)) ?></div>
        <div class="fw-500 mt-2"><?= e($user['name']) ?></div>
        <div class="small text-muted"><?= e($user['email']) ?></div>
        <hr>
        <ul class="dash-nav">
          <li><a href="<?= APP_URL ?>/dashboard" class="active"><i class="bi bi-grid me-2"></i>Overview</a></li>
          <li><a href="<?= APP_URL ?>/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
          <li><a href="<?= APP_URL ?>/logout"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
        </ul>
      </div>
    </div>

    <!-- MAIN -->
    <div class="col-lg-9">
      <!-- STATS CARDS -->
      <?php
      $total     = count($bookings);
      $pending   = count(array_filter($bookings, fn($b) => $b['status']==='pending'));
      $confirmed = count(array_filter($bookings, fn($b) => $b['status']==='confirmed'));
      $completed = count(array_filter($bookings, fn($b) => $b['status']==='completed'));
      ?>
      <div class="row g-3 mb-4">
        <?php foreach ([
          ['Total Bookings', $total,     'bi-calendar-check', 'gold'],
          ['Pending',        $pending,   'bi-clock-history',  'warning'],
          ['Confirmed',      $confirmed, 'bi-check-circle',   'success'],
          ['Completed',      $completed, 'bi-trophy',         'info'],
        ] as [$label,$val,$icon,$color]): ?>
        <div class="col-6 col-md-3">
          <div class="stat-card stat-card-<?= $color ?>">
            <i class="bi <?= $icon ?> stat-icon"></i>
            <div class="stat-val"><?= $val ?></div>
            <div class="stat-lbl"><?= $label ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- BOOKINGS TABLE -->
      <div class="dash-card">
        <div class="dash-card-header">
          <h5 class="mb-0">My Bookings</h5>
          <a href="<?= APP_URL ?>/packages" class="btn btn-gold btn-sm">+ New Booking</a>
        </div>

        <?php if (empty($bookings)): ?>
        <div class="text-center py-5">
          <i class="bi bi-suitcase fs-1 d-block mb-3 opacity-25"></i>
          <p class="text-muted">No bookings yet.</p>
          <a href="<?= APP_URL ?>/packages" class="btn btn-gold">Explore Packages</a>
        </div>
        <?php else: ?>
        <div class="table-responsive">
          <table class="table voy-table">
            <thead>
              <tr>
                <th>Ref</th><th>Package</th><th>Travel Date</th>
                <th>Amount</th><th>Status</th><th>Payment</th><th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($bookings as $b): ?>
              <tr>
                <td><code><?= e($b['booking_ref']) ?></code></td>
                <td><?= e($b['package_title']) ?></td>
                <td><?= date('d M Y', strtotime($b['travel_date'])) ?></td>
                <td><?= $currency ?><?= number_format($b['total_price'],0) ?></td>
                <td><span class="badge-status badge-status-<?= $b['status'] ?>"><?= ucfirst($b['status']) ?></span></td>
                <td><span class="badge-status badge-status-<?= $b['payment_status'] ?? 'pending' ?>"><?= ucfirst($b['payment_status'] ?? 'pending') ?></span></td>
                <td>
                  <a href="<?= APP_URL ?>/bookings/<?= (int)$b['id'] ?>" class="btn btn-sm btn-outline-secondary">View</a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include VIEW_PATH . '/partials/footer.php'; ?>
