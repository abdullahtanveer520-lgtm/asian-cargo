<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Admin Users';
$activeNav = 'admins';

$admin = currentAdmin();
if ($admin['role'] !== 'super_admin') {
    flash('error', 'Only super admins can manage admin users.');
    redirect('/admin/');
}

$db = getDB();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfVerify()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $fullName = trim($_POST['full_name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $role = $_POST['role'] ?? 'staff';

        if ($fullName === '') $errors[] = 'Full name is required.';
        if ($username === '' || !preg_match('/^[a-zA-Z0-9_.]{3,50}$/', $username)) $errors[] = 'Username must be 3-50 characters (letters, numbers, _ or .).';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
        if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
        if (!in_array($role, ['super_admin', 'staff'], true)) $errors[] = 'Invalid role.';

        if (empty($errors)) {
            $check = $db->prepare('SELECT id FROM admins WHERE username = ? OR email = ?');
            $check->execute([$username, $email]);
            if ($check->fetch()) {
                $errors[] = 'That username or email is already in use.';
            } else {
                $stmt = $db->prepare('INSERT INTO admins (full_name, username, email, password_hash, role) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([$fullName, $username, $email, password_hash($password, PASSWORD_BCRYPT), $role]);
                flash('success', 'Admin user created.');
                redirect('/admin/admins.php');
            }
        }
    } elseif ($action === 'toggle_active') {
        $targetId = (int) ($_POST['id'] ?? 0);
        if ($targetId !== (int) $admin['id']) {
            $stmt = $db->prepare('UPDATE admins SET is_active = 1 - is_active WHERE id = ?');
            $stmt->execute([$targetId]);
        }
        redirect('/admin/admins.php');
    } elseif ($action === 'delete') {
        $targetId = (int) ($_POST['id'] ?? 0);
        if ($targetId !== (int) $admin['id']) {
            $stmt = $db->prepare('DELETE FROM admins WHERE id = ?');
            $stmt->execute([$targetId]);
            flash('success', 'Admin user removed.');
        } else {
            flash('error', "You can't delete your own account.");
        }
        redirect('/admin/admins.php');
    }
}

$admins = $db->query('SELECT * FROM admins ORDER BY created_at ASC')->fetchAll();
$flashSuccess = flash('success');
$flashError = flash('error');

include __DIR__ . '/includes/layout_header.php';
?>

<?php if ($flashSuccess): ?>
<div class="alert alert-success">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></svg>
  <div><?= e($flashSuccess) ?></div>
</div>
<?php endif; ?>
<?php if ($flashError): ?>
<div class="alert alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
  <div><?= e($flashError) ?></div>
</div>
<?php endif; ?>
<?php if (!empty($errors)): ?>
<div class="alert alert-error">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
  <div><?php foreach ($errors as $err): ?><div><?= e($err) ?></div><?php endforeach; ?></div>
</div>
<?php endif; ?>

<div class="why-grid" style="align-items:flex-start; gap:24px; grid-template-columns: 1fr 1.3fr;">
  <div class="admin-card">
    <div class="admin-card-head"><h3>Add Admin User</h3></div>
    <form method="post" style="padding:22px;">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="create">
      <div class="form-group">
        <label>Full Name <span class="req">*</span></label>
        <input type="text" name="full_name" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Username <span class="req">*</span></label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Email <span class="req">*</span></label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="form-group">
        <label>Password <span class="req">*</span></label>
        <input type="password" name="password" class="form-control" minlength="8" required>
        <p class="form-hint">At least 8 characters.</p>
      </div>
      <div class="form-group">
        <label>Role</label>
        <select name="role" class="form-control">
          <option value="staff">Staff (manage shipments &amp; quotes)</option>
          <option value="super_admin">Super Admin (full access)</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Create Admin User</button>
    </form>
  </div>

  <div class="admin-card">
    <div class="admin-card-head"><h3>All Admin Users</h3></div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>Name</th><th>Username</th><th>Role</th><th>Status</th><th>Last Login</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($admins as $a): ?>
          <tr>
            <td><?= e($a['full_name']) ?><?= $a['id'] === $admin['id'] ? ' <span style="color:var(--slate-400); font-size:0.78rem;">(you)</span>' : '' ?></td>
            <td><?= e($a['username']) ?></td>
            <td><?= e(str_replace('_', ' ', ucfirst($a['role']))) ?></td>
            <td>
              <span class="badge" style="background:<?= $a['is_active'] ? 'var(--green-100)' : 'var(--red-100)' ?>; color:<?= $a['is_active'] ? 'var(--green-600)' : 'var(--red-600)' ?>;">
                <?= $a['is_active'] ? 'Active' : 'Disabled' ?>
              </span>
            </td>
            <td><?= $a['last_login_at'] ? e(formatDate($a['last_login_at'], 'd M, h:i A')) : 'Never' ?></td>
            <td>
              <?php if ($a['id'] !== $admin['id']): ?>
              <div class="table-actions">
                <form method="post"><?= csrfField() ?><input type="hidden" name="action" value="toggle_active"><input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <button type="submit" class="icon-btn" title="<?= $a['is_active'] ? 'Disable' : 'Enable' ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M4.93 4.93l14.14 14.14"/></svg>
                  </button>
                </form>
                <form method="post" onsubmit="return confirm('Remove this admin user?');"><?= csrfField() ?><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= $a['id'] ?>">
                  <button type="submit" class="icon-btn danger" title="Delete">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
                  </button>
                </form>
              </div>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/layout_footer.php'; ?>
