<?php
// ============================================================================
// EatWise - Procedural Logout Handler
// ============================================================================
require_once __DIR__ . '/db.php';

// Hapus semua data sesi
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Mulai sesi baru untuk pesan flash
session_start();
set_flash('info', 'Anda telah berhasil keluar dari akun EatWise.');
header('Location: login.php');
exit;
