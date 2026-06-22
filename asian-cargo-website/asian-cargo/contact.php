<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageTitle = 'Contact Us';
$activePage = 'contact';

$errors = [];
$success = false;
$values = ['full_name' => '', 'email' => '', 'phone' => '', 'subject' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfVerify()) {
        $errors[] = 'Your session expired. Please try submitting the form again.';
    }

    foreach ($values as $key => $_) {
        $values[$key] = trim($_POST[$key] ?? '');
    }

    if ($values['full_name'] === '') $errors[] = 'Please enter your full name.';
    if ($values['email'] === '' || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if ($values['message'] === '') $errors[] = 'Please enter a message.';

    if (empty($errors)) {
        $stmt = getDB()->prepare(
            'INSERT INTO contact_messages (full_name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $values['full_name'],
            $values['email'],
            $values['phone'] !== '' ? $values['phone'] : null,
            $values['subject'] !== '' ? $values['subject'] : null,
            $values['message'],
        ]);
        $success = true;
        $values = array_map(fn() => '', $values);
    }
}

$branches = getDB()->query('SELECT * FROM branches ORDER BY is_head_office DESC LIMIT 1')->fetch();

include __DIR__ . '/includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="/">Home</a> / Contact Us</p>
    <h1>Contact Us</h1>
  </div>
</section>

<section class="section-tight">
  <div class="container">
    <div class="why-grid" style="align-items:flex-start;">
      <div class="form-card">
        <h3 style="margin-bottom:6px;">Send us a message</h3>
        <p style="font-size:0.9rem; margin-bottom:24px;">We typically reply within one business day.</p>

        <?php if ($success): ?>
          <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
            <div>Thanks for reaching out! Your message has been sent — we'll get back to you shortly.</div>
          </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
          <div class="alert alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
            <div><?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?></div>
          </div>
        <?php endif; ?>

        <form action="/contact.php" method="post" novalidate>
          <?= csrfField() ?>
          <div class="form-row">
            <div class="form-group">
              <label>Full Name <span class="req">*</span></label>
              <input type="text" name="full_name" class="form-control" value="<?= e($values['full_name']) ?>" required>
            </div>
            <div class="form-group">
              <label>Phone Number</label>
              <input type="tel" name="phone" class="form-control" value="<?= e($values['phone']) ?>">
            </div>
          </div>
          <div class="form-group">
            <label>Email Address <span class="req">*</span></label>
            <input type="email" name="email" class="form-control" value="<?= e($values['email']) ?>" required>
          </div>
          <div class="form-group">
            <label>Subject</label>
            <input type="text" name="subject" class="form-control" value="<?= e($values['subject']) ?>">
          </div>
          <div class="form-group">
            <label>Message <span class="req">*</span></label>
            <textarea name="message" class="form-control" required><?= e($values['message']) ?></textarea>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Send Message</button>
        </form>
      </div>

      <div>
        <span class="eyebrow">Get In Touch</span>
        <h2 class="section-title">We'd love to hear from you</h2>
        <p>Reach out by phone, email or WhatsApp — or visit our head office in person.</p>

        <div class="why-list" style="margin-top:24px;">
          <div class="why-item">
            <div class="why-num"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
            <div><h4>Phone</h4><p><?= e(setting('contact_phone', '+92 42 1234 5678')) ?></p></div>
          </div>
          <div class="why-item">
            <div class="why-num"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 6 12 13 2 6"/></svg></div>
            <div><h4>Email</h4><p><?= e(setting('contact_email', 'info@asiancargo.pk')) ?></p></div>
          </div>
          <div class="why-item">
            <div class="why-num"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div><h4>Head Office</h4><p><?= e($branches['address'] ?? 'Lahore, Pakistan') ?></p></div>
          </div>
          <div class="why-item">
            <div class="why-num"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg></div>
            <div><h4>Office Hours</h4><p><?= e(setting('office_hours', 'Mon - Sat: 9:00 AM - 7:00 PM')) ?></p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
