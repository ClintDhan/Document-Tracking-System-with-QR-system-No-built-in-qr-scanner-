<?php 
session_start();
require_once '../db.php';

if(isset($_POST['submit'])) {
    $qr_id = $_POST['qr_id'];
    $control = $_POST['control'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $department = $_POST['department'];
    $createdBy = $_SESSION['user_id'];
    $pages = $_POST['pages'];
    $remark = !empty($_POST['remark']) ? $_POST['remark'] : 'No remarks';

    // ✅ CHECK duplicate
    $stmt = $conn->prepare("SELECT * FROM document WHERE description = ?");
    $stmt->bind_param("s", $description);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0) {
        $_SESSION['error'] = "Document description already exists.";
        header("Location: ../admin/admin-receive-document.php?control=" . urlencode($control));
        exit();
    } else {
        // ✅ INSERT document
        $stmt = $conn->prepare("INSERT INTO document (qr_id, type, description, department, created_by, pages) 
                 VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $qr_id, $type, $description, $department, $createdBy, $pages);
        $result1 = $stmt->execute();
        $document_id = $conn->insert_id;

        // ✅ Mark QR as used
        $stmt2 = $conn->prepare("UPDATE qr_code SET is_used = 1 WHERE id = ?");
        $stmt2->bind_param("i", $qr_id);
        $qrUpdate = $stmt2->execute();
        
        if($result1 && $qrUpdate) {
            
            // ✅ INSERT log
            $stmt = $conn->prepare("INSERT INTO document_log (document_id, action, performed_at, performed_by, remarks)
            VALUES (?, 'Received', NOW(), ?, ?)");
            $stmt->bind_param("sss", $document_id, $createdBy, $remark);
            $stmt->execute();

            $_SESSION['success'] = "Document successfully received.";
            header("Location: ../admin/admin-document.php");
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong";
            header("Location: ../admin/admin-receive-document.php?control=" . urlencode($control));
            exit();
        }
    }
}
?>