<?php 
session_start();
require_once '../db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Get QR info from URL
$qr_id = $_GET['qr'] ?? null;           // id of the QR
$qrControl = $_GET['control'] ?? null;  // control number

if (!$qr_id || !$qrControl) {
    die("Invalid QR access."); // safety check
}

// Optional: fetch QR info from database
$stmt = $conn->prepare("SELECT * FROM qr_code WHERE id = ?");
$stmt->bind_param("i", $qr_id);
$stmt->execute();
$result = $stmt->get_result();
$qr = $result->fetch_assoc();

if (!$qr) {
    die("QR not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Document</title>
    <link rel="icon" type="image/png" href="../asset/img/log.png">
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class='user-container'>
        <div class="user-form">

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

            <!-- <button class='btn-home d-flex justify-content-center' onclick="history.back()">&larr; <span class="span-home">Home</span></button> -->
            <button class="btn-home" onclick="history.back()">
  ❮ BACK
</button>
            <div class='option-form'>
                    <p class='option-receive' style="text-align: center;">Create Document</p>
                    <p class='option-text' style="text-align: center;">Please indicate document information</p>

                <form action="../operation/receivedocument.php" method='POST' 
                    style='display: flex; justify-content: center; flex-direction: column;'>
                    <input type="hidden" name="qr_id" value="<?= htmlspecialchars($_GET['qr'] ?? '') ?>">
                    <input type="hidden" name="control_num" value="<?= htmlspecialchars($qrControl) ?>">
                    
                    <div>
                        <label for="">Type</label><br>
                        <input required class='receive-input' type="text" placeholder='Type' name='type'>
                    </div>

                    <div class="mt-2">
                        <label for="">Description</label> <br>
                        <textarea required name='description' id="" class='receive-textarea' rows='3' placeholder='Description'></textarea>
                    </div>
                   

                    <div class="mt-2">
                        <label for="">Department</label> <br>
                        <input required type="text" placeholder='Department' class='receive-input' name='department'>
                    </div>

                     <div class="mt-2">
                        <label for="">Number of copies</label> <br>
                        <input required class='receive-input' type="number" placeholder='Copies' name='pages'>
                    </div>

                    <div class="mt-2">
                        <label for="">Status</label> <br>
                        <select name="status" class="update-input" id='statusSelect'>
                            <option value="Received">Received</option>
                            <option value="Released">Released</option>
                            <option value="Returned">Returned</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="" id="releasedInputLabel">Released To</label>
                        <input type="text"
                        required
                        id="releasedTo"
                        class="receive-input"
                        name="released_to"
                        placeholder="Released To"
                        style="display: none;">
                    </div>

                    <div class="mt-2">
                        <label for="" id="returnedInputLabel">Return Reason</label>
                        <input type="text"
                        required
                        id="returnReason"
                        class="receive-input"
                        name="returned_reason"
                        placeholder="Return Reason"
                        style="display: none;">
                    </div>

                    <div class="mt-2">
                        <label for="">Remarks</label> <br>
                        <input type="text" placeholder='(Optional)' class='receive-input' name='remark'>
                    </div>

                    <button class='btn-submit' type="submit" name="submit">SAVE</button>
                </form>
            </div>

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