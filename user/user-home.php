<?php require_once '../db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body>
    <div class='user-container'>
        <div class='user-form'>

            <div class='user-nav-bar'>
                <div class='user-name'><p>Hi! Jhea!</p></div>
                <div class='user-date'><p>01/24/2026</p></div>
                <button class='log-out'>LOGOUT</button>
            </div>
            
            <div class='user-option'>

                    <button class='btn-receive'>
                        RECEIVE DOCUMENT
                    </button>

                    <button class='btn-update'>
                        UPDATE DOCUMENT
                    </button>

                    <button class='btn-view'>
                        VIEW DOCUMENT
                    </button>
            </div>

            <div class='user-dash'>
                <div class='user-dash-flx'>
                    <div class='user-dash-grid'>
                        <div class='user-received'><p>Received Documents</p></div>
                        <div></div>
                        <div class='user-released'><p>Released Documents</p></div>
                        <div class='user-returned'><p>Returned Documents</p></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>