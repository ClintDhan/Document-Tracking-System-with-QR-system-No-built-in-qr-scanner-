<?php 
    session_start();
    require_once '../db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Print QR Codes</title>
</head>
<body>

<h2>Print QR Codes</h2>

<form action="../operation/generate_qr_download.php" method="post">
    <label>QR per page:</label>
    <select name="qty" required>
        <option value="5">5 QR</option>
        <option value="10">10 QR</option>
        <option value="20">20 QR</option>
    </select>

    <br><br>

    <button type="submit">Print QR</button>
</form>

</body>
</html>
