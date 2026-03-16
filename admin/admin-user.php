<?php 
session_start();
require_once "../db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "superadmin") {
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
           <table class="user-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Role</th>
                        <th>Action</th>
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
                            <td><a class="admin-user-edit-btn" href="admin-edit-user.php?user=<?= $users['id'] ?>">Edit</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
           </table>
        </div>

    </div>
    
</body>
</html>