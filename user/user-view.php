<?php 

require_once '../db.php'; 

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

            <button class='btn-home d-flex justify-content-center'><i class="bi bi-chevron-left"></i><a href="">Home</a></button>

            <div class='option-form'>
                <p class='option-receive' style="text-align: center;">View Document</p>

                <div>
                    <label for="">Type</label> <br>
                    <input type="text" class="update-input" name="type" value="<?= $document['type'] ?>" disabled>
                </div>
                <div class="mt-2">
                    <label for="">Description</label> <br>
                    <input type="text" class="update-input" name="description" value="<?= $document['description'] ?>" disabled>
                </div>
                <div class="mt-2">
                     <label for="">Status</label> <br>
                    <input type="text" class="update-input" name="status" value="<?= $document['status'] ?>" disabled>
                </div>
                <div class="mt-2">
                    <label for="">Department</label> <br>
                    <input type="text" class="update-input" name="department" value="<?= $document['department'] ?>" disabled>
                </div>
                <div class="mt-2">
                    <label for="">Control Number</label> <br>
                    <input type="text" class="update-input" name="control" value="<?= $control ?>" disabled>
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
                <?php else: ?>
                     <div class="mt-2">
                        <label for="">Status</label> <br>
                        <input type="text" class="update-input" name="status" value="<?= $document['status'] ?>" disabled>
                    </div>
                <?php endif; ?>
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