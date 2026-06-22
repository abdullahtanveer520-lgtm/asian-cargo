<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageTitle = 'Our Services';
$activePage = 'services';
include __DIR__ . '/includes/header.php';

$services = [
    [
        'id' => 'air',
        'title' => 'Air Freight',
        'desc' => 'When time matters most, our air freight service gets your cargo moving fast. We work with daily flight schedules across major airlines to offer flexible, reliable delivery windows for urgent and time-sensitive shipments.',
        'points' => ['Daily flight schedules to major hubs', 'Express and economy air options', 'Special handling for fragile or temperature-sensitive cargo', 'Live tracking from pickup to landing'],
        'icon' => '<path d="M17.8 19.2 16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-1 .1-1.3.5l-.6.8c-.4.5-.2 1.2.3 1.5L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.4 5.8c.3.5 1 .7 1.5.3l.8-.6c.4-.3.6-.8.5-1.3z"/>',
    ],
    [
        'id' => 'ocean',
        'title' => 'Ocean Freight',
        'desc' => 'For larger volumes where cost-efficiency matters more than speed, our ocean freight service offers full container load (FCL) and less-than-container load (LCL) options to ports worldwide.',
        'points' => ['FCL & LCL container options', 'Port-to-port and door-to-door delivery', 'Bulk and commercial cargo handling', 'Competitive rates for high-volume shipping'],
        'icon' => '<path d="M2 21c.6.5 1.2 1 2.5 1 2.5 0 2.5-2 5-2 2.5 0 2.5 2 5 2 2.5 0 2.5-2 5-2 1.3 0 1.9.5 2.5 1M4 18l1-9h14l1 9M9 9V5h6v4"/>',
    ],
    [
        'id' => 'express',
        'title' => 'Express Courier',
        'desc' => 'Documents, parcels, samples and gifts — delivered door-to-door with speed and care. Our express network covers both domestic and international destinations with reliable next-day options.',
        'points' => ['Domestic & international parcel delivery', 'Document courier with proof of delivery', 'Next-day and same-day options available', 'Real-time status updates by SMS/WhatsApp'],
        'icon' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
    ],
    [
        'id' => 'road',
        'title' => 'Road Freight',
        'desc' => 'Our domestic trucking network connects every major city in Pakistan, offering dependable ground transport for cargo that doesn\'t need to fly.',
        'points' => ['Nationwide coverage across Pakistan', 'Full truck load (FTL) and part load options', 'Scheduled and on-demand pickups', 'Secure handling for commercial goods'],
        'icon' => '<rect x="1" y="7" width="15" height="10" rx="1"/><path d="M16 10h3l3 3v4h-6z"/><circle cx="5.5" cy="19.5" r="1.5"/><circle cx="17.5" cy="19.5" r="1.5"/>',
    ],
];
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="/">Home</a> / Services</p>
    <h1>Our Services</h1>
  </div>
</section>

<?php foreach ($services as $i => $s): ?>
<section class="section <?= $i % 2 === 1 ? '' : '' ?>" id="<?= e($s['id']) ?>" style="<?= $i % 2 === 1 ? 'background:var(--white); border-top:1px solid var(--slate-200); border-bottom:1px solid var(--slate-200);' : '' ?>">
  <div class="container">
    <div class="why-grid" style="<?= $i % 2 === 1 ? 'direction:rtl;' : '' ?>">
      <div style="<?= $i % 2 === 1 ? 'direction:ltr;' : '' ?>">
        <div class="service-icon" style="margin-bottom:20px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><?= $s['icon'] ?></svg>
        </div>
        <h2 class="section-title"><?= e($s['title']) ?></h2>
        <p style="font-size:1.02rem;"><?= e($s['desc']) ?></p>
        <ul style="display:flex; flex-direction:column; gap:12px; margin:20px 0;">
          <?php foreach ($s['points'] as $point): ?>
          <li style="display:flex; gap:10px; align-items:flex-start; font-size:0.92rem; color:var(--slate-600);">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#1F9D55" stroke-width="3" style="flex-shrink:0; margin-top:2px;"><path d="M20 6 9 17l-5-5"/></svg>
            <?= e($point) ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <a href="/quote.php?service=<?= e($s['id'] === 'air' ? 'air_freight' : ($s['id'] === 'ocean' ? 'ocean_freight' : ($s['id'] === 'express' ? 'express_courier' : 'road_freight'))) ?>" class="btn btn-primary">Get a Quote for <?= e($s['title']) ?></a>
      </div>
      <div class="why-visual" style="<?= $i % 2 === 1 ? 'direction:ltr;' : '' ?> min-height:280px; display:flex; align-items:center; justify-content:center;">
        <svg viewBox="0 0 24 24" fill="none" stroke="#F2994A" stroke-width="1.2" width="160" height="160" style="opacity:0.85;"><?= $s['icon'] ?></svg>
      </div>
    </div>
  </div>
</section>
<?php endforeach; ?>

<section class="section-tight">
  <div class="container">
    <div class="cta-band">
      <div>
        <h2>Not sure which service fits your shipment?</h2>
        <p>Tell us what you're shipping and we'll recommend the best option.</p>
      </div>
      <div class="cta-actions">
        <a href="/quote.php" class="btn btn-primary">Get a Free Quote</a>
        <a href="/contact.php" class="btn btn-outline">Talk to Us</a>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
