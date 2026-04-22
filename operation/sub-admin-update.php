<?php 
session_start();
require_once "../db.php";

if(isset($_POST['approved']) || isset($_POST['mayor'])) {

    $conn->begin_transaction();

    try {

        $document_id = $_POST['id'] ?? null;
        $updatedby = $_SESSION['user_id'];
        $remark = !empty($_POST['remark']) ? $_POST['remark'] : 'No remarks';
        $changes = [];

        if (!$document_id) {
            throw new Exception("Document ID missing");
        }

        // GET DOCUMENT
        $stmt = $conn->prepare("SELECT * FROM document WHERE id = ?");
        $stmt->bind_param("s", $document_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            throw new Exception("Document not found");
        }

        // STATUS LOGIC
        if(isset($_POST['approved'])) {
            $status = "Approved";
        }

        if(isset($_POST['mayor'])) {
            $status = "For MJCA Approval";
        }

        if($row['status'] != $status){
            $changes[] = "Status: {$row['status']} -> {$status}";
        }

        $changesString = !empty($changes) ? implode(", ", $changes) : null;

        // UPDATE DOCUMENT
        $stmt = $conn->prepare("UPDATE document SET status = ? WHERE id = ?");
        $stmt->bind_param("ss", $status, $document_id);
        $stmt->execute();

        // INSERT LOG
        $stmt = $conn->prepare("
            INSERT INTO document_log(document_id, action, changes, performed_by, remarks) 
            VALUES(?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("sssss", $document_id, $status, $changesString, $updatedby, $remark);
        $stmt->execute();

        $conn->commit();

        header("Location: ../admin/admin-document.php");
        exit();

    } catch (Exception $e) {

        $conn->rollback();
        die($e->getMessage());
    }
}
?>