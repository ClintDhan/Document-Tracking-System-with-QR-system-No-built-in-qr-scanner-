<?php 

session_start();
require_once '../db.php'; 

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
                <div class='user-name'><p>Hi! Jhea!</p></div>
                <div class='user-date'><p>01/24/2026</p></div>
                <button class='log-out'>LOGOUT</button>
            </div>

            <button class='btn-home'>Home</button>

            <div class='option-form'>
                <p class='option-receive'>Update Document</p>
                <p class='option-text'>Please indicate the necessary changes</p>
            
                <form action="../operation/updatedocument.php" 
                        method='POST'
                        style='display: flex; justify-content: center; align-items: center; flex-direction: column;'
                        >
                    <input type="hidden" name="qr_id" value="<?= $qr_id ?>">
                    <input type="hidden" name="document_id" value="<?= $document_id ?>">
                    <input type="hidden" name="control_num" value="<?= $control_num ?>">
                    <input type="text" placeholder='Type' class='update-input mt-2' name='type' value="<?= $document['type'] ?>">
                    <textarea id="" placeholder='Description' class='update-textare mt-2' name='description'><?= $document['description'] ?></textarea>
                    <input type="text" placeholder='Department' class='update-input mt-2' name='department' value="<?= $document['department']?>">
                

                    <select name="status" class="update-input mt-2" id='statusSelect'>
                        <option value="Received" <?= ($document['status'] ?? '') == 'Received' ? 'selected' : '' ?>>Received</option>
                        <option value="Released" <?= ($document['status'] ?? '') == 'Released' ? 'selected' : '' ?>>Released</option>
                        <option value="Returned" <?= ($document['status'] ?? '') == 'Returned' ? 'selected' : '' ?>>Returned</option>
                    </select>

                    <input type="text"
       id="releasedTo"
       class="update-input mt-2"
       name="released_to"
       placeholder="Released To"
       value="<?= htmlspecialchars($document['released_to'] ?? '') ?>"
       style="<?= ($document['status'] ?? '') == 'Released' ? 'display:block;' : 'display:none;' ?>">


                    <button class='btn-submit' type='submit' name='submit'>UPDATE</button>
                </form>
            </div>

        </div>

    </div>

<script>

const statusSelect = document.getElementById('statusSelect');
const releasedInput = document.getElementById('releasedTo');


function showInput() {
    if (statusSelect.value === 'Released') {
        releasedInput.style.display = 'block';
        releasedInput.required = true;
    } else {
        releasedInput.style.display = 'none';
        releasedInput.required = false;
    }

}

showInput();
statusSelect.addEventListener('change' ,showInput );

</script>
</body>
</html>