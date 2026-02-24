<?php 
require_once "../db.php";


$search = "";

// if user typed something
if(isset($_POST['query'])) {
$search = $conn->real_escape_string($_POST['query']);

}

$sql = "SELECT document.id, document.type,
                document.description, document.status,
                document.department, document.created_at,
                document.updated_at, document.released_to,
                document.returned_reason, user.name AS creator_name
                FROM document INNER JOIN user ON document.created_by = user.id
    ";

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
                <th>No</th>
                <th>Type</th>
                <th>Description</th>
                <th>Status</th>
                <th>Department</th>
                <th>Created by</th>
                <th>Created at</th>
                <th>Last Updated</th>
                <th>Released To</th>
                <th>Returned Reason</th>
            </tr>
         </thead>
         <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "
        <tr>
            <td>".$row['id']."</td>
            <td>".$row['type']."</td>
            <td>".$row['description']."</td>
            <td>".$row['status']."</td>
            <td>".$row['department']."</td>
            <td>".$row['creator_name']."</td>
            <td>".$row['created_at']."</td>
            <td>".$row['updated_at']."</td>
            <td>".$row['released_to']."</td>
            <td>".$row['returned_reason']."</td>

        </tr>";
}

echo "</tbody>
    </table>";
?>