<?php 
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


$control = $_GET['control'] ?? null;
$sql = "SELECT * FROM qr_code WHERE control_num ='$control'";
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
                        <a href="admin-logs.php">DOCUMENT LOGS</a>
                        <a href="admin-user-log.php">USER LOGS</a>
                        <a href="admin-qr.php">QR MANAGEMENT</a>
                        <a href="admin-user.php">USERS</a>
                    </div>

                    <div class="admin-logout">
                        <button class='log-out admin-logout'>↪ LOGOUT</button>
                    </div>
                </div>
            <div class="doc-edit-container mt-2">
                <p class="text-center" style="font-weight: 700;">Receive document</p>
                <form action="../operation/admin-receive-document.php" method="POST">
                    <div class="doc-edit-flx">

                    <input type="hidden" name="qr_id" value="<?= $row['id'] ?>">

                    <div>
                        <label for="">Type</label> <br>
                        <input type="text" name='type' value="" class="admin-doc-input">
                    </div>

                    <div class="mt-2">
                        <label for="">Description</label> <br>
                        <textarea name='description' rows="4" id="" class="admin-doc-area"></textarea>            
                    </div>

                    <div class="mt-2">
                        <label for="">Number of copies</label> <br>
                        <input type="number" name='pages' class="admin-doc-input">
                    </div>

                    <div class="mt-2">
                        <label for="">Department</label> <br>
                        <input type="text" name='department' class="admin-doc-input">
                    </div>

                    
                    <button class='btn-submit admin-doc-submit' type='submit' name='submit'>SAVE</button>
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