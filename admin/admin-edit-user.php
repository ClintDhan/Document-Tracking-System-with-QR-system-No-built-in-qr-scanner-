<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "superadmin") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_GET['user'] ?? null;

$sql = "SELECT * FROM user where id = '$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
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
                <a href="admin-dashboard.php" class="">DASHBOARD</a>
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

        <div class="admin-edit-user-container">
                <p style="text-align: center;" class="admin-add-title">Enter User</p>
                <p style="text-align: center;" class="admin-add-sub">Enter new user detail</p>

            <div>
                <form action="../operation/admin-edit-user-info.php" method="POST">
                    <div class="admin-add-user-flx mt-3">
                        <p>Name</p>
                        <input type="text" value="<?= $user['name'] ?>" class="admin-add-user-input" name="name">
                    </div>

                    <div class="admin-add-user-flx">
                        <p>Status</p>
                        <select name="is_active">
                            <option value="1" <?= ($user['is_active'] == 1) ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= ($user['is_active'] == 0) ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                     <div class="admin-add-user-flx">
                        <p>Role</p>
                        <select name="is_admin">
                            <option value="1" <?= ($user['is_active'] == 0) ? 'selected' : '' ?>>User</option>
                            <option value="0"  <?= ($user['is_active'] == 1) ? 'selected' : '' ?>>Admin</option>
                        </select>                   
                    </div>
                    <button class="btn-submit" name="submit">Save</button>
                </form>
            </div>

        </div>

    </div>

    
</body>
</html>