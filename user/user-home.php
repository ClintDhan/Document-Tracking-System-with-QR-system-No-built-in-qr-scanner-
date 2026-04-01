<?php 
session_start();
require_once '../db.php';

// ----------------------------
// 1️⃣ Check login
// ----------------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
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

// logic for the received documents

$receivedSql = "SELECT COUNT(*) AS received_docs 
                FROM document
                WHERE status = 'Received' 
                  AND created_by = {$_SESSION['user_id']} 
                  AND DATE(created_at) = CURDATE()";
$result5 = $conn->query($receivedSql);
$row = $result5->fetch_assoc();
$receivedDocs = $row['received_docs'];

$releasedSql = "SELECT COUNT(*) AS released_docs FROM document WHERE status = 'Released' AND created_by = {$_SESSION['user_id']} AND DATE(created_at) = CURDATE()";
$result6 = $conn->query($releasedSql);
$row2 = $result6->fetch_assoc();
$releasedDocs = $row2['released_docs']; 

$returnedSql = "SELECT COUNT(*) AS returned_docs FROM document WHERE status = 'Returned' AND created_by = {$_SESSION['user_id']} AND DATE(created_at) = CURDATE()";
$result7 = $conn->query($returnedSql);
$row3 = $result7->fetch_assoc();
$returnedDocs = $row3['returned_docs'];
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
                <div class='user-name'>
                    <p>Hi <span class="span-name"><?= $_SESSION['name']; ?>!</p>
                    <p style="color: gray;"><?= date('m/d/Y') ?></p>
                </div>
                <form action="../operation/logout.php" method='POST'>
                    <button class='log-out'>LOGOUT</button>
                </form>
            </div>

            <!-- QR STATUS -->
            <div class="qr-status-container d-flex justify-content-center align-items-center p-2">
                <?php if ($qrControl && isset($qr) && $qr['is_used'] == 0): ?>
                    <p>QR Scanned: <?= htmlspecialchars($qr['control_num']) ?></p>
                <?php elseif ($qrControl && isset($qr) && $qr['is_used'] == 1): ?>
                    <p>QR Scanned: <?= htmlspecialchars($qr['control_num']) ?></p>
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
            <?php elseif (!$qr): ?>
                 <div class='user-option'>
                    <p>Enter Control Number</p>
                    <form action="../operation/control-search.php" method="post">
                        <div class="control-search-container">
                            <input class="mt-3" type="text" name="control" placeholder="MO-DATE-123456">
                            <button class="btn-submit btn-search" name="submit" type="submit">SEARCH</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>

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
