<?php
session_start();
require_once '../db.php';

$sql = "SELECT 
    document_log.id,
    document_log.action,
    document_log.performed_at,
    document.type AS document_type,
    user.name AS performer
FROM document_log
INNER JOIN user 
    ON document_log.performed_by = user.id
INNER JOIN document 
    ON document_log.document_id = document.id
ORDER BY document_log.performed_at DESC;
";

$result = $conn->query($sql);
$document_log = $result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logs</title>
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
                <a href="admin-logs.php" class="active">LOGS</a>
                <a href="admin-qr.php">QR MANAGEMENT</a>
                <a href="admin-user.php">USERS</a>
            </div>

            <div class="admin-logout">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
            </div>
        </div>

        <div class="admin-logs-container">
            <div class="admin-logs-table">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Type</th>
                            <th>Action</th>
                            <th>Performed At</th>
                            <th>Performed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($document_log as $doc): ?>
                        <tr>
                            <td><?= htmlspecialchars($doc['id'])?></td>
                            <td><?= htmlspecialchars($doc['document_type'])?></td>
                            <td><?= htmlspecialchars($doc['action'])?></td>
                            <td><?= htmlspecialchars($doc['performed_at'])?></td>
                            <td><?= htmlspecialchars($doc['performer'])?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    
</body>
</html>