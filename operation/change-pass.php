<?php
session_start();
require_once '../db.php';

if(isset($_POST['submit'])) {

    $newPass = $_POST['newPass'];
    $reEnterPass = $_POST['rePass'];
    $performed = 'Logged In';
    $redirect = urldecode($_POST['redirect'] ?? '');

    // Get current user
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if((empty($newPass) || empty($reEnterPass)) && !empty($redirect)) {
        $_SESSION['error'] = "Password cannot be empty.";
        header("Location: ../first-login.php?redirect=" . urlencode($redirect));
        exit();
    }

    if((empty($newPass) || empty($reEnterPass)) && empty($redirect)) {
        $_SESSION['error'] = "Password cannot be empty.";
        header("Location: ../first-login.php");
        exit();
    }

    if ($newPass === $reEnterPass) {

        // Update password
        $stmt = $conn->prepare("UPDATE user SET first_login = 1, password = ? WHERE id = ?");
        $stmt->bind_param("si", $newPass, $_SESSION['user_id']);
        $stmt->execute();

        // Log once only
        $stmt = $conn->prepare("INSERT INTO auth_logs (performed, user_id) VALUES (?, ?)");
        $stmt->bind_param("si", $performed, $_SESSION['user_id']);
        $stmt->execute();

        // ✅ Handle redirect
        if (!empty($redirect)) {
            header("Location: $redirect");
            exit;
        }

        // ✅ Default fallback (no redirect)
        if ($row['role'] === 'superadmin' || $row['role'] === 'admin') {
            header("Location: ../admin/admin-dashboard.php");
        } else {
            header("Location: ../user/user-home.php");
        }
        exit;

    } else {
        $_SESSION['error'] = "Both passwords did not match, please double check.";
        header("Location: ../first-login.php?redirect=" . urlencode($redirect));
        exit();
    }

} else {
    echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set');
    exit;
}
?>