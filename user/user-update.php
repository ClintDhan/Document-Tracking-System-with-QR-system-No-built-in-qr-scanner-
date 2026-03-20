<?php 

session_start();
require_once '../db.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$qr_id = $_GET['qr'] ?? null;
$document_id = $_GET['document'] ?? null;
$control_num = $_GET['control'] ?? null;

$sql = "SELECT * FROM document WHERE qr_id = '$qr_id'";
$result = $conn->query($sql);
$document = mysqli_fetch_assoc($result);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Document</title>
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body>
    <div class='user-container'>
        <div class="user-form">

            <div class='user-nav-bar'>
                <div class='user-name'>
                    <p>Hi <span class="span-name"><?= $_SESSION['name']; ?>!</p>
                    <p><?= date('m/d/Y') ?></p>
                </div>
                <form action="../operation/logout.php" method='POST'>
                    <button class='log-out'>LOGOUT</button>
                </form>
            </div>

            <button class='btn-home d-flex justify-content-center' onclick="history.back()">&larr; <span class="span-home">Home</span></button>

            <div class='option-form'>
                <p class='option-receive' style="text-align: center;">Update Document</p>
                <p class='option-text' style="text-align: center;">Please indicate the necessary changes</p>
            
                <form action="../operation/updatedocument.php" 
                        method='POST'
                        style='display: flex; justify-content: center; flex-direction: column;'
                        >
                    <input type="hidden" name="qr_id" value="<?= $qr_id ?>">
                    <input type="hidden" name="document_id" value="<?= $document_id ?>">
                    <input type="hidden" name="control_num" value="<?= $control_num ?>">


                    <div>
                        <label for="">Type</label> <br>
                        <input type="text" placeholder='Type' class='update-input' name='type' value="<?= $document['type'] ?>">
                    </div>
                    <div class="mt-2">
                        <label for="">Description</label> <br>
                        <textarea id="" placeholder='Description' class='update-textare' name='description'><?= $document['description'] ?></textarea>

                    </div>
                    <div class="mt-2">
                        <label for="">Department</label> <br>
                        <input type="text" placeholder='Department' class='update-input' name='department' value="<?= $document['department']?>">
                    </div>
                    <div class="mt-2">
                        <label for="">Number of pages</label> <br>
                        <input type="number" placeholder='Pages' class='update-input' name='pages' value="<?= $document['pages'] ?>">
                    </div>
                    <div class="mt-2">
                        <label for="">Status</label> <br>
                        <select name="status" class="update-input" id='statusSelect'>
                            <option value="Received" <?= ($document['status'] ?? '') == 'Received' ? 'selected' : '' ?>>Received</option>
                            <option value="Released" <?= ($document['status'] ?? '') == 'Released' ? 'selected' : '' ?>>Released</option>
                            <option value="Returned" <?= ($document['status'] ?? '') == 'Returned' ? 'selected' : '' ?>>Returned</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="" id="releasedInputLabel">Released To</label>
                        <input type="text"
                        id="releasedTo"
                        class="update-input"
                        name="released_to"
                        placeholder="Released To"
                        value="<?= htmlspecialchars($document['released_to'] ?? '') ?>"
                        style="<?= ($document['status'] ?? '') == 'Released' ? 'display:block;' : 'display:none;' ?> padding: none;">
                    </div>

                    <div class="mt-2">
                        <label for="" id="returnedInputLabel">Return Reason</label>
                        <input type="text"
                        id="returnReason"
                        class="update-input"
                        name="returned_reason"
                        placeholder="Return Reason"
                        value="<?= htmlspecialchars($document['returned_reason'] ?? '') ?>"
                        style="<?= ($document['status'] ?? '') == 'Returned' ? 'display:block;' : 'display:none;' ?> padding: none;">
                    </div>
                    

                    <button class='btn-submit' type='submit' name='submit'>UPDATE</button>
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