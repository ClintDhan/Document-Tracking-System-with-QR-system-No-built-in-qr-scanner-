<?php 
session_start();
require_once '../db.php';

if(isset($_POST['submit'])) {
    $control = $_POST['control_num']; 
    $qr_id = $_POST['qr_id'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $department = $_POST['department'];
    $createdBy = $_SESSION['user_id'];
    $pages = $_POST['pages'];
    $remark = !empty($_POST['remark']) ? $_POST['remark'] : 'No remarks';

    // Check if a document with the same desc exists
    $sql = "SELECT * FROM document WHERE description = '$description' AND id != '$document_id'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        $_SESSION['error'] = "Document description already exists.";
        header("Location: ../user/user-home.php?control=" . urlencode($control));
        exit();
    } else {
        // Insert document
        $sql1 = "INSERT INTO document (qr_id, type, description, department, created_by, pages) 
                 VALUES ('$qr_id', '$type', '$description', '$department', '$createdBy', '$pages')";
        $result1 = $conn->query($sql1);
        $document_id = $conn->insert_id;

        // Mark QR as used
        $stmt2 = $conn->prepare("UPDATE qr_code SET is_used = 1 WHERE id = ?");
        $stmt2->bind_param("i", $qr_id);
        $qrUpdate = $stmt2->execute();
        

        if($result1 && $qrUpdate) {
            
            $sqlLog = "INSERT INTO document_log (document_id, action, performed_at, performed_by, remarks)
            VALUES ($document_id, 'Received', NOW(), $createdBy, '$remark')";
            $conn->query($sqlLog);

            $_SESSION['success'] = "Document successfully received.";
            header("Location: ../user/user-home.php?control=" . urlencode($control));
            exit();
        } else {
            $_SESSION['error'] = "Something went wrong";
            header("Location: ../user/user-home.php?control=" . urlencode($control));
            exit();
        }
    }
}
?>
