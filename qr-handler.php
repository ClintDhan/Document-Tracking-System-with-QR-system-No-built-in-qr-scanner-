<?php
session_start();
require_once 'db.php';

if (!isset($_GET['control'])) {
    die("No QR scanned.");
}

$control = $_GET['control'];

// Absolute base path for your project
$base = '/DocumentTrackingSys';

// Logged-in users → redirect based on role
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin') {
        header("Location: $base/admin/admin-view-document.php?control=" . urlencode($control));
        exit;
    } else {
        header("Location: $base/user/user-home.php?control=" . urlencode($control));
        exit;
    }
}

// Not logged-in → redirect to login with absolute redirect
$redirect = "$base/qr-handler.php?control=" . urlencode($control);
header("Location: $base/login.php?redirect=" . urlencode($redirect));
exit;

?>