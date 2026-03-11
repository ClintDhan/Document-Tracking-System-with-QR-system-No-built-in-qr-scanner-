<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logs</title>
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
                <a href="admin-document.php">DOCUMENTS</a>
                <a href="admin-logs.php" class="active">DOCUMENT LOGS</a>
                <a href="admin-logs.php">USER LOGS</a>
                <a href="admin-qr.php">QR MANAGEMENT</a>
                <a href="admin-user.php">USERS</a>
            </div>

            <div class="admin-logout">
                <form action="../operation/logout.php" method="POST">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
                </form>
            </div>
        </div>

         <div class="admin-logs-container">
            <div class="search-container">
            <p>Document Logs</p>
            <input type="text" onkeyup="loadData(this.value)" placeholder="Search for document....">
            </div>
                        <div id="result">
                <?php require_once "../operation/admin-log-search.php" ?>
            </div>
        </div>

    </div>

     <script>
        function loadData(query) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../operation/admin-log-search.php" , true);

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