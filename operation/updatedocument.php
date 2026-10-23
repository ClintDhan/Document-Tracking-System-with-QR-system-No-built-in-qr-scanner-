
<?php
session_start();
require_once '../db.php';

if (isset($_POST['submit'])) {

    $conn->begin_transaction();

    try {

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
        $remark = !empty($_POST['remark'])
            ? $_POST['remark']
            : 'No remarks';

        // GET CURRENT DOCUMENT
        $stmt = $conn->prepare(
            "SELECT * FROM document WHERE id = ?"
        );
        $stmt->bind_param("s", $document_id);
        $stmt->execute();

        $row = $stmt->get_result()->fetch_assoc();

        if (!$row) {
            throw new Exception("Document not found");
        }

        // PRESERVE VALUES FOR THE HISTORICAL LOG
        $log_released_to = !empty($released_to)
            ? $released_to
            : $row['released_to'];

        $log_returned_reason = !empty($returned_reason)
            ? $returned_reason
            : $row['returned_reason'];

        // DUPLICATE CHECK (only if description changed)
        if ($description != $row['description']) {

            $stmt = $conn->prepare(
                "SELECT id FROM document
                 WHERE description = ? AND id != ?"
            );
            $stmt->bind_param("ss", $description, $document_id);
            $stmt->execute();

            $checkResult = $stmt->get_result();

            if ($checkResult->num_rows > 0) {
                $conn->rollback();

                $_SESSION['error'] =
                    "Document description already exists.";

                header(
                    "Location: ../user/user-update.php?document="
                    . urlencode($document_id)
                    . "&qr=" . urlencode($qr_id)
                    . "&control=" . urlencode($control)
                );
                exit();
            }
        }

        // TRACK CHANGES
        $changes = [];

        if ($row['type'] != $type) {
            $changes[] = "Type: {$row['type']} -> {$type}<br>";
        }

        if ($row['description'] != $description) {
            $changes[] = "Description: {$row['description']} -> {$description}<br>";
        }

        if ($row['status'] != $status) {
            $changes[] = "Status: {$row['status']} -> {$status}<br>";
        }

        if ($row['department'] != $department) {
            $changes[] = "Department: {$row['department']} -> {$department}<br>";
        }

        if ($row['pages'] != $pages) {
            $changes[] = "Pages: {$row['pages']} -> {$pages}<br>";
        }

        if (($row['released_to'] ?? '') != ($released_to ?? '')) {
            $changes[] = "Released To: "
                . ($row['released_to'] ?? 'None')
                . " -> "
                . ($released_to ?? 'None')
                . "<br>";
        }

        if (($row['returned_reason'] ?? '') != ($returned_reason ?? '')) {
            $changes[] = "Returned Reason: "
                . ($row['returned_reason'] ?? 'None')
                . " -> "
                . ($returned_reason ?? 'None')
                . "<br>";
        }

        $changesString = !empty($changes)
            ? implode("", $changes)
            : null;

        // CLEAR CURRENT VALUES WHEN THEY DO NOT APPLY
        if ($status != 'Released') {
            $released_to = null;
        }

        if ($status != 'Returned') {
            $returned_reason = null;
        }

        // UPDATE CURRENT DOCUMENT
        $stmt = $conn->prepare(
            "UPDATE document
             SET type = ?,
                 description = ?,
                 status = ?,
                 department = ?,
                 pages = ?,
                 released_to = ?,
                 returned_reason = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssssssss",
            $type,
            $description,
            $status,
            $department,
            $pages,
            $released_to,
            $returned_reason,
            $document_id
        );

        $stmt->execute();

        // INSERT HISTORICAL LOG
        $stmt = $conn->prepare(
            "INSERT INTO document_log
             (document_id, action, changes, performed_by,
              released_to, returned_reason, remarks)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssssss",
            $document_id,
            $status,
            $changesString,
            $updatedby,
            $log_released_to,
            $log_returned_reason,
            $remark
        );

        $stmt->execute();

        // COMMIT TRANSACTION
        $conn->commit();

        $_SESSION['success'] = "Document successfully updated.";

        header(
            "Location: ../user/user-home.php?control="
            . urlencode($control)
        );
        exit();

    } catch (Throwable $e) {

        $conn->rollback();

        error_log("User document update failed: " . $e->getMessage());

        $_SESSION['error'] = "Update failed.";

        header(
            "Location: ../user/user-home.php?control="
            . urlencode($control)
        );
        exit();
    }
}
?>
