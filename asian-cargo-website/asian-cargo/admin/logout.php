<?php
require_once __DIR__ . '/../config/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrfVerify()) {
    $_SESSION = [];
    session_destroy();
}

redirect('/admin/login.php');
