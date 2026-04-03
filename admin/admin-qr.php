<?php
session_start();
require_once "../db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
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
                <a href="" class="admin-logo-title">MAYOR'S OFFICE DTS</a>
                <p class="admin-logo-sub">ADMIN</p>
            </div>

            <div class="nav-anchor">
                <a href="admin-dashboard.php">DASHBOARD</a>
                <a href="admin-document.php">DOCUMENTS</a>

                <div class="dropdown">
                    <p class="logs-text">LOGS ▾</p>
                    <div class="dropdown-content">
                        <a href="admin-auth-log.php">AUTHENTICATION LOGS</a>
                        <a href="admin-logs.php">DOCUMENT LOGS</a>
                        <a href="admin-user-log.php">USER LOGS</a>
                    </div>
                </div>

                <a href="admin-qr.php"  class="active">QR MANAGEMENT</a>
                <a href="admin-user.php">USERS</a>
            </div>

            <div class="admin-logout">
<form action="../operation/logout.php" method="POST">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
                </form>           
             </div>
             <div class="burger-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                    </svg>
            </div>
        </div>

        <div class="admin-qr-container">
            <div class="search-container">
                <p>QR Management</p>

                <div class="d-flex">
                    <input type="text" onkeyup="loadData(this.value)" placeholder="Search for control....">
                </div>
            </div>

            <div class="admin-doc-sub-contain">
                <div>

                </div>
                <div class="sub-contain-btn-container">
                    <form action="../operation/generate_qr_download.php" method="POST" target="_blank">
                        <select name="qty" class="generate-select" required  onchange="generateQR(this)">
                            <option value="" disabled selected hidden>GENERATE QR</option>
                            <option value="5">5 QR</option>
                            <option value="10">10 QR</option>
                            <option value="20">20 QR</option>
                        </select>
                    </form>
                    <form action="../operation/regenerate_unused_qr.php" method="POST" target="_blank">
                        <select name="qty" class="unused-select" required
                            onchange="this.form.submit()">
                            <option value="" disabled selected hidden>DOWNLOAD UNUSED QR</option>
                            <option value="5">5 QR</option>
                            <option value="10">10 QR</option>
                            <option value="20">20 QR</option>
                        </select>
                    </form>
                </div>

            </div>

            <div id="result">
                <?php require_once "../operation/admin-qr-search.php" ?>
            </div>
        </div>

    </div>
    <script>

    document.addEventListener("click", function (e) {
    const dropdown = document.querySelector(".dropdown");

    if (dropdown.contains(e.target)) {
        dropdown.querySelector(".dropdown-content").classList.toggle("show");
    } else {
        dropdown.querySelector(".dropdown-content").classList.remove("show");
    }
    });
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