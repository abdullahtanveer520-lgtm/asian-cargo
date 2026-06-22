<?php
/**
 * Include this at the very top of every protected admin page.
 * Redirects to login if not authenticated.
 */
require_once __DIR__ . '/../../config/bootstrap.php';

if (empty($_SESSION['admin_id'])) {
    redirect('/admin/login.php');
}

function currentAdmin(): array
{
    static $admin = null;
    if ($admin === null) {
        $stmt = getDB()->prepare('SELECT id, full_name, username, email, role FROM admins WHERE id = ?');
        $stmt->execute([$_SESSION['admin_id']]);
        $admin = $stmt->fetch();

        if (!$admin) {
            session_destroy();
            redirect('/admin/login.php');
        }
    }
    return $admin;
}
