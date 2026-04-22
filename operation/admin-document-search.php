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
        document.returned_reason, document.pages,
        user.name AS creator_name,
        qr_code.control_num AS control_num
        FROM document
        INNER JOIN user ON document.created_by = user.id
        LEFT JOIN qr_code ON document.qr_id = qr_code.id";

// if search is not empty, filter results
if ($search != "") {
    $sql .= " WHERE document.type LIKE '%$search%' OR document.description LIKE '%$search%' OR document.status LIKE '%$search%' OR 
                document.department LIKE '%$search%' OR document.created_at LIKE '%$search%' OR document.updated_at LIKE '%$search%'
                OR document.released_to LIKE '%$search%' OR document.returned_reason LIKE '%$search%' OR user.name LIKE '%$search%' OR control_num LIKE '%$search%'";
}

$sql .= " ORDER BY document.id DESC , document.created_at DESC";

$result = $conn->query($sql);
echo "<table class='admin-docs-table'>
        <thead>
            <tr>
                <th class='admin-docs-no' style='width:50px;'>No</th>
                <th class='admin-docs-type'>Type</th>
                <th class='admin-docs-desc' style='width:150px;'>Description</th>
                <th class='admin-docs-sts'>Status</th>
                <th class='admin-docs-dep'>Department</th>
                <th class='admin-docs-no' style='width:50px;'>Copies</th>
                <th class='admin-docs-created'>Created by</th>
                <th class='admin-docs-created-at'>Created at</th>
                <th class='admin-docs-created-at'>Control</th>
                <th class='admin-docs-action' style='width:80px;'>Action</th>
            </tr>
         </thead>
         <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "
        <tr class='tr-hover'>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['id']."</td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['type']."</td>
            <td class='truncate admin-docs-type' onclick=\"this.classList.toggle('expanded')\">".$row['description']."</td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">
                <div class='document-status-container'>
                    <div style='border-radius: 4px;' class='".
                        ($row['status'] == 'Returned' ? 'status-returned' :
                        ($row['status'] == 'Approved' ? 'status-review' :
                        ($row['status'] == 'Released' ? 'status-released' :
                        'status-default')))
                    ."'>".$row['status']."</div>
                </div>
            </td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['department']."</td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['pages']."</td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['creator_name']."</td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['created_at']."</td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['control_num']."</td>
            <td>
            <a class='admin-doc-btn' href='../admin/admin-doc-edit.php?doc=".$row['id']."'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil-square' viewBox='0 0 16 16'>
                <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
                <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z'/>
            </svg>
            </a>
            <a class='admin-doc-btn' href='../admin/admin-view-document.php?doc=".$row['id']."' style='background-color: gray;'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-search' viewBox='0 0 16 16'>
                <path d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0'/>
            </svg>
            </a>

            </td>


        </tr>";
}

echo "</tbody>
    </table>";
?>