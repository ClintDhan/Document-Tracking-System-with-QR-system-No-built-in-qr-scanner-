<?php 
session_start();
require_once '../db.php'; 

if(isset($_POST['submit'])) {

    $control = $_POST['control'];

    // ✅ SELECT
    $stmt = $conn->prepare("SELECT * FROM qr_code WHERE control_num = ?");
    $stmt->bind_param("s", $control);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        header("Location: ../user/user-home.php?control=" . urlencode($control));
        exit();
    } else {
        header("Location: ../user/user-home.php?error=invalid_qr");
        exit();
    }
}
?>