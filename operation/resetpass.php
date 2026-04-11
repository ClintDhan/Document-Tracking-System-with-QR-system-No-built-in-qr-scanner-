<?php 
require_once "../db.php";
session_start();


if(isset($_POST['submit'])) {
$id = $_POST['id'];
$performedBy = $_SESSION['user_id'];
$password = $_POST['password'];


$nameSql = "SELECT * FROM user where id ='$id'";
$nameResult = $conn->query($nameSql);
$nameResult1 = $nameResult->fetch_assoc();
$name = $nameResult1['name'];

$upSql = "UPDATE user SET password ='$password', first_login = 0 WHERE id ='$id'";
$result = $conn->query($upSql);

    if($result) {
        $note = "Reset password to {$password}";
        $action = "Reset password";
        $logSql = "INSERT INTO user_log(performed_by, user_id, action, note)
                    VALUES('$performedBy', '$id', '$action', '$note')";
        $resultLog = $conn->query($logSql);

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