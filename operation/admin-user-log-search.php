<?php 
require_once "../db.php";


$search = "";

// if user typed something
if(isset($_POST['query'])) {
$search = $conn->real_escape_string($_POST['query']);

}

$sql = "SELECT user_log.id, user_log.action,
        user_log.note, user_log.date_performed,
        u1.name AS creator_name,
        u2.name AS target_name
        FROM user_log
        INNER JOIN user u1 ON user_log.performed_by = u1.id
        INNER JOIN user u2 ON user_log.user_id = u2.id";

// if search is not empty, filter results
if ($search != "") {
    $sql .= " WHERE user_log.action LIKE '%$search%' OR user_log.note LIKE '%$search%' OR user_log.date_performed LIKE '%$search%' OR 
                u1.name LIKE '%$search%' OR u2.name LIKE '%$search%'";
}

$sql .= " ORDER BY user_log.id DESC , user_log.date_performed DESC";

$result = $conn->query($sql);
echo "<table class='user-log-table table table-striped'>
        <thead>
            <tr>
                <th class='user-log-no'>No</th>
                <th class='user-log-by'>Performed By</th>
                <th class='user-log-date'>Performed At</th>
                <th class='user-log-action'>Action</th>
                <th class='user-log-note'>Note</th>
                <th class='user-log-name'>Username</th>
            </tr>
         </thead>
         <tbody>";

while ($row = $result->fetch_assoc()) {
    echo "
        <tr class='tr-hover'>
            <td>".$row['id']."</td>
            <td>".$row['creator_name']."</td>
            <td>".$row['date_performed']."</td>
            <td>
            <div class='user-action-container'>
                <div style='border-radius: 4px;' class='".
                    ($row['action'] == 'Changed user information' ? 'status-returned' :
                    ($row['action'] == 'Reset password' ? 'status-review' :
                    ($row['action'] == 'Created a new user' ? 'status-released' :
                    'status-default')))
                ."'>".$row['action']."</div>
            </div>
            
            </td>
            <td class='truncate' onclick=\"this.classList.toggle('expanded')\">".$row['note']."</td>
            <td>".$row['target_name']."</td>
        </tr>";
}

echo "</tbody>
    </table>";
?>