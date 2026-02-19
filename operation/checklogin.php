<?php
session_start();
require_once '../db.php';

$name = $_POST['name'];
$password = $_POST['password'];

$sql = "SELECT * FROM user where name = '$name' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows == 1 ) {
    $user = $result->fetch_assoc();
    
    if($password === $user['password']) {
        if($user['is_active'] == 1) {
             session_regenerate_id(true);

             $_SESSION['user_id'] = $user['id'];
             $_SESSION['name'] = $user['name'];
             $_SESSION['password'] = $user['password'];
            
            $redirect = '../user/user-home.php';
            if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
            $redirect = urldecode($_POST['redirect']); // decode QR control
            }
            
            if($user['is_admin'] == 1) {
                header("Location: ../admin/admin-dashboard.php");
                exit;
            }
            elseif ($user['is_admin'] == 0) {
                header("Location: $redirect");
                exit;
            }
        }
        else {
            echo 'Your account is invalid';
        }
    }
    else {
        echo 'Invalid Password';
    }
}
else {
    echo "Invalid username or password";
}
$conn->close();
?>