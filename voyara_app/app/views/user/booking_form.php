<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle    = 'Book: ' . e($package['title']);
$navLocations = PackageModel::locations();
include VIEW_PATH . '/partials/header.php';
$currency = setting('currency_symbol', '$');
?>

<div class="page-header">
  <div class="container">
    <h1 class="page-header-title">Book Your <em>Journey</em></h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= APP_URL ?>/">Home</a></li>
        <li class="breadcrumb-item"><a href="<?= APP_URL ?>/packages">Packages</a></li>
        <li class="breadcrumb-item active"><?= e($package['title']) ?></li>
      </ol>
    </nav>
  </div>
</div>

<div class="container py-5">
  <?php include VIEW_PATH . '/partials/flash.php'; ?>

  <div class="row g-5 justify-content-center">

    <!-- BOOKING FORM -->
    <div class="col-lg-7">
      <div class="dash-card">
        <div class="dash-card-header">
          <h5 class="mb-0"><i class="bi bi-calendar-check me-2"></i>Booking Details</h5>
        </div>
        <div class="p-4">
          <form method="POST" action="<?= APP_URL ?>/book">
            <?= CSRF::field() ?>
            <input type="hidden" name="package_id" value="<?= (int)$package['id'] ?>">

            <div class="mb-4">
              <label class="form-label fw-500">Travel Date <span class="text-danger">*</span></label>
              <input type="date" name="travel_date" class="form-control form-control-lg"
                     min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
              <div class="form-text">Select your preferred departure date.</div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-500">Number of Persons <span class="text-danger">*</span></label>
              <input type="number" name="persons" class="form-control form-control-lg"
                     id="personsInput"
                     min="1"
                     max="<?= (int)($package['max_persons'] ?: 99) ?>"
                     value="1" required
                     data-price="<?= (float)$package['price'] ?>"
                     data-price-per="<?= e($package['price_per']) ?>">
              <?php if ($package['max_persons']): ?>
              <div class="form-text">Maximum <?= (int)$package['max_persons'] ?> persons.</div>
              <?php endif; ?>
            </div>

            <div class="mb-4">
              <label class="form-label fw-500">Special Requests</label>
              <textarea name="special_requests" class="form-control" rows="4"
                        placeholder="Dietary requirements, accessibility needs, preferences, celebrations..."></textarea>
              <div class="form-text">Optional — we'll do our best to accommodate.</div>
            </div>

            <!-- PRICE SUMMARY -->
            <div class="p-4 bg-cream rounded mb-4">
              <h6 class="mb-3">Price Summary</h6>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Package Rate</span>
                <span><?= $currency ?><?= number_format($package['price'], 2) ?> / <?= e($package['price_per']) ?></span>
              </div>
              <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Persons</span>
                <span id="summaryPersons">1</span>
              </div>
              <hr>
              <div class="d-flex justify-content-between fw-600 fs-5">
                <span>Estimated Total</span>
                <span id="summaryTotal" class="text-gold">
                  <?= $currency ?><?= number_format($package['price'], 0) ?>
                </span>
              </div>
              <p class="small text-muted mt-2 mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Final price confirmed after booking review. Payment via bank transfer.
              </p>
            </div>

            <button type="submit" class="btn btn-gold btn-lg w-100">
              <i class="bi bi-check-circle me-2"></i>Submit Booking Request
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- PACKAGE SUMMARY SIDEBAR -->
    <div class="col-lg-4">
      <div class="booking-sidebar">
        <?php if ($package['cover_image']): ?>
        <img src="<?= UPLOAD_URL ?>/packages/<?= e($package['cover_image']) ?>"
             class="img-fluid rounded mb-3" alt="<?= e($package['title']) ?>">
        <?php endif; ?>

        <h5 class="mb-1"><?= e($package['title']) ?></h5>
        <p class="small text-muted mb-3">
          <i class="bi bi-geo-alt-fill text-gold me-1"></i>
          <?= e($package['location_name'] ?? '') ?>
        </p>

        <div class="d-flex gap-3 mb-3">
          <div class="text-center">
            <div class="fw-600"><?= (int)$package['duration_days'] ?></div>
            <div class="small text-muted">Days</div>
          </div>
          <?php if ($package['max_persons']): ?>
          <div class="text-center">
            <div class="fw-600"><?= (int)$package['max_persons'] ?></div>
            <div class="small text-muted">Max Persons</div>
          </div>
          <?php endif; ?>
          <div class="text-center">
            <div class="fw-600 text-gold"><?= $currency ?><?= number_format($package['price'], 0) ?></div>
            <div class="small text-muted">Per <?= e($package['price_per']) ?></div>
          </div>
        </div>

        <hr>

        <div class="booking-sidebar-footer">
          <p class="small text-muted mb-1">
            <i class="bi bi-shield-check me-1 text-success"></i>Secure booking — no payment now
          </p>
          <p class="small text-muted mb-1">
            <i class="bi bi-bank me-1 text-gold"></i>Bank transfer after confirmation
          </p>
          <p class="small text-muted mb-0">
            <i class="bi bi-arrow-counterclockwise me-1 text-gold"></i>Free cancellation while pending
          </p>
        </div>

        <a href="<?= APP_URL ?>/packages/<?= e($package['slug']) ?>" class="btn btn-outline-secondary w-100 mt-3 btn-sm">
          ← Back to Package
        </a>
      </div>
    </div>

  </div>
</div>

<script>
(function () {
  const input    = document.getElementById('personsInput');
  const totalEl  = document.getElementById('summaryTotal');
  const personsEl= document.getElementById('summaryPersons');
  const price    = <?= (float)$package['price'] ?>;
  const pricePer = '<?= e($package['price_per']) ?>';
  const currency = '<?= e($currency) ?>';

  function update() {
    const n     = parseInt(input.value) || 1;
    const total = pricePer === 'person' ? price * n : price;
    personsEl.textContent = n;
    totalEl.textContent   = currency + total.toLocaleString('en-US', {maximumFractionDigits: 0});
  }

  if (input) input.addEventListener('input', update);
})();
</script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
