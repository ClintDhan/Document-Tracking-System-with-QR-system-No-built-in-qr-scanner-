<?php 
session_start();
require_once "../db.php";

if(isset($_POST['submit'])) {

    $document_id = $_POST['id'] ?? null;
    $updatedby = $_SESSION['user_id'];
    $status = "Reviewed";
    $changes = [];

    if (!$document_id) {
        die("Document ID missing");
    }

    $sql = "SELECT * FROM document WHERE id = '$document_id'";
    $result = $conn->query($sql);

    if ($result->num_rows === 0) {
        die("Document not found");
    }

    $row = $result->fetch_assoc();

    if($row['status'] != $status){
        $changes[] = "Status: {$row['status']} -> {$status}";
    }

    $changesString = !empty($changes) ? implode(", ", $changes) : null;

    $sql2 = "UPDATE document SET status = '$status' WHERE id = '$document_id'";
    $result2 = $conn->query($sql2);

    if($result2) {
        $logSql = "INSERT INTO document_log(document_id, action, changes, performed_by) 
                   VALUES('$document_id', '$status', '$changesString', '$updatedby')";
        $conn->query($logSql);

        header("Location: ../admin/admin-document.php");
        exit();
    }
}
?>