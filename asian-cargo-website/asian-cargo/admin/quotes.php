<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Quote Requests';
$activeNav = 'quotes';

$db = getDB();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfVerify()) {
    $qid = (int) ($_POST['id'] ?? 0);
    $newStatus = $_POST['status'] ?? '';
    if (in_array($newStatus, ['new', 'contacted', 'closed'], true) && $qid > 0) {
        $stmt = $db->prepare('UPDATE quote_requests SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $qid]);
        flash('success', 'Quote request updated.');
    }
    redirect('/admin/quotes.php');
}

$statusFilter = $_GET['status'] ?? '';
$where = '';
$params = [];
if ($statusFilter !== '') {
    $where = 'WHERE status = ?';
    $params[] = $statusFilter;
}

$stmt = $db->prepare("SELECT * FROM quote_requests {$where} ORDER BY created_at DESC");
$stmt->execute($params);
$quotes = $stmt->fetchAll();

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
      <select name="status" onchange="this.form.submit()">
        <option value="">All Statuses</option>
        <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>New</option>
        <option value="contacted" <?= $statusFilter === 'contacted' ? 'selected' : '' ?>>Contacted</option>
        <option value="closed" <?= $statusFilter === 'closed' ? 'selected' : '' ?>>Closed</option>
      </select>
    </form>
  </div>
  <div class="admin-table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Name</th><th>Contact</th><th>Service</th><th>Route</th><th>Weight</th><th>Status</th><th>Received</th></tr>
      </thead>
      <tbody>
        <?php if (empty($quotes)): ?>
        <tr><td colspan="7"><div class="empty-state">No quote requests<?= $statusFilter ? ' with this status' : '' ?>.</div></td></tr>
        <?php endif; ?>
        <?php foreach ($quotes as $q): ?>
        <tr>
          <td><?= e($q['full_name']) ?></td>
          <td style="font-size:0.82rem;"><?= e($q['email']) ?><br><?= e($q['phone']) ?></td>
          <td><?= e(serviceLabel($q['service_type'])) ?></td>
          <td><?= e($q['origin_city']) ?> &rarr; <?= e($q['destination_city']) ?></td>
          <td><?= $q['weight_kg'] ? e($q['weight_kg']) . ' kg' : '—' ?></td>
          <td>
            <form method="post" style="display:inline;">
              <?= csrfField() ?>
              <input type="hidden" name="id" value="<?= $q['id'] ?>">
              <select name="status" class="form-control" style="padding:5px 8px; font-size:0.78rem;" onchange="this.form.submit()">
                <option value="new" <?= $q['status'] === 'new' ? 'selected' : '' ?>>New</option>
                <option value="contacted" <?= $q['status'] === 'contacted' ? 'selected' : '' ?>>Contacted</option>
                <option value="closed" <?= $q['status'] === 'closed' ? 'selected' : '' ?>>Closed</option>
              </select>
            </form>
          </td>
          <td><?= e(formatDate($q['created_at'], 'd M, h:i A')) ?></td>
        </tr>
        <?php if ($q['package_description']): ?>
        <tr><td colspan="7" style="background:var(--slate-100); font-size:0.85rem; padding:8px 22px;"><strong>Note:</strong> <?= e($q['package_description']) ?></td></tr>
        <?php endif; ?>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php include __DIR__ . '/includes/layout_footer.php'; ?>
