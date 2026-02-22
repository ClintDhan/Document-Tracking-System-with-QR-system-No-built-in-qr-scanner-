<?php
session_start();
require_once "../db.php";

$sql = "SELECT qr_code.id,
                qr_code.control_num,
                qr_code.is_used,
                user.name AS creator
                
        FROM qr_code INNER JOIN user on qr_code.created_by = user.id";

$result = $conn->query($sql);
$qr = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin QR Management</title>
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
                <a href="admin-qr.php" class="active">QR MANAGEMENT</a>
                <a href="admin-user.php">USERS</a>
            </div>

            <div class="admin-logout">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
            </div>
        </div>

        <div class="admin-qr-container">
           <table class="qr-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Control Number</th>
                        <th>Used/Not Used</th>
                        <th>Created By</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($qr as $qrcode): ?>
                        <tr>
                            <td><?= htmlspecialchars($qrcode['id']) ?></td>
                            <td><?= htmlspecialchars($qrcode['control_num']) ?></td>
                            <td> <?php if($qrcode['is_used'] == 0) {
                                        echo "Not used";
                                    } 
                                    else {
                                        echo "Used";
                                    }?></td>
                            <td><?= htmlspecialchars($qrcode['creator']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
           </table>
        </div>

    </div>
    
</body>
</html>