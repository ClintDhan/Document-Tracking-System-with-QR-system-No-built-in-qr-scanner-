<?php
session_start();
require_once '../db.php';

$name = $_POST['name'];
$password = $_POST['password'];

// get redirect (from QR)
$redirect = '../user/user-home.php';
if (!empty($_POST['redirect'])) {
    $redirect = urldecode($_POST['redirect']);
}

$sql = "SELECT * FROM user WHERE name = '$name' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();

    if ($password === $user['password']) {
        if ($user['is_active'] == 1) {

            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // FIRST LOGIN CHECK
            if ($user['first_login'] == 0) {
                // pass redirect to first-login page
                $redirectParam = !empty($_POST['redirect']) ? '?redirect=' . urlencode($_POST['redirect']) : '';
                header("Location: ../first-login.php$redirectParam");
                exit;
            }

            // ALREADY LOGGED IN → use QR redirect if available
            if (!empty($_POST['redirect'])) {
                header("Location: $redirect");
                exit;
            }

            // DEFAULT DASHBOARD
            if ($user['role'] == "superadmin" || $user['role'] == "admin") {
                header("Location: ../admin/admin-dashboard.php");
                exit;
            } else {
                header("Location: ../user/user-home.php");
                exit;
            }

        } else {
            echo 'Your account is invalid';
        }
    } else {
        echo 'Invalid Password';
    }
} else {
    echo "Invalid username or password";
}

$conn->close();
?>