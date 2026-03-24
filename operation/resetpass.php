<?php 
require_once "../db.php";
session_start();


if(isset($_POST['submit'])) {
$id = $_POST['id'];
$performedBy = $_SESSION['user_id'];
$password = $_POST['password'];


$upSql = "UPDATE user SET password ='$password', first_login = 0 WHERE id ='$id'";
$result = $conn->query($upSql);

    if($result) {
        $note = "Changed password to {$password}";
        $action = "Change password";
        $logSql = "INSERT INTO user_log(admin_id, user_id, action, note)
                    VALUES('$performedBy', '$id', '$action', '$note')";
        $resultLog = $conn->query($logSql);

        if(!$resultLog) {
            echo "naay mali ani haha";
        }
        else {
            header("Location: ../admin/admin-user.php");
            exit();
        }

    }

    else {
        echo "naay error";
    }


}

?>