<?php
session_start();
require_once '../db.php';

if(isset($_POST['submit'])) {

    $conn->begin_transaction();

    try {

        $newPass = $_POST['newPass'];
        $reEnterPass = $_POST['rePass'];
        $performed = 'Logged In';
        $redirect = urldecode($_POST['redirect'] ?? '');

        $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['user_id']);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if(empty($newPass) || empty($reEnterPass)) {
            $conn->rollback();
            $_SESSION['error'] = "Password cannot be empty.";
            header("Location: ../first-login.php?redirect=" . urlencode($redirect));
            exit();
        }

        if ($newPass !== $reEnterPass) {
            $conn->rollback();
            $_SESSION['error'] = "Both passwords did not match, please double check.";
            header("Location: ../first-login.php?redirect=" . urlencode($redirect));
            exit();
        }

        // UPDATE password (plain text as requested)
        $stmt = $conn->prepare("UPDATE user 
            SET first_login = 1, password = ? 
            WHERE id = ?");
        $stmt->bind_param("si", $newPass, $_SESSION['user_id']);
        $stmt->execute();

        // INSERT log
        $stmt = $conn->prepare("INSERT INTO auth_logs (performed, user_id) VALUES (?, ?)");
        $stmt->bind_param("si", $performed, $_SESSION['user_id']);
        $stmt->execute();

        $conn->commit();

        if (!empty($redirect)) {
            header("Location: $redirect");
            exit;
        }

        if ($row['role'] === 'superadmin' || $row['role'] === 'admin') {
            header("Location: ../admin/admin-dashboard.php");
        } else {
            header("Location: ../user/user-home.php");
        }

        exit;

    } catch (Exception $e) {

        $conn->rollback();

        $_SESSION['error'] = "Something went wrong.";
        header("Location: ../first-login.php");
        exit();
    }
}
?>