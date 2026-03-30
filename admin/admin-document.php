<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
// $sql ="
//     SELECT 
//         document.id,
//         document.type,
//         document.description,
//         document.status,
//         document.department,
//         document.created_at,
//         document.updated_at,
//         document.released_to,
//         document.returned_reason,
//         user.name AS creator_name
//     FROM document
//     INNER JOIN user 
//         ON document.created_by = user.id
//     ORDER BY document.id DESC , document.created_at DESC
// ";

// $result = $conn->query($sql);
// $documents = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Documents</title>
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body class="admin-body">
    <div class="admin-dash-container">
        <div class="admin-navbar">
            <div class="admin-logo">
                <a href="" class="admin-logo-title">MAYOR'S OFFICE DTS</a>
                <p class="admin-logo-sub">ADMIN</p>
            </div>

            <div class="nav-anchor">
                <a href="admin-dashboard.php" class="">DASHBOARD</a>
                <a href="admin-document.php" class="active">DOCUMENTS</a>
                <a href="admin-logs.php">DOCUMENT LOGS</a>
                <a href="admin-user-log.php">USER LOGS</a>
                <a href="admin-qr.php">QR MANAGEMENT</a>
                <a href="admin-user.php">USERS</a>
            </div>

            <div class="admin-logout">
                <form action="../operation/logout.php" method="POST">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
                </form>
            </div>
        </div>

        <div class="admin-docs-container">
            <div class="search-container">
            <p>Documents</p>
            <input type="text" onkeyup="loadData(this.value)" placeholder="Search for document....">
            </div>
            <div id="result">
                <?php require_once "../operation/admin-document-search.php" ?>
            </div>
        </div>

    </div>
    <script>
        function loadData(query) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../operation/admin-document-search.php" , true);

            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("result").innerHTML = this.responseText;
                }
            }
            xhr.send("query=" + query);
        }
    </script>
    
</body>
</html>