<?php 
session_start();
require_once "../db.php";

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
                <a href="">MAYOR'S OFFICE DTS</a>
            </div>

            <div class="nav-anchor">
                <a href="admin-dashboard.php" class="">DASHBOARD</a>
                <a href="admin-document.php">DOCUMENTS</a>
                <a href="admin-logs.php">LOGS</a>
                <a href="admin-qr.php">QR MANAGEMENT</a>
                <a href="admin-user.php" class="active">USERS</a>
            </div>

            <div class="admin-logout">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
            </div>
        </div>

        <div class="admin-user-container">
           <table class="user-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($user as $users): ?>
                        <tr>
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
                            <td><?php 
                                    if($users['is_admin'] == 0) {
                                        echo "User";
                                    } 
                                    else {
                                        echo "Admin";
                                    }
                            ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
           </table>
        </div>

    </div>
    
</body>
</html>