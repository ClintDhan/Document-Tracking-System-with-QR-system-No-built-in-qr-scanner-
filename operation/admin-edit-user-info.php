<?php 
session_start();
require_once '../db.php'; 

if(isset($_POST['submit'])) {

    $conn->begin_transaction(); // ✅ START TRANSACTION

    try {

        $updatedby = $_SESSION['user_id'];
        $status = $_POST['is_active'];
        $id = $_POST['id'];
        $name = $_POST['name'];
        $role = $_POST['role'];
        $action = "Change user information";

        $changes = [];

        // GET user
        $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        // CHECK duplicate username
        $stmt = $conn->prepare("SELECT * FROM user WHERE name = ? AND id != ?");
        $stmt->bind_param("ss", $name, $id);
        $stmt->execute();
        $checkUserResult = $stmt->get_result();    

        if($checkUserResult->num_rows > 0) {
            $conn->rollback();
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

        // UPDATE user
        $stmt = $conn->prepare("UPDATE user 
                SET name = ?,
                    is_active = ?,
                    role = ?
                WHERE id = ?");
        $stmt->bind_param("ssss", $name, $status, $role, $id);
        $stmt->execute();

        // INSERT log
        $stmt = $conn->prepare("INSERT INTO user_log(performed_by, user_id, action, note) 
                VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssss", $updatedby, $id, $action, $changesString);
        $stmt->execute();

        $conn->commit(); // ✅ SAVE EVERYTHING

        $_SESSION['success'] = "User successfully updated.";
        header("Location: ../admin/admin-user.php");
        exit();

    } catch (Exception $e) {

        $conn->rollback(); // ❌ undo everything

        $_SESSION['error'] = "Update failed.";
        header("Location: ../admin/admin-user.php");
        exit();
    }
}
?>