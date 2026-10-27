
<?php
session_start();
require_once '../db.php';

// ----------------------------
// 1. Check login
// ----------------------------
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ----------------------------
// 2. Get QR information
// ----------------------------
$qr_id = $_GET['qr'] ?? null;
$qrControl = $_GET['control'] ?? null;

if (!$qr_id || !$qrControl) {
    die("Invalid QR access.");
}

$stmt = $conn->prepare("SELECT * FROM qr_code WHERE id = ?");
$stmt->bind_param("i", $qr_id);
$stmt->execute();

$result = $stmt->get_result();
$qr = $result->fetch_assoc();

if (!$qr) {
    die("QR not found.");
}

// ----------------------------
// 3. Restore submitted form values
// ----------------------------
$formData = $_SESSION['receive_form'] ?? [];

unset($_SESSION['receive_form']);
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

<body class="user-body">

    <?php if (isset($_SESSION['error'])): ?>
        <div class="toast-notification toast-error" id="updateToast">
            <div class="toast-check-container">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    viewBox="0 0 16 16"
                    id="toastIcon"
                >
                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5m.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                </svg>
            </div>

            <span class="toast-message" id="toastMessage">
                <?= htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?>
            </span>

            <i class="bi bi-x toast-exit-btn" id="toastClose"></i>
        </div>

        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="user-home-header-container">
        <header class="user-home-header">
            <div class="header-inner">
                <a class="brand" href="">DTS</a>

                <div class="account">
                    <div class="user">
                        <strong><?= htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
                        <br>
                        <span class="role">Records Officer</span>
                    </div>

                    <form class="user-header-form" action="../operation/logout.php" method="POST">
                        <button class="logout">Logout</button>
                    </form>
                </div>
            </div>
        </header>
    </div>

    <div class="main">
        <div class="user-main-title-container">
            <h2>Receive Document</h2>
        </div>

        <form
            class="panel form-panel"
            action="../operation/receivedocument.php"
            method="POST"
        >
            <input
                type="hidden"
                name="qr_id"
                value="<?= htmlspecialchars((string) $qr_id, ENT_QUOTES, 'UTF-8') ?>"
            >

            <input
                type="hidden"
                name="control_num"
                value="<?= htmlspecialchars($qrControl, ENT_QUOTES, 'UTF-8') ?>"
            >

            <div class="field">
                <label class="label" for="document-name">Document Type</label>

                <input
                    class="user-document-input"
                    id="document-name"
                    name="type"
                    required
                    value="<?= htmlspecialchars($formData['type'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="field">
                <label class="label" for="description">Description</label>

                <textarea
                    class="user-document-input"
                    id="description"
                    name="description"
                ><?= htmlspecialchars($formData['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
            </div>

            <div class="field">
                <label class="label" for="department">Department</label>

                <input
                    class="user-document-input"
                    type="text"
                    id="department"
                    name="department"
                    value="<?= htmlspecialchars($formData['department'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <div class="grid-two">
                <div class="field">
                    <label class="label" for="statusSelect">Status</label>

                    <select class="user-document-input" name="status" id="statusSelect">
                        <option
                            value="Received"
                            <?= ($formData['status'] ?? 'Received') === 'Received' ? 'selected' : '' ?>
                        >Received</option>

                        <option
                            value="Released"
                            <?= ($formData['status'] ?? '') === 'Released' ? 'selected' : '' ?>
                        >Released</option>

                        <option
                            value="Returned"
                            <?= ($formData['status'] ?? '') === 'Returned' ? 'selected' : '' ?>
                        >Returned</option>
                    </select>
                </div>

                <div class="field">
                    <label class="label" for="copies">Copies</label>

                    <input
                        class="user-document-input"
                        id="copies"
                        name="pages"
                        type="number"
                        min="1"
                        value="<?= htmlspecialchars($formData['pages'] ?? '1', ENT_QUOTES, 'UTF-8') ?>"
                    >
                </div>
            </div>

            <div class="field">
                <label class="label" for="returnReason" id="returnedInputLabel">
                    Return Reason
                </label>

                <input
                    type="text"
                    id="returnReason"
                    class="user-document-input"
                    name="returned_reason"
                    value="<?= htmlspecialchars($formData['returned_reason'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    style="display: none;"
                >
            </div>

            <div class="field">
                <label class="label" for="releasedTo" id="releasedInputLabel">
                    Released To
                </label>

                <input
                    type="text"
                    id="releasedTo"
                    class="user-document-input"
                    name="released_to"
                    value="<?= htmlspecialchars($formData['released_to'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    style="display: none;"
                >
            </div>

            <div class="field">
                <label class="label" for="remarks">Remarks</label>

                <input
                    class="user-document-input"
                    type="text"
                    id="remarks"
                    name="remark"
                    value="<?= htmlspecialchars($formData['remark'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>

            <button class="user-qr-scan-btn full" name="submit" type="submit">
                Receive
            </button>
        </form>
    </div>

    <script>
        // ----------------------------
        // Show the appropriate fields based on status
        // ----------------------------
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

                returnedInputLabel.style.display = 'none';
                returnReason.style.display = 'none';
                returnReason.required = false;
            }
        }

        showInput();
        statusSelect.addEventListener('change', showInput);

        // ----------------------------
        // Error toast
        // ----------------------------
        const updateToast = document.getElementById('updateToast');
        const toastMessage = document.getElementById('toastMessage');
        const toastClose = document.getElementById('toastClose');

        let toastTimer;

        function showToast(message) {
            if (!updateToast || !toastMessage) return;

            clearTimeout(toastTimer);

            toastMessage.textContent = message;

            updateToast.style.display = 'flex';
            updateToast.classList.add('show-toast');

            toastTimer = setTimeout(() => {
                updateToast.classList.remove('show-toast');
                updateToast.style.display = 'none';
            }, 5000);
        }

        if (toastClose) {
            toastClose.addEventListener('click', () => {
                clearTimeout(toastTimer);
                updateToast.classList.remove('show-toast');
                updateToast.style.display = 'none';
            });
        }

        if (updateToast && toastMessage) {
            showToast(toastMessage.textContent.trim());
        }
    </script>

</body>
</html>
