<?php 
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user = $_GET['user'] ?? null;
$sql = "SELECT * FROM user where id ='$user'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

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
        </div>

        <div class="admin-add-user-container">
                <p style="text-align: center;" class="admin-add-title">Reset User Password</p>
                <p style="text-align: center;" class="admin-add-sub">
                    Click save to reset <span class="reset-username"><?= htmlspecialchars($row['name']) ?></span>'s password
                </p>
            <div>
                <form action="../operation/resetpass.php" method="POST">
                    <input type="hidden" name='id' value="<?= $row['id'] ?>">
                    <div class="admin-add-user-flx mt-3">
                        <p>New Password</p>
                        <input type="text" value="<?= $password ?>" name="password" class="admin-add-user-input" readonly>
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
