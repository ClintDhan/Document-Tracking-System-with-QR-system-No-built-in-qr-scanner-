<?php 
session_start();
require_once '../db.php';

$doc = $_GET['doc'] ?? null;
$sql = "SELECT * FROM document where id = '$doc'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Document</title>
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
                        <a href="admin-document.php" class="active">DOCUMENTS</a>
                        <a href="admin-logs.php">LOGS</a>
                        <a href="admin-qr.php">QR MANAGEMENT</a>
                        <a href="admin-user.php">USERS</a>
                    </div>

                    <div class="admin-logout">
                        <button class='log-out admin-logout'>↪ LOGOUT</button>
                    </div>
                </div>
            <div class="doc-edit-container mt-2">
                <p>Edit document</p>
                <form action="../operation/admin-edit-document.php" method="POST">
                    <input type="text" name='type' value=<?= $row['type'] ?>>
                    <input type="text" name='description' value=<?= $row['description'] ?>>
                    <input type="text" name='department' value=<?= $row['department'] ?>>
                    <input type="text" name='released_to' value=<?= $row['released_to'] ?>>
                    <input type="text" name='returned_reason' value=<?= $row['returned_reason'] ?>>
                </form>
            </div>
        </div>

</body>
</html>