<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Dashboard';
$activeNav = 'dashboard';

$db = getDB();
$totalShipments = $db->query('SELECT COUNT(*) c FROM shipments')->fetch()['c'];
$inTransit = $db->query("SELECT COUNT(*) c FROM shipments WHERE status IN ('picked_up','in_transit','arrived_hub','customs_clearance','out_for_delivery')")->fetch()['c'];
$delivered = $db->query("SELECT COUNT(*) c FROM shipments WHERE status = 'delivered'")->fetch()['c'];
$newQuotes = $db->query("SELECT COUNT(*) c FROM quote_requests WHERE status = 'new'")->fetch()['c'];
$unreadMessages = $db->query('SELECT COUNT(*) c FROM contact_messages WHERE is_read = 0')->fetch()['c'];

$recentShipments = $db->query('SELECT * FROM shipments ORDER BY created_at DESC LIMIT 6')->fetchAll();
$recentQuotes = $db->query('SELECT * FROM quote_requests ORDER BY created_at DESC LIMIT 5')->fetchAll();

include __DIR__ . '/includes/layout_header.php';
?>

<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-icon" style="background:#E3EBF6; color:var(--navy-700);">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="7" width="15" height="10" rx="1"/><path d="M16 10h3l3 3v4h-6z"/></svg>
    </div>
    <div>
      <div class="kpi-num"><?= number_format($totalShipments) ?></div>
      <div class="kpi-label">Total Shipments</div>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon" style="background:var(--amber-100); color:var(--amber-600);">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
    </div>
    <div>
      <div class="kpi-num"><?= number_format($inTransit) ?></div>
      <div class="kpi-label">In Transit</div>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon" style="background:var(--green-100); color:var(--green-600);">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
    </div>
    <div>
      <div class="kpi-num"><?= number_format($delivered) ?></div>
      <div class="kpi-label">Delivered</div>
    </div>
  </div>
  <div class="kpi-card">
    <div class="kpi-icon" style="background:var(--red-100); color:var(--red-600);">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
    </div>
    <div>
      <div class="kpi-num"><?= number_format($newQuotes) ?></div>
      <div class="kpi-label">New Quote Requests</div>
    </div>
  </div>
</div>

<div class="admin-card" style="margin-bottom:24px;">
  <div class="admin-card-head">
    <h3>Recent Shipments</h3>
    <a href="/admin/shipments.php" class="btn btn-sm btn-navy">View All</a>
  </div>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Tracking #</th><th>Route</th><th>Service</th><th>Status</th><th>Updated</th></tr>
      </thead>
      <tbody>
        <?php if (empty($recentShipments)): ?>
          <tr><td colspan="5"><div class="empty-state">No shipments yet. <a href="/admin/shipments.php?action=new">Add your first shipment</a>.</div></td></tr>
        <?php endif; ?>
        <?php foreach ($recentShipments as $s): $info = statusInfo($s['status']); ?>
        <tr onclick="window.location='/admin/shipment_view.php?id=<?= $s['id'] ?>'" style="cursor:pointer;">
          <td class="mono"><?= e($s['tracking_number']) ?></td>
          <td><?= e($s['origin_city']) ?> &rarr; <?= e($s['destination_city']) ?></td>
          <td><?= e(serviceLabel($s['service_type'])) ?></td>
          <td><span class="status-pill <?= e($info['class']) ?>"><?= e($info['label']) ?></span></td>
          <td><?= e(formatDate($s['updated_at'], 'd M, h:i A')) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="admin-card">
  <div class="admin-card-head">
    <h3>Recent Quote Requests</h3>
    <a href="/admin/quotes.php" class="btn btn-sm btn-navy">View All</a>
  </div>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Name</th><th>Service</th><th>Route</th><th>Status</th><th>Received</th></tr>
      </thead>
      <tbody>
        <?php if (empty($recentQuotes)): ?>
          <tr><td colspan="5"><div class="empty-state">No quote requests yet.</div></td></tr>
        <?php endif; ?>
        <?php foreach ($recentQuotes as $q): ?>
        <tr>
          <td><?= e($q['full_name']) ?></td>
          <td><?= e(serviceLabel($q['service_type'])) ?></td>
          <td><?= e($q['origin_city']) ?> &rarr; <?= e($q['destination_city']) ?></td>
          <td><span class="badge" style="background:<?= $q['status'] === 'new' ? 'var(--amber-100)' : 'var(--slate-100)' ?>; color:<?= $q['status'] === 'new' ? 'var(--amber-600)' : 'var(--slate-600)' ?>;"><?= e(ucfirst($q['status'])) ?></span></td>
          <td><?= e(formatDate($q['created_at'], 'd M, h:i A')) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/includes/layout_footer.php'; ?>
