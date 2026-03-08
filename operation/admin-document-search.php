<?php 
require_once "../db.php";


$search = "";

// if user typed something
if(isset($_POST['query'])) {
$search = $conn->real_escape_string($_POST['query']);

}

$sql = $sql = "SELECT document.id, document.type,
        document.description, document.status,
        document.department, document.created_at,
        document.updated_at, document.released_to,
        document.returned_reason,
        user.name AS creator_name,
        qr_code.control_num
        FROM document
        INNER JOIN user ON document.created_by = user.id
        LEFT JOIN qr_code ON document.qr_id = qr_code.id";

// if search is not empty, filter results
if ($search != "") {
    $sql .= " WHERE document.type LIKE '%$search%' OR document.description LIKE '%$search%' OR document.status LIKE '%$search%' OR 
                document.department LIKE '%$search%' OR document.created_at LIKE '%$search%' OR document.updated_at LIKE '%$search%'
                OR document.released_to LIKE '%$search%' OR document.returned_reason LIKE '%$search%' OR user.name LIKE '%$search%'";
}

$sql .= " ORDER BY document.id DESC , document.created_at DESC";

$result = $conn->query($sql);
echo "<table class='admin-docs-table'>
        <thead>
            <tr>
                <th class='admin-docs-no'>No</th>
                <th class='admin-docs-type'>Type</th>
                <th class='admin-docs-desc'>Description</th>
                <th class='admin-docs-sts'>Status</th>
                <th class='admin-docs-dep'>Department</th>
                <th class='admin-docs-created'>Created by</th>
                <th class='admin-docs-created-at'>Created at</th>
                <th class='admin-docs-updt'>Last Updated</th>
                <th class='admin-docs-released'>Released To</th>
                <th class='admin-docs-returned'>Returned Reason</th>
                <th class='admin-docs-action'>Action</th>
            </tr>
         </thead>
         <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "
        <tr class='tr-hover'>
            <td>".$row['id']."</td>
            <td>".$row['type']."</td>
            <td>".$row['description']."</td>
            <td><div style='border-radius: 4px;' class='".
                ($row['status'] == 'Returned' ? 'status-returned' :
                ($row['status'] == 'Under Review' ? 'status-review' :
                ($row['status'] == 'Released' ? 'status-released' :
                'status-default')))
            ."'>".$row['status']."</div></td>
            <td>".$row['department']."</td>
            <td>".$row['creator_name']."</td>
            <td>".$row['created_at']."</td>
            <td>".$row['updated_at']."</td>
            <td>".$row['released_to']."</td>
            <td>".$row['returned_reason']."</td>
            <td>
            <a class='admin-doc-btn' href='../admin/admin-doc-edit.php?doc=".$row['id']."'>EDIT</a>

            <a class='admin-doc-btn'
            href='../operation/document-download-qr.php?control=".$row['control_num']."'
            target='_blank' style='background: green;'>
            DOWNLOAD QR
            </a>
            </td>


        </tr>";
}

echo "</tbody>
    </table>";
?>