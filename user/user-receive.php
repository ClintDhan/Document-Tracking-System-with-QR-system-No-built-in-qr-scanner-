<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
// Get QR info from URL
$qr_id = $_GET['qr'] ?? null;           // id of the QR
$qrControl = $_GET['control'] ?? null;  // control number

if (!$qr_id || !$qrControl) {
    die("Invalid QR access."); // safety check
}

// Optional: fetch QR info from database
$stmt = $conn->prepare("SELECT * FROM qr_code WHERE id = ?");
$stmt->bind_param("i", $qr_id);
$stmt->execute();
$result = $stmt->get_result();
$qr = $result->fetch_assoc();

if (!$qr) {
    die("QR not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Document</title>
    <link rel="icon" type="image/png" href="../asset/img/log.png">
    <link rel="stylesheet" href="../asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../asset/style/style.css">
</head>

<body>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

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
            <h2>Receive Document</h2>
        </div>

        <form class="panel form-panel" action="../operation/receivedocument.php" method='POST'>
            <input type="hidden" name="qr_id" value="<?= htmlspecialchars($_GET['qr'] ?? '') ?>">
            <input type="hidden" name="control_num" value="<?= htmlspecialchars($qrControl) ?>">

            <div class="field">
                <label class="label" for="document-name">Document Type</label>
                <input class="user-document-input" id="document-name" name='type' required="">
            </div>
            <div class="field">
                <label class="label" for="description">Description</label>
                <textarea class="user-document-input" id="description" name="description"></textarea>
            </div>
            <div class="field">
                <label class="label" for="department">Department</label>
                <input class="user-document-input" type="text" id="department" name="department">
            </div>
            <div class="grid-two">
                <div class="field">
                    <label class="label" for="status">Status</label>
                    <select class="user-document-input" name="status" id='statusSelect'>
                            <option value="Received">Received</option>
                            <option value="Released">Released</option>
                            <option value="Returned">Returned</option>
                    </select>
                </div>
                <div class="field">
                    <label class="label" for="copies">Copies</label>
                    <input class="user-document-input" id="copies" name="pages" type="number" min="1" value="1">
                </div>
            </div>

            <div class="field">
                <label class="label" for="returnReason" id="returnedInputLabel">Return Reason</label>
                <input type="text"
                            required
                            id="returnReason"
                            class="user-document-input"
                            name="returned_reason"
                            style="display: none;">            
            </div>

            <div class="field">
                <label class="label" for="releasedTo" id="releasedInputLabel">Released To</label>
                 <input type="text"
                            required
                            id="releasedTo"
                            class="user-document-input"
                            name="released_to"
                            style="display: none;">
            </div>
            
            <div class="field">
                <label class="label" for="remarks">Remarks</label>
                <input class="user-document-input" type="text" id="remarks" name="remark">
            </div>

            <button class="user-qr-scan-btn full" name="submit" type="submit">Receive</button>
        </form>

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
            } else if (statusSelect.value === 'Returned') {
                returnedInputLabel.style.display = 'block';
                returnReason.style.display = 'block';
                returnReason.required = true;
                releasedInputLabel.style.display = 'none';
                releasedInput.style.display = 'none';
                releasedInput.required = false;
            } else {
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
        statusSelect.addEventListener('change', showInput);
    </script>
</body>

</html>