<?php 
require_once "../db.php";
session_start();

if(isset($_POST['submit'])) {

    $conn->begin_transaction();

    try {

        $id = $_POST['id'];
        $performedBy = $_SESSION['user_id'];
        $password = $_POST['password'];

        // GET USER
        $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $name = $user['name'];

        // UPDATE PASSWORD
        $stmt = $conn->prepare("
            UPDATE user 
            SET password = ?, first_login = 0 
            WHERE id = ?
        ");
        $stmt->bind_param("ss", $password, $id);
        $stmt->execute();

        // INSERT LOG
        $note = "Reset password to {$password}";
        $action = "Reset password";

        $stmt = $conn->prepare("
            INSERT INTO user_log(performed_by, user_id, action, note)
            VALUES(?, ?, ?, ?)
        ");
        $stmt->bind_param("ssss", $performedBy, $id, $action, $note);
        $stmt->execute();

        $conn->commit();

        $_SESSION['success'] = "{$name}'s password has been reset. Please check the user logs to verify.";
        header("Location: ../admin/admin-user.php");
        exit();

    } catch (Exception $e) {

        $conn->rollback();

        $_SESSION['error'] = "There is an error resetting the password.";
        header("Location: ../admin/admin-reset-password?user=$id");
        exit();
    }
}
?>