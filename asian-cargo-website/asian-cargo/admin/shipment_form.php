<?php
require_once __DIR__ . '/includes/auth.php';

$db = getDB();
$id = (int) ($_GET['id'] ?? 0);
$isEdit = $id > 0;
$pageTitle = $isEdit ? 'Edit Shipment' : 'New Shipment';
$activeNav = 'shipments';

$shipment = null;
if ($isEdit) {
    $stmt = $db->prepare('SELECT * FROM shipments WHERE id = ?');
    $stmt->execute([$id]);
    $shipment = $stmt->fetch();
    if (!$shipment) {
        flash('error', 'Shipment not found.');
        redirect('/admin/shipments.php');
    }
}

$errors = [];
$v = $shipment ?? [
    'sender_name' => '', 'sender_phone' => '', 'sender_address' => '',
    'origin_city' => '', 'origin_country' => 'Pakistan',
    'receiver_name' => '', 'receiver_phone' => '', 'receiver_address' => '',
    'destination_city' => '', 'destination_country' => '',
    'service_type' => 'air_freight', 'package_description' => '',
    'weight_kg' => '', 'pieces' => 1, 'estimated_delivery' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfVerify()) {
        $errors[] = 'Your session expired. Please try again.';
    }

    foreach (['sender_name','sender_phone','sender_address','origin_city','origin_country',
              'receiver_name','receiver_phone','receiver_address','destination_city','destination_country',
              'service_type','package_description','weight_kg','pieces','estimated_delivery'] as $field) {
        $v[$field] = trim($_POST[$field] ?? '');
    }

    if ($v['sender_name'] === '') $errors[] = 'Sender name is required.';
    if ($v['sender_phone'] === '') $errors[] = 'Sender phone is required.';
    if ($v['origin_city'] === '') $errors[] = 'Origin city is required.';
    if ($v['receiver_name'] === '') $errors[] = 'Receiver name is required.';
    if ($v['receiver_phone'] === '') $errors[] = 'Receiver phone is required.';
    if ($v['destination_city'] === '') $errors[] = 'Destination city is required.';
    if ($v['destination_country'] === '') $errors[] = 'Destination country is required.';
    if (!in_array($v['service_type'], ['air_freight','ocean_freight','express_courier','road_freight'], true)) {
        $errors[] = 'Invalid service type.';
    }

    $pieces = (int) ($v['pieces'] ?: 1);
    $weight = $v['weight_kg'] !== '' ? (float) $v['weight_kg'] : null;
    $estDelivery = $v['estimated_delivery'] !== '' ? $v['estimated_delivery'] : null;

    if (empty($errors)) {
        if ($isEdit) {
            $stmt = $db->prepare(
                'UPDATE shipments SET sender_name=?, sender_phone=?, sender_address=?, origin_city=?, origin_country=?,
                 receiver_name=?, receiver_phone=?, receiver_address=?, destination_city=?, destination_country=?,
                 service_type=?, package_description=?, weight_kg=?, pieces=?, estimated_delivery=? WHERE id=?'
            );
            $stmt->execute([
                $v['sender_name'], $v['sender_phone'], $v['sender_address'] ?: null, $v['origin_city'], $v['origin_country'],
                $v['receiver_name'], $v['receiver_phone'], $v['receiver_address'] ?: null, $v['destination_city'], $v['destination_country'],
                $v['service_type'], $v['package_description'] ?: null, $weight, $pieces, $estDelivery, $id,
            ]);
            flash('success', 'Shipment ' . e($shipment['tracking_number']) . ' updated successfully.');
            redirect('/admin/shipment_view.php?id=' . $id);
        } else {
            $trackingNumber = generateTrackingNumber($v['origin_city']);
            $stmt = $db->prepare(
                'INSERT INTO shipments (tracking_number, sender_name, sender_phone, sender_address, origin_city, origin_country,
                 receiver_name, receiver_phone, receiver_address, destination_city, destination_country,
                 service_type, package_description, weight_kg, pieces, estimated_delivery, status, created_by)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "booked", ?)'
            );
            $stmt->execute([
                $trackingNumber,
                $v['sender_name'], $v['sender_phone'], $v['sender_address'] ?: null, $v['origin_city'], $v['origin_country'],
                $v['receiver_name'], $v['receiver_phone'], $v['receiver_address'] ?: null, $v['destination_city'], $v['destination_country'],
                $v['service_type'], $v['package_description'] ?: null, $weight, $pieces, $estDelivery,
                $_SESSION['admin_id'],
            ]);
            $newId = $db->lastInsertId();

            $evt = $db->prepare(
                'INSERT INTO tracking_events (shipment_id, status, location, remarks, created_by) VALUES (?, "booked", ?, "Shipment booked and confirmed", ?)'
            );
            $evt->execute([$newId, $v['origin_city'] . ', ' . $v['origin_country'], $_SESSION['admin_id']]);

            flash('success', "Shipment created successfully. Tracking number: {$trackingNumber}");
            redirect('/admin/shipment_view.php?id=' . $newId);
        }
    }
}

include __DIR__ . '/includes/layout_header.php';
?>

<?php if (!empty($errors)): ?>
<div class="alert alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
  <div><?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?></div>
</div>
<?php endif; ?>

<form method="post" novalidate>
  <?= csrfField() ?>

  <div class="admin-card" style="margin-bottom:20px;">
    <div class="admin-card-head"><h3>Sender Details</h3></div>
    <div style="padding:22px;">
      <div class="form-row">
        <div class="form-group">
          <label>Sender Name <span class="req">*</span></label>
          <input type="text" name="sender_name" class="form-control" value="<?= e($v['sender_name']) ?>" required>
        </div>
        <div class="form-group">
          <label>Sender Phone <span class="req">*</span></label>
          <input type="text" name="sender_phone" class="form-control" value="<?= e($v['sender_phone']) ?>" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Origin City <span class="req">*</span></label>
          <input type="text" name="origin_city" class="form-control" value="<?= e($v['origin_city']) ?>" required <?= $isEdit ? 'readonly' : '' ?>>
          <?php if ($isEdit): ?><p class="form-hint">Origin city can't be changed after the tracking number is generated.</p><?php endif; ?>
        </div>
        <div class="form-group">
          <label>Origin Country <span class="req">*</span></label>
          <input type="text" name="origin_country" class="form-control" value="<?= e($v['origin_country']) ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label>Sender Address</label>
        <input type="text" name="sender_address" class="form-control" value="<?= e($v['sender_address'] ?? '') ?>">
      </div>
    </div>
  </div>

  <div class="admin-card" style="margin-bottom:20px;">
    <div class="admin-card-head"><h3>Receiver Details</h3></div>
    <div style="padding:22px;">
      <div class="form-row">
        <div class="form-group">
          <label>Receiver Name <span class="req">*</span></label>
          <input type="text" name="receiver_name" class="form-control" value="<?= e($v['receiver_name']) ?>" required>
        </div>
        <div class="form-group">
          <label>Receiver Phone <span class="req">*</span></label>
          <input type="text" name="receiver_phone" class="form-control" value="<?= e($v['receiver_phone']) ?>" required>
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Destination City <span class="req">*</span></label>
          <input type="text" name="destination_city" class="form-control" value="<?= e($v['destination_city']) ?>" required>
        </div>
        <div class="form-group">
          <label>Destination Country <span class="req">*</span></label>
          <input type="text" name="destination_country" class="form-control" value="<?= e($v['destination_country']) ?>" required>
        </div>
      </div>
      <div class="form-group">
        <label>Receiver Address</label>
        <input type="text" name="receiver_address" class="form-control" value="<?= e($v['receiver_address'] ?? '') ?>">
      </div>
    </div>
  </div>

  <div class="admin-card" style="margin-bottom:20px;">
    <div class="admin-card-head"><h3>Shipment Details</h3></div>
    <div style="padding:22px;">
      <div class="form-row">
        <div class="form-group">
          <label>Service Type <span class="req">*</span></label>
          <select name="service_type" class="form-control" required>
            <option value="air_freight" <?= $v['service_type'] === 'air_freight' ? 'selected' : '' ?>>Air Freight</option>
            <option value="ocean_freight" <?= $v['service_type'] === 'ocean_freight' ? 'selected' : '' ?>>Ocean Freight</option>
            <option value="express_courier" <?= $v['service_type'] === 'express_courier' ? 'selected' : '' ?>>Express Courier</option>
            <option value="road_freight" <?= $v['service_type'] === 'road_freight' ? 'selected' : '' ?>>Road Freight</option>
          </select>
        </div>
        <div class="form-group">
          <label>Estimated Delivery Date</label>
          <input type="date" name="estimated_delivery" class="form-control" value="<?= e($v['estimated_delivery'] ?? '') ?>">
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Weight (kg)</label>
          <input type="number" step="0.1" min="0" name="weight_kg" class="form-control" value="<?= e((string) ($v['weight_kg'] ?? '')) ?>">
        </div>
        <div class="form-group">
          <label>Number of Pieces</label>
          <input type="number" min="1" name="pieces" class="form-control" value="<?= e((string) ($v['pieces'] ?? 1)) ?>">
        </div>
      </div>
      <div class="form-group">
        <label>Package Description</label>
        <textarea name="package_description" class="form-control"><?= e($v['package_description'] ?? '') ?></textarea>
      </div>
    </div>
  </div>

  <div style="display:flex; gap:12px;">
    <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Save Changes' : 'Create Shipment' ?></button>
    <a href="/admin/shipments.php" class="btn btn-outline" style="color:var(--navy-900); border-color:var(--slate-200);">Cancel</a>
  </div>
</form>

<?php include __DIR__ . '/includes/layout_footer.php'; ?>
