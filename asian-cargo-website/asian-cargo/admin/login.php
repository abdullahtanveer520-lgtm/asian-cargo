<?php
require_once __DIR__ . '/../config/bootstrap.php';

if (!empty($_SESSION['admin_id'])) {
    redirect('/admin/');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrfVerify()) {
        $error = 'Your session expired. Please try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = (string) ($_POST['password'] ?? '');

        // Basic throttling against brute force
        $_SESSION['login_attempts'] = $_SESSION['login_attempts'] ?? 0;
        $_SESSION['login_window'] = $_SESSION['login_window'] ?? time();

        if (time() - $_SESSION['login_window'] > 600) {
            $_SESSION['login_attempts'] = 0;
            $_SESSION['login_window'] = time();
        }

        if ($_SESSION['login_attempts'] >= 8) {
            $error = 'Too many failed attempts. Please wait a few minutes and try again.';
        } elseif ($username === '' || $password === '') {
            $error = 'Please enter both username and password.';
        } else {
            $stmt = getDB()->prepare('SELECT * FROM admins WHERE username = ? AND is_active = 1');
            $stmt->execute([$username]);
            $admin = $stmt->fetch();

            if ($admin && password_verify($password, $admin['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['login_attempts'] = 0;

                $upd = getDB()->prepare('UPDATE admins SET last_login_at = NOW() WHERE id = ?');
                $upd->execute([$admin['id']]);

                redirect('/admin/');
            } else {
                $_SESSION['login_attempts']++;
                $error = 'Invalid username or password.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Asian Cargo</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/css/style.css">
<link rel="stylesheet" href="/css/admin.css">
</head>
<body>
<div class="login-page">
  <div class="login-card">
    <div class="logo">
      <span class="logo-mark">
        <svg viewBox="0 0 24 24" fill="none" stroke="#F2994A" stroke-width="2"><path d="M20 8l-8-5-8 5v8l8 5 8-5V8z"/><path d="M12 12l8-5M12 12v9M12 12L4 7"/></svg>
      </span>
      Asian Cargo
    </div>
    <h2>Admin Login</h2>
    <p class="sub">Sign in to manage shipments and bookings</p>

    <?php if ($error): ?>
      <div class="alert alert-error">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4M12 17h.01"/></svg>
        <div><?= e($error) ?></div>
      </div>
    <?php endif; ?>

    <form action="/admin/login.php" method="post" novalidate>
      <?= csrfField() ?>
      <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required autofocus autocomplete="username">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required autocomplete="current-password">
      </div>
      <button type="submit" class="btn btn-primary btn-block">Log In</button>
    </form>

    <p style="text-align:center; margin-top:20px;">
      <a href="/" style="font-size:0.85rem; color:var(--slate-400);">&larr; Back to website</a>
    </p>
  </div>
</div>
</body>
</html>
