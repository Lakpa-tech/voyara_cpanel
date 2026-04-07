<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
$pageTitle = '404 Not Found'; include VIEW_PATH . '/partials/header.php'; ?>
<div class="container text-center py-5 my-5">
  <h1 class="display-1 fw-light text-gold">404</h1>
  <h2 class="mb-3">Page Not Found</h2>
  <p class="text-muted mb-4">The page you're looking for doesn't exist or has been moved.</p>
  <a href="<?= APP_URL ?>/" class="btn btn-gold">Back to Home</a>
</div>
<?php include VIEW_PATH . '/partials/footer.php'; ?>
