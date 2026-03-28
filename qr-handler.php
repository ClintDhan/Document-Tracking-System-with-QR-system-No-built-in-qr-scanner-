<?php
session_start();
require_once 'db.php';

if (!isset($_GET['control'])) {
    die("No QR scanned.");
}

$control = $_GET['control'];
$sql = "SELECT * FROM qr_code WHERE control_num = '$control'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

// Absolute base path for your project
$base = '/DocumentTrackingSys';

// Logged-in users → redirect based on role
if (isset($_SESSION['user_id'])) {
    if (($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin') && $row['is_used'] == 1) {
        header("Location: $base/admin/admin-view-document.php?control=" . urlencode($control));
        exit;
    }
    elseif(($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'superadmin') && $row['is_used'] == 0) {
        header("Location: $base/admin/admin-receive-document.php?control=" . urlencode($control));
        exit;
    }
    else {
        header("Location: $base/user/user-home.php?control=" . urlencode($control));
        exit;
    }
}

// Not logged-in → redirect to login with absolute redirect
$redirect = "$base/qr-handler.php?control=" . urlencode($control);
header("Location: $base/login.php?redirect=" . urlencode($redirect));
exit;

?>