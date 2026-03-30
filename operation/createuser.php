<?php
session_start();
require_once '../db.php';

$admin_id = $_SESSION['user_id'];

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];


    $sql = "SELECT * FROM user WHERE name = '$name'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        echo 'user already existed!';
    }
    else {
        $sql1 = "INSERT INTO user (name , password) VALUES ('$name' , $password)";
        $result1 = $conn->query($sql1);

        if($result1) {
            $new_user_id = $conn->insert_id;

            $action = 'Create user';
            $sql2 = "INSERT INTO user_log(performed_by, user_id, action) VALUES ($admin_id , $new_user_id, '$action')";
            $conn->query($sql2);
            header("Location: ../admin/admin-user.php");
            exit;
        }
    }
}
?>