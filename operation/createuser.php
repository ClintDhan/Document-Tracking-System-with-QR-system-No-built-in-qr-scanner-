<?php
session_start();
require_once '../db.php';

$admin_id = $_SESSION['user_id'];

if(isset($_POST['submit'])) {

    $conn->begin_transaction();

    try {

        $name = $_POST['name'];
        $password = $_POST['password'];

        // CHECK EXISTING USER
        $stmt = $conn->prepare("SELECT * FROM user WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $conn->rollback();
            $_SESSION['error'] = "Username {$name} already exists.";
            header("Location: ../admin/admin-add-user.php");
            exit();
        }

        // INSERT USER
        $stmt = $conn->prepare("INSERT INTO user (name, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $password);
        $stmt->execute();

        $new_user_id = $conn->insert_id;
        $action = 'Create user';

        // INSERT LOG
        $stmt = $conn->prepare("INSERT INTO user_log(performed_by, user_id, action) 
                                VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $admin_id, $new_user_id, $action);
        $stmt->execute();

        $conn->commit();

        $_SESSION['success'] = "User created successfully.";
        header("Location: ../admin/admin-user.php");
        exit();

    } catch (Exception $e) {

        $conn->rollback();

        $_SESSION['error'] = "Failed to create user.";
        header("Location: ../admin/admin-add-user.php");
        exit();
    }
}
?>