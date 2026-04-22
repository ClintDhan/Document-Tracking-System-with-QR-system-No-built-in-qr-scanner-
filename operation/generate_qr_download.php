<?php
session_start();
require_once '../db.php';
ob_clean();
require_once __DIR__ . '/../phpqrcode/qrlib.php'; // offline QR code library


// 1️⃣ Validate POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['qty'])) {
    exit('Invalid request');
}

$qty = (int) $_POST['qty'];
$createdBy = $_SESSION['user_id'] ?? 1;

// 2️⃣ Function: Sequential control number
function generateControlNumber($conn) {
    $year = date('Y');

    $sql = "SELECT control_num 
            FROM qr_code 
            WHERE control_num LIKE ?
            ORDER BY id DESC 
            LIMIT 1";

    $like = "MO-$year-%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $lastNum = (int) substr($row['control_num'], -6);
        $nextNum = $lastNum + 1;
    } else {
        $nextNum = 1;
    }

    return "MO-$year-" . str_pad($nextNum, 6, '0', STR_PAD_LEFT);
}

// 3️⃣ Your server IP (local network)
$serverIP = '192.168.68.101'; // <-- change this to your PC's IP

// 4️⃣ Generate QR codes in DB
$codes = [];
for ($i = 0; $i < $qty; $i++) {
    $control = generateControlNumber($conn);

    // Save QR in DB
    $stmt = $conn->prepare("
        INSERT INTO qr_code (control_num, created_by, is_used)
        VALUES (?, ?, 0)
    ");
    $stmt->bind_param("si", $control, $createdBy);
    $stmt->execute();

    // URL to your system including control number
    $url = "http://$serverIP/DocumentTrackingSys/qr-handler.php?control=$control";

    $codes[] = ['control' => $control, 'url' => $url];
}

// 5️⃣ Output printable HTML
header("Content-Type: text/html");
header("Content-Disposition: attachment; filename=qr_codes.html");

// Grid columns based on qty
$columns = match ($qty) {
    5 => 1,
    10 => 2,
    20 => 4,
    default => 2
};
?>

<!DOCTYPE html>
<html>
<head>
    <title>Printable QR Codes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .qr-page { display: grid; grid-template-columns: repeat(<?= $columns ?>, 1fr); gap: 20px; }
        .qr-item { text-align: center; border: 1px dashed #000; padding: 10px; }
        .label { margin-top: 5px; font-size: 12px; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
<div class="qr-page">
<?php
foreach ($codes as $codeData) {
    $url = $codeData['url'];
    $control = $codeData['control'];

    // Generate QR directly into a data URI
    ob_start();
    QRcode::png($url, null, QR_ECLEVEL_L, 4, 2);
    $imageString = ob_get_contents();
    ob_end_clean();
    $base64 = base64_encode($imageString);
    $dataUri = 'data:image/png;base64,' . $base64;
    ?>
    <div class="qr-item">
        <img src="<?= $dataUri ?>">
        <div class="label" style="font-size: 16px;"><?= $control ?></div>
    </div>
<?php } ?>
</div>
</body>
</html>
