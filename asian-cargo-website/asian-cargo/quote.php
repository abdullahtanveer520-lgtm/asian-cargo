<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageTitle = 'Get a Quote';
$activePage = 'quote';

$errors = [];
$success = false;

$values = [
    'full_name'   => '',
    'email'       => '',
    'phone'       => '',
    'service_type'=> $_GET['service'] ?? 'air_freight',
    'origin_city' => '',
    'destination_city' => '',
    'weight_kg'   => '',
    'package_description' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfVerify()) {
        $errors[] = 'Your session expired. Please try submitting the form again.';
    }

    foreach ($values as $key => $_) {
        $values[$key] = trim($_POST[$key] ?? '');
    }

    if ($values['full_name'] === '') $errors[] = 'Please enter your full name.';
    if ($values['email'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if ($values['phone'] === '') $errors[] = 'Please enter a phone number.';
    if ($values['origin_city'] === '') $errors[] = 'Please enter the origin city.';
    if ($values['destination_city'] === '') $errors[] = 'Please enter the destination city.';
    if (!in_array($values['service_type'], ['air_freight','ocean_freight','express_courier','road_freight'], true)) {
        $errors[] = 'Please select a valid service type.';
    }

    if (empty($errors)) {
        $stmt = getDB()->prepare(
            'INSERT INTO quote_requests (full_name, email, phone, service_type, origin_city, destination_city, weight_kg, package_description)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $values['full_name'],
            $values['email'],
            $values['phone'],
            $values['service_type'],
            $values['origin_city'],
            $values['destination_city'],
            $values['weight_kg'] !== '' ? $values['weight_kg'] : null,
            $values['package_description'] !== '' ? $values['package_description'] : null,
        ]);

        $success = true;
        $values = array_map(fn() => '', $values);
        $values['service_type'] = 'air_freight';
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="/">Home</a> / Get a Quote</p>
    <h1>Request a Free Quote</h1>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="form-card" style="max-width:760px; margin:0 auto;">

      <?php if ($success): ?>
        <div class="alert alert-success">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
          <div>Thank you! Your quote request has been received. Our team will contact you shortly at the email or phone number you provided.</div>
        </div>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
          <div>
            <?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <form action="/quote.php" method="post" novalidate>
        <?= csrfField() ?>

        <div class="form-row">
          <div class="form-group">
            <label>Full Name <span class="req">*</span></label>
            <input type="text" name="full_name" class="form-control" value="<?= e($values['full_name']) ?>" required>
          </div>
          <div class="form-group">
            <label>Phone Number <span class="req">*</span></label>
            <input type="tel" name="phone" class="form-control" value="<?= e($values['phone']) ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label>Email Address <span class="req">*</span></label>
          <input type="email" name="email" class="form-control" value="<?= e($values['email']) ?>" required>
        </div>

        <div class="form-group">
          <label>Service Type <span class="req">*</span></label>
          <select name="service_type" class="form-control" required>
            <option value="air_freight" <?= $values['service_type'] === 'air_freight' ? 'selected' : '' ?>>Air Freight</option>
            <option value="ocean_freight" <?= $values['service_type'] === 'ocean_freight' ? 'selected' : '' ?>>Ocean Freight</option>
            <option value="express_courier" <?= $values['service_type'] === 'express_courier' ? 'selected' : '' ?>>Express Courier</option>
            <option value="road_freight" <?= $values['service_type'] === 'road_freight' ? 'selected' : '' ?>>Road Freight</option>
          </select>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Origin City <span class="req">*</span></label>
            <input type="text" name="origin_city" class="form-control" placeholder="e.g. Lahore" value="<?= e($values['origin_city']) ?>" required>
          </div>
          <div class="form-group">
            <label>Destination City <span class="req">*</span></label>
            <input type="text" name="destination_city" class="form-control" placeholder="e.g. Dubai" value="<?= e($values['destination_city']) ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label>Approximate Weight (kg)</label>
          <input type="number" step="0.1" min="0" name="weight_kg" class="form-control" value="<?= e($values['weight_kg']) ?>">
          <p class="form-hint">Optional — leave blank if you're not sure yet.</p>
        </div>

        <div class="form-group">
          <label>Package Description</label>
          <textarea name="package_description" class="form-control" placeholder="What are you shipping?"><?= e($values['package_description']) ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-block">Submit Quote Request</button>
      </form>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
