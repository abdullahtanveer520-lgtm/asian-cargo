<?php
require_once __DIR__ . '/config/bootstrap.php';

$pageTitle = 'Home';
$activePage = 'home';

$branches = getDB()->query('SELECT * FROM branches ORDER BY is_head_office DESC, display_order ASC LIMIT 4')->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<section class="hero">
  <div class="container">
    <div class="hero-grid">
      <div>
        <span class="eyebrow hero-eyebrow">Air &middot; Ocean &middot; Express &middot; Road</span>
        <h1>Moving your cargo, <span>anywhere in the world</span></h1>
        <p class="hero-sub">
          <?= e(setting('site_name', 'Asian Cargo')) ?> handles freight forwarding, customs clearance and
          door-to-door delivery for businesses and individuals across Pakistan — connected to over
          <?= e(setting('countries_served', '120')) ?> countries.
        </p>
        <div class="hero-ctas">
          <a href="/quote.php" class="btn btn-primary">Get a Free Quote</a>
          <a href="/services.php" class="btn btn-outline">Explore Services</a>
        </div>

        <div class="route-line" aria-hidden="true">
          <svg viewBox="0 0 520 64">
            <line x1="20" y1="32" x2="500" y2="32" stroke="rgba(255,255,255,0.18)" stroke-width="2"/>
            <line class="route-dash" x1="20" y1="32" x2="500" y2="32" stroke="#F2994A" stroke-width="2"/>
            <circle cx="20" cy="32" r="6" fill="#F2994A"/>
            <circle cx="500" cy="32" r="6" fill="#F2994A"/>
            <g class="route-plane" transform="translate(250,32)">
              <path d="M-14,-2 L10,-2 L16,0 L10,2 L-14,2 L-18,6 L-22,6 L-19,0 L-22,-6 L-18,-6 Z" fill="#FFFFFF"/>
            </g>
          </svg>
        </div>
      </div>

      <div class="track-card">
        <h3>Track Your Shipment</h3>
        <p class="track-card-sub">Enter your tracking number to see real-time status.</p>
        <form action="/track.php" method="get" class="track-form">
          <input type="text" name="tracking_number" placeholder="e.g. AC2026LHE0001" required
                 pattern="[A-Za-z0-9\-]{6,30}" autocomplete="off">
          <button type="submit" class="btn btn-primary">Track</button>
        </form>
        <p class="track-recent">
          Try a demo: 
          <button type="button" onclick="document.querySelector('.track-form input').value='AC2026LHE0001'">AC2026LHE0001</button>,
          <button type="button" onclick="document.querySelector('.track-form input').value='AC2026KHI0002'">AC2026KHI0002</button>
        </p>
      </div>
    </div>
  </div>
  <div class="hero-spacer"></div>
</section>

<section class="stats-strip">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-item">
        <div class="stat-num"><?= e(setting('years_experience', '15')) ?>+</div>
        <div class="stat-label">Years of Experience</div>
      </div>
      <div class="stat-item">
        <div class="stat-num"><?= number_format((int) setting('shipments_delivered', '50000')) ?>+</div>
        <div class="stat-label">Shipments Delivered</div>
      </div>
      <div class="stat-item">
        <div class="stat-num"><?= e(setting('countries_served', '120')) ?>+</div>
        <div class="stat-label">Countries Served</div>
      </div>
      <div class="stat-item">
        <div class="stat-num"><?= number_format((int) setting('happy_clients', '8000')) ?>+</div>
        <div class="stat-label">Happy Clients</div>
      </div>
    </div>
  </div>
</section>

<section class="section" id="services">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">What We Do</span>
      <h2 class="section-title">Freight solutions built around your cargo</h2>
      <p class="section-sub">From a single parcel to a full container load, we move it reliably — by air, sea or road.</p>
    </div>

    <div class="services-grid">
      <div class="service-card">
        <div class="service-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-1 .1-1.3.5l-.6.8c-.4.5-.2 1.2.3 1.5L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.4 5.8c.3.5 1 .7 1.5.3l.8-.6c.4-.3.6-.8.5-1.3z"/></svg>
        </div>
        <h3>Air Freight</h3>
        <p>Fast, time-critical shipping with daily flight schedules to major hubs worldwide.</p>
        <a href="/services.php#air" class="service-link">Learn more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>

      <div class="service-card">
        <div class="service-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1M4 18l1-9h14l1 9M9 9V5h6v4"/></svg>
        </div>
        <h3>Ocean Freight</h3>
        <p>Cost-effective FCL &amp; LCL container shipping for large and bulk cargo.</p>
        <a href="/services.php#ocean" class="service-link">Learn more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>

      <div class="service-card">
        <div class="service-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
        </div>
        <h3>Express Courier</h3>
        <p>Door-to-door delivery of documents and parcels with next-day options.</p>
        <a href="/services.php#express" class="service-link">Learn more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>

      <div class="service-card">
        <div class="service-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="7" width="15" height="10" rx="1"/><path d="M16 10h3l3 3v4h-6z"/><circle cx="5.5" cy="19.5" r="1.5"/><circle cx="17.5" cy="19.5" r="1.5"/></svg>
        </div>
        <h3>Road Freight</h3>
        <p>Reliable domestic trucking network connecting every major city in Pakistan.</p>
        <a href="/services.php#road" class="service-link">Learn more <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>
    </div>
  </div>
</section>

<section class="section" style="background:var(--white); border-top:1px solid var(--slate-200); border-bottom:1px solid var(--slate-200);">
  <div class="container">
    <div class="why-grid">
      <div>
        <span class="eyebrow">Why Choose Us</span>
        <h2 class="section-title">Logistics handled with care, start to finish</h2>
        <div class="why-list">
          <div class="why-item">
            <div class="why-num">01</div>
            <div>
              <h4>Real-time tracking</h4>
              <p>Know exactly where your shipment is, from pickup to delivery, with live status updates.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-num">02</div>
            <div>
              <h4>Customs handled for you</h4>
              <p>Our team manages documentation and clearance so your cargo doesn't get stuck.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-num">03</div>
            <div>
              <h4>Nationwide branch network</h4>
              <p>Drop off or get support from branches across Pakistan's major cities.</p>
            </div>
          </div>
          <div class="why-item">
            <div class="why-num">04</div>
            <div>
              <h4>Transparent pricing</h4>
              <p>Get a free, no-obligation quote before you book — no hidden charges.</p>
            </div>
          </div>
        </div>
      </div>

      <div class="why-visual">
        <div class="why-visual-content">
          <span class="why-badge">Live Network</span>
          <h3 style="color:white; font-size:1.5rem;">Connected across <?= e(setting('countries_served', '120')) ?>+ countries</h3>
          <p style="color:rgba(255,255,255,0.65);">Our partner network and hub coverage means your cargo moves on schedule, wherever it's headed.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">Find Us</span>
      <h2 class="section-title">Branches across Pakistan</h2>
      <p class="section-sub">Visit your nearest office to book a shipment or speak with our team in person.</p>
    </div>

    <div class="branches-grid">
      <?php foreach ($branches as $branch): ?>
      <div class="branch-card <?= $branch['is_head_office'] ? 'head-office' : '' ?>">
        <div class="branch-pin">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div>
          <h4>
            <?= e($branch['branch_name']) ?>
            <?php if ($branch['is_head_office']): ?><span class="branch-tag">Head Office</span><?php endif; ?>
          </h4>
          <p><?= e($branch['address']) ?></p>
          <p class="branch-phone"><?= e($branch['phone']) ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center; margin-top:32px;">
      <a href="/branches.php" class="btn btn-navy">View All Branches</a>
    </div>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="cta-band">
      <div>
        <h2>Ready to ship with us?</h2>
        <p>Get a free quote in minutes — our team will get back to you the same day.</p>
      </div>
      <div class="cta-actions">
        <a href="/quote.php" class="btn btn-primary">Get a Free Quote</a>
        <a href="/contact.php" class="btn btn-outline">Contact Us</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
