<?php 
session_start();
require_once "../db.php";


if(isset($_POST['submit'])) {

$document_id = $_POST['id'];
$updatedby = $_SESSION['user_id'];
$qrControl = $_POST['qr'];
$status = "Reviewed";
$changes = [];

$sql = "SELECT * FROM document WHERE id = '$document_id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if($row['status'] != $status){
    $changes[] = "Status: {$row['status']} -> {$status}";
}

$changesString = !empty($changes) ? implode("", $changes) : null;

$sql2 = "UPDATE document SET status = '$status' WHERE id = '$document_id'";
$result2 = $conn->query($sql2);

if($result2) {
    $logSql = "INSERT INTO document_log(document_id, action, changes, performed_by) 
               VALUES('$document_id', '$status', '$changesString', '$updatedby')";
    $conn->query($logSql);
    header("Location: ../sub-admin/sub-admin.php?control=$qrControl");
    exit();
}

}

?>