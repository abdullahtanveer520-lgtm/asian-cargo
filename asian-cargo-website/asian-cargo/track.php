<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageTitle = 'Track Shipment';
$activePage = 'track';

$trackingNumber = trim($_GET['tracking_number'] ?? '');
$shipment = null;
$events = [];
$notFound = false;

if ($trackingNumber !== '') {
    $stmt = getDB()->prepare('SELECT * FROM shipments WHERE tracking_number = ?');
    $stmt->execute([$trackingNumber]);
    $shipment = $stmt->fetch();

    if ($shipment) {
        $stmt = getDB()->prepare('SELECT * FROM tracking_events WHERE shipment_id = ? ORDER BY event_time DESC');
        $stmt->execute([$shipment['id']]);
        $events = $stmt->fetchAll();
    } else {
        $notFound = true;
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="/">Home</a> / Track Shipment</p>
    <h1>Track Your Shipment</h1>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="form-card" style="max-width:680px; margin:0 auto 40px;">
      <form action="/track.php" method="get" class="track-form">
        <input type="text" name="tracking_number" class="form-control" placeholder="Enter tracking number, e.g. AC2026LHE0001"
               value="<?= e($trackingNumber) ?>" required autocomplete="off">
        <button type="submit" class="btn btn-primary">Track</button>
      </form>
    </div>

    <?php if ($notFound): ?>
      <div class="track-empty" style="max-width:680px; margin:0 auto;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#8B93A0" stroke-width="1.5" style="margin:0 auto 16px;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <h3>No shipment found</h3>
        <p>We couldn't find a shipment with tracking number <strong><?= e($trackingNumber) ?></strong>. Please check the number and try again, or contact our support team.</p>
        <a href="/contact.php" class="btn btn-navy" style="margin-top:12px;">Contact Support</a>
      </div>
    <?php elseif ($shipment): ?>
      <?php
        $info = statusInfo($shipment['status']);
        $steps = statusSteps();
        $stepKeys = array_keys($steps);
        $currentIndex = array_search($shipment['status'], $stepKeys, true);
        $isException = in_array($shipment['status'], ['delayed', 'exception'], true);
      ?>
      <div class="track-summary" style="max-width:920px; margin:0 auto;">
        <div class="track-summary-top">
          <div>
            <div style="font-size:0.78rem; color:var(--slate-400); text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px;">Tracking Number</div>
            <div class="track-number"><?= e($shipment['tracking_number']) ?></div>
          </div>
          <span class="status-pill <?= e($info['class']) ?>"><?= e($info['label']) ?></span>
        </div>

        <div class="track-meta-grid">
          <div class="track-meta-item">
            <div class="label">From</div>
            <div class="value"><?= e($shipment['origin_city']) ?>, <?= e($shipment['origin_country']) ?></div>
          </div>
          <div class="track-meta-item">
            <div class="label">To</div>
            <div class="value"><?= e($shipment['destination_city']) ?>, <?= e($shipment['destination_country']) ?></div>
          </div>
          <div class="track-meta-item">
            <div class="label">Service</div>
            <div class="value"><?= e(serviceLabel($shipment['service_type'])) ?></div>
          </div>
          <div class="track-meta-item">
            <div class="label">Est. Delivery</div>
            <div class="value"><?= $shipment['estimated_delivery'] ? formatDate($shipment['estimated_delivery'], 'd M Y') : 'TBA' ?></div>
          </div>
        </div>

        <?php if (!$isException): ?>
        <div class="progress-track">
          <?php foreach ($steps as $key => $label):
            $stepPos = array_search($key, $stepKeys, true);
            $stateClass = $stepPos < $currentIndex ? 'done' : ($stepPos === $currentIndex ? 'current' : '');
          ?>
          <div class="progress-step <?= $stateClass ?>">
            <div class="dot"></div>
            <div class="step-label"><?= e($label) ?></div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="alert alert-error" style="margin-top:24px;">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
          <div>This shipment currently has a <strong><?= e($info['label']) ?></strong> status. Please contact our support team for the latest update.</div>
        </div>
        <?php endif; ?>
      </div>

      <div class="track-summary" style="max-width:920px; margin:24px auto 0;">
        <h3 style="font-size:1.1rem; margin-bottom:24px;">Shipment History</h3>
        <div class="timeline">
          <?php foreach ($events as $event): $eInfo = statusInfo($event['status']); ?>
          <div class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <h4><?= e($eInfo['label']) ?></h4>
              <div class="timeline-time"><?= e(formatDate($event['event_time'])) ?> <?= $event['location'] ? '· ' . e($event['location']) : '' ?></div>
              <?php if ($event['remarks']): ?><p><?= e($event['remarks']) ?></p><?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php else: ?>
      <div class="track-empty" style="max-width:680px; margin:0 auto;">
        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#8B93A0" stroke-width="1.5" style="margin:0 auto 16px;"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg>
        <h3>Enter your tracking number above</h3>
        <p>You'll find it on your booking receipt or the confirmation message we sent you.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
