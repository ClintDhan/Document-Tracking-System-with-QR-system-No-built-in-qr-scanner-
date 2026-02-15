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

    if ($qr) {
    $qr_id = $qr['id']; // <-- primary key of qr_code
    } else {
    $qrError = "Invalid QR code.";
    }

}

$document = null;

if (isset($qr_id)) {
    $sql2 = "SELECT * FROM document WHERE qr_id = '$qr_id'";
    $result2 = $conn->query($sql2);
    $document = $result2->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body>
    <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['success']; ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>
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
            <?php if ($qr && !$qr['is_used']): ?>
                <div class='user-option'>
                    <a href="user-receive.php?qr=<?= $qr_id ?>&control=<?= urlencode($qrControl) ?>" class='btn-receive'>RECEIVE DOCUMENT</a>
                </div>
            <?php elseif ($qr && $qr['is_used']): ?>
                <div class='user-option'>
                    <a href="user-update.php?document=<?= $document['id'] ?>&qr=<?= $qr_id ?>&control=<?= urlencode($qrControl)?>" class='btn-update'>UPDATE DOCUMENT</a>
                    <a href="user-view.php?qr=<?= $qr_id ?>&control=<?= urlencode($qrControl) ?>&document=<?= $document['id'] ?>" class='btn-view'>VIEW DOCUMENT</a>
                </div>
            <?php elseif ($qrError): ?>
                <p style="color:red;"><?= $qrError ?></p>
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
