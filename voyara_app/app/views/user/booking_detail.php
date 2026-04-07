<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle    = 'Booking ' . $booking['booking_ref'];
$navLocations = PackageModel::locations();
include VIEW_PATH . '/partials/header.php';
$currency = setting('currency_symbol','$');
?>
<div class="page-header">
  <div class="container">
    <h1 class="page-header-title">Booking <em><?= e($booking['booking_ref']) ?></em></h1>
  </div>
</div>

<div class="container py-5">
  <?php include VIEW_PATH . '/partials/flash.php'; ?>
  <div class="row g-4">
    <div class="col-lg-8">

      <!-- BOOKING SUMMARY -->
      <div class="dash-card mb-4">
        <div class="dash-card-header"><h5 class="mb-0">Booking Summary</h5></div>
        <div class="p-4">
          <div class="row g-3">
            <?php $details = [
              ['Package',     $booking['package_title']],
              ['Travel Date', date('d M Y', strtotime($booking['travel_date']))],
              ['Persons',     $booking['persons']],
              ['Total Price', $currency . number_format($booking['total_price'],2)],
              ['Status',      '<span class="badge-status badge-status-'.$booking['status'].'">'.ucfirst($booking['status']).'</span>'],
              ['Booked On',   date('d M Y H:i', strtotime($booking['created_at']))],
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
            <p class="mb-0 mt-1 small text-muted"><?= nl2br(e($booking['special_requests'])) ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- PAYMENT SECTION -->
      <div class="dash-card mb-4">
        <div class="dash-card-header"><h5 class="mb-0">Payment</h5></div>
        <div class="p-4">
          <p class="text-muted small">Payment Status:
            <span class="badge-status badge-status-<?= $booking['payment_status'] ?? 'pending' ?>">
              <?= ucfirst($booking['payment_status'] ?? 'pending') ?>
            </span>
          </p>

          <?php if (($booking['payment_status'] ?? '') === 'pending' && $booking['status'] === 'pending'): ?>
          <!-- BANK TRANSFER INSTRUCTIONS -->
          <div class="bank-info mb-4">
            <h6><i class="bi bi-bank me-2"></i>Bank Transfer Details</h6>
            <table class="table table-sm">
              <tr><td class="fw-500">Bank Name</td><td><?= e(setting('bank_name')) ?></td></tr>
              <tr><td class="fw-500">Account Name</td><td><?= e(setting('bank_account_name')) ?></td></tr>
              <tr><td class="fw-500">Account Number</td><td><?= e(setting('bank_account')) ?></td></tr>
              <tr><td class="fw-500">Routing Number</td><td><?= e(setting('bank_routing')) ?></td></tr>
              <tr><td class="fw-500">SWIFT Code</td><td><?= e(setting('bank_swift')) ?></td></tr>
              <tr><td class="fw-500">Amount to Pay</td><td class="fw-600 text-gold"><?= $currency ?><?= number_format($booking['total_price'],2) ?></td></tr>
              <tr><td class="fw-500">Reference</td><td><code><?= e($booking['booking_ref']) ?></code></td></tr>
            </table>
          </div>

          <!-- UPLOAD RECEIPT -->
          <form method="POST" action="<?= APP_URL ?>/bookings/<?= (int)$booking['id'] ?>/payment" enctype="multipart/form-data">
            <?= CSRF::field() ?>
            <h6>Upload Payment Proof</h6>
            <div class="mb-3">
              <label class="form-label">Transaction Reference <span class="text-danger">*</span></label>
              <input type="text" name="transaction_ref" class="form-control" required
                     placeholder="e.g. TXN123456789" value="<?= e($booking['transaction_ref'] ?? '') ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Receipt Screenshot / PDF</label>
              <input type="file" name="receipt" class="form-control" accept="image/*,application/pdf">
              <div class="form-text">Max 5MB. JPG, PNG, WEBP or PDF.</div>
            </div>
            <button type="submit" class="btn btn-gold">Submit Payment Proof</button>
          </form>
          <?php elseif ($booking['transaction_ref']): ?>
          <p class="mb-1"><strong>Transaction Ref:</strong> <?= e($booking['transaction_ref']) ?></p>
          <?php if ($booking['receipt_path']): ?>
          <a href="<?= UPLOAD_URL ?>/receipts/<?= e($booking['receipt_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
            <i class="bi bi-file-earmark me-1"></i>View Receipt
          </a>
          <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>

      <!-- CANCEL BUTTON -->
      <?php if ($booking['status'] === 'pending'): ?>
      <form method="POST" action="<?= APP_URL ?>/bookings/<?= (int)$booking['id'] ?>/cancel"
            onsubmit="return confirm('Cancel this booking?')">
        <?= CSRF::field() ?>
        <button type="submit" class="btn btn-outline-danger btn-sm">Cancel Booking</button>
      </form>
      <?php endif; ?>
    </div>

    <div class="col-lg-4">
      <div class="dash-card p-4">
        <a href="<?= APP_URL ?>/packages/<?= e(DB::fetchColumn('SELECT slug FROM packages WHERE id = ?', [$booking['package_id']])) ?>" class="d-block mb-3">
          <?php if ($booking['cover_image']): ?>
          <img src="<?= UPLOAD_URL ?>/packages/<?= e($booking['cover_image']) ?>" class="img-fluid rounded" alt="">
          <?php endif; ?>
        </a>
        <h5><?= e($booking['package_title']) ?></h5>
        <p class="text-muted small">Need help? Contact us at <?= e(setting('site_email')) ?></p>
        <a href="<?= APP_URL ?>/dashboard" class="btn btn-outline-secondary w-100">← Back to Dashboard</a>
      </div>
    </div>
  </div>
</div>
<?php include VIEW_PATH . '/partials/footer.php'; ?>
