<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle   = 'Home';
$navLocations = PackageModel::locations();
include VIEW_PATH . '/partials/header.php';
?>

<!-- HERO -->
<section class="hero-section">
  <div class="hero-bg"></div>
  <div class="hero-overlay"></div>
  <div class="container hero-content">
    <p class="hero-eyebrow">Curated Journeys Worldwide</p>
    <h1 class="hero-title">Where Will Your<br><em>Story Begin?</em></h1>
    <p class="hero-subtitle">Bespoke travel experiences crafted for those who seek wonder in every corner of the world.</p>

    <!-- SEARCH BAR -->
    <form action="<?= APP_URL ?>/packages" method="GET" class="hero-search-bar">
      <div class="search-group">
        <label>Destination</label>
        <select name="location" class="form-select">
          <option value="">Any Destination</option>
          <?php foreach ($locations as $loc): ?>
          <option value="<?= e($loc['slug']) ?>"><?= e($loc['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="search-group">
        <label>Category</label>
        <select name="category" class="form-select">
          <option value="">All Types</option>
          <?php foreach ($categories as $cat): ?>
          <option value="<?= e($cat['slug']) ?>"><?= e($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="search-group">
        <label>Duration</label>
        <select name="max_days" class="form-select">
          <option value="">Any Duration</option>
          <option value="3">Up to 3 days</option>
          <option value="7">Up to 7 days</option>
          <option value="14">Up to 14 days</option>
          <option value="30">Up to 30 days</option>
        </select>
      </div>
      <button type="submit" class="btn btn-gold search-submit">
        <i class="bi bi-search me-2"></i>Search
      </button>
    </form>
  </div>
</section>

<!-- CATEGORIES STRIP -->
<section class="py-5 bg-cream">
  <div class="container">
    <div class="d-flex gap-3 flex-wrap justify-content-center">
      <?php foreach ($categories as $cat): ?>
      <a href="<?= APP_URL ?>/packages?category=<?= e($cat['slug']) ?>" class="cat-pill">
        <i class="bi <?= e($cat['icon'] ?? 'bi-compass') ?>"></i>
        <?= e($cat['name']) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- FEATURED PACKAGES -->
<section class="py-6">
  <div class="container">
    <div class="section-header">
      <div>
        <p class="section-label">Handpicked for You</p>
        <h2 class="section-title">Featured <em>Packages</em></h2>
      </div>
      <a href="<?= APP_URL ?>/packages" class="btn btn-outline-gold">View All</a>
    </div>

    <div class="row g-4">
      <?php foreach ($featured as $pkg): ?>
      <div class="col-md-6 col-lg-4">
        <?php include VIEW_PATH . '/partials/package_card.php'; ?>
      </div>
      <?php endforeach; ?>
      <?php if (empty($featured)): ?>
      <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-compass fs-1 d-block mb-3 opacity-25"></i>
        <p>No featured packages yet. Check back soon!</p>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- WHY VOYARA -->
<section class="py-6 bg-ink text-white">
  <div class="container">
    <div class="row align-items-center g-5">
      <div class="col-lg-5">
        <p class="section-label text-gold">Why Choose Us</p>
        <h2 class="section-title text-white">Travel with <em class="text-gold-light">Confidence</em></h2>
        <p class="text-white-50 mt-3">We've been crafting unforgettable journeys since 2012. Every package is designed with care, every experience is vetted, and every traveller is treated like family.</p>
      </div>
      <div class="col-lg-7">
        <div class="row g-4">
          <?php $features = [
            ['bi-shield-check','Verified Quality','Every destination and hotel is personally reviewed by our travel experts.'],
            ['bi-headset','24/7 Support','Round-the-clock assistance wherever you are in the world.'],
            ['bi-wallet2','Transparent Pricing','No hidden fees. What you see is exactly what you pay.'],
            ['bi-star','Expert Guides','Local guides who bring destinations to life with authentic stories.'],
          ]; foreach ($features as [$icon, $title, $desc]): ?>
          <div class="col-sm-6">
            <div class="why-card">
              <i class="bi <?= $icon ?> fs-2 text-gold mb-3 d-block"></i>
              <h5 class="fw-500 mb-2"><?= $title ?></h5>
              <p class="small text-white-50 mb-0"><?= $desc ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<section class="py-5 bg-cream">
  <div class="container">
    <div class="row g-4 text-center">
      <?php $stats = [['98K+','Happy Travelers'],['140+','Destinations'],['4.97','Average Rating'],['12yr','Of Excellence']];
      foreach ($stats as [$num,$label]): ?>
      <div class="col-6 col-md-3">
        <div class="stat-number"><?= $num ?></div>
        <div class="stat-label"><?= $label ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="py-6">
  <div class="container">
    <div class="cta-block text-center">
      <p class="section-label">Ready to Explore?</p>
      <h2 class="section-title mb-3">Your Dream Journey <em>Awaits</em></h2>
      <p class="text-muted mb-4">Browse our curated packages and take the first step toward your next adventure.</p>
      <a href="<?= APP_URL ?>/packages" class="btn btn-gold btn-lg me-2">Explore Packages</a>
      <?php if (!Auth::check()): ?>
      <a href="<?= APP_URL ?>/register" class="btn btn-outline-gold btn-lg">Create Account</a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
