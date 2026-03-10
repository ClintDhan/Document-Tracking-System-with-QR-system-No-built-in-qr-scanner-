<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: ../login.php");
    exit();
}
$totalDocs = "SELECT COUNT(*) AS total_docs FROM document";
$result1 = $conn->query($totalDocs);
$row = $result1->fetch_assoc();
$totalDocsCount = $row['total_docs'];

$totalRecs = "SELECT COUNT(*) AS total_received FROM document WHERE status = 'Received'";
$result2 = $conn->query($totalRecs);
$row1 = $result2->fetch_assoc();
$totalReceivedCount = $row1['total_received'];

$totalUnderReview = "SELECT COUNT(*) AS total_review FROM document WHERE status = 'Under Review'";
$result3 = $conn->query($totalUnderReview);
$row2 = $result3->fetch_assoc();
$totalReviewCount = $row2['total_review'];

$totalReleased = "SELECT COUNT(*) AS total_released FROM document WHERE status = 'Released'";
$result4 = $conn->query($totalReleased);
$row3 = $result4->fetch_assoc();
$totalReleasedCount = $row3['total_released'];

$totalReturned = "SELECT COUNT(*) AS total_returned FROM document WHERE status = 'Returned'";
$result5 = $conn->query($totalReturned);
$row4 = $result5->fetch_assoc();
$totalReturnedCount = $row4['total_returned'];

// today count 

$todayRecs = " SELECT COUNT(*) AS today_received
    FROM document
    WHERE status = 'Received'
    AND (DATE(created_at) = CURDATE() OR DATE(updated_at) = CURDATE())";
$result6 = $conn->query($todayRecs);
$row5 = $result6->fetch_assoc();
$todayReceivedCount = $row5['today_received'];

$todayReview = "SELECT COUNT(*) AS today_review FROM document WHERE status = 'Under Review' AND (DATE(created_at) = CURDATE() OR DATE(updated_at) = CURDATE())";
$result7 = $conn->query($todayReview);
$row6 = $result7->fetch_assoc();
$todayReviewCount = $row6['today_review'];

$todayReleased = "SELECT COUNT(*) AS today_released FROM document WHERE status = 'Released' AND (DATE(created_at) = CURDATE() OR DATE(updated_at) = CURDATE())";
$result8 = $conn->query($todayReleased);
$row7 = $result8->fetch_assoc();
$todayReleasedCount = $row7['today_released'];

$todayReturned = "SELECT COUNT(*) AS today_returned FROM document WHERE status = 'Returned' AND (DATE(created_at) = CURDATE() OR DATE(updated_at) = CURDATE())";
$result9 = $conn->query($todayReturned);
$row8 = $result9->fetch_assoc();
$todayReturnedCount = $row8['today_returned'];


$recent = "SELECT document_log.id,
                    document_log.action,
                    user.name AS performed,
                    document.type AS type
                    FROM document_log
                    INNER JOIN user ON document_log.performed_by = user.id
                    INNER JOIN document ON document_log.document_id = document.id
                    ORDER BY document_log.id DESC LIMIT 7";
$result10 = $conn->query($recent);
$document_log = $result10->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
                <a href="admin-dashboard.php" class="active">DASHBOARD</a>
                <a href="admin-document.php">DOCUMENTS</a>
                <a href="admin-logs.php">DOCUMENT LOGS</a>
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

        <div class="admin-card-container">
            <div class="card-flx">
                <div class="admin-card-circle" style="background-color: #3B82F6;">
                <p class="card-count"><?= $totalDocsCount ?></p>
            </div>
                <p class="card-text">TOTAL DOCUMENTS</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle" style="background-color: #22C55E;">
                <p class="card-count"><?= $totalReceivedCount ?></p></div>
                <p class="card-text">RECEIVED</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle" style="background-color: #FBBF24;">
                    
                <p class="card-count"><?= $totalReviewCount ?></p>
                </div>
                <p class="card-text">UNDER REVIEW</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle" style="background-color: #7C3AED;">
                    
                <p class="card-count"><?= $totalReleasedCount ?></p>
                </div>
                <p class="card-text">RELEASED</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle" style="background-color: #EF4444;"><p class="card-count"><?= $totalReturnedCount ?></p></div>
                <p class="card-text">RETURNED</p>
            </div>
        </div>
        <div class="admin-middle-container">
            <div class="first middle-form">
                <div class="d-flex justify-content-between">
                    <p class="recent-title">RECENT ACTIVITY</p>
                    <button class="recent-view">VIEW</button>
                </div>

                <table class="first-table">
                    <thead>
                        <tr>
                            <th class="recent-log">No</th>
                            <th class="recent-type">Type</th>
                            <th class="recent-action">Action performed</th>
                            <th class="recent-performed">Performed by</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach($document_log as $docs): ?>
                            <tr>
                                <td><?= htmlspecialchars($docs['id']) ?></td>
                                <td><?= htmlspecialchars($docs['type']) ?></td>
                                <td><?= htmlspecialchars($docs['action']) ?></td>
                                <td><?= htmlspecialchars($docs['performed']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


            <div class="middle-form">
                <p class="todays-title">TODAY'S DOCUMENTS</p>

                <div class="mt-2 d-flex justify-content-between">
                    <p class="todays-text">Received</p>
                    <p class="todays-num" style="color: #166534;"><?= $todayReceivedCount ?></p>
                </div>

                <div class="d-flex justify-content-between">
                    <p class="todays-text">Under review</p>
                    <p class="todays-num" style="color: #92400E;"><?= $todayReviewCount ?></p>
                </div>

                <div class="d-flex justify-content-between">
                    <p class="todays-text">Released</p>
                    <p class="todays-num" style="color: #075985;"><?= $todayReleasedCount ?></p>
                </div>

                <div class="d-flex justify-content-between">
                    <p class="todays-text">Returned</p>
                    <p class="todays-num" style="color: #991B1B"><?= $todayReturnedCount ?></p>
                </div>
                
            </div>
            <div class="middle-form"></div>
        </div>
    </div>

    
</body>
</html>