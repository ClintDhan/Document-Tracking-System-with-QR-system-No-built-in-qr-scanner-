<?php 
session_start();
require_once '../db.php';

if(isset($_POST['approve']) || isset($_POST['mayor'])) {

    $conn->begin_transaction();

    try {

        $qr_id = $_POST['qr_id'];
        $control = $_POST['control'];
        $type = $_POST['type'];
        $description = $_POST['description'];
        $department = $_POST['department'];
        $createdBy = $_SESSION['user_id'];
        $pages = $_POST['pages'];
        $remark = !empty($_POST['remark']) ? $_POST['remark'] : 'No remarks';

        // STATUS LOGIC
        if(isset($_POST['approve'])) {
            $status = "Approved";
        }

        if(isset($_POST['mayor'])) {
            $status = "For MJCA Approval";
        }

        // CHECK DUPLICATE
        $stmt = $conn->prepare("SELECT id FROM document WHERE description = ?");
        $stmt->bind_param("s", $description);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0) {
            $conn->rollback();
            $_SESSION['error'] = "Document description already exists.";
            header("Location: ../admin/admin-receive-document.php?control=" . urlencode($control));
            exit();
        }

        // INSERT DOCUMENT
        $stmt = $conn->prepare("
            INSERT INTO document 
            (qr_id, type, description, status, department, created_by, pages)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "isssssi",
            $qr_id,
            $type,
            $description,
            $status,
            $department,
            $createdBy,
            $pages
        );

        $stmt->execute();
        $document_id = $conn->insert_id;

        // UPDATE QR
        $stmt = $conn->prepare("UPDATE qr_code SET is_used = 1 WHERE id = ?");
        $stmt->bind_param("i", $qr_id);
        $stmt->execute();

        // INSERT LOG
        $stmt = $conn->prepare("
            INSERT INTO document_log 
            (document_id, action, performed_at, performed_by, remarks)
            VALUES (?, ?, NOW(), ?, ?)
        ");

        $stmt->bind_param("ssss", $document_id, $status, $createdBy, $remark);
        $stmt->execute();

        $conn->commit();

        $_SESSION['success'] = "Document successfully {$status}.";
        header("Location: ../admin/admin-document.php");
        exit();

    } catch (Exception $e) {

        $conn->rollback();

        $_SESSION['error'] = "Something went wrong while processing document.";
        header("Location: ../admin/admin-receive-document.php?control=" . urlencode($control));
        exit();
    }
}
?>