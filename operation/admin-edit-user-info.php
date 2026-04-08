<?php 
session_start();
require_once '../db.php'; 

if(isset($_POST['submit'])) {

    $updatedby = $_SESSION['user_id'];
    $status = $_POST['is_active'];
    $id = $_POST['id'];
    $name = $_POST['name'];
    $role = $_POST['role'];
    $action = "Change user information";

    $changes = [];

    $getUser = "SELECT * FROM user where id ='$id'";
    $getUserResult = $conn->query($getUser);
    $row = $getUserResult->fetch_assoc();

    $checkUser = "SELECT * FROM user WHERE name = '$name' AND id != '$id'";
    $checkUserResult = $conn->query($checkUser);    

    if($checkUserResult->num_rows > 0) {
        $_SESSION['error'] = "Username {$name} already exists.";
        header("Location: ../admin/admin-edit-user.php?user=$id");
        exit();
    }

    if ($row['name'] != $name) {
        $changes[] = "Name: {$row['name']} -> {$name}<br>";
    }

    $oldStatus = ($row['is_active'] == 1) ? 'Active' : 'Inactive';
    $newStatus = ($status == 1) ? 'Active' : 'Inactive';

    if ($row['is_active'] != $status) {
        $changes[] = "Status: {$oldStatus} -> {$newStatus}<br>";
    }

    if($row['role'] != $role) {
        $changes[] = "Role: {$row['role']} -> {$role}<br>";
    }

    $changesString = !empty($changes) ? implode("", $changes) : null;


    $sql = "UPDATE user 
            SET name = '$name',
                is_active = '$status',
                role = '$role'
            WHERE id = '$id'";
    $conn->query($sql);

   
    $logSql = "INSERT INTO user_log(performed_by, user_id, action, note) 
               VALUES('$updatedby', '$id', '$action' , '$changesString')";
    $conn->query($logSql);

    $_SESSION['success'] = "User successfully updated.";
    header("Location: ../admin/admin-user.php");
    exit();
}
?>
