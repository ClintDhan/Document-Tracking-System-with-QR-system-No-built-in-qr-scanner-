<?php 
require_once "../db.php";


$search = "";

// if user typed something
if(isset($_POST['query'])) {
$search = $conn->real_escape_string($_POST['query']);

}

$sql = $sql = "SELECT auth_logs.id, auth_logs.performed,
        auth_logs.performed_at,
        user.name AS creator_name
        FROM auth_logs
        INNER JOIN user ON auth_logs.user_id = user.id";

// if search is not empty, filter results
if ($search != "") {
    $sql .= " WHERE auth_logs.id LIKE '%$search%' OR auth_logs.performed LIKE '%$search%' OR auth_logs.performed_at LIKE '%$search%' OR 
    user.name LIKE '%$search%'";
}

$sql .= " ORDER BY auth_logs.id DESC , auth_logs.performed_at DESC";

$result = $conn->query($sql);
echo "<table class='admin-auth-table'>
        <thead>
            <tr>
                <th>No</th>
                <th>Performed</th>
                <th>Performed At</th>
                <th>Performed By</th>
            </tr>
         </thead>
         <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "
        <tr class='tr-hover'>
            <td>".$row['id']."</td>
            <td>".$row['performed']."</td>
            <td>".$row['performed_at']."</td>
            <td>".$row['creator_name']."</td>

        </tr>";
}

echo "</tbody>
    </table>";
?>