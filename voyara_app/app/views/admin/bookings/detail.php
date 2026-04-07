<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Booking ' . $booking['booking_ref']; include VIEW_PATH . '/admin/layout_top.php'; ?>
<?php $currency = setting('currency_symbol','$'); ?>

<div class="d-flex gap-2 mb-4">
  <a href="<?= APP_URL ?>/admin/bookings" class="btn btn-outline-secondary btn-sm">← All Bookings</a>
</div>

<div class="row g-4">
  <div class="col-lg-8">

    <!-- SUMMARY -->
    <div class="admin-card mb-4">
      <div class="admin-card-header"><h5>Booking Summary</h5></div>
      <div class="p-4">
        <div class="row g-3">
          <?php $details = [
            ['Booking Ref',   '<code>'.$booking['booking_ref'].'</code>'],
            ['Package',       e($booking['package_title'])],
            ['Customer',      e($booking['user_name'])],
            ['Email',         '<a href="mailto:'.e($booking['user_email']).'">'.e($booking['user_email']).'</a>'],
            ['Phone',         e($booking['user_phone'] ?: '—')],
            ['Travel Date',   date('d M Y', strtotime($booking['travel_date']))],
            ['Persons',       (int)$booking['persons']],
            ['Total Price',   $currency . number_format($booking['total_price'],2)],
            ['Agent',         e($booking['agent_name'] ?: 'Unassigned')],
            ['Booked On',     date('d M Y H:i', strtotime($booking['created_at']))],
          ]; foreach ($details as [$label,$val]): ?>
          <div class="col-sm-6">
            <div class="detail-field">
              <span class="detail-field-label"><?= $label ?></span>
              <span class="detail-field-val"><?= $val ?></span>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php if ($booking['special_requests']): ?>
        <div class="mt-3 p-3 bg-light rounded">
          <strong class="small">Special Requests:</strong>
          <p class="mb-0 small text-muted mt-1"><?= nl2br(e($booking['special_requests'])) ?></p>
        </div>
        <?php endif; ?>
        <?php if ($booking['admin_notes']): ?>
        <div class="mt-3 p-3 bg-warning-subtle rounded">
          <strong class="small">Admin Notes:</strong>
          <p class="mb-0 small mt-1"><?= nl2br(e($booking['admin_notes'])) ?></p>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- PAYMENT -->
    <div class="admin-card mb-4">
      <div class="admin-card-header"><h5>Payment Details</h5></div>
      <div class="p-4">
        <div class="row g-3 mb-3">
          <div class="col-sm-4">
            <div class="detail-field">
              <span class="detail-field-label">Amount</span>
              <span class="detail-field-val fw-600 text-gold"><?= $currency ?><?= number_format($booking['paid_amount'] ?? $booking['total_price'],2) ?></span>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="detail-field">
              <span class="detail-field-label">Payment Status</span>
              <span class="badge-status badge-status-<?= $booking['payment_status'] ?? 'pending' ?>">
                <?= ucfirst($booking['payment_status'] ?? 'pending') ?>
              </span>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="detail-field">
              <span class="detail-field-label">Transaction Ref</span>
              <span class="detail-field-val"><?= e($booking['transaction_ref'] ?: '—') ?></span>
            </div>
          </div>
        </div>

        <?php if ($booking['receipt_path']): ?>
        <div class="mb-3">
          <a href="<?= UPLOAD_URL ?>/receipts/<?= e($booking['receipt_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-file-earmark me-1"></i>View Receipt
          </a>
        </div>
        <?php endif; ?>

        <?php if (($booking['payment_status'] ?? '') === 'pending' && $booking['transaction_ref']): ?>
        <form method="POST" action="<?= APP_URL ?>/admin/bookings/<?= (int)$booking['id'] ?>/payment" class="d-flex gap-2">
          <?= CSRF::field() ?>
          <button name="payment_status" value="verified" class="btn btn-success btn-sm">
            <i class="bi bi-check-circle me-1"></i>Verify Payment
          </button>
          <button name="payment_status" value="rejected" class="btn btn-danger btn-sm"
                  onclick="return confirm('Reject this payment?')">
            <i class="bi bi-x-circle me-1"></i>Reject
          </button>
        </form>
        <?php endif; ?>
      </div>
    </div>

  </div><!-- /col-lg-8 -->

  <div class="col-lg-4">

    <!-- UPDATE STATUS -->
    <div class="admin-card mb-4">
      <div class="admin-card-header"><h5>Update Status</h5></div>
      <div class="p-4">
        <span class="badge-status badge-status-<?= $booking['status'] ?> mb-3 d-inline-block">
          Current: <?= ucfirst($booking['status']) ?>
        </span>
        <form method="POST" action="<?= APP_URL ?>/admin/bookings/<?= (int)$booking['id'] ?>/status">
          <?= CSRF::field() ?>
          <div class="mb-3">
            <select name="status" class="form-select">
              <?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?>
              <option value="<?= $s ?>" <?= $booking['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="mb-3">
            <textarea name="admin_notes" class="form-control form-control-sm" rows="3" placeholder="Admin notes (optional)"><?= e($booking['admin_notes'] ?? '') ?></textarea>
          </div>
          <button type="submit" class="btn btn-admin-primary w-100">Update Status</button>
        </form>
      </div>
    </div>

    <!-- ASSIGN AGENT -->
    <div class="admin-card mb-4">
      <div class="admin-card-header"><h5>Assign Agent</h5></div>
      <div class="p-4">
        <form method="POST" action="<?= APP_URL ?>/admin/bookings/<?= (int)$booking['id'] ?>/assign">
          <?= CSRF::field() ?>
          <div class="mb-3">
            <select name="agent_id" class="form-select">
              <option value="">— Select Agent —</option>
              <?php foreach ($agents as $ag): ?>
              <option value="<?= (int)$ag['id'] ?>" <?= $booking['agent_id'] == $ag['id'] ? 'selected' : '' ?>>
                <?= e($ag['name']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="btn btn-outline-secondary w-100">Assign</button>
        </form>
      </div>
    </div>

  </div>
</div>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
