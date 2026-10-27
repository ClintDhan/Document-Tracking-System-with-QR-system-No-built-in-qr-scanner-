
<?php
session_start();
require_once '../db.php';

// ----------------------------
// 1. Check login and submission
// ----------------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['submit'])) {
    header("Location: ../user/user-home.php");
    exit();
}

// ----------------------------
// 2. Get submitted values
// ----------------------------
$control = $_POST['control_num'] ?? '';
$qr_id = $_POST['qr_id'] ?? '';
$type = trim($_POST['type'] ?? '');
$description = trim($_POST['description'] ?? '');
$department = trim($_POST['department'] ?? '');
$createdBy = (int) $_SESSION['user_id'];
$pages = (int) ($_POST['pages'] ?? 1);
$status = $_POST['status'] ?? 'Received';
$released_to = trim($_POST['released_to'] ?? '');
$returned_reason = trim($_POST['returned_reason'] ?? '');
$remark = trim($_POST['remark'] ?? '');

if ($remark === '') {
    $remark = 'No remarks';
}

// ----------------------------
// 3. Preserve form values
// ----------------------------
$formData = [
    'type' => $type,
    'description' => $description,
    'department' => $department,
    'status' => $status,
    'pages' => $pages,
    'released_to' => $released_to,
    'returned_reason' => $returned_reason,
    'remark' => $_POST['remark'] ?? ''
];

// Clear values that do not apply to the selected status
if ($status !== 'Released') {
    $released_to = '';
}

if ($status !== 'Returned') {
    $returned_reason = '';
}

// ----------------------------
// 4. Validate required identifiers
// ----------------------------
if ($qr_id === '' || $control === '') {
    $_SESSION['error'] = "Invalid QR access.";

    header("Location: ../user/user-home.php");
    exit();
}

$conn->begin_transaction();

try {
    // ----------------------------
    // 5. Check whether description already exists
    // ----------------------------
    $stmt = $conn->prepare(
        "SELECT id FROM document WHERE description = ?"
    );

    $stmt->bind_param("s", $description);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $conn->rollback();

        $_SESSION['error'] = "Document description already exists.";
        $_SESSION['receive_form'] = $formData;

        header(
            "Location: ../user/user-receive.php?qr="
            . urlencode($qr_id)
            . "&control="
            . urlencode($control)
        );

        exit();
    }

    // ----------------------------
    // 6. Insert document
    // ----------------------------
    $stmt = $conn->prepare("
        INSERT INTO document
        (
            qr_id,
            type,
            description,
            status,
            department,
            created_by,
            pages,
            released_to,
            returned_reason
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "issssiiis",
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

    // ----------------------------
    // 7. Mark QR as used
    // ----------------------------
    $stmt2 = $conn->prepare(
        "UPDATE qr_code SET is_used = 1 WHERE id = ?"
    );

    $stmt2->bind_param("i", $qr_id);
    $stmt2->execute();

    // ----------------------------
    // 8. Insert document log
    // ----------------------------
    $stmt3 = $conn->prepare("
        INSERT INTO document_log
        (
            document_id,
            action,
            performed_at,
            performed_by,
            remarks
        )
        VALUES (?, ?, NOW(), ?, ?)
    ");

    $stmt3->bind_param(
        "isis",
        $document_id,
        $status,
        $createdBy,
        $remark
    );

    $stmt3->execute();

    // ----------------------------
    // 9. Commit transaction
    // ----------------------------
    $conn->commit();

    $_SESSION['success'] = "Document successfully {$status}.";

    header(
        "Location: ../user/user-home.php?control="
        . urlencode($control)
    );

    exit();

} catch (Throwable $e) {
    $conn->rollback();

    error_log("Receive document failed: " . $e->getMessage());

    $_SESSION['error'] = "Something went wrong while saving document.";
    $_SESSION['receive_form'] = $formData;

    header(
        "Location: ../user/user-receive.php?qr="
        . urlencode($qr_id)
        . "&control="
        . urlencode($control)
    );

    exit();
}
?>
