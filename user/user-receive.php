<?php require_once '../db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receive Document</title>
    <link rel="stylesheet" href="../asset/style/style.css">
</head>
<body>
    <div class='user-container'>
        <div class="user-form">

            <div class='user-nav-bar'>
                <div class='user-name'><p>Hi! Jhea!</p></div>
                <div class='user-date'><p>01/24/2026</p></div>
                <button class='log-out'>LOGOUT</button>
            </div>

            <button class='btn-home'>Home</button>

            <div class='option-form'>
                <p class='option-receive'>Receive Document</p>
                <p class='option-text'>Please indicate document information</p>

                <input type="text" placeholder='Title'>
                <textarea name="" id=""></textarea>
                <input type="text" placeholder='Department'>

                <button>CREATE</button>
            </div>

        </div>

    </div>
</body>
</html>