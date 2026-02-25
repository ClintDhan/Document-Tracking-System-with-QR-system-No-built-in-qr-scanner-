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
    $document_id = $_POST['doc_id'];
    $returned_reason = $_POST['returned_reason'] ?? null;

    
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
                released_to = " . ($released_to ? "'$released_to'" : "NULL") . ",
                returned_reason = " . ($returned_reason ? "'$returned_reason'" : "NULL") . "
            WHERE id = '$document_id'";

    $conn->query($sql);

   
    $logSql = "INSERT INTO document_log(document_id, action, performed_by) 
               VALUES('$document_id', '$status', '$updatedby')";
    $conn->query($logSql);

    header("Location: ../admin/admin-document.php");
    exit();
}

else {
    echo "error";
}
?>
