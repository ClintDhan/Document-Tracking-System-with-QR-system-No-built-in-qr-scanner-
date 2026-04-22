<?php 
require_once "../db.php";


$search = "";

// if user typed something
if(isset($_POST['query'])) {
$search = $conn->real_escape_string($_POST['query']);

}

$sql = "SELECT document_log.id , document_log.action , document_log.remarks , document_log.performed_at, document_log.changes, document.type AS document_type, document.description AS document_description, user.name AS performer
        FROM document_log INNER JOIN user ON document_log.performed_by = user.id INNER JOIN document ON document_log.document_id = document.id";

// if search is not empty, filter results
if ($search != "") {
    $sql .= " WHERE document_log.id LIKE '%$search%' OR document_log.action LIKE '%$search%' OR document_log.performed_at LIKE '%$search%' OR 
                document.type LIKE '%$search%' OR user.name LIKE '%$search%' OR document.description LIKE '%$search%'";
}

$sql .= " ORDER BY document_log.performed_at DESC";

$result = $conn->query($sql);
echo "<table class='admin-logs-table'>
        <thead>
            <tr>
                <th class='logs-no'>No</th>
                <th class='logs-type'>Type</th>
                <th class='logs-description'>Description</th>
                <th class='logs-act'>Performed</th>
                <th>Remarks</th>
                <th class='logs-change'>Changes</th>
                <th class='logs-date'>Performed At</th>
                <th class='logs-by'>Performed By</th>
            </tr>
         </thead>
         <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "
        <tr class='tr-hover'>
            <td>".$row['id']."</td>
            <td>".$row['document_type']."</td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['document_description']."</td>            <td>
            <div class='document-action-container'>
                <div style='border-radius: 4px;' class='".
                    ($row['action'] == 'Returned' ? 'status-returned' :
                    ($row['action'] == 'Approved' ? 'status-review' :
                    ($row['action'] == 'Released' ? 'status-released' :
                    'status-default')))
                ."'>".$row['action']."</div>
            </div>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['remarks']."</td>
            </td>
            <td class='truncate' style='text-align: justify;' onclick=\"this.classList.toggle('expanded')\">
                ".$row['changes']."
            </td>
            <td>".$row['performed_at']."</td>
            <td>".$row['performer']."</td>
        </tr>";
}

echo "</tbody>
    </table>";
?>