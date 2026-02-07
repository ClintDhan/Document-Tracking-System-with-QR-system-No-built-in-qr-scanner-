<?php 
session_start();
require_once '../db.php'; 

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
    <div class='user-container'>
        <div class="user-form">

            <div class='user-nav-bar'>
                <div class='user-name'><p>Hi! <?= $_SESSION['name']; ?></p></div>
                <div class='user-date'><p><?= date('m/d/y') ?> </p></div>
                <form action="../operation/logout.php" method='POST'>
                    <button class='log-out'>LOGOUT</button>
                </form>
            </div>

            <button class='btn-home'>Home</button>

            <div class='option-form'>
                    <p class='option-receive'>Receive Document</p>
                    <p class='option-text'>Please indicate document information</p>

                <form action="../operation/receivedocument.php" method='POST' 
                    style='display: flex; justify-content: center; align-items: center; flex-direction: column;'>
                    <input type="hidden" name="qr_id" value="<?= htmlspecialchars($_GET['qr'] ?? '') ?>">
                    <input type="hidden" name="control_num" value="<?= htmlspecialchars($qrControl) ?>">
                    <input class='receive-input' type="text" placeholder='Title' name='type'>
                    <textarea name='description' id="" class='receive-textarea mt-2' rows='3' placeholder='Description'></textarea>
                    <input type="text" placeholder='Department' class='receive-input mt-2' name='department'>
                    <button class='btn-submit' type="submit" name="submit">CREATE</button>
                </form>
            </div>

        </div>

    </div>
</body>
</html>