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

$sql = "SELECT * FROM document WHERE qr_id = '$qr_id'";
$result = $conn->query($sql);
$document = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Document</title>
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
                <p class='option-receive' style="text-align: center;">View Document</p>

                <div>
                    <label for="">Type</label> <br>
                    <input type="text" class="update-input" name="type" value="<?= $document['type'] ?>" disabled>
                </div>
                <div class="mt-2">
                    <label for="">Description</label> <br>
                    <textarea 
                                class="update-textare" 
                                name="description" 
                                rows="3" 
                                disabled><?= htmlspecialchars($document['description'] ?? '') ?></textarea>                
                </div>
                <div class="mt-2">
                    <label for="">Department</label> <br>
                    <input type="text" class="update-input" name="department" value="<?= $document['department'] ?>" disabled>
                </div>
                <div class="mt-2">
                        <label for="">Number of pages</label> <br>
                        <input type="number" placeholder='Pages' class='update-input' name='pages' value="<?= $document['pages'] ?>" readonly>
                </div>
                <?php if($document['status'] == 'Released'): ?>
                    <div class="mt-2">
                        <label for="">Status</label> <br>
                        <input type="text" class="update-input" name="status" value="<?= $document['status'] ?>" disabled>
                    </div>
                    <div class="mt-2">
                        <label for="">Released To</label> <br>
                        <input type="text" class="update-input" name="released" value="<?= $document['released_to'] ?>" disabled>
                    </div>
                <?php elseif($document['status'] == 'Returned'): ?>
                     <div class="mt-2">
                        <label for="">Status</label> <br>
                        <input type="text" class="update-input" name="status" value="<?= $document['status'] ?>" disabled>
                    </div>
                    <div class="mt-2">
                        <label for="">Returned Reason</label> <br>
                        <input type="text" class="update-input" name="returned_reason" value="<?= $document['returned_reason'] ?>" disabled>
                    </div>
                <?php else: ?> 
                    <div class="mt-2">
                        <label for="">Status</label> <br>
                        <input type="text" class="update-input" name="status" value="<?= $document['status'] ?>" disabled>
                    </div>
                <?php endif; ?>
                <div class="mt-2">
                    <label for="">Control Number</label> <br>
                    <input type="text" class="update-input" name="control" value="<?= $control ?>" disabled>
                </div>
                <div class="mt-2">
                     <label for="">Created</label> <br>
                    <input type="text" class="update-input" name="status" value="<?= $document['created_at'] ?>" disabled>
                </div>
                <div class="mt-2">
                     <label for="">Last updated</label> <br>
                    <input type="text" class="update-input" name="status" value="<?= $document['updated_at'] ?>" disabled>
                </div>

            </div>

        </div>

    </div>
</body>
</html>