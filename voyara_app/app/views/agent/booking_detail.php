<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Booking ' . $booking['booking_ref']; include VIEW_PATH . '/agent/layout_top.php'; ?>
<?php $currency = setting('currency_symbol','$'); ?>

<div class="mb-3"><a href="<?= APP_URL ?>/agent/bookings" class="btn btn-outline-secondary btn-sm">← Back</a></div>

<div class="row g-4">
  <div class="col-lg-8">
    <div class="admin-card mb-4">
      <div class="admin-card-header"><h5>Booking Details</h5></div>
      <div class="p-4">
        <div class="row g-3">
          <?php $details = [
            ['Ref',        '<code>'.$booking['booking_ref'].'</code>'],
            ['Customer',   e($booking['user_name'])],
            ['Email',      '<a href="mailto:'.e($booking['user_email']).'">'.e($booking['user_email']).'</a>'],
            ['Package',    e($booking['package_title'])],
            ['Travel Date',date('d M Y', strtotime($booking['travel_date']))],
            ['Persons',    (int)$booking['persons']],
            ['Total',      $currency . number_format($booking['total_price'],2)],
            ['Payment',    ucfirst($booking['payment_status'] ?? 'pending')],
          ];
          foreach ($details as [$l,$v]): ?>
          <div class="col-sm-6">
            <div class="detail-field">
              <span class="detail-field-label"><?= $l ?></span>
              <span class="detail-field-val"><?= $v ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php if ($booking['special_requests']): ?>
        <div class="mt-3 p-3 bg-light rounded small"><?= nl2br(e($booking['special_requests'])) ?></div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="admin-card p-4">
      <h6>Update Status</h6>
      <span class="badge-status badge-status-<?= $booking['status'] ?> mb-3 d-inline-block">
        <?= ucfirst($booking['status']) ?>
      </span>
      <form method="POST" action="<?= APP_URL ?>/agent/bookings/<?= (int)$booking['id'] ?>/status">
        <?= CSRF::field() ?>
        <div class="mb-3">
          <select name="status" class="form-select">
            <?php foreach (['confirmed','completed','cancelled'] as $s): ?>
            <option value="<?= $s ?>" <?= $booking['status']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <textarea name="agent_notes" class="form-control form-control-sm" rows="3" placeholder="Notes..."></textarea>
        </div>
        <button type="submit" class="btn btn-admin-primary w-100">Update</button>
      </form>
    </div>
  </div>
</div>

<?php include VIEW_PATH . '/agent/layout_bottom.php'; ?>
