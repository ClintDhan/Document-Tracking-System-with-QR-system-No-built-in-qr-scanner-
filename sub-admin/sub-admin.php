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


$sqlSub = "SELECT * FROM document where qr_id ='$qr_id'";
$resultAni = $conn->query($sqlSub);
$documentType = mysqli_fetch_assoc($resultAni);
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

            <div class="user-option sub-admin-option">
                <form action="../operation/sub-admin-update.php" method="POST">
                    <input type="hidden" name="id" value="<?= $documentType['id'] ?>">
                    <div>
                        <label for="">Type</label> <br>
                        <input type="text" value="<?= htmlspecialchars($documentType['type']) ?>" disabled>
                    </div>
                    <div>
                        <label for="">Status</label> <br>
                        <input type="text"  value="<?= htmlspecialchars($documentType['status']) ?>" disabled>
                    </div>
                    <div class="mt-3">
                        <button class="sub-admin-submit" type="submit" name="submit" value="reviewed">REVIEWED</button>
                    </div>
                </form>

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