<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


if (isset($_GET['doc'])) {
    $value = $_GET['doc'];
    $sql = "SELECT * FROM document WHERE id = '$value'";
} 
elseif (isset($_GET['qr_id'])) {
    $value = $_GET['qr_id'];
    $sql = "SELECT * FROM document WHERE qr_id = '$value'";
} 
elseif (isset($_GET['control'])) {
    $control = $_GET['control'] ?? null;
    // JOIN qr_code to document
    $qrSql = "SELECT * FROM qr_code WHERE control_num = '$control'";
    $qrSqlResult = $conn->query($qrSql);
    $row1 = $qrSqlResult->fetch_assoc();
    $qr_id = $row1['id'];
    $sql = "SELECT * FROM document WHERE qr_id = '$qr_id'";
} 
else {
    die("No document specified.");
}

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
                        <a href="" class="admin-logo-title">MAYOR'S OFFICE DTS</a>
                        <p class="admin-logo-sub">ADMIN</p>
                    </div>

                    <div class="nav-anchor">
                        <a href="admin-dashboard.php" class="">DASHBOARD</a>
                        <a href="admin-document.php" class="active">DOCUMENTS</a>
                        <a href="admin-logs.php">DOCUMENT LOGS</a>
                        <a href="admin-user-log.php">USER LOGS</a>
                        <a href="admin-qr.php">QR MANAGEMENT</a>
                        <a href="admin-user.php">USERS</a>
                    </div>

                    <div class="admin-logout">
                         <form action="../operation/logout.php" method="POST">
                            <button class='log-out admin-logout'>↪ LOGOUT</button>
                        </form>
                    </div>
                </div>
            <div class="doc-edit-container mt-2">
                <p class="text-center" style="font-weight: 700;">View document</p>
                <form action="../operation/sub-admin-update.php" method="POST">
                    <div class="doc-edit-flx">
                    <input type="hidden" name="doc_id" value="<?= $doc ?>">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div>
                    <label for="">Type</label> <br>
                    <input type="text" name='type' value="<?= htmlspecialchars($row['type']) ?>" class="admin-doc-input" readonly>
                    </div>

                    <div class="mt-2">
                    <label for="">Description</label> <br>
                    <textarea readonly name='description' rows="4" id="" class="admin-doc-area"><?= $row['description'] ?></textarea>            
                    </div>

                    <div class="mt-2">
                    <label for="">Department</label> <br>
                    <input type="text" name='department' value="<?= htmlspecialchars($row['department']) ?>" class="admin-doc-input" readonly>
                    </div>
                
                    <div class="mt-2">
                    <label for="">Status</label> <br>
                    <input type="text" id="statusSelect" name="status" value="<?= htmlspecialchars($row['status']) ?>" class="admin-doc-input" readonly>
                    </div>

                    <div class="mt-2">
                     <label for="" id="releasedInputLabel">Released To</label>
                        <input type="text"
                        id="releasedTo"
                        class="admin-doc-input"
                        name="released_to"
                        placeholder="Released To"
                        value="<?= htmlspecialchars($row['released_to'] ?? '') ?>"
                        style="<?= ($row['status'] ?? '') == 'Released' ? 'display:block;' : 'display:none;' ?> padding: none;" readonly>
                    </div>

                    <div class="mt-2">
                    <label for="" id="returnedInputLabel">Return Reason</label>
                        <input type="text"
                        id="returnReason"
                        class="admin-doc-input"
                        name="returned_reason"
                        placeholder="Return Reason"
                        value="<?= htmlspecialchars($row['returned_reason'] ?? '') ?>"
                        style="<?= ($row['status'] ?? '') == 'Returned' ? 'display:block;' : 'display:none;' ?> padding: none;" readonly>                    
                    </div>

                    <?php if (
                            ($_SESSION['role'] == "admin") &&
                            ($row['status'] == "Received" || $row['status'] == "Returned")
                        ): ?>
                            <button class='btn-submit admin-doc-submit' type='submit' name='submit'>REVIEWED</button>
                        <?php endif; ?>
                    </div>
                </form>
                
            <button class="adm-bck-btn" onclick="window.location.href='admin-document.php'">
  ❮ BACK
</button>
            </div>
        </div>
<script>
const statusSelect = document.getElementById('statusSelect');
const releasedInput = document.getElementById('releasedTo');
const releasedInputLabel = document.getElementById('releasedInputLabel');
const returnedInputLabel = document.getElementById('returnedInputLabel');
const returnReason = document.getElementById('returnReason');


function showInput() {
    if (statusSelect.value === 'Released') {
        releasedInputLabel.style.display = 'block';
        releasedInput.style.display = 'block';
        releasedInput.required = true;
        returnedInputLabel.style.display = 'none';
        returnReason.style.display = 'none';
        returnReason.required = false;
    }
    
    else if(statusSelect.value === 'Returned') {
        returnedInputLabel.style.display = 'block';
        returnReason.style.display = 'block';
        returnReason.required = true;
         releasedInputLabel.style.display = 'none';
        releasedInput.style.display = 'none';
        releasedInput.required = false;
    }
    else {
        releasedInputLabel.style.display = 'none';
        releasedInput.style.display = 'none';
        releasedInput.required = false;
        releasedInput.value = ''; // ADD THIS

        returnedInputLabel.style.display = 'none';
        returnReason.style.display = 'none';
        returnReason.required = false;
        returnReason.value = ''; // ADD THIS
    }

}

showInput();
statusSelect.addEventListener('change' ,showInput );
</script>
</body>


</html>