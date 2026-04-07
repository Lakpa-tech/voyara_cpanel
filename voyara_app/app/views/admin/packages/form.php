<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = $package ? 'Edit Package' : 'New Package';
$isEdit    = $package !== null;
$action    = $isEdit ? APP_URL . '/admin/packages/' . (int)$package['id'] : APP_URL . '/admin/packages';
include VIEW_PATH . '/admin/layout_top.php';
?>

<form method="POST" action="<?= $action ?>" enctype="multipart/form-data" id="pkgForm">
  <?= CSRF::field() ?>

  <div class="row g-4">
    <!-- LEFT -->
    <div class="col-lg-8">

      <!-- BASIC INFO -->
      <div class="admin-card mb-4">
        <div class="admin-card-header"><h5>Basic Information</h5></div>
        <div class="p-4">
          <div class="mb-3">
            <label class="form-label">Package Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" required maxlength="250"
                   value="<?= e($package['title'] ?? '') ?>" placeholder="e.g. Magical Santorini 7-Day Journey">
          </div>
          <div class="mb-3">
            <label class="form-label">Short Description</label>
            <input type="text" name="short_desc" class="form-control" maxlength="400"
                   value="<?= e($package['short_desc'] ?? '') ?>" placeholder="Brief one-liner shown on cards">
          </div>
          <div class="mb-3">
            <label class="form-label">Full Description <span class="text-danger">*</span></label>
            <textarea name="description" class="form-control" rows="8" required><?= e($package['description'] ?? '') ?></textarea>
          </div>

          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Category <span class="text-danger">*</span></label>
              <select name="category_id" class="form-select" required>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= (int)$cat['id'] ?>" <?= (($package['category_id'] ?? 0) == $cat['id']) ? 'selected' : '' ?>>
                  <?= e($cat['name']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Location <span class="text-danger">*</span></label>
              <select name="location_id" class="form-select" required>
                <?php foreach ($locations as $loc): ?>
                <option value="<?= (int)$loc['id'] ?>" <?= (($package['location_id'] ?? 0) == $loc['id']) ? 'selected' : '' ?>>
                  <?= e($loc['name']) ?>, <?= e($loc['country']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Duration (days) <span class="text-danger">*</span></label>
              <input type="number" name="duration_days" class="form-control" required min="1"
                     value="<?= (int)($package['duration_days'] ?? 1) ?>">
            </div>
            <div class="col-md-4">
              <label class="form-label">Price <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><?= e(setting('currency_symbol','$')) ?></span>
                <input type="number" name="price" class="form-control" step="0.01" min="0" required
                       value="<?= number_format((float)($package['price'] ?? 0),2,'.','') ?>">
              </div>
            </div>
            <div class="col-md-4">
              <label class="form-label">Price Per</label>
              <select name="price_per" class="form-select">
                <option value="person" <?= (($package['price_per'] ?? 'person') === 'person') ? 'selected' : '' ?>>Person</option>
                <option value="group"  <?= (($package['price_per'] ?? '') === 'group') ? 'selected' : '' ?>>Group</option>
              </select>
            </div>
            <div class="col-md-4">
              <label class="form-label">Max Persons</label>
              <input type="number" name="max_persons" class="form-control" min="1"
                     value="<?= (int)($package['max_persons'] ?? '') ?: '' ?>" placeholder="Leave blank for unlimited">
            </div>
          </div>
        </div>
      </div>

      <!-- ITINERARY -->
      <div class="admin-card mb-4">
        <div class="admin-card-header">
          <h5>Day-by-Day Itinerary</h5>
          <button type="button" class="btn btn-sm btn-outline-secondary" id="addDayBtn">
            <i class="bi bi-plus"></i> Add Day
          </button>
        </div>
        <div class="p-4" id="itineraryContainer">
          <?php $itin = $package['itineraries'] ?? []; ?>
          <?php if (empty($itin)): $itin = [['day_number'=>1,'title'=>'','description'=>'','meals'=>'','accommodation'=>'']]; endif; ?>
          <?php foreach ($itin as $i => $day): ?>
          <div class="itinerary-row mb-4 p-3 border rounded" data-index="<?= $i ?>">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <strong class="day-label">Day <?= (int)$day['day_number'] ?></strong>
              <?php if ($i > 0): ?>
              <button type="button" class="btn btn-sm btn-outline-danger remove-day"><i class="bi bi-trash"></i></button>
              <?php endif; ?>
            </div>
            <div class="mb-2">
              <input type="text" name="itinerary_title[]" class="form-control" placeholder="Day title"
                     value="<?= e($day['title']) ?>" required>
            </div>
            <div class="mb-2">
              <textarea name="itinerary_desc[]" class="form-control" rows="3" placeholder="What happens this day?"><?= e($day['description']) ?></textarea>
            </div>
            <div class="row g-2">
              <div class="col-md-6">
                <input type="text" name="itinerary_meals[]" class="form-control form-control-sm"
                       placeholder="Meals (e.g. breakfast, lunch)" value="<?= e($day['meals'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <input type="text" name="itinerary_hotel[]" class="form-control form-control-sm"
                       placeholder="Accommodation" value="<?= e($day['accommodation'] ?? '') ?>">
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- INCLUSIONS / EXCLUSIONS -->
      <div class="admin-card mb-4">
        <div class="admin-card-header"><h5>Inclusions & Exclusions</h5></div>
        <div class="p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label text-success fw-500">Inclusions (one per line)</label>
              <textarea name="inclusions" class="form-control" rows="6"
                        placeholder="Accommodation&#10;Daily breakfast&#10;Airport transfer"><?php
                if (!empty($package['inclusions'])) {
                  echo e(implode("\n", array_column($package['inclusions'], 'item')));
                }
              ?></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label text-danger fw-500">Exclusions (one per line)</label>
              <textarea name="exclusions" class="form-control" rows="6"
                        placeholder="Flights&#10;Travel insurance&#10;Personal expenses"><?php
                if (!empty($package['exclusions'])) {
                  echo e(implode("\n", array_column($package['exclusions'], 'item')));
                }
              ?></textarea>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /col-lg-8 -->

    <!-- RIGHT SIDEBAR -->
    <div class="col-lg-4">

      <!-- PUBLISH -->
      <div class="admin-card mb-4">
        <div class="admin-card-header"><h5>Publish</h5></div>
        <div class="p-4">
          <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" id="isActive"
                   <?= ($package['is_active'] ?? 1) ? 'checked' : '' ?>>
            <label class="form-check-label" for="isActive">Active (visible to users)</label>
          </div>
          <div class="form-check form-switch mb-4">
            <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured"
                   <?= ($package['is_featured'] ?? 0) ? 'checked' : '' ?>>
            <label class="form-check-label" for="isFeatured">Featured on homepage</label>
          </div>
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-admin-primary">
              <i class="bi bi-save me-1"></i><?= $isEdit ? 'Update Package' : 'Create Package' ?>
            </button>
            <a href="<?= APP_URL ?>/admin/packages" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </div>
      </div>

      <!-- COVER IMAGE -->
      <div class="admin-card mb-4">
        <div class="admin-card-header"><h5>Cover Image</h5></div>
        <div class="p-4">
          <?php if ($isEdit && $package['cover_image']): ?>
          <img src="<?= UPLOAD_URL ?>/packages/<?= e($package['cover_image']) ?>"
               class="img-fluid rounded mb-3" alt="">
          <?php endif; ?>
          <input type="file" name="cover_image" class="form-control" accept="image/*">
          <div class="form-text">JPG, PNG, WEBP. Max 5MB.</div>
        </div>
      </div>

      <!-- GALLERY -->
      <div class="admin-card mb-4">
        <div class="admin-card-header"><h5>Gallery Images</h5></div>
        <div class="p-4">
          <?php if ($isEdit && !empty($package['images'])): ?>
          <div class="gallery-admin-grid mb-3">
            <?php foreach ($package['images'] as $img): ?>
            <div class="gallery-admin-item">
              <img src="<?= UPLOAD_URL ?>/packages/<?= e($img['image_path']) ?>" alt="">
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
          <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
          <div class="form-text">Select multiple images. Max 5MB each.</div>
        </div>
      </div>

    </div><!-- /col-lg-4 -->
  </div><!-- /row -->
</form>

<script>

let dayCount = <?= count($itin ?? [1]) ?>;
document.getElementById('addDayBtn').addEventListener('click', function() {
  dayCount++;
  const container = document.getElementById('itineraryContainer');
  const div = document.createElement('div');
  div.className = 'itinerary-row mb-4 p-3 border rounded';
  div.innerHTML = `
    <div class="d-flex justify-content-between align-items-center mb-2">
      <strong>Day ${dayCount}</strong>
      <button type="button" class="btn btn-sm btn-outline-danger remove-day"><i class="bi bi-trash"></i></button>
    </div>
    <div class="mb-2"><input type="text" name="itinerary_title[]" class="form-control" placeholder="Day title" required></div>
    <div class="mb-2"><textarea name="itinerary_desc[]" class="form-control" rows="3" placeholder="What happens this day?"></textarea></div>
    <div class="row g-2">
      <div class="col-md-6"><input type="text" name="itinerary_meals[]" class="form-control form-control-sm" placeholder="Meals"></div>
      <div class="col-md-6"><input type="text" name="itinerary_hotel[]" class="form-control form-control-sm" placeholder="Accommodation"></div>
    </div>`;
  container.appendChild(div);
  div.querySelector('.remove-day').addEventListener('click', function() { div.remove(); });
});
document.querySelectorAll('.remove-day').forEach(btn => {
  btn.addEventListener('click', function() { this.closest('.itinerary-row').remove(); });
});
</script>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
