/**
 * Author:  Kiran Khadka
 * Version: 1.0.0 (First edition)
 * Contact: +977-9869756622
 * Mail:    therealkiranda@gmail.com
 * © 2026 Kiran Khadka. All rights reserved.
 */

document.addEventListener('DOMContentLoaded', function () {

  const nav = document.getElementById('mainNav');
  if (nav) {
    const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 50);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  document.querySelectorAll('.alert').forEach(alert => {
    setTimeout(() => {
      const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
      if (bsAlert) bsAlert.close();
    }, 5000);
  });

  if ('IntersectionObserver' in window) {
    const imgs = document.querySelectorAll('img[loading="lazy"]');
    const obs  = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.src = e.target.dataset.src || e.target.src;
          obs.unobserve(e.target);
        }
      });
    }, { rootMargin: '200px' });
    imgs.forEach(img => obs.observe(img));
  }

  if ('IntersectionObserver' in window) {
    const cards = document.querySelectorAll('.package-card, .why-card, .stat-card');
    const revObs = new IntersectionObserver((entries) => {
      entries.forEach((e, i) => {
        if (e.isIntersecting) {
          setTimeout(() => e.target.classList.add('revealed'), i * 80);
          revObs.unobserve(e.target);
        }
      });
    }, { threshold: 0.1 });
    cards.forEach(c => { c.style.opacity = '0'; c.style.transform = 'translateY(16px)'; c.style.transition = 'opacity 0.5s, transform 0.5s'; revObs.observe(c); });
    document.querySelectorAll('.revealed').forEach(c => { c.style.opacity = '1'; c.style.transform = 'none'; });
  }

  document.addEventListener('animationend', () => {}, false);
  const style = document.createElement('style');
  style.textContent = '.revealed { opacity: 1 !important; transform: translateY(0) !important; }';
  document.head.appendChild(style);
});
