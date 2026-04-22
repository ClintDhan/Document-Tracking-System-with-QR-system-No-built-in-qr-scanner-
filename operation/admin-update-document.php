<?php 
session_start();
require_once '../db.php'; 

if(isset($_POST['submit'])) {

    $conn->begin_transaction(); // ✅ START TRANSACTION

    try {

        $updatedby = $_SESSION['user_id'];

        $type = $_POST['type'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $department = $_POST['department'];
        $released_to = $_POST['released_to'] ?? null;
        $document_id = $_POST['doc_id'];
        $returned_reason = $_POST['returned_reason'] ?? null;
        $copies = $_POST['pages'];
        $remark = !empty($_POST['remark']) ? $_POST['remark'] : 'No remarks';

        $changes = [];

        // GET document
        $stmt = $conn->prepare("SELECT * FROM document WHERE id = ?");
        $stmt->bind_param("s", $document_id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        // CHECK duplicate
        $stmt = $conn->prepare("SELECT * FROM document WHERE description = ? AND id != ?");
        $stmt->bind_param("ss", $description, $document_id);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if($checkResult->num_rows > 0) {
            $conn->rollback();
            $_SESSION['error'] = "Document description already exists.";
            header("Location: ../admin/admin-doc-edit.php");
            exit();
        }

        if($row['type'] != $type) {
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

        if($row['pages'] != $copies) {
            $changes[] = "Copies: {$row['pages']} -> {$copies}<br>";
        }

        $changesString = !empty($changes) ? implode("", $changes) : null;

        if($status != 'Released') {
            $released_to = null;
        }

        if($status != 'Returned') {
            $returned_reason = null;
        }

        // UPDATE document
        $stmt = $conn->prepare("UPDATE document 
                SET type = ?,
                    description = ?,
                    pages = ?,
                    status = ?,
                    released_to = ?,
                    returned_reason = ?
                WHERE id = ?");
        $stmt->bind_param("sssssss", $type, $description, $copies, $status, $released_to, $returned_reason, $document_id);
        $stmt->execute();

        // INSERT log
        $stmt = $conn->prepare("INSERT INTO document_log
            (document_id, action, changes, performed_by, remarks) 
            VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $document_id, $status, $changesString, $updatedby, $remark);
        $stmt->execute();

        $conn->commit(); // ✅ SAVE ALL CHANGES

        $_SESSION['success'] = "Document successfully updated.";
        header("Location: ../admin/admin-document.php");
        exit();

    } catch (Exception $e) {

        $conn->rollback(); // ❌ undo everything

        $_SESSION['error'] = "Update failed.";
        header("Location: ../admin/admin-doc-edit.php");
        exit();
    }
}

else {
    $_SESSION['error'] = "Something went wrong";
    header("Location: ../admin/admin-doc-edit.php");
    exit();
}
?>