<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}


if (isset($_GET['doc'])) {
    $value = $_GET['doc'];
    $sql = "SELECT d.*, dl.remarks
            FROM document d
            LEFT JOIN document_log dl 
                ON d.id = dl.document_id
            WHERE d.id = '$value'
            ORDER BY dl.performed_at DESC
            LIMIT 1";
    $result = $conn->query($sql);
    $row1 = $result->fetch_assoc();

    if ($row1) {
        $qr_id = $row1['qr_id'];

        $qrQuery = "SELECT * FROM qr_code WHERE id = '$qr_id'";
        $result1 = $conn->query($qrQuery);
        $qr = $result1->fetch_assoc();

        $control_num = $qr ? $qr['control_num'] : '';
    }
}
elseif (isset($_GET['qr_id'])) {
    $value = $_GET['qr_id'];
    $sql = "SELECT d.*, dl.remarks
            FROM document d
            LEFT JOIN document_log dl 
                ON d.id = dl.document_id
            WHERE d.id = '$value'
            ORDER BY dl.performed_at DESC
            LIMIT 1";
    $result = $conn->query($sql);
    $row1 = $result->fetch_assoc();

    if ($row1) {
        $qr_id = $row1['qr_id'];

        $qrQuery = "SELECT * FROM qr_code WHERE id = '$qr_id'";
        $result1 = $conn->query($qrQuery);
        $qr = $result1->fetch_assoc();

        $control_num = $qr ? $qr['control_num'] : '';
    }
}
elseif (isset($_GET['control'])) {

    $control = $_GET['control'] ?? null;

    // QR QUERY (para sa $row1)
    $sql = "SELECT * FROM qr_code WHERE control_num = '$control'";
    $qrSqlResult = $conn->query($sql);
    $row1 = $qrSqlResult->fetch_assoc();

    if ($row1) {
        $control_num = $row1['control_num'];
    }

    // JOIN QUERY (para sa $row)
    $sql = "
        SELECT qc.*, d.*, dl.remarks
        FROM qr_code qc
        LEFT JOIN document d ON qc.id = d.qr_id
        LEFT JOIN document_log dl ON d.id = dl.document_id
        WHERE qc.control_num = '$control'
        ORDER BY dl.performed_at DESC
        LIMIT 1
    ";

} else {
    die("No document specified.");
}


$result = $conn->query($sql);
$row = $result->fetch_assoc();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Document</title>
    <link rel="icon" type="image/png" href="../asset/img/log.png">
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body class="admin-body">
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
        <div class="admin-dash-container">
                <div class="admin-navbar">
                    <div class="admin-logo">
                        <a href="" class="admin-logo-title">MAYOR'S OFFICE DTS</a>
                        <p class="admin-logo-sub">ADMIN</p>
                    </div>

                    <div class="nav-anchor">
                        <a href="admin-dashboard.php">DASHBOARD</a>
                        <a href="admin-document.php" class="active">DOCUMENTS</a>

                        <div class="dropdown">
                            <p class="logs-text">LOGS ▾</p>
                            <div class="dropdown-content">
                                <a href="admin-auth-log.php">AUTHENTICATION LOGS</a>
                                <a href="admin-logs.php">DOCUMENT LOGS</a>
                                <a href="admin-user-log.php">USER LOGS</a>
                            </div>
                        </div>

                        <a href="admin-qr.php">QR MANAGEMENT</a>
                        <a href="admin-user.php">USERS</a>
                    </div>

                    <div class="admin-logout">
                         <form action="../operation/logout.php" method="POST">
                            <button class='log-out admin-logout'> Log out</button>
                        </form>
                    </div>
                    <div class="burger-icon" id="onclickModalBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/>
                        </svg>
                    </div>
                
            </div>
            <div class="doc-edit-container mt-2">
                <p class="text-center" style="font-weight: 700;">View document</p>
                <form action="../operation/sub-admin-update.php" method="POST">
                    <div class="doc-edit-flx">
                        <input type="hidden" name="doc_id" value="<?= $doc ?>">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <div>
                        <label for="" style="font-weight: 600;">Type</label> <br>
                        <input type="text" name='type' value="<?= htmlspecialchars($row['type']) ?>" class="admin-doc-input" readonly>
                    </div>

                    <div class="mt-2">
                        <label for="" style="font-weight: 600;">Description</label> <br>
                        <textarea readonly name='description' rows="4" id="" class="admin-doc-area"><?= $row['description'] ?></textarea>            
                    </div>

                    <div class="mt-2">
                        <label for="" style="font-weight: 600;">Department</label> <br>
                        <input type="text" name='department' value="<?= htmlspecialchars($row['department']) ?>" class="admin-doc-input" readonly>
                    </div>

                    <div class="mt-2">
                        <label for="" style="font-weight: 600;">Number of Copies</label> <br>
                        <input type="number" name='pages' value="<?= htmlspecialchars($row['pages']) ?>" class="admin-doc-input" readonly>
                    </div>
                
                    <div class="mt-2">
                        <label for="" style="font-weight: 600;">Status</label> <br>
                        <input 
                        style="<?= ($row['status'] ?? '') == 'Returned' ? 'background-color: #f8d7da; color: #555;' :
                                    (($row['status'] ?? '') == 'Released' ? 'background-color: #e6ccff; color: #555;' :
                                    (($row['status'] ?? '') == 'Received' ? 'background-color: #d4edda; color: #555;' :
                                    (($row['status'] ?? '') == 'Approved' ? 'background-color: #fff3cd; color: #555;' : '' )))?>"                   
                                                
                         
                        type="text" id="statusSelect" name="status" value="<?= htmlspecialchars($row['status']) ?>" class="admin-doc-input" readonly>
                    </div>

                    <div class="mt-2">
                        <label for="" id="releasedInputLabel" style="font-weight: 600;">Released To</label>
                        <input type="text"
                        id="releasedTo"
                        class="admin-doc-input"
                        name="released_to"
                        placeholder="Released To"
                        value="<?= htmlspecialchars($row['released_to'] ?? '') ?>"
                        style="<?= ($row['status'] ?? '') == 'Released' ? 'display:block;' : 'display:none;' ?> padding: none;" readonly>
                    </div>

                    <div class="mt-2">
                        <label for="" id="returnedInputLabel" style="font-weight: 600;">Return Reason</label>
                            <textarea id="returnReason" readonly name="returned_reason" name='description' rows="4" id="" class="admin-doc-area"
                            value="<?= htmlspecialchars($row['returned_reason'] ?? '') ?>"
                            style="<?= ($row['status'] ?? '') == 'Returned' ? 'display:block;' : 'display:none;' ?> padding: none;"
                            ><?= $row['returned_reason'] ?></textarea>            

                    </div>

                    <div class="mt-2">
                        <label for="" style="font-weight: 600;">Last Updated</label> <br>
                        <input type="text" name="updated_at" value="<?= htmlspecialchars($row['updated_at']) ?>" class="admin-doc-input" readonly>
                    </div>

                    <div class="mt-2">
                        <label for="" style="font-weight: 600;">Control Number</label> <br>
                        <input type="text" id="" name="control_num" value="<?= htmlspecialchars($control_num) ?>" class="admin-doc-input" readonly>
                    </div>

                    <?php if(!empty($row['remarks'])): ?>
                    <div class="mt-2">
                        <label for="" style="font-weight: 600;">Latest Remark</label> <br>
                        <input type="text" id="" value="<?= htmlspecialchars($row['remarks']) ?>" class="admin-doc-input" readonly>
                    </div>
                    <?php endif; ?>

                    <?php if (
                            ($_SESSION['role'] == "admin") &&
                            ($row['status'] == "Received" || $row['status'] == "Returned")
                        ): ?>

                    <div class="mt-2">
                        <label for="" style="font-weight: 600;">Remarks</label> <br>
                        <input type="text" id="" placeholder="(Optional)" name="remark" class="admin-doc-input">
                    </div>

                    <button class='btn-submit admin-doc-submit admin-btn-sub' type='submit' name='approved'>APPROVE</button>
                    <button class='btn-submit admin-doc-submit admin-btn-sub' type='submit' name='mayor' style="margin-top: 5px; background-color: green;">FOR MAYOR'S APPROVAL</button>
                        <?php endif; ?>
                    </div>
                </form>
                
                <button class="adm-bck-btn" onclick="window.location.href='admin-document.php'">
                    ❮ BACK
                </button>
            </div>
        </div>

<div class="onclick-container" id="modal">
        <div class="modal-close-container">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="bi bi-x-lg modal-close" viewBox="0 0 16 16">
                <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/>
            </svg>
        </div>
        <div class="burger-modal">
            <div class="burger-modal-header">
                <p class="admin-logo-title">MAYOR'S OFFICE DTS</p>
            </div>
            <div class="burger-modal-anchor">
                <a href="admin-dashboard.php" class="side-anchor side-anchor-first burger-anchor-active">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.39.39 0 0 0-.029-.518z"/>
                        <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A8 8 0 0 1 0 10m8-7a7 7 0 0 0-6.603 9.329c.203.575.923.876 1.68.63C4.397 12.533 6.358 12 8 12s3.604.532 4.923.96c.757.245 1.477-.056 1.68-.631A7 7 0 0 0 8 3"/>
                    </svg>
                    Dashboard
                </a>
                <a href="admin-document.php" class="side-anchor">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-paperclip" viewBox="0 0 16 16">
                        <path d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z"/>
                    </svg>
                Documents
                </a>
                <!-- <p class="burger-modal-logs">Logs</p> -->
                <hr>
                <a href="admin-auth-log.php" class="side-anchor side-anchor-auth">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-open" viewBox="0 0 16 16">
                        <path d="M8.5 10c-.276 0-.5-.448-.5-1s.224-1 .5-1 .5.448.5 1-.224 1-.5 1"/>
                        <path d="M10.828.122A.5.5 0 0 1 11 .5V1h.5A1.5 1.5 0 0 1 13 2.5V15h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3V1.5a.5.5 0 0 1 .43-.495l7-1a.5.5 0 0 1 .398.117M11.5 2H11v13h1V2.5a.5.5 0 0 0-.5-.5M4 1.934V15h6V1.077z"/>
                    </svg>
                    Authentication Logs
                </a>
                <a href="admin-logs.php" class="side-anchor">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-break" viewBox="0 0 16 16">
                        <path d="M14 4.5V9h-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v7H2V2a2 2 0 0 1 2-2h5.5zM13 12h1v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2h1v2a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1zM.5 10a.5.5 0 0 0 0 1h15a.5.5 0 0 0 0-1z"/>
                    </svg>
                    Document Logs
                </a>
                <a href="admin-user-log.php" class="side-anchor side-anchor-user-logs">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-gear" viewBox="0 0 16 16">
                        <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m.256 7a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1zm3.63-4.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
                    </svg>
                    User Logs
                </a>
                <hr>
                <a href="admin-qr.php" class="side-anchor">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                        <path d="M2 2h2v2H2z"/>
                        <path d="M6 0v6H0V0zM5 1H1v4h4zM4 12H2v2h2z"/>
                        <path d="M6 10v6H0v-6zm-5 1v4h4v-4zm11-9h2v2h-2z"/>
                        <path d="M10 0v6h6V0zm5 1v4h-4V1zM8 1V0h1v2H8v2H7V1zm0 5V4h1v2zM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8zm0 0v1H2V8H1v1H0V7h3v1zm10 1h-1V7h1zm-1 0h-1v2h2v-1h-1zm-4 0h2v1h-1v1h-1zm2 3v-1h-1v1h-1v1H9v1h3v-2zm0 0h3v1h-2v1h-1zm-4-1v1h1v-2H7v1z"/>
                        <path d="M7 12h1v3h4v1H7zm9 2v2h-3v-1h2v-1z"/>
                    </svg>
                    QR Management
                </a>
                <a href="admin-user.php" class="side-anchor">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>    
                    Users
            
                </a>
                <form action="../operation/logout.php" method="POST">
                    <button class='burger-logout'>Log out</button>
                </form>
            </div>
        </div>
    </div>
<script>
const statusSelect = document.getElementById('statusSelect');
const releasedInput = document.getElementById('releasedTo');
const releasedInputLabel = document.getElementById('releasedInputLabel');
const returnedInputLabel = document.getElementById('returnedInputLabel');
const returnReason = document.getElementById('returnReason');


function showInput() {
    if (statusSelect.value === 'Released') {
        releasedInputLabel.style.display = 'block';
        releasedInput.style.display = 'block';
        releasedInput.required = true;
        returnedInputLabel.style.display = 'none';
        returnReason.style.display = 'none';
        returnReason.required = false;
    }
    
    else if(statusSelect.value === 'Returned') {
        returnedInputLabel.style.display = 'block';
        returnReason.style.display = 'block';
        returnReason.required = true;
         releasedInputLabel.style.display = 'none';
        releasedInput.style.display = 'none';
        releasedInput.required = false;
    }
    else {
        releasedInputLabel.style.display = 'none';
        releasedInput.style.display = 'none';
        releasedInput.required = false;
        releasedInput.value = ''; // ADD THIS

        returnedInputLabel.style.display = 'none';
        returnReason.style.display = 'none';
        returnReason.required = false;
        returnReason.value = ''; // ADD THIS
    }

}

showInput();
statusSelect.addEventListener('change' ,showInput );

 document.addEventListener("click", function (e) {
    const dropdown = document.querySelector(".dropdown");

    if (dropdown.contains(e.target)) {
        dropdown.querySelector(".dropdown-content").classList.toggle("show");
    } else {
        dropdown.querySelector(".dropdown-content").classList.remove("show");
    }
    });

document.getElementById('onclickModalBtn').addEventListener('click', function () {
        document.getElementById('modal').classList.add('show-modal');
    });

    document.querySelector('.modal-close').addEventListener('click', function () {
        document.getElementById('modal').classList.remove('show-modal');
    });
</script>
</body>


</html>