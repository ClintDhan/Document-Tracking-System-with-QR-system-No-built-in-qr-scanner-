<?php
session_start();
require_once '../db.php';

if (isset($_POST['submit'])) {

    $conn->begin_transaction();

    try {

        $control = $_POST['control_num'];
        $qr_id = $_POST['qr_id'];
        $type = $_POST['type'];
        $description = $_POST['description'];
        $department = $_POST['department'];
        $createdBy = $_SESSION['user_id'];
        $pages = $_POST['pages'];
        $status = $_POST['status'];
        $released_to = $_POST['released_to'] ?? null;
        $returned_reason = $_POST['returned_reason'] ?? null;
        $remark = !empty($_POST['remark']) ? $_POST['remark'] : 'No remarks';

        if ($status != 'Released') $released_to = null;
        if ($status != 'Returned') $returned_reason = null;

        // CHECK DUPLICATE
        $stmt = $conn->prepare("SELECT id FROM document WHERE description = ?");
        $stmt->bind_param("s", $description);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $conn->rollback();
            $_SESSION['error'] = "Document description already exists.";
            header("Location: ../user/user-home.php?control=" . urlencode($control));
            exit();
        }

        // INSERT DOCUMENT
        $stmt = $conn->prepare("
            INSERT INTO document 
            (qr_id, type, description, status, department, created_by, pages, released_to, returned_reason)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "issssisss",
            $qr_id,
            $type,
            $description,
            $status,
            $department,
            $createdBy,
            $pages,
            $released_to,
            $returned_reason
        );

        $stmt->execute();
        $document_id = $conn->insert_id;

        // UPDATE QR
        $stmt2 = $conn->prepare("UPDATE qr_code SET is_used = 1 WHERE id = ?");
        $stmt2->bind_param("i", $qr_id);
        $stmt2->execute();

        // INSERT LOG
        $stmt3 = $conn->prepare("
            INSERT INTO document_log (document_id, action, performed_at, performed_by, remarks)
            VALUES (?, ?, NOW(), ?, ?)
        ");

        $stmt3->bind_param("isis", $document_id, $status, $createdBy, $remark);
        $stmt3->execute();

        $conn->commit();

        $_SESSION['success'] = "Document successfully {$status}.";
        header("Location: ../user/user-home.php?control=" . urlencode($control));
        exit();

    } catch (Exception $e) {

        $conn->rollback();

        $_SESSION['error'] = "Something went wrong while saving document.";
        header("Location: ../user/user-home.php?control=" . urlencode($control));
        exit();
    }
}
?>