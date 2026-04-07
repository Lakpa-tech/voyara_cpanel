<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = 'Settings';
include VIEW_PATH . '/admin/layout_top.php';
?>

<ul class="nav nav-tabs mb-4">
  <li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? 'settings') === 'settings' ? 'active' : '' ?>"
       href="<?= APP_URL ?>/admin/settings?tab=settings">
      <i class="bi bi-gear me-1"></i>Site Settings
    </a>
  </li>
  <li class="nav-item">
    <a class="nav-link <?= ($_GET['tab'] ?? '') === 'info' ? 'active' : '' ?>"
       href="<?= APP_URL ?>/admin/settings?tab=info">
      <i class="bi bi-info-circle me-1"></i>System Info
    </a>
  </li>
</ul>

<?php if (($_GET['tab'] ?? 'settings') === 'settings'): ?>

<div class="admin-card" style="max-width:700px">
  <div class="admin-card-header"><h5>Site Settings</h5></div>
  <div class="p-4">
    <form method="POST" action="<?= APP_URL ?>/admin/settings">
      <?= CSRF::field() ?>

      <p class="form-section-title">General</p>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label">Site Name</label>
          <input type="text" name="site_name" class="form-control" value="<?= e($settings['site_name'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Email</label>
          <input type="email" name="site_email" class="form-control" value="<?= e($settings['site_email'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Contact Phone</label>
          <input type="text" name="site_phone" class="form-control" value="<?= e($settings['site_phone'] ?? '') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Currency Symbol</label>
          <input type="text" name="currency_symbol" class="form-control" value="<?= e($settings['currency_symbol'] ?? '$') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label">Currency Code</label>
          <input type="text" name="currency_code" class="form-control" value="<?= e($settings['currency_code'] ?? 'USD') ?>">
        </div>
      </div>

      <p class="form-section-title">Bank Transfer Details</p>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <label class="form-label">Bank Name</label>
          <input type="text" name="bank_name" class="form-control" value="<?= e($settings['bank_name'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Account Name</label>
          <input type="text" name="bank_account_name" class="form-control" value="<?= e($settings['bank_account_name'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Account Number</label>
          <input type="text" name="bank_account" class="form-control" value="<?= e($settings['bank_account'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Routing Number</label>
          <input type="text" name="bank_routing" class="form-control" value="<?= e($settings['bank_routing'] ?? '') ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">SWIFT Code</label>
          <input type="text" name="bank_swift" class="form-control" value="<?= e($settings['bank_swift'] ?? '') ?>">
        </div>
      </div>

      <button type="submit" class="btn btn-admin-primary">
        <i class="bi bi-save me-1"></i>Save Settings
      </button>
    </form>
  </div>
</div>

<?php else: ?>

<div class="row g-4" style="max-width:860px">

  <div class="col-12">
    <div class="admin-card overflow-hidden">
      <div class="admin-card-header">
        <h5><i class="bi bi-person-badge me-2"></i>System Information</h5>
      </div>

      <div class="info-author-banner">
        <div class="info-author-logo">Voy<span>ara</span></div>
        <div class="info-author-meta">
          <div class="info-author-name">Kiran Khadka</div>
          <div class="info-author-role">Developer &amp; Owner</div>
        </div>
        <div class="ms-auto info-version-badge">v1.0.0</div>
      </div>

      <div class="p-4">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="info-field">
              <div class="info-field-icon"><i class="bi bi-person-fill"></i></div>
              <div>
                <div class="info-field-label">Author</div>
                <div class="info-field-value">Kiran Khadka</div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-field">
              <div class="info-field-icon"><i class="bi bi-patch-check-fill"></i></div>
              <div>
                <div class="info-field-label">Version</div>
                <div class="info-field-value">1.0.0 <span class="info-edition">First Edition</span></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-field">
              <div class="info-field-icon"><i class="bi bi-telephone-fill"></i></div>
              <div>
                <div class="info-field-label">Contact</div>
                <div class="info-field-value"><a href="tel:+9779869756622">+977-9869756622</a></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="info-field">
              <div class="info-field-icon"><i class="bi bi-envelope-fill"></i></div>
              <div>
                <div class="info-field-label">Email</div>
                <div class="info-field-value"><a href="mailto:therealkiranda@gmail.com">therealkiranda@gmail.com</a></div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="info-field">
              <div class="info-field-icon"><i class="bi bi-c-circle-fill"></i></div>
              <div>
                <div class="info-field-label">Copyright</div>
                <div class="info-field-value">© 2026 Kiran Khadka. All rights reserved.</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="info-tech-bar">
        <span class="info-tech-label">Built with</span>
        <span class="info-tech-pill"><i class="bi bi-filetype-php"></i> PHP 8.2</span>
        <span class="info-tech-pill"><i class="bi bi-database"></i> MySQL 8</span>
        <span class="info-tech-pill"><i class="bi bi-bootstrap"></i> Bootstrap 5</span>
        <span class="info-tech-pill"><i class="bi bi-server"></i> Apache / AWS</span>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="admin-card h-100">
      <div class="admin-card-header"><h5><i class="bi bi-bar-chart me-2"></i>Live Stats</h5></div>
      <div class="p-4">
        <?php $liveStats = [
          ['Total Packages',  DB::fetchColumn('SELECT COUNT(*) FROM packages')],
          ['Active Packages', DB::fetchColumn("SELECT COUNT(*) FROM packages WHERE is_active=1")],
          ['Total Users',     DB::fetchColumn('SELECT COUNT(*) FROM users')],
          ['Total Bookings',  DB::fetchColumn('SELECT COUNT(*) FROM bookings')],
          ['Total Reviews',   DB::fetchColumn('SELECT COUNT(*) FROM reviews')],
          ['Total Agents',    DB::fetchColumn('SELECT COUNT(*) FROM agents')],
        ];
        foreach ($liveStats as [$label, $val]): ?>
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
          <span class="text-muted small"><?= $label ?></span>
          <strong><?= number_format((int)$val) ?></strong>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="admin-card h-100">
      <div class="admin-card-header"><h5><i class="bi bi-layers me-2"></i>Build Details</h5></div>
      <div class="p-4">
        <?php $buildInfo = [
          ['Application',  'Voyara Travel Booking System'],
          ['Edition',      'First Edition'],
          ['Architecture', 'MVC — Plain PHP, No Framework'],
          ['Database',     'MySQL 8 via PDO'],
          ['Frontend',     'Bootstrap 5 + Custom CSS'],
          ['Security',     'CSRF, bcrypt, PDO Prepared Stmts'],
          ['Environment',  APP_ENV],
          ['PHP Version',  PHP_VERSION],
        ];
        foreach ($buildInfo as [$label, $val]): ?>
        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
          <span class="text-muted small"><?= $label ?></span>
          <span class="small fw-500"><?= e($val) ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>

<style>
.info-author-banner{display:flex;align-items:center;gap:20px;background:linear-gradient(135deg,#111614 0%,#1e2820 100%);padding:28px;border-radius:0}
.info-author-logo{font-family:Georgia,serif;font-size:28px;font-weight:300;color:#fff;letter-spacing:.1em;white-space:nowrap}
.info-author-logo span{color:#c49a3c;font-style:italic}
.info-author-name{color:#fff;font-size:18px;font-weight:500}
.info-author-role{color:rgba(255,255,255,.45);font-size:12px;letter-spacing:.08em;text-transform:uppercase;margin-top:2px}
.info-version-badge{background:rgba(196,154,60,.18);color:#c49a3c;border:1px solid rgba(196,154,60,.35);padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;letter-spacing:.06em;white-space:nowrap}
.info-field{display:flex;align-items:flex-start;gap:14px;background:#f8f9fa;border-radius:8px;padding:14px 16px;height:100%}
.info-field-icon{width:36px;height:36px;border-radius:8px;background:rgba(196,154,60,.12);color:#c49a3c;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0}
.info-field-label{font-size:10px;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:rgba(14,13,11,.4);margin-bottom:3px}
.info-field-value{font-size:14px;font-weight:500;color:#0e0d0b}
.info-field-value a{color:#2a6b6e;text-decoration:none}
.info-field-value a:hover{text-decoration:underline}
.info-edition{font-size:11px;background:#d1e7dd;color:#155724;padding:2px 8px;border-radius:10px;margin-left:6px}
.info-tech-bar{display:flex;flex-wrap:wrap;align-items:center;gap:10px;padding:16px 24px;background:#f8f9fa;border-top:1px solid #e4e6ea}
.info-tech-label{font-size:11px;font-weight:600;letter-spacing:.12em;text-transform:uppercase;color:rgba(14,13,11,.4);margin-right:4px}
.info-tech-pill{display:inline-flex;align-items:center;gap:6px;background:#fff;border:1px solid #e4e6ea;padding:5px 12px;border-radius:20px;font-size:12px;font-weight:500;color:#444}
.nav-tabs .nav-link{color:rgba(14,13,11,.55);border:none;border-bottom:2px solid transparent;border-radius:0;padding:10px 18px;font-size:14px}
.nav-tabs .nav-link.active{color:#c49a3c;border-bottom-color:#c49a3c;background:transparent}
.nav-tabs{border-bottom:1px solid #e4e6ea}
</style>

<?php endif; ?>

<?php include VIEW_PATH . '/admin/layout_bottom.php'; ?>
