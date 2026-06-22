<?php
/** @var string $pageTitle */
/** @var string $activePage */
$activePage = $activePage ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? setting('site_name', 'Asian Cargo')) ?> | <?= e(setting('site_name', 'Asian Cargo')) ?></title>
<meta name="description" content="<?= e(setting('site_tagline', 'Your trusted partner in global logistics.')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/style.css">
<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%230B1F3A%22/><path d=%22M20 60 L50 30 L80 60 L65 60 L65 75 L35 75 L35 60 Z%22 fill=%22%23F2994A%22/></svg>">
</head>
<body>

<div class="topbar">
  <div class="container">
    <div class="topbar-left">
      <span class="topbar-item">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <?= e(setting('contact_phone', '+92 42 1234 5678')) ?>
      </span>
      <span class="topbar-item">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z" stroke="none"/><path d="M22 6l-10 7L2 6"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
        <?= e(setting('contact_email', 'info@asiancargo.pk')) ?>
      </span>
      <span class="topbar-item">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        <?= e(setting('office_hours', 'Mon - Sat: 9:00 AM - 7:00 PM')) ?>
      </span>
    </div>
    <div class="topbar-social">
      <a href="<?= e(setting('facebook_url', '#')) ?>" aria-label="Facebook" target="_blank" rel="noopener">FB</a>
      <a href="<?= e(setting('instagram_url', '#')) ?>" aria-label="Instagram" target="_blank" rel="noopener">IG</a>
      <a href="<?= e(setting('linkedin_url', '#')) ?>" aria-label="LinkedIn" target="_blank" rel="noopener">LI</a>
    </div>
  </div>
</div>

<header class="site-header">
  <div class="container">
    <a href="/" class="logo">
      <span class="logo-mark">
        <svg viewBox="0 0 24 24" fill="none" stroke="#F2994A" stroke-width="2"><path d="M20 8l-8-5-8 5v8l8 5 8-5V8z"/><path d="M12 12l8-5M12 12v9M12 12L4 7"/></svg>
      </span>
      <span>
        <?= e(setting('site_name', 'Asian Cargo')) ?>
        <span class="logo-sub">LOGISTICS &amp; FREIGHT</span>
      </span>
    </a>

    <nav class="main-nav" id="mainNav">
      <ul>
        <li><a href="/" class="<?= $activePage === 'home' ? 'active' : '' ?>">Home</a></li>
        <li><a href="/services.php" class="<?= $activePage === 'services' ? 'active' : '' ?>">Services</a></li>
        <li><a href="/track.php" class="<?= $activePage === 'track' ? 'active' : '' ?>">Track Shipment</a></li>
        <li><a href="/branches.php" class="<?= $activePage === 'branches' ? 'active' : '' ?>">Branches</a></li>
        <li><a href="/about.php" class="<?= $activePage === 'about' ? 'active' : '' ?>">About Us</a></li>
        <li><a href="/contact.php" class="<?= $activePage === 'contact' ? 'active' : '' ?>">Contact</a></li>
      </ul>
      <a href="/quote.php" class="btn btn-navy btn-sm">Get a Quote</a>
    </nav>

    <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false">
      <span></span>
    </button>
  </div>
</header>
