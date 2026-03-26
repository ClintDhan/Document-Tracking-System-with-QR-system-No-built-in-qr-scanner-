<?php
session_start();
require_once '../db.php';

if(isset($_POST['submit'])) {

    $newPass = $_POST['newPass'];
    $reEnterPass = $_POST['rePass'];

    // Get current user
    $sql = "SELECT * FROM user WHERE id = ".$_SESSION['user_id'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if(empty($newPass) || empty($reEnterPass)) {
        echo "Password cannot be empty";
        exit;
    }

    if($newPass === $reEnterPass) {

        // Update password and mark first_login complete
        $updatePass = "UPDATE user SET first_login = 1, password = '$newPass' WHERE id =".$_SESSION['user_id'];
        $conn->query($updatePass);

        // ✅ Handle redirect from QR or login
        $redirect = urldecode($_GET['redirect'] ?? '');

        if(!empty($redirect)) {
            header("Location: $redirect");
            exit;
        }

        // Default behavior if no redirect
        if($row['role'] === 'superadmin' || $row['role'] === 'admin') {
            header("Location: ../admin/admin-dashboard.php");
            exit;
        } else {
            header("Location: ../user/user-home.php");
            exit;
        }

    } else {
        echo "Both passwords did not match, please double check";
        exit;
    }

} else {
    echo "User ID: " . ($_SESSION['user_id'] ?? 'Not set');
    exit;
}
?>