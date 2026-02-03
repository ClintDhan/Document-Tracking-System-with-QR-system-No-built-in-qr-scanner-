<?php 
session_start();
require_once '../db.php';

// ----------------------------
// 1️⃣ Check login
// ----------------------------
if (!isset($_SESSION['user_id'])) {
    $redirectUrl = $_SERVER['REQUEST_URI']; // preserves ?control=...
    header("Location: ../login.php?redirect=" . urlencode($redirectUrl));
    exit();
}

// ----------------------------
// 2️⃣ QR scan handling
// ----------------------------
$qrControl = $_GET['control'] ?? null;
$qr = null;
$qrError = null;

if ($qrControl) {
    $sql = "SELECT * FROM qr_code WHERE control_num = '$qrControl'";
    $result = $conn->query($sql);
    $qr = $result->fetch_assoc();

    if (!$qr) {
        $qrError = "Invalid QR code.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body>
    <div class='user-container'>
        <div class='user-form'>

            <!-- NAV BAR -->
            <div class='user-nav-bar'>
                <div class='user-name'><p>Hi! <?= $_SESSION['name']; ?></p></div>
                <div class='user-date'><p><?= date('m/d/Y') ?></p></div>
                <form action="../operation/logout.php" method='POST'>
                    <button class='log-out'>LOGOUT</button>
                </form>
            </div>

            <!-- QR STATUS -->
            <div>
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

            <!-- ACTION BUTTONS -->
            <?php if (($qrControl && isset($qr)) || isset($qrError)): ?>
                <div class='user-option'>
                    <?php if (isset($qrError)): ?>
                        <!-- only show error -->

                    <?php elseif ($qr['is_used'] == 0): ?>
                        <!-- QR unused -->
                        <a href="user-receive.php?qr=<?= $qr['id'] ?>" class='btn-receive'>RECEIVE DOCUMENT</a>
                        <a href="user-update.php?qr=<?= $qr['id'] ?>" class='btn-update'>UPDATE DOCUMENT</a>
                        <a href="user-view.php?qr=<?= $qr['id'] ?>" class='btn-view'>VIEW DOCUMENT</a>

                    <?php else: ?>
                        <!-- QR already used -->
                        <a href="user-update.php?qr=<?= $qr['id'] ?>" class='btn-update'>UPDATE DOCUMENT</a>
                        <a href="user-view.php?qr=<?= $qr['id'] ?>" class='btn-view'>VIEW DOCUMENT</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- DASHBOARD -->
            <div class='user-dash'>
                <div class='user-dash-flx'>
                    <div class='user-dash-grid'>
                        <div class='user-received'><p>Received Documents</p></div>
                        <div class='user-released'><p>Released Documents</p></div>
                        <div class='user-returned'><p>Returned Documents</p></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
