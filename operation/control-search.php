<?php 
session_start();
require_once '../db.php'; 

if(isset($_POST['submit'])) {

    $control = $_POST['control'];

    $sql = "SELECT * FROM qr_code WHERE control_num = '$control'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        header("Location: ../user/user-home.php?control=" . urlencode($control));
        exit();
    } else {
        header("Location: ../user/user-home.php?error=invalid_qr");
        exit();
    }
}
?>