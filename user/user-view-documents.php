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
<body class="user-body">


    <div class="user-home-header-container">
            <header class="user-home-header">
                <div class="header-inner">
                    <a class="brand" href="">DTS</a>
                    <div class="account">
                        <div class="user"><strong><?= $_SESSION['name']; ?></strong> <br><span class="role">Records Officer</span></div>

                        <form class="user-header-form" action="../operation/logout.php" method='POST'>
                            <button
                                class="logout">Logout</button>
                        </form>
                    </div>
                </div>
            </header>
    </div>

    <div class="main">
        <div class='user-main-title-container'>
            <h2>All Documents</h2>
        </div>

        <div class="panel user-view-document-max">

            <div class="user-view-document-table-container">
                <div class="document-table-title-container search-container">
                    <p></p>
                    <input type="text" onkeyup="loadData(this.value)" placeholder="Search for document....">
                </div>
                
                 <div id="result" class="result-scroll result-user-view-scroll user-view-document-container">
                    <?php require_once "../operation/user-view-document-search.php" ?>
                </div>

            </div>


            <div class="actions">
          <button onclick="history.back()" class="user-qr-scan-btn btn-user-bck btn-outline">Back</button>  
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