<?php
require_once __DIR__ . '/includes/auth.php';
$pageTitle = 'Branches';
$activeNav = 'branches';

$db = getDB();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfVerify()) {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $bid = (int) ($_POST['id'] ?? 0);
        $name = trim($_POST['branch_name'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $isHead = isset($_POST['is_head_office']) ? 1 : 0;

        if ($name === '' || $city === '' || $address === '' || $phone === '') {
            $errors[] = 'Branch name, city, address and phone are required.';
        }

        if (empty($errors)) {
            if ($isHead) {
                $db->exec('UPDATE branches SET is_head_office = 0');
            }
            if ($bid > 0) {
                $stmt = $db->prepare('UPDATE branches SET branch_name=?, city=?, address=?, phone=?, email=?, is_head_office=? WHERE id=?');
                $stmt->execute([$name, $city, $address, $phone, $email ?: null, $isHead, $bid]);
                flash('success', 'Branch updated.');
            } else {
                $maxOrder = $db->query('SELECT COALESCE(MAX(display_order),0) m FROM branches')->fetch()['m'];
                $stmt = $db->prepare('INSERT INTO branches (branch_name, city, address, phone, email, is_head_office, display_order) VALUES (?, ?, ?, ?, ?, ?, ?)');
                $stmt->execute([$name, $city, $address, $phone, $email ?: null, $isHead, $maxOrder + 1]);
                flash('success', 'Branch added.');
            }
            redirect('/admin/branches.php');
        }
    } elseif ($action === 'delete') {
        $bid = (int) ($_POST['id'] ?? 0);
        $stmt = $db->prepare('DELETE FROM branches WHERE id = ?');
        $stmt->execute([$bid]);
        flash('success', 'Branch deleted.');
        redirect('/admin/branches.php');
    }
}

$editId = (int) ($_GET['edit'] ?? 0);
$editBranch = null;
if ($editId > 0) {
    $stmt = $db->prepare('SELECT * FROM branches WHERE id = ?');
    $stmt->execute([$editId]);
    $editBranch = $stmt->fetch();
}

$branches = $db->query('SELECT * FROM branches ORDER BY is_head_office DESC, display_order ASC')->fetchAll();
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

<div class="why-grid" style="align-items:flex-start; gap:24px; grid-template-columns: 1fr 1.3fr;">
  <div class="admin-card">
    <div class="admin-card-head"><h3><?= $editBranch ? 'Edit Branch' : 'Add Branch' ?></h3></div>
    <form method="post" style="padding:22px;">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="save">
      <?php if ($editBranch): ?><input type="hidden" name="id" value="<?= $editBranch['id'] ?>"><?php endif; ?>

      <div class="form-group">
        <label>Branch Name <span class="req">*</span></label>
        <input type="text" name="branch_name" class="form-control" value="<?= e($editBranch['branch_name'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>City <span class="req">*</span></label>
        <input type="text" name="city" class="form-control" value="<?= e($editBranch['city'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Address <span class="req">*</span></label>
        <input type="text" name="address" class="form-control" value="<?= e($editBranch['address'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Phone <span class="req">*</span></label>
        <input type="text" name="phone" class="form-control" value="<?= e($editBranch['phone'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= e($editBranch['email'] ?? '') ?>">
      </div>
      <div class="form-group">
        <label style="display:flex; align-items:center; gap:8px; font-weight:500;">
          <input type="checkbox" name="is_head_office" value="1" <?= !empty($editBranch['is_head_office']) ? 'checked' : '' ?> style="width:auto;">
          Set as Head Office
        </label>
      </div>
      <div style="display:flex; gap:10px;">
        <button type="submit" class="btn btn-primary"><?= $editBranch ? 'Save Changes' : 'Add Branch' ?></button>
        <?php if ($editBranch): ?><a href="/admin/branches.php" class="btn btn-outline" style="color:var(--navy-900); border-color:var(--slate-200);">Cancel</a><?php endif; ?>
      </div>
    </form>
  </div>

  <div class="admin-card">
    <div class="admin-card-head"><h3>All Branches</h3></div>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead><tr><th>Name</th><th>City</th><th>Phone</th><th></th></tr></thead>
        <tbody>
          <?php foreach ($branches as $b): ?>
          <tr>
            <td><?= e($b['branch_name']) ?> <?php if ($b['is_head_office']): ?><span class="branch-tag">HQ</span><?php endif; ?></td>
            <td><?= e($b['city']) ?></td>
            <td><?= e($b['phone']) ?></td>
            <td>
              <div class="table-actions">
                <a href="/admin/branches.php?edit=<?= $b['id'] ?>" class="icon-btn" title="Edit">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </a>
                <form method="post" onsubmit="return confirm('Delete this branch?');">
                  <?= csrfField() ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $b['id'] ?>">
                  <button type="submit" class="icon-btn danger" title="Delete">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6h14z"/></svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/layout_footer.php'; ?>
