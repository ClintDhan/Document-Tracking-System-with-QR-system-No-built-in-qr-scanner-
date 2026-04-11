<?php
require_once '../db.php';
ob_clean();
require_once __DIR__ . '/../phpqrcode/qrlib.php'; // offline QR code library


if (!isset($_GET['control'])) {
    die("Invalid request");
}

$control = $_GET['control'];

// Your server IP
$serverIP = '192.168.1.15';

// QR URL
$url = "http://$serverIP/DocumentTrackingSys/qr-handler.php?control=$control";

// Force download
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="'.$control.'.png"');

// Generate QR
QRcode::png($url, false, QR_ECLEVEL_L, 6, 2);
exit;
?>