<?php 
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != "superadmin") {
    header("Location: ../login.php");
    exit();
}
$doc = $_GET['doc'] ?? null;
$sql = "SELECT * FROM document where id = '$doc'";
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
            <div class="doc-edit-container mt-2">
                <p class="text-center" style="font-weight: 700;">Edit document</p>
                <form action="../operation/admin-update-document.php" method="POST">
                    <div class="doc-edit-flx">
                    <input type="hidden" name="doc_id" value="<?= $doc ?>">
                    <div>
                    <label for="">Type</label> <br>
                    <input type="text" name='type' value="<?= htmlspecialchars($row['type']) ?>" class="admin-doc-input">
                    </div>

                    <div class="mt-2">
                    <label for="">Description</label> <br>
                    <textarea name='description' rows="4" id="" class="admin-doc-area"><?= $row['description'] ?></textarea>            
                    </div>

                    <div class="mt-2">
                    <label for="">Department</label> <br>
                    <input type="text" name='department' value="<?= htmlspecialchars($row['department']) ?>" class="admin-doc-input">
                    </div>

                    <div class="mt-2">
                    <label for="">Status</label> <br>
                    <select name="status" id='statusSelect' class="admin-doc-input">
                        <option value="Received" <?= ($row['status'] ?? '') == 'Received' ? 'selected' : '' ?>>Received</option>
                        <option value="Under Review" <?= ($row['status'] ?? '') == 'Under Review' ? 'selected' : '' ?>>Under Review</option>
                        <option value="Released" <?= ($row['status'] ?? '') == 'Released' ? 'selected' : '' ?>>Released</option>
                        <option value="Returned" <?= ($row['status'] ?? '') == 'Returned' ? 'selected' : '' ?>>Returned</option>
                    </select>
                    </div>

                    <div class="mt-2">
                     <label for="" id="releasedInputLabel">Released To</label>
                        <input type="text"
                        id="releasedTo"
                        class="admin-doc-input"
                        name="released_to"
                        placeholder="Released To"
                        value="<?= htmlspecialchars($row['released_to'] ?? '') ?>"
                        style="<?= ($row['status'] ?? '') == 'Released' ? 'display:block;' : 'display:none;' ?> padding: none;">
                    </div>

                    <div class="mt-2">
                    <label for="" id="returnedInputLabel">Return Reason</label>
                        <input type="text"
                        id="returnReason"
                        class="admin-doc-input"
                        name="returned_reason"
                        placeholder="Return Reason"
                        value="<?= htmlspecialchars($row['returned_reason'] ?? '') ?>"
                        style="<?= ($row['status'] ?? '') == 'Returned' ? 'display:block;' : 'display:none;' ?> padding: none;">                    
                    </div>

                    
                    <button class='btn-submit admin-doc-submit' type='submit' name='submit'>UPDATE</button>
                    </div>
                </form>
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