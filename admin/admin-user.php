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

                <div class="dropdown">
                    <p class="logs-text">LOGS ▾</p>
                    <div class="dropdown-content">
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
                            <td>
                                <div class="user-status-container">
                                <?php 
                                    if($users['is_active'] == 0) {
                                        echo "<div style='background-color:#EF4444; color: white; padding: 2px; border-radius: 4px;'>Inactive</div>";
                                    } 
                                    else {
                                        echo "<div style='background-color:#22C55E; color: white; padding: 2px; border-radius: 4px;'>Active</div>";
                                    }
                            ?>
                            </div>
                        </td>
                            <td><?= htmlspecialchars($users['created_at'])?></td>
                            <td><?= htmlspecialchars($users['role'])?></td>
                            <td>
                                <div class="d-flex">
                                    <a class="admin-user-edit-btn" href="admin-edit-user.php?user=<?= $users['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                        </svg>
                                    </a>
                                    <a class="admin-user-reset-btn" href="admin-reset-password.php?user=<?= $users['id'] ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-shield-exclamation" viewBox="0 0 16 16">
                                            <path d="M5.338 1.59a61 61 0 0 0-2.837.856.48.48 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.7 10.7 0 0 0 2.287 2.233c.346.244.652.42.893.533q.18.085.293.118a1 1 0 0 0 .101.025 1 1 0 0 0 .1-.025q.114-.034.294-.118c.24-.113.547-.29.893-.533a10.7 10.7 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.8 11.8 0 0 1-2.517 2.453 7 7 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7 7 0 0 1-1.048-.625 11.8 11.8 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 63 63 0 0 1 5.072.56"/>
                                            <path d="M7.001 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.553.553 0 0 1-1.1 0z"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
           </table>
           </div>
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