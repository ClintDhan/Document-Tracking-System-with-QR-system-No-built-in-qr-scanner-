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
    <link rel="icon" type="image/png" href="../asset/img/log.png">
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

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    <div class='user-container'>
        <div class='user-form'>

            <!-- NAV BAR -->
            <div class='user-nav-bar'>
                <div class='user-name'>
                    <p>Hi, <span class="span-name"><?= $_SESSION['name']; ?>!</p>
                    <p style="color: gray;"><?= date('m/d/Y') ?></p>
                </div>
                <form action="../operation/logout.php" method='POST'>
                    <button class='log-out'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                        <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                        </svg>
                    </button>
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
                    <a href="user-receive.php?qr=<?= $qr_id ?>&control=<?= urlencode($qrControl) ?>" class='btn-receive'>CREATE DOCUMENT</a>
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
                            <input class="mt-3" type="text" name="control" placeholder="MO-YEAR-123456" required>
                            <button class="btn-submit btn-search" name="submit" type="submit">SEARCH</button>
                        </div>
                    </form>
                </div>
            <?php elseif ($result && $result->num_rows <= 0): ?>
                <div class='user-option'>
                    <p class="text-center" style="font-weight: 700;">SCANNED QR DOES NOT EXIST OR WAS DELETED. PLEASE CONTACT JHEA</p>
                </div>
            <?php endif; ?>

            <!-- DASHBOARD -->
            <div class='user-dash'>
                <div class="user-dash-title">
                    <p class="todays-title">TODAY'S DOCUMENTS</p>
                    <a href="user-view-documents.php" class="user-document-view">VIEW DOCUMENTS</a>
                </div>
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
