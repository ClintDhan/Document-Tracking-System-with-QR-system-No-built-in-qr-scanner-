<?php
session_start();
require_once '../db.php';

$admin_id = $_SESSION['user_id'];

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];

    // ✅ SELECT
    $stmt = $conn->prepare("SELECT * FROM user WHERE name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $_SESSION['error'] = "Username {$name} already exists.";
        header("Location: ../admin/admin-add-user.php");
        exit();
    }
    else {
        // ✅ INSERT user
        $stmt = $conn->prepare("INSERT INTO user (name , password) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $password);
        $result1 = $stmt->execute();

        if($result1) {
            $new_user_id = $conn->insert_id;

            $action = 'Create user';

            // ✅ INSERT log
            $stmt = $conn->prepare("INSERT INTO user_log(performed_by, user_id, action) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $admin_id, $new_user_id, $action);
            $stmt->execute();

            $_SESSION['success'] = "User created successfully.";
            header("Location: ../admin/admin-user.php");
            exit();
            exit;
        }
    }
}
?>