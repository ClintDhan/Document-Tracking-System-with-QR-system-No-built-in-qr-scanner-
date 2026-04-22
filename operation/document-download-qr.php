<?php
require_once '../db.php';
ob_clean();
require_once __DIR__ . '/../phpqrcode/qrlib.php';

if (!isset($_GET['control'])) {
    die("Invalid request");
}

$control = $_GET['control'];
$serverIP = '192.168.68.101'; // DIRI EEDIT MING
$url = "http://$serverIP/DocumentTrackingSys/qr-handler.php?control=$control";

// Generate QR to memory (not output directly)
ob_start();
QRcode::png($url, null, QR_ECLEVEL_L, 6, 2);
$qrImageString = ob_get_contents();
ob_end_clean();

// Create image from QR
$qrImage = imagecreatefromstring($qrImageString);

// Get QR size
$qrWidth = imagesx($qrImage);
$qrHeight = imagesy($qrImage);

// Font settings
$fontSize = 5; // built-in font
$textHeight = imagefontheight($fontSize) + 10;

// Create new image with extra space for text
$newImage = imagecreatetruecolor($qrWidth, $qrHeight + $textHeight);

// Colors
$white = imagecolorallocate($newImage, 255, 255, 255);
$black = imagecolorallocate($newImage, 0, 0, 0);

// Fill background
imagefill($newImage, 0, 0, $white);

// Copy QR to new image
imagecopy($newImage, $qrImage, 0, 0, 0, 0, $qrWidth, $qrHeight);

// Center text
$textWidth = imagefontwidth($fontSize) * strlen($control);
$x = ($qrWidth - $textWidth) / 2;
$y = $qrHeight + 5;

// Add text
imagestring($newImage, $fontSize, $x, $y, $control, $black);

// Output as download
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="'.$control.'.png"');

imagepng($newImage);

// Clean up
imagedestroy($qrImage);
imagedestroy($newImage);

exit;
?>