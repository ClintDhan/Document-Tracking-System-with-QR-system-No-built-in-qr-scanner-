<?php 
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "superadmin") {
    header("Location: ../login.php");
    exit();
}

$password = random_int(100000, 999999);
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
                <a href="admin-user-log.php">USER LOGS</a>
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
                <form action="../operation/createuser.php" method="POST">
                    <div class="admin-add-user-flx mt-3">
                        <p>User's name</p>
                        <input type="text" class="admin-add-user-input" name="name">
                    </div>

                    <div class="admin-add-user-flx">
                        <p>User's Password</p>
                        <input type="text" value="<?= $password ?>" name="password" class="admin-add-user-input" readonly>
                    </div>
                    <button class="btn-submit" name="submit">Create User</button>
                </form>
            </div>

        </div>
    </div>

    


</body>

</html>
