<?php
require_once __DIR__ . '/config/bootstrap.php';
$pageTitle = 'Our Branches';
$activePage = 'branches';

$branches = getDB()->query('SELECT * FROM branches ORDER BY is_head_office DESC, display_order ASC')->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<section class="page-header">
  <div class="container">
    <p class="breadcrumb"><a href="/">Home</a> / Branches</p>
    <h1>Our Branches</h1>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="section-head center">
      <span class="eyebrow">Find Us</span>
      <h2 class="section-title">Visit a branch near you</h2>
      <p class="section-sub">Walk in to book a shipment, ask about a quote, or get help with an existing booking.</p>
    </div>

    <div class="branches-grid">
      <?php foreach ($branches as $branch): ?>
      <div class="branch-card <?= $branch['is_head_office'] ? 'head-office' : '' ?>">
        <div class="branch-pin">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div>
          <h4>
            <?= e($branch['branch_name']) ?>
            <?php if ($branch['is_head_office']): ?><span class="branch-tag">Head Office</span><?php endif; ?>
          </h4>
          <p><?= e($branch['address']) ?></p>
          <p class="branch-phone"><?= e($branch['phone']) ?></p>
          <?php if ($branch['email']): ?><p><?= e($branch['email']) ?></p><?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>

      <?php if (empty($branches)): ?>
        <p>No branches have been added yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
