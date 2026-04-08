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
                    <p>Hi <span class="span-name"><?= $_SESSION['name']; ?>!</p>
                    <p style="color: gray;"><?= date('m/d/Y') ?></p>
                </div>
                <form action="../operation/logout.php" method='POST'>
                    <button class='log-out'>LOGOUT</button>
                </form>
            </div>

            <!-- <button class='btn-home d-flex justify-content-center' onclick="history.back()">&larr; <span class="span-home">Home</span></button> -->
            <button class="btn-home" onclick="history.back()">
  ❮ BACK
</button>
            <div class='option-form'>
                    <p class='option-receive' style="text-align: center;">Receive Document</p>
                    <p class='option-text' style="text-align: center;">Please indicate document information</p>

                <form action="../operation/receivedocument.php" method='POST' 
                    style='display: flex; justify-content: center; flex-direction: column;'>
                    <input type="hidden" name="qr_id" value="<?= htmlspecialchars($_GET['qr'] ?? '') ?>">
                    <input type="hidden" name="control_num" value="<?= htmlspecialchars($qrControl) ?>">
                    
                    <div>
                        <label for="">Type</label><br>
                        <input class='receive-input' type="text" placeholder='Type' name='type'>
                    </div>
                    <div class="mt-2">
                        <label for="">Description</label> <br>
                        <textarea name='description' id="" class='receive-textarea' rows='3' placeholder='Description'></textarea>
                    </div>
                    <div class="mt-2">
                        <label for="">Number of copies</label> <br>
                        <input class='receive-input' type="number" placeholder='Copies' name='pages'>
                    </div>

                    <div class="mt-2">
                        <label for="">Department</label> <br>
                        <input type="text" placeholder='Department' class='receive-input' name='department'>
                    </div>

                    <div class="mt-2">
                        <label for="">Remarks</label> <br>
                        <input type="text" placeholder='Remarks' class='receive-input' name='remark'>
                    </div>

                    <button class='btn-submit' type="submit" name="submit">CREATE</button>
                </form>
            </div>

        </div>

    </div>
</body>
</html>