<?php
session_start();
require_once '../db.php';
require_once '../phpqrcode/qrlib.php'; // offline QR code library

// 1️⃣ Validate POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['qty'])) {
    exit('Invalid request');
}

$qty = (int) $_POST['qty'];

// 2️⃣ Your server IP
$serverIP = '192.168.68.104'; // <-- change this to your PC's IP

// 3️⃣ Fetch unused QR codes from DB
$codes = [];

$stmt = $conn->prepare("
    SELECT control_num
    FROM qr_code
    WHERE is_used = 0
    ORDER BY id ASC
    LIMIT ?
");
$stmt->bind_param("i", $qty);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $control = $row['control_num'];

    // URL to your system including control number
    $url = "http://$serverIP/DocumentTrackingSys/qr-handler.php?control=$control";

    $codes[] = ['control' => $control, 'url' => $url];
}

// 4️⃣ Output printable HTML
header("Content-Type: text/html");
header("Content-Disposition: attachment; filename=unused_qr_codes.html");

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
    <title>Printable Unused QR Codes</title>
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
        <img src="<?= $dataUri ?>" alt="QR Code">
        <div class="label"><?= $control ?></div>
    </div>
<?php } ?>
</div>
</body>
</html>