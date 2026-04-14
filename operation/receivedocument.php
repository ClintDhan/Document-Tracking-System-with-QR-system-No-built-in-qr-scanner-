<?php
session_start();
require_once '../db.php';

if (isset($_POST['submit'])) {

    $control = $_POST['control_num'];
    $qr_id = $_POST['qr_id'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $department = $_POST['department'];
    $createdBy = $_SESSION['user_id'];
    $pages = $_POST['pages'];
    $remark = !empty($_POST['remark']) ? $_POST['remark'] : 'No remarks';

    // 🔥 CHECK IF DESCRIPTION ALREADY EXISTS
    $stmt = $conn->prepare("SELECT id FROM document WHERE description = ?");
    $stmt->bind_param("s", $description);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $_SESSION['error'] = "Document description already exists.";
        header("Location: ../user/user-home.php?control=" . urlencode($control));
        exit();

    } else {

        // 🔥 INSERT DOCUMENT
        $stmt = $conn->prepare("
            INSERT INTO document (qr_id, type, description, department, created_by, pages)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param("issssi", $qr_id, $type, $description, $department, $createdBy, $pages);
        $result1 = $stmt->execute();

        $document_id = $conn->insert_id;

        // 🔥 UPDATE QR CODE
        $stmt2 = $conn->prepare("UPDATE qr_code SET is_used = 1 WHERE id = ?");
        $stmt2->bind_param("i", $qr_id);
        $qrUpdate = $stmt2->execute();

        // 🔥 IF BOTH SUCCESS
        if ($result1 && $qrUpdate) {

            // 🔥 INSERT LOG
            $stmt3 = $conn->prepare("
                INSERT INTO document_log (document_id, action, performed_at, performed_by, remarks)
                VALUES (?, 'Received', NOW(), ?, ?)
            ");

            $stmt3->bind_param("iis", $document_id, $createdBy, $remark);
            $stmt3->execute();

            $_SESSION['success'] = "Document successfully received.";
            header("Location: ../user/user-home.php?control=" . urlencode($control));
            exit();

        } else {

            $_SESSION['error'] = "Something went wrong while saving document.";
            header("Location: ../user/user-home.php?control=" . urlencode($control));
            exit();
        }
    }
}
?>