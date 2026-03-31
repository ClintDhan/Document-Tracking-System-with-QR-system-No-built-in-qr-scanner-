<?php
session_start();
require_once '../db.php';

$performed = 'Logged Out';
$sql = "INSERT INTO auth_logs (performed, user_id) 
                        VALUES ('$performed', {$_SESSION['user_id']})";
        $conn->query($sql);
$_SESSION = [];


session_destroy();


header("Location: ../login.php");
exit;
?>
