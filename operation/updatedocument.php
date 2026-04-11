<?php
session_start();
require_once '../db.php';

if (isset($_POST['submit'])) {

    $updatedby = $_SESSION['user_id'];

    $doc_id = $_POST['document'];
    $qr = $_POST['qr'];
    $type = $_POST['type'];
    $description = $_POST['description'];
    $status = $_POST['status'];
    $department = $_POST['department'];
    $released_to = $_POST['released_to'] ?? null;
    $qr_id = $_POST['qr_id'];
    $document_id = $_POST['document_id'];
    $control = $_POST['control_num'];
    $returned_reason = $_POST['returned_reason'] ?? null;
    $pages = $_POST['pages'];
    $remark = !empty($_POST['remark']) ? $_POST['remark'] : 'No remarks';

    
    if ($status != 'Released') {
        $released_to = null;
    }
    if ($status != 'Returned') {
        $returned_reason = null;
    }

    // ✅ Get current document first
    $getDoc = "SELECT * FROM document WHERE id = '$document_id'";
    $getDocResult = $conn->query($getDoc);
    $row = $getDocResult->fetch_assoc();

    // ✅ Check duplicate only if description changed
    if ($description != $row['description']) {
        $checkSql = "SELECT id FROM document WHERE description = '$description'";
        $checkResult = $conn->query($checkSql);
        if ($checkResult->num_rows > 0) {
            $_SESSION['error'] = "Document description already exists.";
            header("Location: ../user/user-update.php?document=" . urlencode($document_id) . "&qr=" . urlencode($qr_id) . "&control=" . urlencode($control));
            exit();
        }
    }

    // ✅ Track changes
    $changes = [];
    if ($row['type'] != $type) $changes[] = "Type: {$row['type']} -> {$type}<br>";
    if ($row['description'] != $description) $changes[] = "Description: {$row['description']} -> {$description}<br>";
    if ($row['status'] != $status) $changes[] = "Status: {$row['status']} -> {$status}<br>";
    if ($row['department'] != $department) $changes[] = "Department: {$row['department']} -> {$department}<br>";
    if ($row['pages'] != $pages) $changes[] = "Pages: {$row['pages']} -> {$pages}<br>";
    $changesString = !empty($changes) ? implode("", $changes) : null;

    // ✅ UPDATE document
    $sql = "UPDATE document 
            SET type = '$type',
                description = '$description',
                status = '$status',
                pages = '$pages',
                released_to = " . ($released_to ? "'$released_to'" : "NULL") . ",
                returned_reason = " . ($returned_reason ? "'$returned_reason'" : "NULL") . "
            WHERE qr_id = '$qr_id'";
    $conn->query($sql);

    // ✅ INSERT log
    $logSql = "INSERT INTO document_log(document_id, action, changes, performed_by, remarks) 
               VALUES('$document_id', '$status', '$changesString', '$updatedby', '$remark')";
    $conn->query($logSql);

    // ✅ Success
    $_SESSION['success'] = "Document successfully updated.";
    header("Location: ../user/user-home.php?control=" . urlencode($control));
    exit();
}
?>