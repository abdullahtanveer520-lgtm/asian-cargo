<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageTitle = 'About Us';
$activePage = 'about';
include __DIR__ . '/includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="/">Home</a> / About Us</p>
    <h1>About <?= e(setting('site_name', 'Asian Cargo')) ?></h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="why-grid">
      <div>
        <span class="eyebrow">Our Story</span>
        <h2 class="section-title">A trusted name in Pakistani logistics</h2>
        <p style="font-size:1.02rem;">
          For over <?= e(setting('years_experience', '15')) ?> years, <?= e(setting('site_name', 'Asian Cargo')) ?> has
          been helping businesses and individuals move goods reliably — across the country and around the world.
          What started as a small freight desk has grown into a full logistics network spanning air, ocean,
          road and express courier services.
        </p>
        <p>
          We built our reputation on doing the unglamorous part of logistics well: tracking every shipment closely,
          handling customs paperwork properly the first time, and picking up the phone when a customer calls.
          That focus on reliability is still what drives us today.
        </p>
        <div class="why-list" style="margin-top:8px;">
          <div class="why-item">
            <div class="why-num">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <div>
              <h4>Our Mission</h4>
              <p>To make global shipping simple, transparent and dependable for every customer, regardless of shipment size.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-num">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <div>
              <h4>Our Vision</h4>
              <p>To be Pakistan's most trusted freight and courier partner, known for service that businesses can build on.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="why-visual" style="min-height:420px;">
        <div class="why-visual-content">
          <span class="why-badge">Since <?= 2026 - (int) e(setting('years_experience', '15')) ?></span>
          <h3 style="color:white; font-size:1.5rem;">Built on reliability, not just reach</h3>
          <p style="color:rgba(255,255,255,0.65);">
            <?= number_format((int) setting('shipments_delivered', '50000')) ?>+ shipments delivered across
            <?= e(setting('countries_served', '120')) ?>+ countries for <?= number_format((int) setting('happy_clients', '8000')) ?>+ clients.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section" style="background:var(--white); border-top:1px solid var(--slate-200);">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">Our Values</span>
      <h2 class="section-title">What guides how we work</h2>
    </div>
    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
        <h3>Reliability</h3>
        <p>We do what we say we'll do — on the dates we commit to.</p>
      </div>
      <div class="service-card">
        <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></div>
        <h3>Transparency</h3>
        <p>Clear pricing and honest timelines — no surprise charges.</p>
      </div>
      <div class="service-card">
        <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg></div>
        <h3>Speed</h3>
        <p>Efficient processes mean your cargo doesn't sit around waiting.</p>
      </div>
      <div class="service-card">
        <div class="service-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <h3>Customer Care</h3>
        <p>A real team you can call — not just a tracking number.</p>
      </div>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="cta-band">
      <div>
        <h2>Want to work with us?</h2>
        <p>Get in touch and let's talk about your shipping needs.</p>
      </div>
      <div class="cta-actions">
        <a href="/quote.php" class="btn btn-primary">Get a Free Quote</a>
        <a href="/contact.php" class="btn btn-outline">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
