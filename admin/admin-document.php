<?php
session_start();
require_once '../db.php';

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
                <a href="">MAYOR'S OFFICE DTS</a>
            </div>

            <div class="nav-anchor">
                <a href="admin-dashboard.php" class="">DASHBOARD</a>
                <a href="admin-document.php" class="active">DOCUMENTS</a>
                <a href="admin-logs.php">LOGS</a>
                <a href="admin-qr.php">QR MANAGEMENT</a>
                <a href="admin-user.php">USERS</a>
            </div>

            <div class="admin-logout">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
            </div>
        </div>

        <div class="admin-docs-container">
            <input type="text" onkeyup="loadData(this.value)" placeholder="Search users...">
            <div id="result">
                <?php require_once "../operation/admin-document-search.php" ?>
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