<?php
require_once '../db.php';
session_start();

// ----------------------------
// 1️⃣ Check if user is logged in
// ----------------------------
if (!isset($_SESSION['user_id'])) {
    // Not logged in → redirect to login page
    header("Location: ../login.php");
    exit();
}

// ----------------------------
// 2️⃣ Validate QR scan
// ----------------------------
if (!isset($_GET['control'])) {
    die("No QR scanned.");
}

$control = $_GET['control'];

// Lookup QR in DB
$stmt = $conn->prepare("SELECT * FROM qr_code WHERE control_num = ?");
$stmt->bind_param("s", $control);
$stmt->execute();
$result = $stmt->get_result();
$qr = $result->fetch_assoc();

if (!$qr) {
    die("Invalid QR code.");
}

// ----------------------------
// 3️⃣ Check if QR is already used
// ----------------------------
if ($qr['is_used'] == 1) {
    die("QR already used.");
}

// ----------------------------
// 4️⃣ QR is valid and unused → show options
// ----------------------------
?>
<!DOCTYPE html>
<html>
<head>
    <title>QR Actions</title>
</head>
<body>
<h2>QR Scanned: <?= htmlspecialchars($qr['control_num']) ?></h2>
<p>Choose action:</p>
<ul>
    <li><a href="receive_document.php?qr=<?= $qr['id'] ?>">Receive Document</a></li>
    <li><a href="update_document.php?qr=<?= $qr['id'] ?>">Update Document</a></li>
    <li><a href="view_document.php?qr=<?= $qr['id'] ?>">View Document</a></li>
</ul>
</body>
</html>
