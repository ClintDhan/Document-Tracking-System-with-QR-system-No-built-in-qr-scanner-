<?php

require_once "../db.php";

$search = "";

// If user typed something
if (isset($_POST['query'])) {
    $search = $conn->real_escape_string($_POST['query']);
}

$sql = "SELECT 
            d.id,
            d.type,
            d.description,
            d.status,
            d.department,
            d.created_at,
            d.updated_at,
            d.released_to,
            d.returned_reason,
            d.pages,
            u.name AS creator_name,
            q.id AS qr_id,
            q.control_num AS control_num

        FROM document AS d

        INNER JOIN user AS u
            ON d.created_by = u.id

        LEFT JOIN qr_code AS q
            ON d.qr_id = q.id";


// If search is not empty, filter results
if ($search != "") {

    $sql .= " WHERE 
                d.type LIKE '%$search%'
                OR d.description LIKE '%$search%'
                OR d.status LIKE '%$search%'
                OR d.department LIKE '%$search%'
                OR d.created_at LIKE '%$search%'
                OR d.updated_at LIKE '%$search%'
                OR d.released_to LIKE '%$search%'
                OR d.returned_reason LIKE '%$search%'
                OR d.pages LIKE '%$search%'
                OR u.name LIKE '%$search%'
                OR q.control_num LIKE '%$search%'";
}


// Sort newest documents first
$sql .= " ORDER BY d.id DESC, d.created_at DESC";


$result = $conn->query($sql);


// Check if query failed
if (!$result) {
    die("Query failed: " . $conn->error);
}


echo "
<table class='user-docs-table table table-striped'>

    <thead>
        <tr>

            <th class='admin-docs-no' style='width:50px;'>
                No
            </th>

            <th class='admin-docs-type'>
                Type
            </th>

            <th class='admin-docs-desc' style='width:150px;'>
                Description
            </th>

            <th class='admin-docs-sts'>
                Status
            </th>

            <th class='admin-docs-dep'>
                Department
            </th>

            <th class='admin-docs-no' style='width:auto;'>
                Copies
            </th>

            <th class='admin-docs-created'>
                Created by
            </th>

            <th class='admin-docs-created-at'>
                Created at
            </th>

            <th class='admin-docs-created-at'>
                Control
            </th>

            <th class='admin-docs-action' style='width:85px;'>
                Action
            </th>

        </tr>
    </thead>

    <tbody>
";


while ($row = $result->fetch_assoc()) {

    // Determine status CSS class
    if ($row['status'] == 'Returned') {

        $statusClass = 'status-returned';

    } elseif ($row['status'] == 'Approved') {

        $statusClass = 'status-review';

    } elseif ($row['status'] == 'Released') {

        $statusClass = 'status-released';

    } elseif ($row['status'] == 'For MJCA Approval') {

        $statusClass = 'status-mayor';

    } else {

        $statusClass = 'status-default';
    }


    // Encode control number for URL
    $control = urlencode($row['control_num']);


    echo "
        <tr class='tr-hover'>

            <td class='truncate'
                onclick=\"this.classList.toggle('expanded')\">
                " . htmlspecialchars($row['id']) . "
            </td>


            <td class='truncate'
                onclick=\"this.classList.toggle('expanded')\">
                " . htmlspecialchars($row['type']) . "
            </td>


            <td class='truncate admin-docs-type'
                onclick=\"this.classList.toggle('expanded')\">
                " . htmlspecialchars($row['description']) . "
            </td>


            <td class='truncate'
                onclick=\"this.classList.toggle('expanded')\">

                <div class='document-status-container'>

                    <div
                        style='border-radius: 4px;'
                        class='" . $statusClass . "'>
                        " . htmlspecialchars($row['status']) . "
                    </div>

                </div>

            </td>


            <td class='truncate'
                onclick=\"this.classList.toggle('expanded')\">
                " . htmlspecialchars($row['department']) . "
            </td>


            <td class='truncate'
                onclick=\"this.classList.toggle('expanded')\">
                " . htmlspecialchars($row['pages']) . "
            </td>


            <td class='truncate'
                onclick=\"this.classList.toggle('expanded')\">
                " . htmlspecialchars($row['creator_name']) . "
            </td>


            <td class='truncate'
                onclick=\"this.classList.toggle('expanded')\">
                " . htmlspecialchars($row['created_at']) . "
            </td>


            <td class='truncate'
                onclick=\"this.classList.toggle('expanded')\">
                " . htmlspecialchars($row['control_num']) . "
            </td>


            <td>

                <a
                    class='admin-doc-btn'
                    href='../user/user-view.php?qr=" . urlencode($row['qr_id']) . "&control=" . $control . "&document=" . urlencode($row['id']) . "'
                    style='background-color: gray;'
                >

                    <svg
                        xmlns='http://www.w3.org/2000/svg'
                        width='16'
                        height='16'
                        fill='currentColor'
                        class='bi bi-search'
                        viewBox='0 0 16 16'
                    >

                        <path
                            d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 1 1.415-1.414l-3.85-3.85a1 1 0 0 1-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0'
                        />

                    </svg>

                </a>

            </td>

        </tr>
    ";
}


echo "
    </tbody>

</table>
";

?>