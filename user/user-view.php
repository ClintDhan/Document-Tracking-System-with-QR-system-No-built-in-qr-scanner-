<?php 
session_start();
require_once '../db.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$qr_id = $_GET['qr'] ?? null;
$document_id = $_GET['document'] ?? null;
$control = $_GET['control'] ?? null;

$sql = "
SELECT d.*, dl.remarks
FROM document d
LEFT JOIN document_log dl 
    ON d.id = dl.document_id
WHERE d.qr_id = '$qr_id'
ORDER BY dl.performed_at DESC
LIMIT 1
";

$result = $conn->query($sql);
$document = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Document</title>
    <link rel="icon" type="image/png" href="../asset/img/log.png">
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body>
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

<button class="btn-home" onclick="history.back()">
  ❮ BACK
</button>
            <div class='option-form'>
                <p class='option-receive' style="text-align: center;">View Document</p>

                <div>
                    <label for="">Type</label> <br>
                    <input type="text" class="update-input" name="type" value="<?= $document['type'] ?>" readonly>
                </div>
                <div class="mt-2">
                    <label for="">Description</label> <br>
                    <textarea 
                                class="update-textare" 
                                name="description" 
                                rows="3" 
                                readonly><?= htmlspecialchars($document['description'] ?? '') ?></textarea>                
                </div>
                <div class="mt-2">
                    <label for="">Department</label> <br>
                    <input type="text" class="update-input" name="department" value="<?= $document['department'] ?>" readonly>
                </div>
                <div class="mt-2">
                        <label for="">Number of copies</label> <br>
                        <input type="number" placeholder='Pages' class='update-input' name='pages' value="<?= $document['pages'] ?>" readonly>
                </div>
                <?php if($document['status'] == 'Released'): ?>
                    <div class="mt-2">
                        <label for="">Status</label> <br>
                        <input
                        style="<?= ($document['status'] ?? '') == 'Returned' ? 'background-color: #f8d7da; color: #555;' :
                                (($document['status'] ?? '') == 'Released' ? 'background-color: #e6ccff; color: #555;' :
                                (($document['status'] ?? '') == 'Received' ? 'background-color: #d4edda; color: #555;' :
                                (($document['status'] ?? '') == 'Reviewed' ? 'background-color: #fff3cd; color: #555;' : '' )))?>"
                        type="text" class="update-input" name="status" value="<?= $document['status'] ?>" readonly>
                    </div>
                    <div class="mt-2">
                        <label for="">Released To</label> <br>
                        <input type="text" class="update-input" name="released" value="<?= $document['released_to'] ?>" readonly>
                    </div>
                <?php elseif($document['status'] == 'Returned'): ?>
                     <div class="mt-2">
                        <label for="">Status</label> <br>
                        <input
                        style="<?= ($document['status'] ?? '') == 'Returned' ? 'background-color: #f8d7da; color: #555;' :
                                (($document['status'] ?? '') == 'Released' ? 'background-color: #e6ccff; color: #555;' :
                                (($document['status'] ?? '') == 'Received' ? 'background-color: #d4edda; color: #555;' :
                                (($document['status'] ?? '') == 'Reviewed' ? 'background-color: #fff3cd; color: #555;' : '' )))?>"
                        type="text" class="update-input" name="status" value="<?= $document['status'] ?>" readonly>
                    </div>
                    <div class="mt-2">
                        <label for="">Returned Reason</label> <br>
                        <input type="text" class="update-input" name="returned_reason" value="<?= $document['returned_reason'] ?>" readonly>
                    </div>
                <?php else: ?> 
                    <div class="mt-2">
                        <label for="">Status</label> <br>
                        <input
                        style="<?= ($document['status'] ?? '') == 'Returned' ? 'background-color: #f8d7da; color: #555;' :
                                (($document['status'] ?? '') == 'Released' ? 'background-color: #e6ccff; color: #555;' :
                                (($document['status'] ?? '') == 'Received' ? 'background-color: #d4edda; color: #555;' :
                                (($document['status'] ?? '') == 'Reviewed' ? 'background-color: #fff3cd; color: #555;' : '' )))?>"
                        type="text" class="update-input" name="status" value="<?= $document['status'] ?>" readonly>
                    </div>
                <?php endif; ?>
                <div class="mt-2">
                    <label for="">Control Number</label> <br>
                    <input type="text" class="update-input" name="control" value="<?= $control ?>" readonly>
                </div>
                <div class="mt-2">
                     <label for="">Date Created</label> <br>
                    <input type="text" class="update-input" name="status" value="<?= $document['created_at'] ?>" readonly>
                </div>
                <div class="mt-2">
                     <label for="">Last Updated</label> <br>
                    <input type="text" class="update-input" name="status" value="<?= $document['updated_at'] ?>" readonly>
                </div>
                <?php if($document['remarks'] != NULL): ?>
                <div class="mt-2">
                     <label for="">Latest Remark</label> <br>
                    <input type="text" class="update-input" name="remark" value="<?= $document['remarks'] ?>" readonly>
                </div>
                <?php endif;?>
            </div>

        </div>

    </div>
</body>
</html>