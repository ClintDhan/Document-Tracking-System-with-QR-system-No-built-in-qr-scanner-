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
            </tr>
         </thead>
         <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "
        <tr>
            <td>".$row['id']."</td>
            <td>".$row['control_num']."</td>
            <td>".(($row['is_used'] == 0) ? "Not used" : "Used")."</td>
            <td>".$row['creator']."</td>
        </tr>";
}

echo "</tbody>
    </table>";
?>