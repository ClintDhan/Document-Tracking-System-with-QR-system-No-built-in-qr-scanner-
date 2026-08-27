<?php 
session_start();
require_once '../db.php';

// ----------------------------
// 1️⃣ Check login
// ----------------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// ----------------------------
// 2️⃣ QR scan handling
// ----------------------------
$qrControl = $_GET['control'] ?? null;
$qr = null;
$qrError = null;

if ($qrControl) {
    $sql = "SELECT * FROM qr_code WHERE control_num = '$qrControl'";
    $result = $conn->query($sql);
    $qr = $result->fetch_assoc();

    if ($qr) {
    $qr_id = $qr['id']; // <-- primary key of qr_code
    } else {
    $qrError = "Invalid QR code.";
    }

}

$document = null;

if (isset($qr_id)) {
    $sql2 = "SELECT * FROM document WHERE qr_id = '$qr_id'";
    $result2 = $conn->query($sql2);
    $document = $result2->fetch_assoc();
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Documents</title>
    <link rel="icon" type="image/png" href="../asset/img/log.png">
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body>

<div class='user-container'>
        <div class='user-form'>

            <!-- NAV BAR -->
            <div class='user-nav-bar'>
                <div class='user-name'>
                    <p>Hi, <span class="span-name"><?= $_SESSION['name']; ?>!</p>
                    <p style="color: gray;"><?= date('m/d/Y') ?></p>
                </div>
                <form action="../operation/logout.php" method='POST'>
                    <button class='log-out'><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                        <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                        </svg>
                    </button>
                </form>
            </div>  

            <button class="btn-home" onclick="history.back()">
            ❮ BACK
            </button>

            <div class="document-table-container">
                <div class="document-table-title-container search-container">
                    <p>Documents</p>
                    <input type="text" onkeyup="loadData(this.value)" placeholder="Search for document....">
                </div>
                
                 <div id="result" class="result-scroll user-view-document-container">
                    <?php require_once "../operation/user-view-document-search.php" ?>
                </div>

            </div>

           

        </div>
    </div>

    <script>
         function loadData(query) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "../operation/user-view-document-search.php" , true);

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