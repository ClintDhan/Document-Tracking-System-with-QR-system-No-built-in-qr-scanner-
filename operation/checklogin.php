<?php
session_start();
require_once '../db.php';

$name = $_POST['name'];
$password = $_POST['password'];
$performed = 'Logged In';

// Default redirect after login
$redirect = '../user/user-home.php';
if (!empty($_POST['redirect'])) {
    $redirect = urldecode($_POST['redirect']);
}

// Prepare login redirect for error cases
$loginRedirect = '../login.php';
if (!empty($_POST['redirect'])) {
    $loginRedirect .= '?redirect=' . urlencode($_POST['redirect']);
}

// SELECT 
$stmt = $conn->prepare("SELECT * FROM user WHERE name = ? AND password = ?");
$stmt->bind_param("ss", $name, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if ($password === $user['password']) {
        if ($user['is_active'] == 1) {

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // FIRST LOGIN CHECK
            if ($user['first_login'] == 0) {
                $firstLoginRedirect = !empty($_POST['redirect']) ? '?redirect=' . urlencode($_POST['redirect']) : '';
                header("Location: ../first-login.php$firstLoginRedirect");
                exit();
            }

            // ALREADY LOGGED IN → use QR redirect if available
            if (!empty($_POST['redirect'])) {
                $stmt = $conn->prepare("INSERT INTO auth_logs (performed, user_id) VALUES (?, ?)");
                $stmt->bind_param("ss", $performed, $_SESSION['user_id']);
                $stmt->execute();

                header("Location: $redirect");
                exit();
            }

            // DEFAULT DASHBOARD
            if ($user['role'] == "superadmin" || $user['role'] == "admin") {
                $stmt = $conn->prepare("INSERT INTO auth_logs (performed, user_id) VALUES (?, ?)");
                $stmt->bind_param("ss", $performed, $_SESSION['user_id']);
                $stmt->execute();

                header("Location: ../admin/admin-dashboard.php");
                exit();
            } else {
                $stmt = $conn->prepare("INSERT INTO auth_logs (performed, user_id) VALUES (?, ?)");
                $stmt->bind_param("ss", $performed, $_SESSION['user_id']);
                $stmt->execute();

                header("Location: ../user/user-home.php");
                exit();
            }

        } else {
            // Account inactive
            $_SESSION['error'] = "Your account was tagged as Inactive. Please reach out to your administrator";
            header("Location: $loginRedirect");
            exit();
        }
    } else {
        // Invalid password
        $_SESSION['error'] = "Invalid Password";
        header("Location: $loginRedirect");
        exit();
    }
} else {
    // Invalid username
    $_SESSION['error'] = "Invalid username or password";
    header("Location: $loginRedirect");
    exit();
}

$conn->close();
?>