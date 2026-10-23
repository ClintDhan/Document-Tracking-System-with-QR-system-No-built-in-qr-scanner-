<?php 
session_start();
require_once '../db.php'; 


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$qr_id = $_GET['qr'] ?? null;
$document_id = $_GET['document'] ?? null;
$control = $_GET['control'] ?? null;

$sql = "
SELECT d.*, dl.remarks
FROM document d
LEFT JOIN document_log dl 
    ON d.id = dl.document_id
WHERE d.qr_id = '$qr_id'
ORDER BY dl.performed_at DESC
LIMIT 1
";

$result = $conn->query($sql);
$document = mysqli_fetch_assoc($result);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Document</title>
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
            <h2>View Document</h2>
        </div>

        <div class="panel detail-panel">
            <dl>
                <div class="detail-row">
                    <dt>Control Number</dt>
                    <dd><?= $control ?></dd>
                </div>
                <div class="detail-row">
                    <dt>Document Type</dt>
                    <dd><?= $document['type'] ?></dd>
                </div>
                <div class="detail-row">
                    <dt>Description</dt>
                    <dd><?= htmlspecialchars($document['description'] ?? '') ?></dd>
                </div>
                <div class="detail-row">
                    <dt>Department</dt>
                    <dd><?= $document['department'] ?></dd>
                </div>
                <div class="detail-row">
                    <dt>Copies</dt>
                    <dd><?= $document['pages'] ?></dd>
                </div>


                <?php if($document['status'] == 'Released'): ?>

                    <div class="detail-row">
                        <dt>Status</dt>
                        <dd style="<?= ($document['status'] ?? '') == 'Returned' ? 'background-color: #f5081cff; width: auto; color: #ffff;' :
                                    (($document['status'] ?? '') == 'Released' ? 'background-color: #8b0effff; width: auto; color: #ffff;' :
                                    (($document['status'] ?? '') == 'Received' ? 'background-color: #12fd49ff; width: auto; color: #ffff;' :
                                    (($document['status'] ?? '') == 'Reviewed' ? 'background-color: #f7bf08ff; width: auto; color: #fff;' : '' )))?>"><?= $document['status'] ?>
                        </dd>
                    </div>

                    <div class="detail-row">
                        <dt>Released To</dt>
                        <dd><?= $document['released_to'] ?></dd>
                    </div>
                <?php elseif($document['status'] == 'Returned'): ?>
                      <div class="detail-row">
                        <dt>Status</dt>
                        <dd style="<?= ($document['status'] ?? '') == 'Returned' ? 'background-color: #f5081cff; width: auto; color: #ffff;' :
                                    (($document['status'] ?? '') == 'Released' ? 'background-color: #8b0effff; width: auto; color: #ffff;' :
                                    (($document['status'] ?? '') == 'Received' ? 'background-color: #12fd49ff; width: auto; color: #ffff;' :
                                    (($document['status'] ?? '') == 'Reviewed' ? 'background-color: #f7bf08ff; width: auto; color: #fff;' : '' )))?>"><?= $document['status'] ?>
                        </dd>
                    </div>

                    <div class="detail-row">
                        <dt>Reason for Return</dt>
                        <dd><?= $document['returned_reason'] ?></dd>
                    </div>
                <?php else: ?> 
                    <div class="detail-row">
                        <dt>Status</dt>
                        <dd style="<?= ($document['status'] ?? '') == 'Returned' ? 'background-color: #f5081cff; width: auto; color: #ffff;' :
                                    (($document['status'] ?? '') == 'Released' ? 'background-color: #8b0effff; width: auto; color: #ffff;' :
                                    (($document['status'] ?? '') == 'Received' ? 'background-color: #12fd49ff; width: auto; color: #ffff;' :
                                    (($document['status'] ?? '') == 'Reviewed' ? 'background-color: #f7bf08ff; width: auto; color: #fff;' : '' )))?>"><?= $document['status'] ?>
                        </dd>
                    </div>
                <?php endif; ?>
                <div class="detail-row">
                    <dt>Date Created</dt>
                    <dd><?= $document['created_at'] ?></dd>
                </div>
                <div class="detail-row">
                    <dt>Last Updated</dt>
                    <dd><?= $document['updated_at'] ?></dd>
                </div>
                <div class="detail-row">
                    <dt>Latest Remark</dt>
                    <dd><?= $document['remarks'] ?></dd>
                </div>
            </dl>
            <div class="actions detail-actions"><button type="button"
        onclick="goBack()"
        class="user-qr-scan-btn btn-user-bck btn-outline">
    Back
</button></div>
        </div>
    </div>
    

<script>
    function goBack() {
    sessionStorage.setItem("hideHomeAlerts", "true");
    history.back();
}
</script>
</body>
</html>