<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
?>
<div class="package-card">
  <?php
  $imgSrc = $pkg['cover_image']
    ? UPLOAD_URL . '/packages/' . $pkg['cover_image']
    : ASSETS_URL . '/images/placeholder.jpg';
  $stars   = round((float)($pkg['avg_rating'] ?? 0));
  $rating  = number_format((float)($pkg['avg_rating'] ?? 0), 1);
  $reviews = (int)($pkg['review_count'] ?? 0);
  ?>
  <a href="<?= APP_URL ?>/packages/<?= e($pkg['slug']) ?>" class="card-img-link">
    <img src="<?= e($imgSrc) ?>"
         alt="<?= e($pkg['title']) ?>"
         class="card-cover-img"
         loading="lazy">
    <span class="card-badge"><?= e($pkg['category_name'] ?? '') ?></span>
    <?php if ($pkg['is_featured']): ?>
    <span class="card-badge card-badge-gold" style="right:12px;left:auto">Featured</span>
    <?php endif; ?>
  </a>
  <div class="card-body-custom">
    <div class="card-location">
      <i class="bi bi-geo-alt-fill me-1 text-gold"></i>
      <?= e($pkg['location_name']) ?>, <?= e($pkg['country']) ?>
    </div>
    <h3 class="card-title-custom">
      <a href="<?= APP_URL ?>/packages/<?= e($pkg['slug']) ?>"><?= e($pkg['title']) ?></a>
    </h3>
    <?php if ($reviews > 0): ?>
    <div class="card-rating">
      <?php for ($i=1;$i<=5;$i++): ?>
      <i class="bi bi-star<?= $i <= $stars ? '-fill' : '' ?> text-gold"></i>
      <?php endfor; ?>
      <span class="ms-1 small text-muted"><?= $rating ?> (<?= $reviews ?>)</span>
    </div>
    <?php endif; ?>
    <div class="card-meta">
      <span><i class="bi bi-clock me-1"></i><?= (int)$pkg['duration_days'] ?> days</span>
      <?php if ($pkg['max_persons']): ?>
      <span><i class="bi bi-people me-1"></i>Max <?= (int)$pkg['max_persons'] ?></span>
      <?php endif; ?>
    </div>
    <div class="card-footer-custom">
      <div class="card-price">
        <span class="price-from">From</span>
        <span class="price-amount"><?= e(setting('currency_symbol','$')) ?><?= number_format($pkg['price'],0) ?></span>
        <span class="price-per">/ <?= e($pkg['price_per']) ?></span>
      </div>
      <a href="<?= APP_URL ?>/packages/<?= e($pkg['slug']) ?>" class="btn btn-gold btn-sm">View</a>
    </div>
  </div>
</div>
