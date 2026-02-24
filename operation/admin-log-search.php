<?php 
require_once "../db.php";


$search = "";

// if user typed something
if(isset($_POST['query'])) {
$search = $conn->real_escape_string($_POST['query']);

}

$sql = "SELECT document_log.id , document_log.action , document_log.performed_at, document.type AS document_type, user.name AS performer
        FROM document_log INNER JOIN user ON document_log.performed_by = user.id INNER JOIN document ON document_log.document_id = document.id";

// if search is not empty, filter results
if ($search != "") {
    $sql .= " WHERE document_log.id LIKE '%$search%' OR document_log.action LIKE '%$search%' OR document_log.performed_at LIKE '%$search%' OR 
                document.type LIKE '%$search%' OR user.name LIKE '%$search%'";
}

$sql .= " ORDER BY document_log.performed_at DESC";

$result = $conn->query($sql);
echo "<table class='admin-logs-table'>
        <thead>
            <tr>
                <th>No</th>
                <th>Type</th>
                <th>Acion</th>
                <th>Performed At</th>
                <th>Performed By</th>
            </tr>
         </thead>
         <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "
        <tr>
            <td>".$row['id']."</td>
            <td>".$row['document_type']."</td>
            <td>".$row['action']."</td>
            <td>".$row['performed_at']."</td>
            <td>".$row['performer']."</td>
        </tr>";
}

echo "</tbody>
    </table>";
?>