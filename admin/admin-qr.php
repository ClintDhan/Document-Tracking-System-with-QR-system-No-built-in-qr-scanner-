<?php
session_start();
require_once "../db.php";

// $sql = "SELECT qr_code.id,
//                 qr_code.control_num,
//                 qr_code.is_used,
//                 user.name AS creator
                
//         FROM qr_code INNER JOIN user on qr_code.created_by = user.id";

// $result = $conn->query($sql);
// $qr = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin QR Management</title>
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
                <a href="admin-logs.php">LOGS</a>
                <a href="admin-qr.php" class="active">QR MANAGEMENT</a>
                <a href="admin-user.php">USERS</a>
            </div>

            <div class="admin-logout">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
            </div>
        </div>

        <div class="admin-qr-container">
            <div class="search-container">
            <p>QR Management</p>

            <div class="d-flex gap-2">
                <form action="../operation/generate_qr_download.php" method="POST" target="_blank">
                    <select name="qty" required style="height: 35px; border-radius: 8px; background-color: #D6ECFF;" onchange="generateQR(this)">
                        <option value="" disabled selected hidden>GENERATE QR</option>
                        <option value="5">5 QR</option>
                        <option value="10">10 QR</option>
                        <option value="20">20 QR</option>
                    </select>
                </form>
                <form action="../operation/regenerate_unused_qr.php" method="POST" target="_blank">
                    <select name="qty" required style="height: 35px; border-radius: 8px; background-color: #DFF7E4;"
                        onchange="this.form.submit()">
                        <option value="" disabled selected hidden>GENERATE UNUSED QR</option>
                        <option value="5">5 QR</option>
                        <option value="10">10 QR</option>
                        <option value="20">20 QR</option>
                    </select>
                 </form>
                <input type="text" onkeyup="loadData(this.value)" placeholder="Search for document....">
            </div>
            </div>            <div id="result">
                <?php require_once "../operation/admin-qr-search.php" ?>
            </div>
        </div>

    </div>
    <script>
        function loadData(query) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../operation/admin-qr-search.php" , true);

            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("result").innerHTML = this.responseText;
                }
            }
            xhr.send("query=" + query);
        }

        function generateQR(select) {
        const form = select.form;
        form.submit();

        setTimeout(() => {
            select.selectedIndex = -1;
            location.reload();
        }, 100);
    }
    </script>
</body>
</html>