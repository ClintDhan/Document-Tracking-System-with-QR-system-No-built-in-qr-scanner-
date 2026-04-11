<?php 
require_once "../db.php";


$search = "";

// if user typed something
if(isset($_POST['query'])) {
$search = $conn->real_escape_string($_POST['query']);

}

$sql = "SELECT qr_code.id,
                qr_code.control_num,
                qr_code.is_used,
                user.name AS creator
                
        FROM qr_code INNER JOIN user on qr_code.created_by = user.id";

// if search is not empty, filter results
if ($search != "") {
    $sql .= " WHERE 
    qr_code.id LIKE '%$search%' 
    OR qr_code.control_num LIKE '%$search%' 
    OR user.name LIKE '%$search%'
    OR (CASE 
            WHEN qr_code.is_used = 1 THEN 'Used'
            ELSE 'Not used'
        END) LIKE '%$search%'";
}

$sql .= " ORDER BY qr_code.id DESC";

$result = $conn->query($sql);
echo "<table class='admin-qr-table'>
        <thead>
            <tr>
                <th>No</th>
                <th>Control Number</th>
                <th>Used/Not Used</th>
                <th>Created By</th>
                <th>Action</th>
            </tr>
         </thead>
         <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "
        <tr class='tr-hover'>
            <td>".$row['id']."</td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['control_num']."</td>
            <td>".(($row['is_used'] == 0) ? "Not used" : "Used")."</td>
            <td>".$row['creator']."</td>
            <td><a class='admin-doc-btn'
                    href='../operation/document-download-qr.php?control=".$row['control_num']."'
                    target='_blank' style='background: green;'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-download' viewBox='0 0 16 16'>
                        <path d='M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5'/>
                        <path d='M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z'/>
                    </svg>
                </a>
            </td>
        </tr>";
}

echo "</tbody>
    </table>";
?>