<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Site Settings';
$activeNav = 'settings';

$admin = currentAdmin();
if ($admin['role'] !== 'super_admin') {
    flash('error', 'Only super admins can access site settings.');
    redirect('/admin/');
}

$db = getDB();
$errors = [];

$fields = [
    'site_name' => 'Site Name',
    'site_tagline' => 'Tagline',
    'contact_phone' => 'Contact Phone',
    'contact_whatsapp' => 'WhatsApp Number (digits only, e.g. 923001234567)',
    'contact_email' => 'Contact Email',
    'office_hours' => 'Office Hours',
    'facebook_url' => 'Facebook URL',
    'instagram_url' => 'Instagram URL',
    'linkedin_url' => 'LinkedIn URL',
    'years_experience' => 'Years of Experience (number)',
    'shipments_delivered' => 'Shipments Delivered (number)',
    'countries_served' => 'Countries Served (number)',
    'happy_clients' => 'Happy Clients (number)',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfVerify()) {
        $errors[] = 'Your session expired. Please try again.';
    } else {
        $stmt = $db->prepare('INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)');
        foreach (array_keys($fields) as $key) {
            $stmt->execute([$key, trim($_POST[$key] ?? '')]);
        }
        flash('success', 'Site settings updated.');
        redirect('/admin/settings.php');
    }
}

$current = [];
foreach ($db->query('SELECT setting_key, setting_value FROM settings')->fetchAll() as $row) {
    $current[$row['setting_key']] = $row['setting_value'];
}

$flashSuccess = flash('success');

include __DIR__ . '/includes/layout_header.php';
?>

<?php if ($flashSuccess): ?>
<div class="alert alert-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
  <div><?= e($flashSuccess) ?></div>
</div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
<div class="alert alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
  <div><?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?></div>
</div>
<?php endif; ?>

<form method="post" class="admin-card" style="padding:22px; max-width:760px;">
  <?= csrfField() ?>
  <?php foreach ($fields as $key => $label): ?>
  <div class="form-group">
    <label><?= e($label) ?></label>
    <input type="text" name="<?= e($key) ?>" class="form-control" value="<?= e($current[$key] ?? '') ?>">
  </div>
  <?php endforeach; ?>
  <button type="submit" class="btn btn-primary">Save Settings</button>
</form>

<?php include __DIR__ . '/includes/layout_footer.php'; ?>
