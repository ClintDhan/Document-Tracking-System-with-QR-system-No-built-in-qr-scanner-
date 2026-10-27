<?php

session_start();
require_once '../db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$qr_id = $_GET['qr'] ?? null;
$document_id = $_GET['document'] ?? null;
$control_num = $_GET['control'] ?? null;

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
    <title>Update Document</title>
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
            <h2>Update Document</h2>
        </div>

        <form class="panel form-panel" action="../operation/updatedocument.php" method='POST'>
            <input type="hidden" name="qr_id" value="<?= $qr_id ?>">
            <input type="hidden" name="document_id" value="<?= $document_id ?>">
            <input type="hidden" name="control_num" value="<?= $control_num ?>">

            <div class="field">
                <label class="label" for="document-name">Document Type</label>
                <input class="user-document-input" id="document-name" name='type' required value="<?= $document['type'] ?>">
            </div>
            <div class="field">
                <label class="label" for="description">Description</label>
                <textarea class="user-document-input" id="description" name="description"><?= $document['description'] ?></textarea>
            </div>
            <div class="field">
                <label class="label" for="department">Department</label>
                <input class="user-document-input" type="text" id="department" name="department" value="<?= $document['department'] ?>">
            </div>
            <div class="grid-two">
                <div class="field">
                    <label class="label" for="status">Status</label>
                    <select class="user-document-input" name="status" id='statusSelect'>
                        <option value="Received" <?= ($document['status'] ?? '') == 'Received' ? 'selected' : '' ?>>Received</option>
                        <option value="Released" <?= ($document['status'] ?? '') == 'Released' ? 'selected' : '' ?>>Released</option>
                        <option value="Returned" <?= ($document['status'] ?? '') == 'Returned' ? 'selected' : '' ?>>Returned</option>
                    </select>
                </div>
                <div class="field">
                    <label class="label" for="copies">Copies</label>
                    <input class="user-document-input" id="copies" name="pages" type="number" min="1" value="<?= $document['pages'] ?>">
                </div>
            </div>

            <div class="field">
                <label class="label" for="returnReason" id="returnedInputLabel">Return Reason</label>
                <input type="text"
                    required
                    id="returnReason"
                    class="user-document-input"
                    name="returned_reason" value="<?= htmlspecialchars($document['returned_reason'] ?? '') ?>"
                    style="<?= ($document['status'] ?? '') == 'Returned' ? 'display:block;' : 'display:none;' ?> padding: none;">
            </div>

            <div class="field">
                <label class="label" for="releasedTo" id="releasedInputLabel">Released To</label>
                <input type="text"
                    required
                    id="releasedTo"
                    class="user-document-input"
                    name="released_to"
                    value="<?= htmlspecialchars($document['released_to'] ?? '') ?>"
                    style="<?= ($document['status'] ?? '') == 'Released' ? 'display:block;' : 'display:none;' ?> padding: none;">
            </div>

            <div class="field">
                <label class="label" for="remarks">Remarks</label>
                <input class="user-document-input" type="text" id="remarks" name="remark">
            </div>

            <button type="button"
                onclick="goBack()"
                class="user-qr-scan-btn btn-user-bck btn-outline">
                Back
            </button>
            <button class="user-qr-scan-btn btn-user-bck" name="submit" type="submit">Update</button>
        </form>
    </div>

    <!-- <div class='user-container'>
        <div class="user-form">
    <button class="btn-home" onclick="history.back()">
    ❮ BACK
    </button>
            <div class='option-form'>
                <p class='option-receive' style="text-align: center;">Update Document</p>
                <p class='option-text' style="text-align: center;">Please indicate the necessary changes</p>
            
                <form action="../operation/updatedocument.php" 
                        method='POST'
                        style='display: flex; justify-content: center; flex-direction: column;'
                        >
                    <input type="hidden" name="qr_id" value="<?= $qr_id ?>">
                    <input type="hidden" name="document_id" value="<?= $document_id ?>">
                    <input type="hidden" name="control_num" value="<?= $control_num ?>">


                    <div>
                        <label for="">Type</label> <br>
                        <input required type="text" placeholder='Type' class='update-input' name='type' value="<?= $document['type'] ?>">
                    </div>
                    <div class="mt-2">
                        <label for="">Description</label> <br>
                        <textarea required id="" placeholder='Description' class='update-textare' name='description'><?= $document['description'] ?></textarea>

                    </div>
                    <div class="mt-2">
                        <label for="">Department</label> <br>
                        <input required type="text" placeholder='Department' class='update-input' name='department' value="<?= $document['department'] ?>">
                    </div>
                    <div class="mt-2">
                        <label for="">Number of copies</label> <br>
                        <input required type="number" placeholder='Pages' class='update-input' name='pages' value="<?= $document['pages'] ?>">
                    </div>

                    <div class="mt-2">
                        <label for="">Status</label> <br>
                        <select name="status" class="update-input" id='statusSelect'>
                            <option value="Received" <?= ($document['status'] ?? '') == 'Received' ? 'selected' : '' ?>>Received</option>
                            <option value="Released" <?= ($document['status'] ?? '') == 'Released' ? 'selected' : '' ?>>Released</option>
                            <option value="Returned" <?= ($document['status'] ?? '') == 'Returned' ? 'selected' : '' ?>>Returned</option>
                        </select>
                    </div>
                    <div class="mt-2">
                        <label for="" id="releasedInputLabel">Released To</label>
                        <input type="text"
                        required
                        id="releasedTo"
                        class="update-input"
                        name="released_to"
                        placeholder="Released To"
                        value="<?= htmlspecialchars($document['released_to'] ?? '') ?>"
                        style="<?= ($document['status'] ?? '') == 'Released' ? 'display:block;' : 'display:none;' ?> padding: none;">
                    </div>

                    <div class="mt-2">
                        <label for="" id="returnedInputLabel">Return Reason</label>
                        <input type="text"
                        required
                        id="returnReason"
                        class="update-input"
                        name="returned_reason"
                        placeholder="Return Reason"
                        value="<?= htmlspecialchars($document['returned_reason'] ?? '') ?>"
                        style="<?= ($document['status'] ?? '') == 'Returned' ? 'display:block;' : 'display:none;' ?> padding: none;">
                    </div>

                    <div class="mt-2">
                        <label for="">Remarks</label> <br>
                        <input type="text" placeholder='(Optional)' class='update-input' name='remark'>
                    </div>

                    <button class='btn-submit' type='submit' name='submit'>UPDATE</button>
                </form>
            </div>

        </div>

    </div> -->

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

        function goBack() {
            sessionStorage.setItem("hideHomeAlerts", "true");
            window.location.replace(
                "user-home.php?control=<?= urlencode($control_num) ?>"
            );
        }

const updateToast = document.getElementById('updateToast');
const toastMessage = document.getElementById('toastMessage');
const toastIcon = document.getElementById('toastIcon');
const toastClose = document.getElementById('toastClose');

let toastTimer;

function showToast(message, type = 'error') {
    if (!updateToast) return;

    clearTimeout(toastTimer);

    updateToast.classList.remove('toast-error', 'toast-success');
    updateToast.classList.add(`toast-${type}`);

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

// Automatically hide the PHP session error after 5 seconds
if (updateToast) {
    showToast(toastMessage.textContent.trim(), 'error');
}
    </script>
</body>

</html>