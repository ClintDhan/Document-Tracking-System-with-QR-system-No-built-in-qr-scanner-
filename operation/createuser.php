<?php
require_once '../db.php';

if(isset($_POST['create'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $role = $_POST['role'];


    $sql = "SELECT * FROM user WHERE name = '$name'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        echo 'user already existed!';
    }
    else {
        $sql1 = "INSERT INTO user (name , password, role) VALUES ('$name' , '$password' , '$role')";
        $result1 = $conn->query($sql1);

         if ($result1) {
            echo 'You created a user';
        } else {
            echo 'Error creating user: ' . $conn->error;
        }
    }

}
 


?>