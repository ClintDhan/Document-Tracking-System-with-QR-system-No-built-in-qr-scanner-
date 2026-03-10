<?php 
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">
</head>

<body class="admin-body">
    <div class="admin-dash-container">
        <div class="admin-navbar">
            <div class="admin-logo">
                <a href="">MAYOR'S OFFICE DTS</a>
            </div>

            <div class="nav-anchor">
                <a href="admin-dashboard.php">DASHBOARD</a>
                <a href="admin-document.php">DOCUMENTS</a>
                <a href="admin-logs.php">DOCUMENT LOGS</a>
                <a href="admin-logs.php">USER LOGS</a>
                <a href="admin-qr.php">QR MANAGEMENT</a>
                <a href="admin-user.php" class="active">USERS</a>
            </div>

            <div class="admin-logout">
                <form action="../operation/logout.php" method="POST">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
                </form>
            </div>
        </div>

    <div class="admin-add-user-container">
    <p style="text-align: center;" class="admin-add-title">Add User</p>
    <p style="text-align: center;" class="admin-add-sub">Enter user detail</p>

    <div>
        <div class="admin-add-user-flx mt-3">
            <p>User's name</p>
            <input type="text" class="admin-add-user-input">
        </div>

        <div class="admin-add-user-flx">
            <p>User's Password</p>
            <input type="password" value="" class="admin-add-user-input" readonly>
        </div>

        <div class="admin-add-user-flx mt-4">
            <p class="enter-pass">Enter your Password</p>
            <p class="enter-pass-sub">Please enter your password to confirm new user details</p>
            <input type="password">
        </div>
        <input class="btn-submit" type="submit" value="Create User" placeholder="Create User">
    </div>

    </div>
    </div>

    


</body>

</html>
