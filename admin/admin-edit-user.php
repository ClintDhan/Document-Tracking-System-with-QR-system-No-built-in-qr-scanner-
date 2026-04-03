<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
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
                <a href="" class="admin-logo-title">MAYOR'S OFFICE DTS</a>
                <p class="admin-logo-sub">ADMIN</p>
            </div>

            <div class="nav-anchor">
                <a href="admin-dashboard.php">DASHBOARD</a>
                <a href="admin-document.php">DOCUMENTS</a>

                <div class="dropdown">
                    <p class="logs-text">LOGS ▾</p>
                    <div class="dropdown-content">
                        <a href="admin-auth-log.php">AUTHENTICATION LOGS</a>
                        <a href="admin-logs.php">DOCUMENT LOGS</a>
                        <a href="admin-user-log.php">USER LOGS</a>
                    </div>
                </div>

                <a href="admin-qr.php">QR MANAGEMENT</a>
                <a href="admin-user.php" class="active">USERS</a>
            </div>

            <div class="admin-logout">
                <form action="../operation/logout.php" method="POST">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
                </form>
            </div>
            <div class="burger-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
            </div>
        </div>

        <div class="admin-edit-user-container">
                <p style="text-align: center;" class="admin-add-title">Enter User</p>
                <p style="text-align: center;" class="admin-add-sub">Enter new user detail</p>

            <div>
                <form action="../operation/admin-edit-user-info.php" method="POST">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <div class="admin-add-user-flx mt-3">
                        <p>Name</p>
                        <input type="text" value="<?= $user['name'] ?>" class="admin-add-user-input" name="name">
                    </div>

                    <div class="admin-add-user-flx mt-2">
                        <p>Status</p>
                        <select name="is_active">
                            <option value="1" <?= ($user['is_active'] == 1) ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= ($user['is_active'] == 0) ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>

                     <div class="admin-add-user-flx mt-2">
                        <p>Role</p>
                        <select name="role">
                            <option value="superadmin" <?= ($user['role'] == 'superadmin') ? 'selected' : '' ?>>Superadmin</option>
                            <option value="admin"  <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                            <option value="user"  <?= ($user['role'] == 'user') ? 'selected' : '' ?>>User</option>
                        </select>                   
                    </div>
                    <button class="btn-submit" name="submit">Save</button>
                </form>
            </div>
        <button class="adm-bck-btn" onclick="window.location.href='admin-user.php'">
  ❮ BACK
</button>
        </div>

    </div>

    <script>
         document.addEventListener("click", function (e) {
    const dropdown = document.querySelector(".dropdown");

    if (dropdown.contains(e.target)) {
        dropdown.querySelector(".dropdown-content").classList.toggle("show");
    } else {
        dropdown.querySelector(".dropdown-content").classList.remove("show");
    }
    });
    </script>
</body>
</html>