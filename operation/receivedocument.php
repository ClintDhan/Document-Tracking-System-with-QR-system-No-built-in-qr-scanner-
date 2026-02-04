<?php 
session_start();
require_once '../db.php';

if(isset($_POST['submit'])) {
    $qr_id = $_POST['qr_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $department = $_POST['department'];
    $createdBy = $_SESSION['user_id'];

    // Check if a document with the same title exists
    $sql = "SELECT * FROM document WHERE title = '$title'";
    $result = $conn->query($sql);

    if($result->num_rows > 0) {
        echo 'Document already exists';
    } else {
        // Insert document
        $sql1 = "INSERT INTO document (qr_id, title, description, department, created_by) 
                 VALUES ('$qr_id', '$title', '$description', '$department', '$createdBy')";
        $result1 = $conn->query($sql1);

        // Mark QR as used
        $stmt2 = $conn->prepare("UPDATE qr_code SET is_used = 1 WHERE id = ?");
        $stmt2->bind_param("i", $qr_id);
        $qrUpdate = $stmt2->execute();

        if($result1 && $qrUpdate) {
            echo 'Document received successfully!';
        } else {
            echo 'Something went wrong.';
        }
    }
}
?>
