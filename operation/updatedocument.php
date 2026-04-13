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

    // Get current document first
    $stmt = $conn->prepare("SELECT * FROM document WHERE id = ?");
    $stmt->bind_param("s", $document_id);
    $stmt->execute();
    $getDocResult = $stmt->get_result();
    $row = $getDocResult->fetch_assoc();

    // Check duplicate only if description changed
    if ($description != $row['description']) {
        $stmt = $conn->prepare("SELECT id FROM document WHERE description = ?");
        $stmt->bind_param("s", $description);
        $stmt->execute();
        $checkResult = $stmt->get_result();

        if ($checkResult->num_rows > 0) {
            $_SESSION['error'] = "Document description already exists.";
            header("Location: ../user/user-update.php?document=" . urlencode($document_id) . "&qr=" . urlencode($qr_id) . "&control=" . urlencode($control));
            exit();
        }
    }

    // this is for changes ngani
    $changes = [];
    if ($row['type'] != $type) $changes[] = "Type: {$row['type']} -> {$type}<br>";
    if ($row['description'] != $description) $changes[] = "Description: {$row['description']} -> {$description}<br>";
    if ($row['status'] != $status) $changes[] = "Status: {$row['status']} -> {$status}<br>";
    if ($row['department'] != $department) $changes[] = "Department: {$row['department']} -> {$department}<br>";
    if ($row['pages'] != $pages) $changes[] = "Pages: {$row['pages']} -> {$pages}<br>";
    $changesString = !empty($changes) ? implode("", $changes) : null;

    // UPDATE 
    $stmt = $conn->prepare("UPDATE document 
            SET type = ?,
                description = ?,
                status = ?,
                pages = ?,
                released_to = ?,
                returned_reason = ?
            WHERE qr_id = ?");

    $stmt->bind_param(
        "sssssss",
        $type,
        $description,
        $status,
        $pages,
        $released_to,
        $returned_reason,
        $qr_id
    );

    $stmt->execute();

    // INSERT log
    $stmt = $conn->prepare("INSERT INTO document_log(document_id, action, changes, performed_by, remarks) 
               VALUES(?, ?, ?, ?, ?)");

    $stmt->bind_param(
        "sssss",
        $document_id,
        $status,
        $changesString,
        $updatedby,
        $remark
    );

    $stmt->execute();


    $_SESSION['success'] = "Document successfully updated.";
    header("Location: ../user/user-home.php?control=" . urlencode($control));
    exit();
}
?>