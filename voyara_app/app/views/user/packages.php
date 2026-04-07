<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle    = 'Browse Packages';
$navLocations = PackageModel::locations();
include VIEW_PATH . '/partials/header.php';
?>

<!-- PAGE HEADER -->
<div class="page-header">
  <div class="container">
    <h1 class="page-header-title">Browse <em>Packages</em></h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= APP_URL ?>/">Home</a></li>
        <li class="breadcrumb-item active">Packages</li>
      </ol>
    </nav>
  </div>
</div>

<div class="container py-5">
  <div class="row g-4">

    <!-- SIDEBAR FILTERS -->
    <div class="col-lg-3">
      <form method="GET" action="<?= APP_URL ?>/packages" class="filter-sidebar">
        <div class="filter-header">
          <h5 class="mb-0"><i class="bi bi-sliders me-2"></i>Filters</h5>
          <a href="<?= APP_URL ?>/packages" class="small text-muted">Clear all</a>
        </div>

        <div class="filter-group">
          <label class="filter-label">Search</label>
          <input type="text" name="keyword" class="form-control" placeholder="Search packages..." value="<?= e($filters['keyword'] ?? '') ?>">
        </div>

        <div class="filter-group">
          <label class="filter-label">Category</label>
          <?php foreach ($categories as $cat): ?>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="category"
                   value="<?= e($cat['slug']) ?>" id="cat_<?= $cat['id'] ?>"
                   <?= (($filters['category'] ?? '') === $cat['slug']) ? 'checked' : '' ?>>
            <label class="form-check-label" for="cat_<?= $cat['id'] ?>"><?= e($cat['name']) ?></label>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="filter-group">
          <label class="filter-label">Destination</label>
          <select name="location" class="form-select form-select-sm">
            <option value="">All Destinations</option>
            <?php foreach ($locations as $loc): ?>
            <option value="<?= e($loc['slug']) ?>" <?= (($filters['location'] ?? '') === $loc['slug']) ? 'selected' : '' ?>>
              <?= e($loc['name']) ?>, <?= e($loc['country']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="filter-group">
          <label class="filter-label">Price Range (<?= e(setting('currency_symbol','$')) ?>)</label>
          <div class="row g-2">
            <div class="col-6">
              <input type="number" name="min_price" class="form-control form-control-sm"
                     placeholder="Min" value="<?= e($filters['min_price'] ?? '') ?>" min="0">
            </div>
            <div class="col-6">
              <input type="number" name="max_price" class="form-control form-control-sm"
                     placeholder="Max" value="<?= e($filters['max_price'] ?? '') ?>" min="0">
            </div>
          </div>
        </div>

        <div class="filter-group">
          <label class="filter-label">Duration (days)</label>
          <div class="row g-2">
            <div class="col-6">
              <input type="number" name="min_days" class="form-control form-control-sm"
                     placeholder="Min" value="<?= e($filters['min_days'] ?? '') ?>" min="1">
            </div>
            <div class="col-6">
              <input type="number" name="max_days" class="form-control form-control-sm"
                     placeholder="Max" value="<?= e($filters['max_days'] ?? '') ?>" min="1">
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-gold w-100">Apply Filters</button>
      </form>
    </div>

    <!-- PACKAGE GRID -->
    <div class="col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0"><?= number_format($total) ?> package<?= $total != 1 ? 's' : '' ?> found</p>
      </div>

      <?php if (empty($packages)): ?>
      <div class="text-center py-5">
        <i class="bi bi-compass fs-1 d-block mb-3 opacity-25"></i>
        <h5>No packages found</h5>
        <p class="text-muted">Try adjusting your filters or <a href="<?= APP_URL ?>/packages">browse all</a>.</p>
      </div>
      <?php else: ?>
      <div class="row g-4">
        <?php foreach ($packages as $pkg): ?>
        <div class="col-md-6 col-xl-4">
          <?php include VIEW_PATH . '/partials/package_card.php'; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- PAGINATION -->
      <?php if ($totalPages > 1): ?>
      <nav class="mt-5">
        <ul class="pagination justify-content-center">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= $i === $page ? 'active' : '' ?>">
            <a class="page-link" href="?<?= http_build_query(array_merge($filters, ['page' => $i])) ?>"><?= $i ?></a>
          </li>
          <?php endfor; ?>
        </ul>
      </nav>
      <?php endif; ?>
      <?php endif; ?>
    </div>

  </div>
</div>

<?php include VIEW_PATH . '/partials/footer.php'; ?>
