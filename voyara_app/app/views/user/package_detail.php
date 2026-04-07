<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle    = $package['title'];
$metaDesc     = $package['short_desc'] ?? '';
$navLocations = PackageModel::locations();
include VIEW_PATH . '/partials/header.php';
$currency = setting('currency_symbol','$');
?>

<!-- PAGE HERO -->
<div class="detail-hero" style="background-image:url('<?= $package['cover_image'] ? e(UPLOAD_URL.'/packages/'.$package['cover_image']) : ASSETS_URL.'/images/placeholder.jpg' ?>')">
  <div class="detail-hero-overlay">
    <div class="container h-100 d-flex flex-column justify-content-end pb-4">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-dark">
          <li class="breadcrumb-item"><a href="<?= APP_URL ?>/">Home</a></li>
          <li class="breadcrumb-item"><a href="<?= APP_URL ?>/packages">Packages</a></li>
          <li class="breadcrumb-item active"><?= e($package['title']) ?></li>
        </ol>
      </nav>
      <span class="badge-category"><?= e($package['category_name']) ?></span>
      <h1 class="detail-title"><?= e($package['title']) ?></h1>
      <div class="d-flex flex-wrap gap-3 align-items-center mt-2">
        <span class="detail-meta"><i class="bi bi-geo-alt-fill me-1"></i><?= e($package['location_name']) ?>, <?= e($package['country']) ?></span>
        <span class="detail-meta"><i class="bi bi-clock me-1"></i><?= (int)$package['duration_days'] ?> Days</span>
        <?php if ((float)$package['avg_rating'] > 0): ?>
        <span class="detail-meta">
          <i class="bi bi-star-fill text-gold me-1"></i><?= number_format((float)$package['avg_rating'],1) ?>
          (<?= (int)$package['review_count'] ?> reviews)
        </span>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<div class="container py-5">
  <div class="row g-5">

    <!-- MAIN CONTENT -->
    <div class="col-lg-8">

      <!-- IMAGE GALLERY -->
      <?php if (!empty($package['images'])): ?>
      <div class="gallery-grid mb-5">
        <?php foreach (array_slice($package['images'],0,4) as $i => $img): ?>
        <div class="gallery-item <?= $i===0 ? 'gallery-item-main' : '' ?>">
          <img src="<?= e(UPLOAD_URL.'/packages/'.$img['image_path']) ?>" alt="Gallery" loading="lazy">
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- DESCRIPTION -->
      <div class="section-block">
        <h3 class="block-title">About This Package</h3>
        <div class="prose"><?= nl2br(e($package['description'])) ?></div>
      </div>

      <!-- ITINERARY -->
      <?php if (!empty($package['itineraries'])): ?>
      <div class="section-block">
        <h3 class="block-title">Day-by-Day Itinerary</h3>
        <div class="itinerary-list">
          <?php foreach ($package['itineraries'] as $day): ?>
          <div class="itinerary-item">
            <div class="itinerary-day">Day <?= (int)$day['day_number'] ?></div>
            <div class="itinerary-content">
              <h5 class="itinerary-title"><?= e($day['title']) ?></h5>
              <p class="itinerary-desc"><?= nl2br(e($day['description'])) ?></p>
              <div class="d-flex flex-wrap gap-3 mt-2">
                <?php if ($day['meals']): ?>
                <span class="itinerary-tag"><i class="bi bi-egg-fried me-1"></i><?= e($day['meals']) ?></span>
                <?php endif; ?>
                <?php if ($day['accommodation']): ?>
                <span class="itinerary-tag"><i class="bi bi-building me-1"></i><?= e($day['accommodation']) ?></span>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- INCLUSIONS / EXCLUSIONS -->
      <?php if (!empty($package['inclusions']) || !empty($package['exclusions'])): ?>
      <div class="section-block">
        <div class="row g-4">
          <?php if (!empty($package['inclusions'])): ?>
          <div class="col-md-6">
            <h5 class="text-success mb-3"><i class="bi bi-check-circle me-2"></i>What's Included</h5>
            <ul class="inclusion-list">
              <?php foreach ($package['inclusions'] as $inc): ?>
              <li><i class="bi bi-check text-success me-2"></i><?= e($inc['item']) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>
          <?php if (!empty($package['exclusions'])): ?>
          <div class="col-md-6">
            <h5 class="text-danger mb-3"><i class="bi bi-x-circle me-2"></i>What's Excluded</h5>
            <ul class="inclusion-list">
              <?php foreach ($package['exclusions'] as $exc): ?>
              <li><i class="bi bi-x text-danger me-2"></i><?= e($exc['item']) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>

      <!-- REVIEWS -->
      <div class="section-block">
        <h3 class="block-title">Traveler Reviews</h3>
        <?php if (empty($reviews)): ?>
        <p class="text-muted">No reviews yet. Be the first!</p>
        <?php else: ?>
        <?php foreach ($reviews as $r): ?>
        <div class="review-item">
          <div class="review-header">
            <div class="review-avatar">
              <?= strtoupper(substr($r['user_name'],0,1)) ?>
            </div>
            <div>
              <div class="fw-500"><?= e($r['user_name']) ?></div>
              <div class="small text-muted"><?= date('M Y', strtotime($r['created_at'])) ?></div>
            </div>
            <div class="ms-auto">
              <?php for ($i=1;$i<=5;$i++): ?>
              <i class="bi bi-star<?= $i <= (int)$r['rating'] ? '-fill' : '' ?> text-gold"></i>
              <?php endfor; ?>
            </div>
          </div>
          <?php if ($r['title']): ?><h6 class="mt-2 mb-1"><?= e($r['title']) ?></h6><?php endif; ?>
          <p class="text-muted mb-0"><?= nl2br(e($r['body'])) ?></p>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <!-- WRITE A REVIEW -->
        <?php if ($canReview): ?>
        <div class="review-form-block mt-4">
          <h5>Write a Review</h5>
          <form method="POST" action="<?= APP_URL ?>/reviews">
            <?= CSRF::field() ?>
            <input type="hidden" name="booking_id" value="<?= (int)$canReview ?>">
            <div class="mb-3">
              <label class="form-label">Rating</label>
              <div class="star-picker" id="starPicker">
                <?php for ($i=1;$i<=5;$i++): ?>
                <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" required>
                <label for="star<?= $i ?>"><i class="bi bi-star-fill"></i></label>
                <?php endfor; ?>
              </div>
            </div>
            <div class="mb-3">
              <input type="text" name="title" class="form-control" placeholder="Review title (optional)" maxlength="200">
            </div>
            <div class="mb-3">
              <textarea name="body" class="form-control" rows="4" placeholder="Share your experience..." required minlength="10"></textarea>
            </div>
            <button class="btn btn-gold">Submit Review</button>
          </form>
        </div>
        <?php endif; ?>
      </div>

    </div><!-- /col-lg-8 -->

    <!-- BOOKING SIDEBAR -->
    <div class="col-lg-4">
      <div class="booking-sidebar sticky-top">
        <div class="booking-price-block">
          <span class="text-muted small">From</span>
          <div class="booking-price"><?= $currency ?><?= number_format($package['price'],0) ?></div>
          <span class="text-muted small">per <?= e($package['price_per']) ?></span>
        </div>

        <?php if (Auth::check()): ?>
        <form method="POST" action="<?= APP_URL ?>/book" class="mt-3">
          <?= CSRF::field() ?>
          <input type="hidden" name="package_id" value="<?= (int)$package['id'] ?>">
          <div class="mb-3">
            <label class="form-label">Travel Date</label>
            <input type="date" name="travel_date" class="form-control"
                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Number of Persons</label>
            <input type="number" name="persons" class="form-control"
                   min="1" max="<?= (int)($package['max_persons'] ?: 99) ?>"
                   value="1" required
                   data-price="<?= (float)$package['price'] ?>"
                   data-price-per="<?= e($package['price_per']) ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Special Requests</label>
            <textarea name="special_requests" class="form-control" rows="3" placeholder="Any dietary requirements, preferences..."></textarea>
          </div>
          <div class="booking-total-row">
            <span>Estimated Total</span>
            <strong id="estimatedTotal"><?= $currency ?><?= number_format($package['price'],0) ?></strong>
          </div>
          <button type="submit" class="btn btn-gold w-100 mt-3 btn-lg">Book Now</button>
        </form>
        <?php else: ?>
        <div class="text-center mt-3">
          <a href="<?= APP_URL ?>/login" class="btn btn-gold w-100 btn-lg">Login to Book</a>
          <p class="small text-muted mt-2">Don't have an account? <a href="<?= APP_URL ?>/register">Register</a></p>
        </div>
        <?php endif; ?>

        <div class="booking-sidebar-footer">
          <p class="small text-muted mb-1"><i class="bi bi-shield-check me-1 text-success"></i>Secure booking</p>
          <p class="small text-muted mb-1"><i class="bi bi-arrow-counterclockwise me-1 text-gold"></i>Free cancellation on pending bookings</p>
          <p class="small text-muted mb-0"><i class="bi bi-bank me-1 text-gold"></i>Bank transfer accepted</p>
        </div>
      </div>
    </div>

  </div><!-- /row -->

  <!-- RELATED PACKAGES -->
  <?php if (!empty($related)): ?>
  <div class="mt-5 pt-4 border-top">
    <h3 class="section-title mb-4">Similar <em>Packages</em></h3>
    <div class="row g-4">
      <?php foreach ($related as $pkg): ?>
      <?php if ($pkg['id'] != $package['id']): ?>
      <div class="col-md-4">
        <?php include VIEW_PATH . '/partials/package_card.php'; ?>
      </div>
      <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</div>

<script>

const personsInput = document.querySelector('input[name="persons"]');
const totalEl      = document.getElementById('estimatedTotal');
if (personsInput && totalEl) {
  personsInput.addEventListener('input', function () {
    const price   = parseFloat(this.dataset.price) || 0;
    const pricePer = this.dataset.pricePer;
    const persons = parseInt(this.value) || 1;
    const total   = pricePer === 'person' ? price * persons : price;
    totalEl.textContent = '<?= $currency ?>' + total.toLocaleString();
  });
}
</script>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
