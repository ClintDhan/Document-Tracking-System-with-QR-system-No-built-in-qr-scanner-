<?php
session_start();
require_once '../db.php';

$from = $_POST['from'];
$to = $_POST['to'];

$query = "
SELECT 
    document.id,
    document.type,
    document.description,
    document.department,
    document.pages,
    document.status,
    user.name AS creator_name,
    document.created_at,
    document.updated_at,
    document.released_to,
    document.returned_reason,
    qr_code.control_num
FROM document
INNER JOIN user ON document.created_by = user.id
LEFT JOIN qr_code ON document.qr_id = qr_code.id
WHERE document.created_at BETWEEN '$from 00:00:00' AND '$to 23:59:59'
";

$result = $conn->query($query);

if ($result->num_rows == 0) {
    $_SESSION['error'] = "No records found for the selected date range";
    header("Location: ../admin/admin-doc-export.php");
    exit();
}

# ✅ IF THERE ARE RECORDS → START EXPORT
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="export.csv"');

$output = fopen("php://output", "w");

// header row
fputcsv($output, [
    'ID',
    'Type',
    'Description',
    'Department',
    'Copies',
    'Status',
    'Created By',
    'Created At',
    'Updated At',
    'Released To',
    'Returned Reason',
    'Control Number'
]);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['type'],
        $row['description'],
        $row['department'],
        $row['pages'],
        $row['status'],
        $row['creator_name'],
        $row['created_at'],
        $row['updated_at'],
        $row['released_to'],
        $row['returned_reason'],
        $row['control_num']
    ]);
}


fclose($output);
exit();
?>