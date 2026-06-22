<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Messages';
$activeNav = 'messages';

$db = getDB();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfVerify()) {
    $mid = (int) ($_POST['id'] ?? 0);
    $action = $_POST['action'] ?? '';
    if ($action === 'mark_read' && $mid > 0) {
        $stmt = $db->prepare('UPDATE contact_messages SET is_read = 1 WHERE id = ?');
        $stmt->execute([$mid]);
    } elseif ($action === 'delete' && $mid > 0) {
        $stmt = $db->prepare('DELETE FROM contact_messages WHERE id = ?');
        $stmt->execute([$mid]);
        flash('success', 'Message deleted.');
    }
    redirect('/admin/messages.php');
}

$messages = $db->query('SELECT * FROM contact_messages ORDER BY is_read ASC, created_at DESC')->fetchAll();
$flashSuccess = flash('success');

include __DIR__ . '/includes/layout_header.php';
?>

<?php if ($flashSuccess): ?>
<div class="alert alert-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
  <div><?= e($flashSuccess) ?></div>
</div>
<?php endif; ?>

<?php if (empty($messages)): ?>
  <div class="admin-card"><div class="empty-state">
    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    <p>No messages yet.</p>
  </div></div>
<?php endif; ?>

<div style="display:flex; flex-direction:column; gap:14px;">
  <?php foreach ($messages as $m): ?>
  <div class="admin-card" style="<?= $m['is_read'] ? '' : 'border-left:3px solid var(--amber-500);' ?>">
    <div style="padding:20px 22px;">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:14px; flex-wrap:wrap; margin-bottom:10px;">
        <div>
          <strong><?= e($m['full_name']) ?></strong>
          <?php if (!$m['is_read']): ?><span class="badge" style="background:var(--amber-100); color:var(--amber-600); margin-left:8px;">New</span><?php endif; ?>
          <div style="font-size:0.82rem; color:var(--slate-400);"><?= e($m['email']) ?><?= $m['phone'] ? ' · ' . e($m['phone']) : '' ?></div>
        </div>
        <div style="font-size:0.78rem; color:var(--slate-400);"><?= e(formatDate($m['created_at'])) ?></div>
      </div>
      <?php if ($m['subject']): ?><p style="font-weight:600; margin-bottom:4px;"><?= e($m['subject']) ?></p><?php endif; ?>
      <p style="margin-bottom:14px;"><?= nl2br(e($m['message'])) ?></p>
      <div style="display:flex; gap:8px;">
        <?php if (!$m['is_read']): ?>
        <form method="post"><?= csrfField() ?><input type="hidden" name="action" value="mark_read"><input type="hidden" name="id" value="<?= $m['id'] ?>">
          <button type="submit" class="btn btn-sm btn-navy">Mark as Read</button>
        </form>
        <?php endif; ?>
        <a href="mailto:<?= e($m['email']) ?>" class="btn btn-sm btn-outline" style="color:var(--navy-900); border-color:var(--slate-200);">Reply by Email</a>
        <form method="post" onsubmit="return confirm('Delete this message?');"><?= csrfField() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $m['id'] ?>">
          <button type="submit" class="btn btn-sm" style="background:var(--red-100); color:var(--red-600);">Delete</button>
        </form>
      </div>
    </div>
  </div>
  <?php endforeach; ?>
</div>

<?php include __DIR__ . '/includes/layout_footer.php'; ?>
