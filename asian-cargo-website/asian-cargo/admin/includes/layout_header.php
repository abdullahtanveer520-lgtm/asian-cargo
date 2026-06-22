<?php
/** @var string $pageTitle */
/** @var string $activeNav */
$admin = currentAdmin();
$activeNav = $activeNav ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Dashboard') ?> | Admin — Asian Cargo</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-shell">
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-logo">
      <span class="logo-mark" style="width:30px;height:30px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="#F2994A" stroke-width="2"><path d="M20 8l-8-5-8 5v8l8 5 8-5V8z"/><path d="M12 12l8-5M12 12v9M12 12L4 7"/></svg>
      </span>
      Asian Cargo Admin
    </div>

    <nav class="admin-nav">
      <a href="/admin/" class="<?= $activeNav === 'dashboard' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9"/><rect x="14" y="3" width="7" height="5"/><rect x="14" y="12" width="7" height="9"/><rect x="3" y="16" width="7" height="5"/></svg>
        Dashboard
      </a>
      <a href="/admin/shipments.php" class="<?= $activeNav === 'shipments' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="7" width="15" height="10" rx="1"/><path d="M16 10h3l3 3v4h-6z"/><circle cx="5.5" cy="19.5" r="1.5"/><circle cx="17.5" cy="19.5" r="1.5"/></svg>
        Shipments
      </a>
      <a href="/admin/quotes.php" class="<?= $activeNav === 'quotes' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M9 13h6M9 17h6"/></svg>
        Quote Requests
      </a>
      <a href="/admin/messages.php" class="<?= $activeNav === 'messages' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Messages
      </a>
      <a href="/admin/branches.php" class="<?= $activeNav === 'branches' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
        Branches
      </a>

      <?php if ($admin['role'] === 'super_admin'): ?>
      <div class="nav-section-label">Administration</div>
      <a href="/admin/admins.php" class="<?= $activeNav === 'admins' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        Admin Users
      </a>
      <a href="/admin/settings.php" class="<?= $activeNav === 'settings' ? 'active' : '' ?>">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
        Site Settings
      </a>
      <?php endif; ?>

      <div class="nav-section-label">Shortcuts</div>
      <a href="/" target="_blank">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6M10 14 21 3"/></svg>
        View Website
      </a>
    </nav>

    <div class="admin-sidebar-footer">
      <div class="admin-user">
        <div class="admin-avatar"><?= e(strtoupper(substr($admin['full_name'], 0, 1))) ?></div>
        <div>
          <div class="name"><?= e($admin['full_name']) ?></div>
          <div class="role"><?= e(str_replace('_', ' ', $admin['role'])) ?></div>
        </div>
      </div>
      <form action="/admin/logout.php" method="post">
        <?= csrfField() ?>
        <button type="submit" class="admin-logout">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5M21 12H9"/></svg>
          Log Out
        </button>
      </form>
    </div>
  </aside>

  <main class="admin-main">
    <div class="admin-topbar">
      <div style="display:flex; align-items:center; gap:14px;">
        <button class="admin-mobile-toggle" id="sidebarToggle" aria-label="Toggle menu">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
        <h1><?= e($pageTitle ?? 'Dashboard') ?></h1>
      </div>
    </div>
    <div class="admin-content">
