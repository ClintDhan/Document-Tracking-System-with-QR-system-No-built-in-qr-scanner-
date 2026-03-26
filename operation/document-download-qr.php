<?php
require_once '../db.php';
require_once '../phpqrcode/qrlib.php';

if (!isset($_GET['control'])) {
    die("Invalid request");
}

$control = $_GET['control'];

// Your server IP
$serverIP = '192.168.68.101';

// QR URL
$url = "http://$serverIP/DocumentTrackingSys/qr-handler.php?control=$control";

// Force download
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="'.$control.'.png"');

// Generate QR
QRcode::png($url, false, QR_ECLEVEL_L, 6, 2);
exit;
?>