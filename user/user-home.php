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

$userId = (int) $_SESSION['user_id'];

$receivedSql = "
    SELECT COUNT(*) AS received_docs
    FROM document_log
    WHERE action = 'Received'
      AND performed_by = $userId
      AND DATE(performed_at) = CURDATE()
";

$result5 = $conn->query($receivedSql);
$row = $result5->fetch_assoc();
$receivedDocs = $row['received_docs'];


$releasedSql = "
    SELECT COUNT(*) AS released_docs
    FROM document_log
    WHERE action = 'Released'
      AND performed_by = $userId
      AND DATE(performed_at) = CURDATE()
";

$result6 = $conn->query($releasedSql);
$row2 = $result6->fetch_assoc();
$releasedDocs = $row2['released_docs'];


$returnedSql = "
    SELECT COUNT(*) AS returned_docs
    FROM document_log
    WHERE action = 'Returned'
      AND performed_by = $userId
      AND DATE(performed_at) = CURDATE()
";

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

<body class="user-body">
    <?php if (isset($_SESSION['success'])): ?>
        <div class="toast-notification toast-success" id="updateToast">
        <div class="toast-check-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
</svg>
        </div>

        <span class="toast-message" id="toastMessage">
            <?= htmlspecialchars($_SESSION['success'], ENT_QUOTES, 'UTF-8'); ?>
        </span>

        <i class="bi bi-x toast-exit-btn" id="toastClose"></i>
    </div>
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="user-home-header-container">
        <header class="user-home-header">
            <div class="header-inner">
                <a class="brand" href="">DTS</a>
                <div class="account">
                    <div class="user"><strong><?= $_SESSION['name']; ?></strong> <br><span class="role">Records Officer</span></div>

                    <form class="user-header-form" action="../operation/logout.php" method='POST'>
                        <button
                            class="logout">Logout</button>
                    </form>
                </div>
            </div>
        </header>
    </div>

    <div class="main">
        <?php if ($qr && !$qr['is_used']): ?>

             <div class='user-main-title-container'>
                <h2>Scan Document</h2>
            </div>

             <div class="ready-qr">
                <p class="eyebrow">Control Number</p>
                <h2 class="ready-title">Control number submitted</h2>
                <a class="user-qr-scan-btn full" href="user-receive.php?qr=<?= $qr_id ?>&control=<?= urlencode($qrControl) ?>" >Receive
                    Document</a> <br><a class="link-secondary" href="user-home.php">Scan another</a>
            </div>

           
        <?php elseif ($qr && $qr['is_used']): ?>
            <div class='user-main-title-container'>
                <h2>Control #<?= urlencode($qrControl)?></h2>
            </div>

            <div class="actions">
                <a class="user-qr-scan-btn"
                href="user-update.php?document=<?= $document['id'] ?>&qr=<?= $qr_id ?>&control=<?= urlencode($qrControl) ?>">Update Document</a>
                <a class="user-qr-scan-btn btn-outline btn-view-document" href="user-view.php?qr=<?= $qr_id ?>&control=<?= urlencode($qrControl) ?>&document=<?= $document['id'] ?>">View Document</a>
                <a class="user-qr-scan-btn btn-outline" href="user-view-documents.php">All Documents</a>
                <a class="user-qr-scan-btn btn-outline" href="user-home.php">Scan Another</a> 
            </div>
           
            <!-- <div class='user-option'>
                <a href="user-update.php?document=<?= $document['id'] ?>&qr=<?= $qr_id ?>&control=<?= urlencode($qrControl) ?>" class='btn-update'>UPDATE DOCUMENT</a>
                <a href="user-view.php?qr=<?= $qr_id ?>&control=<?= urlencode($qrControl) ?>&document=<?= $document['id'] ?>" class='btn-view'>VIEW DOCUMENT</a>
            </div> -->
        <?php elseif (!$qr): ?>
            <div class='user-main-title-container'>
                <h2>Scan Document</h2>
            </div>

            <div class="actions">
                <a class="user-qr-scan-btn" href="user-view-documents.php">All Documents</a> 
            </div>

            <div class="no-qr-panel">
                <div class="qr-placeholder">No QR scanned</div>
                <p class="intro muted">Scan a QR code or type the control number manually.</p>

                
                    <div class="field">
                    <form action="../operation/control-search.php" method="post">
                        <label for="control" class="user-qr-form-label">Control Number</label> <br>
                        <input class="user-form-new-input" id="control" name="control"
                            placeholder="e.g. MO-2026-123456" required>
                        </div>
                        
                        <button class="user-qr-scan-btn full" name="submit" type="submit">Submit Control Number</button>
                </form>
            </div>
            <!-- <div class='user-option'>
                    <p>Enter Control Number</p>
                    <form action="../operation/control-search.php" method="post">
                        <div class="control-search-container">
                            <input class="mt-3" type="text" name="control" placeholder="MO-YEAR-123456" required>
                            <button class="btn-submit btn-search" name="submit" type="submit">SEARCH</button>
                        </div>
                    </form>
                </div> -->
        <?php elseif ($result && $result->num_rows <= 0): ?>
            <div class='user-option'>
                <p class="text-center" style="font-weight: 700;">SCANNED QR DOES NOT EXIST OR WAS DELETED. PLEASE CONTACT JHEA</p>
            </div>
        <?php endif; ?>

        
<div class="document-lists">
    <section class="panel list">
        <div class="list-heading">
            <h6>Received Today</h6>
            <span class="count success"><?= $receivedDocs ?></span>
        </div>
        <ul>
            <?php
            $sql = "SELECT
                        qr_code.control_num,
                        document.type,
                        document.department,
                        document_log.action,
                        document_log.performed_at
                    FROM document_log
                    JOIN document
                        ON document.id = document_log.document_id
                    JOIN qr_code
                        ON qr_code.id = document.qr_id
                    WHERE document_log.action = 'Received'
                    AND document_log.performed_by = {$_SESSION['user_id']}
                    AND DATE(document_log.performed_at) = CURDATE()";

            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
            ?>
                <li>
                    <p class="control">
                        <?= htmlspecialchars($row['control_num']) ?>
                    </p>
                    <p class="doc-name">
                        <?= htmlspecialchars($row['type']) ?>
                    </p>
                    <p class="department">
                        <?= htmlspecialchars($row['department']) ?>
                    </p>
                </li>
            <?php } ?>
        </ul>
    </section>

    <section class="panel list">
        <div class="list-heading">
            <h6>Released Today</h6>
            <span class="count"><?= $releasedDocs ?></span>
        </div>
        <ul>
            <?php
            $sql = "SELECT
                        qr_code.control_num,
                        document.type,
                        document.department,
                        document_log.action,
                        document_log.performed_at
                    FROM document_log
                    JOIN document
                        ON document.id = document_log.document_id
                    JOIN qr_code
                        ON qr_code.id = document.qr_id
                    WHERE document_log.action = 'Released'
                    AND document_log.performed_by = {$_SESSION['user_id']}
                    AND DATE(document_log.performed_at) = CURDATE()";

            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
            ?>
                <li>
                    <p class="control">
                        <?= htmlspecialchars($row['control_num']) ?>
                    </p>
                    <p class="doc-name">
                        <?= htmlspecialchars($row['type']) ?>
                    </p>
                    <p class="department">
                        <?= htmlspecialchars($row['department']) ?>
                    </p>
                </li>
            <?php } ?>
        </ul>
    </section>

    <section class="panel list">
        <div class="list-heading">
            <h6>Returned Today</h6>
            <span class="count warning"><?= $returnedDocs ?></span>
        </div>
        <ul>
            <?php
            $sql = "SELECT
                        qr_code.control_num,
                        document.type,
                        document.department,
                        document_log.action,
                        document_log.performed_at
                    FROM document_log
                    JOIN document
                        ON document.id = document_log.document_id
                    JOIN qr_code
                        ON qr_code.id = document.qr_id
                    WHERE document_log.action = 'Returned'
                    AND document_log.performed_by = {$_SESSION['user_id']}
                    AND DATE(document_log.performed_at) = CURDATE()";

            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
            ?>
                <li>
                    <p class="control">
                        <?= htmlspecialchars($row['control_num']) ?>
                    </p>
                    <p class="doc-name">
                        <?= htmlspecialchars($row['type']) ?>
                    </p>
                    <p class="department">
                        <?= htmlspecialchars($row['department']) ?>
                    </p>
                </li>
            <?php } ?>
        </ul>
    </section>
</div>

    </div>


    <!-- <div class='user-container'>
        <div class='user-form'>


            <!-- <div class='user-nav-bar'>
                <div class='user-name'>
                    <p>Hi, <span class="span-name"><?= $_SESSION['name']; ?>!</p>
                    <p style="color: gray;"><?= date('m/d/Y') ?></p>
                </div>
                <form action="../operation/logout.php" method='POST'>
                    <button class='log-out'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z" />
                            <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z" />
                        </svg>
                    </button>
                </form>
            </div> -->
            <!-- QR STATUS -->
            <!-- <div class="qr-status-container d-flex justify-content-center align-items-center p-2">
                <?php if ($qrControl && isset($qr) && $qr['is_used'] == 0): ?>
                    <p>QR Scanned: <?= htmlspecialchars($qr['control_num']) ?></p>
                <?php elseif ($qrControl && isset($qr) && $qr['is_used'] == 1): ?>
                    <p>QR Scanned: <?= htmlspecialchars($qr['control_num']) ?></p>
                <?php elseif (isset($qrError)): ?>
                    <p style="color:red;"><?= $qrError ?></p>
                <?php else: ?>
                    <p>No QR scanned</p>
                <?php endif; ?>
            </div> -->

            <!-- ACTION BUTTONS -->
          

            <!-- DASHBOARD -->
            <!-- <div class='user-dash'>
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
            </div> -->

        </div>
    </div> 

    <script>
    //     window.addEventListener("pageshow", () => {
    //     document.querySelectorAll(".flash-message").forEach(alert => {
    //         alert.style.display = "none";
    //     });
    // });

    window.addEventListener("pageshow", () => {
    if (sessionStorage.getItem("hideHomeAlerts") === "true") {
        document.querySelectorAll(".alert").forEach(alert => {
            alert.style.display = "none";
        });

        sessionStorage.removeItem("hideHomeAlerts");
    }
});

        const updateToast = document.getElementById('updateToast');
const toastMessage = document.getElementById('toastMessage');
const toastClose = document.getElementById('toastClose');

let toastTimer;

function showToast(message, type = 'error') {
    if (!updateToast || !toastMessage) return;

    clearTimeout(toastTimer);

    updateToast.classList.remove('toast-error', 'toast-success');
    updateToast.classList.add(`toast-${type}`);

    toastMessage.textContent = message;

    updateToast.style.display = 'flex';
    updateToast.classList.add('show-toast');

    toastTimer = setTimeout(() => {
        updateToast.classList.remove('show-toast');
        updateToast.style.display = 'none';
    }, 5000);
}

if (toastClose) {
    toastClose.addEventListener('click', () => {
        clearTimeout(toastTimer);
        updateToast.classList.remove('show-toast');
        updateToast.style.display = 'none';
    });
}

// Display the success toast for 5 seconds
if (updateToast) {
    const toastType = updateToast.classList.contains('toast-success')
        ? 'success'
        : 'error';

    showToast(toastMessage.textContent.trim(), toastType);
}
    </script>
</body>

</html>