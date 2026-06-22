<?php
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$id = (int) ($_GET['id'] ?? 0);

$stmt = $db->prepare('SELECT * FROM shipments WHERE id = ?');
$stmt->execute([$id]);
$shipment = $stmt->fetch();

if (!$shipment) {
    flash('error', 'Shipment not found.');
    redirect('/admin/shipments.php');
}

$pageTitle = 'Shipment ' . $shipment['tracking_number'];
$activeNav = 'shipments';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfVerify()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'add_event') {
            $newStatus = $_POST['status'] ?? '';
            $location = trim($_POST['location'] ?? '');
            $remarks = trim($_POST['remarks'] ?? '');

            $validStatuses = ['booked','picked_up','in_transit','arrived_hub','customs_clearance','out_for_delivery','delivered','delayed','exception'];
            if (!in_array($newStatus, $validStatuses, true)) {
                $errors[] = 'Please select a valid status.';
            }

            if (empty($errors)) {
                $db->beginTransaction();
                try {
                    $evt = $db->prepare(
                        'INSERT INTO tracking_events (shipment_id, status, location, remarks, created_by) VALUES (?, ?, ?, ?, ?)'
                    );
                    $evt->execute([$id, $newStatus, $location ?: null, $remarks ?: null, $_SESSION['admin_id']]);

                    $upd = $db->prepare('UPDATE shipments SET status = ? WHERE id = ?');
                    $upd->execute([$newStatus, $id]);

                    $db->commit();
                    flash('success', 'Status updated to "' . statusInfo($newStatus)['label'] . '" and customer tracking page refreshed.');
                    redirect('/admin/shipment_view.php?id=' . $id);
                } catch (Exception $e) {
                    $db->rollBack();
                    $errors[] = 'Something went wrong updating the status. Please try again.';
                }
            }
        } elseif ($action === 'delete_shipment') {
            $del = $db->prepare('DELETE FROM shipments WHERE id = ?');
            $del->execute([$id]);
            flash('success', 'Shipment deleted.');
            redirect('/admin/shipments.php');
        }
    }
}

$stmt = $db->prepare('SELECT * FROM tracking_events WHERE shipment_id = ? ORDER BY event_time DESC');
$stmt->execute([$id]);
$events = $stmt->fetchAll();

$info = statusInfo($shipment['status']);

include __DIR__ . '/includes/layout_header.php';
?>

<?php if (!empty($errors)): ?>
<div class="alert alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
  <div><?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?></div>
</div>
<?php endif; ?>

<div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:14px; margin-bottom:20px;">
  <div>
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:6px;">
      <span class="track-number" style="font-size:1.4rem;"><?= e($shipment['tracking_number']) ?></span>
      <span class="status-pill <?= e($info['class']) ?>"><?= e($info['label']) ?></span>
    </div>
    <p style="margin:0; font-size:0.9rem;">Created <?= e(formatDate($shipment['created_at'])) ?> &middot; Last updated <?= e(formatDate($shipment['updated_at'])) ?></p>
  </div>
  <div style="display:flex; gap:10px;">
    <a href="/track.php?tracking_number=<?= urlencode($shipment['tracking_number']) ?>" target="_blank" class="btn btn-sm btn-outline" style="color:var(--navy-900); border-color:var(--slate-200);">View Public Page</a>
    <a href="/admin/shipment_form.php?id=<?= $id ?>" class="btn btn-sm btn-navy">Edit Details</a>
  </div>
</div>

<div class="why-grid" style="align-items:flex-start; gap:24px;">
  <div>
    <div class="admin-card" style="margin-bottom:20px;">
      <div class="admin-card-head"><h3>Shipment Information</h3></div>
      <div style="padding:22px;">
        <div class="track-meta-grid" style="grid-template-columns:repeat(2,1fr); row-gap:18px;">
          <div class="track-meta-item"><div class="label">Sender</div><div class="value"><?= e($shipment['sender_name']) ?></div></div>
          <div class="track-meta-item"><div class="label">Sender Phone</div><div class="value"><?= e($shipment['sender_phone']) ?></div></div>
          <div class="track-meta-item"><div class="label">Receiver</div><div class="value"><?= e($shipment['receiver_name']) ?></div></div>
          <div class="track-meta-item"><div class="label">Receiver Phone</div><div class="value"><?= e($shipment['receiver_phone']) ?></div></div>
          <div class="track-meta-item"><div class="label">From</div><div class="value"><?= e($shipment['origin_city']) ?>, <?= e($shipment['origin_country']) ?></div></div>
          <div class="track-meta-item"><div class="label">To</div><div class="value"><?= e($shipment['destination_city']) ?>, <?= e($shipment['destination_country']) ?></div></div>
          <div class="track-meta-item"><div class="label">Service</div><div class="value"><?= e(serviceLabel($shipment['service_type'])) ?></div></div>
          <div class="track-meta-item"><div class="label">Weight / Pieces</div><div class="value"><?= $shipment['weight_kg'] ? e($shipment['weight_kg']) . ' kg' : '—' ?> / <?= e($shipment['pieces']) ?></div></div>
          <div class="track-meta-item"><div class="label">Est. Delivery</div><div class="value"><?= $shipment['estimated_delivery'] ? e(formatDate($shipment['estimated_delivery'], 'd M Y')) : 'TBA' ?></div></div>
        </div>
        <?php if ($shipment['package_description']): ?>
        <div style="margin-top:18px; padding-top:18px; border-top:1px solid var(--slate-200);">
          <div class="label" style="font-size:0.75rem; color:var(--slate-400); text-transform:uppercase; margin-bottom:4px;">Package Description</div>
          <p style="margin:0;"><?= e($shipment['package_description']) ?></p>
        </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="admin-card">
      <div class="admin-card-head"><h3>Tracking History</h3></div>
      <div style="padding:22px;">
        <?php if (empty($events)): ?>
          <div class="empty-state">No tracking events yet.</div>
        <?php endif; ?>
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
    </div>
  </div>

  <div>
    <div class="admin-card" style="margin-bottom:20px;">
      <div class="admin-card-head"><h3>Update Status</h3></div>
      <form method="post" style="padding:22px;">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="add_event">
        <div class="form-group">
          <label>New Status <span class="req">*</span></label>
          <select name="status" class="form-control" required>
            <?php foreach (statusSteps() + ['delayed' => 'Delayed', 'exception' => 'Exception'] as $key => $label): ?>
            <option value="<?= e($key) ?>" <?= $shipment['status'] === $key ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group">
          <label>Location</label>
          <input type="text" name="location" class="form-control" placeholder="e.g. Dubai, UAE">
        </div>
        <div class="form-group">
          <label>Remarks</label>
          <textarea name="remarks" class="form-control" placeholder="Optional note about this update"></textarea>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Add Status Update</button>
      </form>
    </div>

    <div class="admin-card" style="border-color:var(--red-100);">
      <div class="admin-card-head"><h3 style="color:var(--red-600);">Danger Zone</h3></div>
      <div style="padding:22px;">
        <p style="font-size:0.85rem;">Deleting a shipment permanently removes it and its tracking history. This cannot be undone.</p>
        <form method="post" onsubmit="return confirm('Are you sure you want to permanently delete this shipment? This cannot be undone.');">
          <?= csrfField() ?>
          <input type="hidden" name="action" value="delete_shipment">
          <button type="submit" class="btn btn-block" style="background:var(--red-100); color:var(--red-600); border:1px solid var(--red-600);">Delete Shipment</button>
        </form>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/layout_footer.php'; ?>
