<?php 
session_start();
require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$sql = "SELECT * FROM user";
$result = $conn->query($sql);
$user = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin User Management</title>
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

            <div class="admin-user-container">
                <div class="d-flex justify-content-between align-items-center">
                <p style="    font-size: 2em;
                        color: #5f5f5f;
                        font-weight: 700;
                        margin-bottom: 10px;">Users</p>

                <div>
                    <a href="admin-add-user.php" class="add-user-btn">ADD USER<span>+</span></a>
                </div>

        </div>
        <div class="result">
           <table class="user-table">
                <thead>
                    <tr>
                        <th class="no-container">No</th>
                        <th class="name-container">Name</th>
                        <th class="status-container">Status</th>
                        <th class="created-container">Created At</th>
                        <th class="role-container">Role</th>
                        <th class="action-container">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($user as $users): ?>
                        <tr class="tr-hover">
                            <td><?= htmlspecialchars($users['id'])?></td>
                            <td><?= htmlspecialchars($users['name'])?></td>
                            <td><?php 
                                    if($users['is_active'] == 0) {
                                        echo "Inactive";
                                    } 
                                    else {
                                        echo "Active";
                                    }
                            ?></td>
                            <td><?= htmlspecialchars($users['created_at'])?></td>
                            <td><?= htmlspecialchars($users['role'])?></td>
                            <td>
                                <div class="d-flex">
                                    <a class="admin-user-edit-btn" href="admin-edit-user.php?user=<?= $users['id'] ?>">EDIT</a>
                                    <a class="admin-user-reset-btn" href="admin-reset-password.php?user=<?= $users['id'] ?>">RESET PASSWORD</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
           </table>
           </div>
        </div>

    </div>
    
</body>
</html>