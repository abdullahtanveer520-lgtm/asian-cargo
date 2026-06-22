<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Shipments';
$activeNav = 'shipments';

$db = getDB();

$search = trim($_GET['q'] ?? '');
$statusFilter = $_GET['status'] ?? '';
$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 15;
$offset = ($page - 1) * $perPage;

$where = [];
$params = [];

if ($search !== '') {
    $where[] = '(tracking_number LIKE ? OR sender_name LIKE ? OR receiver_name LIKE ? OR destination_city LIKE ?)';
    $like = "%{$search}%";
    array_push($params, $like, $like, $like, $like);
}
if ($statusFilter !== '') {
    $where[] = 'status = ?';
    $params[] = $statusFilter;
}

$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$total = $db->prepare("SELECT COUNT(*) c FROM shipments {$whereSql}");
$total->execute($params);
$totalCount = $total->fetch()['c'];
$totalPages = max(1, (int) ceil($totalCount / $perPage));

$stmt = $db->prepare("SELECT * FROM shipments {$whereSql} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}");
$stmt->execute($params);
$shipments = $stmt->fetchAll();

$flashSuccess = flash('success');

include __DIR__ . '/includes/layout_header.php';
?>

<?php if ($flashSuccess): ?>
<div class="alert alert-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
  <div><?= e($flashSuccess) ?></div>
</div>
<?php endif; ?>

<div class="admin-card">
  <div class="admin-card-head">
    <form class="search-bar" method="get">
      <input type="text" name="q" placeholder="Search tracking #, name, city..." value="<?= e($search) ?>">
      <select name="status" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <?php foreach (statusSteps() + ['delayed' => 'Delayed', 'exception' => 'Exception'] as $key => $label): ?>
        <option value="<?= e($key) ?>" <?= $statusFilter === $key ? 'selected' : '' ?>><?= e($label) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn btn-sm btn-navy">Filter</button>
    </form>
    <a href="/admin/shipment_form.php" class="btn btn-sm btn-primary">+ New Shipment</a>
  </div>

  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr>
          <th>Tracking #</th><th>Sender</th><th>Route</th><th>Service</th><th>Status</th><th>Updated</th><th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($shipments)): ?>
        <tr><td colspan="7">
          <div class="empty-state">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="7" width="15" height="10" rx="1"/><path d="M16 10h3l3 3v4h-6z"/></svg>
            <p>No shipments found<?= $search || $statusFilter ? ' matching your filters' : '' ?>.</p>
          </div>
        </td></tr>
        <?php endif; ?>
        <?php foreach ($shipments as $s): $info = statusInfo($s['status']); ?>
        <tr>
          <td class="mono"><?= e($s['tracking_number']) ?></td>
          <td><?= e($s['sender_name']) ?></td>
          <td><?= e($s['origin_city']) ?> &rarr; <?= e($s['destination_city']) ?></td>
          <td><?= e(serviceLabel($s['service_type'])) ?></td>
          <td><span class="status-pill <?= e($info['class']) ?>"><?= e($info['label']) ?></span></td>
          <td><?= e(formatDate($s['updated_at'], 'd M, h:i A')) ?></td>
          <td>
            <div class="table-actions">
              <a href="/admin/shipment_view.php?id=<?= $s['id'] ?>" class="icon-btn" title="View / Update Status">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </a>
              <a href="/admin/shipment_form.php?id=<?= $s['id'] ?>" class="icon-btn" title="Edit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              </a>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <?php if ($totalPages > 1): ?>
  <div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <?php if ($i === $page): ?>
        <span class="current"><?= $i ?></span>
      <?php else: ?>
        <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($statusFilter) ?>"><?= $i ?></a>
      <?php endif; ?>
    <?php endfor; ?>
  </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/layout_footer.php'; ?>
