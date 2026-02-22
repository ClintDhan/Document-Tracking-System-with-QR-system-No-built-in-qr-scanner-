<?php
session_start();
require_once '../db.php';

$sql ="
    SELECT 
        document.id,
        document.type,
        document.description,
        document.status,
        document.department,
        document.created_at,
        document.updated_at,
        document.released_to,
        document.returned_reason,
        user.name AS creator_name
    FROM document
    INNER JOIN user 
        ON document.created_by = user.id
    ORDER BY document.id DESC , document.created_at DESC
";

$result = $conn->query($sql);
$documents = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Documents</title>
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

        <div class="admin-docs-container">
            <table class='admin-docs-table'>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Department</th>
                        <th>Created by</th>
                        <th>Created at</th>
                        <th>Last Updated</th>
                        <th>Released To</th>
                        <th>Returned Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $doc): ?>
                    <tr>
                        <td><?= htmlspecialchars($doc['id']) ?></td>
                        <td><?= htmlspecialchars($doc['type']) ?></td>
                        <td><?= htmlspecialchars($doc['description']) ?></td>
                        <td><?= htmlspecialchars($doc['status']) ?></td>
                        <td><?= htmlspecialchars($doc['department']) ?></td>
                        <td><?= htmlspecialchars($doc['creator_name']) ?></td>
                        <td><?= htmlspecialchars($doc['created_at']) ?></td>
                        <td><?= htmlspecialchars($doc['updated_at']) ?></td>
                        <td><?= htmlspecialchars($doc['released_to']) ?></td>
                        <td><?= htmlspecialchars($doc['returned_reason']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
</body>
</html>