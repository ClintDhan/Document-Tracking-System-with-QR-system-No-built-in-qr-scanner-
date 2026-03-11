<?php
session_start();
require_once '../db.php';


if(isset($_POST['submit'])) {
    $newPass = $_POST['newPass'];
    $reEnterPass = $_POST['rePass'];

    $sql = "SELECT * FROM user WHERE id = ".$_SESSION['user_id'];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if(empty($newPass) || empty($reEnterPass)) {
        echo "Password cannot be empty";
        exit;
    }

    if($newPass == $reEnterPass) {
        $updatePass = "UPDATE user SET first_login = 1, password = '$newPass' WHERE id =".$_SESSION['user_id'];
        $conn->query($updatePass);

        if($row['is_admin'] == 1) {
            header("Location: ../admin/admin-dashboard.php");
            exit;
        }
            elseif ($row['is_admin'] == 0) {
            header("Location: ../user/user-home.php");
            exit;
        }
    }
    else {
            echo "Both password did not match, please double check";
        }
}

else {
    echo $_SESSION['user_id'];
}

?>