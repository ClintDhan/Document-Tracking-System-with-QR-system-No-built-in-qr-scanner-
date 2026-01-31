<?php
include 'phpqrcode/qrlib.php';

$qrText = 'http://192.168.1.15/DocumentTrackingSys/login.php';

QRcode::png($qrText, false, QR_ECLEVEL_L, 4);
?>