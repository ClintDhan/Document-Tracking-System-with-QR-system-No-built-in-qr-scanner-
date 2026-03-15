<?php

session_start();
require_once '../db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sub admin</title>
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">

</head>
<body>
    <div class='user-container'>
        <div class='user-form'>

            <!-- NAV BAR -->
            <div class='user-nav-bar'>
                <div class='user-name'><p>Hi! <span class="span-name"><?= $_SESSION['name']; ?></p></div>
                <div class='user-date'><p><?= date('m/d/Y') ?></p></div>
                <form action="../operation/logout.php" method='POST'>
                    <button class='log-out'>↪ LOGOUT</button>
                </form>
            </div>

            <!-- QR STATUS -->
            <div class="qr-status-container d-flex justify-content-center align-items-center p-2">
                <?php if ($qrControl && isset($qr) && $qr['is_used'] == 0): ?>
                    <p>QR Scanned: <?= htmlspecialchars($qr['control_num']) ?></p>
                <?php elseif ($qrControl && isset($qr) && $qr['is_used'] == 1): ?>
                    <p>QR Scanned: <?= htmlspecialchars($qr['control_num']) ?> (Already Used)</p>
                <?php elseif (isset($qrError)): ?>
                    <p style="color:red;"><?= $qrError ?></p>
                <?php else: ?>
                    <p>No QR scanned</p>
                <?php endif; ?>
            </div>

            <!-- DASHBOARD -->
            <div class='user-dash'>
                <div class='user-dash-flx'>
                    <div class='user-dash-grid'>
                        <div class='user-received user-homecount-container'>
                            <p class="user-dash-count"><?= $receivedDocs ?></p>
                            <p>Received Documents</p>
                        </div>
                        <div class='user-released user-homecount-container'>
                            <p class="user-dash-count"><?= $releasedDocs ?></p>
                            <p>Released Documents</p>
                        </div>
                        <div class='user-returned user-homecount-container'>
                            <p class="user-dash-count"><?= $returnedDocs ?></p>
                            <p>Returned Documents</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>