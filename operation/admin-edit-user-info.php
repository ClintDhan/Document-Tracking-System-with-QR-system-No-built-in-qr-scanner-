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

    // ✅ GET user
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $getUserResult = $stmt->get_result();
    $row = $getUserResult->fetch_assoc();

    // ✅ CHECK duplicate username
    $stmt = $conn->prepare("SELECT * FROM user WHERE name = ? AND id != ?");
    $stmt->bind_param("ss", $name, $id);
    $stmt->execute();
    $checkUserResult = $stmt->get_result();    

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

    // ✅ UPDATE user
    $stmt = $conn->prepare("UPDATE user 
            SET name = ?,
                is_active = ?,
                role = ?
            WHERE id = ?");
    $stmt->bind_param("ssss", $name, $status, $role, $id);
    $stmt->execute();

    // ✅ INSERT log
    $stmt = $conn->prepare("INSERT INTO user_log(performed_by, user_id, action, note) 
               VALUES(?, ?, ?, ?)");
    $stmt->bind_param("ssss", $updatedby, $id, $action, $changesString);
    $stmt->execute();

    $_SESSION['success'] = "User successfully updated.";
    header("Location: ../admin/admin-user.php");
    exit();
}
?>