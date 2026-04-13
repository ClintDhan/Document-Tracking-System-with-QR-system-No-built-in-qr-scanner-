<?php 
require_once "../db.php";
session_start();

if(isset($_POST['submit'])) {
$id = $_POST['id'];
$performedBy = $_SESSION['user_id'];
$password = $_POST['password'];

// ✅ SELECT user
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$nameResult = $stmt->get_result();
$nameResult1 = $nameResult->fetch_assoc();
$name = $nameResult1['name'];

// ✅ UPDATE password
$stmt = $conn->prepare("UPDATE user SET password = ?, first_login = 0 WHERE id = ?");
$stmt->bind_param("ss", $password, $id);
$result = $stmt->execute();

if($result) {
    $note = "Reset password to {$password}";
    $action = "Reset password";

    // ✅ INSERT log
    $stmt = $conn->prepare("INSERT INTO user_log(performed_by, user_id, action, note)
                    VALUES(?, ?, ?, ?)");
    $stmt->bind_param("ssss", $performedBy, $id, $action, $note);
    $resultLog = $stmt->execute();

    if(!$resultLog) {
        $_SESSION['error'] = "There is an error reseting {$name}'s password";
        header("Location: ../admin/admin-reset-password?user=$id");
        exit();
    }
    else {
        $_SESSION['success'] = "{$name}'s password has been reset. Please check the user logs to verify.";            
        header("Location: ../admin/admin-user.php");
        exit();
    }

}

else {
    $_SESSION['error'] = "There is an error reseting {$name}'s password";
    header("Location: ../admin/admin-reset-password?user=$id");
    exit();
}

}
?>