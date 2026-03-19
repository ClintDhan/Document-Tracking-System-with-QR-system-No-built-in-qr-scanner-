<?php 
session_start();
require_once '../db.php'; 

if(isset($_POST['submit'])) {

    $updatedby = $_SESSION['user_id'];

    $type = $_POST['type'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $department = $_POST['department'];
    $released_to = $_POST['released_to'] ?? null;
    $qr_id = $_POST['qr_id'];
    $document_id = $_POST['document_id'];
    $control = $_POST['control_num'];
    $returned_reason = $_POST['returned_reason'] ?? null;
    $pages = $_POST['pages'];

    $changes = [];

    $getDoc = "SELECT * FROM document where id ='$document_id'";
    $getDocResult = $conn->query($getDoc);
    $row = $getDocResult->fetch_assoc();

    if ($row['type'] != $type) {
        $changes[] = "Type: {$row['type']} -> {$type}<br>";
    }

    if($row['description'] != $description) {
        $changes[] = "Description: {$row['description']} -> {$description}<br>";
    }

    if($row['status'] != $status) {
        $changes[] = "Status: {$row['status']} -> {$status}<br>";
    }
    if($row['department'] != $department) {
        $changes[] = "Department: {$row['department']} -> {$department}<br>";
    }
    if($row['pages'] != $pages) {
        $changes[] = "Pages: {$row['pages']} -> {$pages}<br>";
    }

    $changesString = !empty($changes) ? implode("", $changes) : null;



    
    if($status != 'Released') {
        $released_to = null;
    }

    if($status != 'Returned') {
        $returned_reason = null;
    }

    $sql = "UPDATE document 
            SET type = '$type',
                description = '$description',
                status = '$status',
                pages = '$pages',
                released_to = " . ($released_to ? "'$released_to'" : "NULL") . ",
                returned_reason = " . ($returned_reason ? "'$returned_reason'" : "NULL") . "
            WHERE qr_id = '$qr_id'";

    $conn->query($sql);

   
    $logSql = "INSERT INTO document_log(document_id, action, changes, performed_by) 
               VALUES('$document_id', '$status', '$changesString' , '$updatedby')";
    $conn->query($logSql);

    $_SESSION['success'] = "Document successfully updated.";
    header("Location: ../user/user-home.php?control=" . urlencode($control));
    exit();
}
?>
