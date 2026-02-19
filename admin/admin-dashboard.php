<?php
session_start();
require_once '../db.php';
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
                <a href="">DASHBOARD</a>
                <a href="">DOCUMENTS</a>
                <a href="">LOGS</a>
                <a href="">QR MANAGEMENT</a>
                <a href="">USERS</a>
            </div>

            <div class="admin-logout">
                <button class='log-out admin-logout'>↪ LOGOUT</button>
            </div>
        </div>

        <div class="admin-card-container">
            <div class="card-flx">
                <div class="admin-card-circle">
                <p class="card-count">55</p>
            </div>
                <p class="card-text">TOTAL DOCUMENTS</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle">
                <p class="card-count">55</p></div>
                <p class="card-text">RECEIVED</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle">
                    
                <p class="card-count">55</p>
                </div>
                <p class="card-text">UNDER REVIEW</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle">
                    
                <p class="card-count">55</p>
                </div>
                <p class="card-text">RELEASED</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle"><p class="card-count">55</p></div>
                <p class="card-text">RETURNED</p>
            </div>
        </div>
        <div class="admin-middle-container">
            <div class="first">
                <div></div>
            </div>
            <div>2nd</div>
            <div>3rd</div>
        </div>
    </div>

    
</body>
</html>