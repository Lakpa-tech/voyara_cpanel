<?php
/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */
?>
<footer class="voy-footer mt-auto">
  <div class="container">
    <div class="row g-4 py-5">
      <div class="col-lg-4">
        <div class="voy-brand fs-3 mb-3">Voy<span>ara</span></div>
        <p class="text-muted small mb-3">Crafting extraordinary journeys for those who believe travel is the most beautiful form of self-discovery.</p>
        <div class="d-flex gap-2">
          <a href="#" class="footer-social"><i class="bi bi-instagram"></i></a>
          <a href="#" class="footer-social"><i class="bi bi-facebook"></i></a>
          <a href="#" class="footer-social"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="footer-social"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading">Explore</h6>
        <ul class="footer-links">
          <li><a href="<?= APP_URL ?>/packages">All Packages</a></li>
          <li><a href="<?= APP_URL ?>/packages?category=adventure">Adventure</a></li>
          <li><a href="<?= APP_URL ?>/packages?category=beach">Beach</a></li>
          <li><a href="<?= APP_URL ?>/packages?category=honeymoon">Honeymoon</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading">Company</h6>
        <ul class="footer-links">
          <li><a href="#">About Us</a></li>
          <li><a href="#">Our Story</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Press</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading">Support</h6>
        <ul class="footer-links">
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Contact Us</a></li>
          <li><a href="#">Cancellations</a></li>
          <li><a href="#">Travel Insurance</a></li>
        </ul>
      </div>
      <div class="col-6 col-lg-2">
        <h6 class="footer-heading">Contact</h6>
        <ul class="footer-links">
          <li><i class="bi bi-envelope me-1"></i><?= e(setting('site_email','info@voyara.com')) ?></li>
          <li><i class="bi bi-telephone me-1"></i><?= e(setting('site_phone','+1 800 VOYARA')) ?></li>
        </ul>
      </div>
    </div>
    <hr class="footer-divider">
    <div class="d-flex flex-wrap justify-content-between align-items-center py-3 gap-2">
      <p class="small text-muted mb-0">&copy; <?= date('Y') ?> <?= e(setting('site_name','Voyara Travel')) ?>. All rights reserved.</p>
      <div class="d-flex gap-3">
        <a href="#" class="small text-muted text-decoration-none">Privacy Policy</a>
        <a href="#" class="small text-muted text-decoration-none">Terms of Service</a>
        <a href="#" class="small text-muted text-decoration-none">Cookie Policy</a>
      </div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= ASSETS_URL ?>/js/main.js"></script>
</body>
</html>
