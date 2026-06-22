<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-col">
        <div class="footer-logo">
          <span class="logo-mark">
            <svg viewBox="0 0 24 24" fill="none" stroke="#F2994A" stroke-width="2"><path d="M20 8l-8-5-8 5v8l8 5 8-5V8z"/><path d="M12 12l8-5M12 12v9M12 12L4 7"/></svg>
          </span>
          <?= e(setting('site_name', 'Asian Cargo')) ?>
        </div>
        <p style="font-size:0.88rem; max-width: 280px;">
          <?= e(setting('site_tagline', 'Your trusted partner in global logistics, delivering air, ocean and express freight across the world.')) ?>
        </p>
        <div class="footer-social" style="margin-top:18px;">
          <a href="<?= e(setting('facebook_url', '#')) ?>" aria-label="Facebook" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7H7.9V12h2.6V9.8c0-2.6 1.5-4 3.9-4 1.1 0 2.3.2 2.3.2v2.5h-1.3c-1.3 0-1.7.8-1.7 1.6V12h2.9l-.5 2.9h-2.4v7A10 10 0 0 0 22 12z"/></svg></a>
          <a href="<?= e(setting('instagram_url', '#')) ?>" aria-label="Instagram" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="0.5" fill="currentColor"/></svg></a>
          <a href="<?= e(setting('linkedin_url', '#')) ?>" aria-label="LinkedIn" target="_blank" rel="noopener"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-3.96 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.68-2.91V8.48z"/></svg></a>
        </div>
      </div>

      <div class="footer-col">
        <h5>Company</h5>
        <ul>
          <li><a href="/about.php">About Us</a></li>
          <li><a href="/branches.php">Our Branches</a></li>
          <li><a href="/contact.php">Contact Us</a></li>
          <li><a href="/admin/">Staff Login</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h5>Services</h5>
        <ul>
          <li><a href="/services.php#air">Air Freight</a></li>
          <li><a href="/services.php#ocean">Ocean Freight</a></li>
          <li><a href="/services.php#express">Express Courier</a></li>
          <li><a href="/services.php#road">Road Freight</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h5>Get In Touch</h5>
        <ul>
          <li><?= e(setting('contact_phone', '+92 42 1234 5678')) ?></li>
          <li><?= e(setting('contact_email', 'info@asiancargo.pk')) ?></li>
          <li><?= e(setting('office_hours', 'Mon - Sat: 9:00 AM - 7:00 PM')) ?></li>
          <li><a href="/quote.php" class="btn btn-primary btn-sm" style="margin-top:6px;">Get a Free Quote</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <span>&copy; <?= date('Y') ?> <?= e(setting('site_name', 'Asian Cargo')) ?>. All rights reserved.</span>
      <span>Designed for fast, reliable logistics across Pakistan &amp; the world.</span>
    </div>
  </div>
</footer>

<?php
$waNumber = setting('contact_whatsapp', '923001234567');
if ($waNumber): ?>
<a class="whatsapp-float" target="_blank" rel="noopener"
   href="https://wa.me/<?= e($waNumber) ?>?text=<?= rawurlencode('Hello Asian Cargo, I would like to ask about a shipment.') ?>"
   aria-label="Chat on WhatsApp">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.74.46 3.43 1.32 4.93L2 22l5.27-1.38a9.9 9.9 0 0 0 4.77 1.21h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.13-2.9-7-1.87-1.87-4.36-2.92-7.01-2.92zm0 18.13h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.13.82.84-3.05-.2-.31a8.18 8.18 0 0 1-1.26-4.35c0-4.54 3.7-8.24 8.25-8.24a8.2 8.2 0 0 1 5.83 2.42 8.18 8.18 0 0 1 2.41 5.82c0 4.55-3.7 8.22-8.24 8.22zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.17.24-.64.81-.78.97-.14.17-.29.19-.53.06-.25-.12-1.05-.39-2-1.23-.74-.66-1.24-1.47-1.39-1.72-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.15.16-.25.25-.42.08-.16.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.43h-.48c-.16 0-.43.06-.66.31-.22.25-.86.85-.86 2.07s.89 2.4 1.01 2.57c.12.16 1.75 2.67 4.24 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.67-1.18.21-.58.21-1.07.14-1.18-.06-.1-.23-.16-.48-.28z"/></svg>
</a>
<?php endif; ?>

<script>
  document.getElementById('navToggle')?.addEventListener('click', function () {
    const nav = document.getElementById('mainNav');
    const expanded = this.getAttribute('aria-expanded') === 'true';
    this.setAttribute('aria-expanded', String(!expanded));
    nav.classList.toggle('open');
  });
</script>
</body>
</html>
