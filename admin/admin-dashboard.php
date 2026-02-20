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
                <a href="" class="active">DASHBOARD</a>
                <a href="admin-document.php">DOCUMENTS</a>
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
                <div class="admin-card-circle" style="background-color: #3B82F6;">
                <p class="card-count">55</p>
            </div>
                <p class="card-text">TOTAL DOCUMENTS</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle" style="background-color: #22C55E;">
                <p class="card-count">55</p></div>
                <p class="card-text">RECEIVED</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle" style="background-color: #FBBF24;">
                    
                <p class="card-count">55</p>
                </div>
                <p class="card-text">UNDER REVIEW</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle" style="background-color: #7C3AED;">
                    
                <p class="card-count">55</p>
                </div>
                <p class="card-text">RELEASED</p>
            </div>
            <div class="card-flx">
                <div class="admin-card-circle" style="background-color: #EF4444;"><p class="card-count">55</p></div>
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
                            <th class="recent-log">Log ID</th>
                            <th class="recent-type">Type</th>
                            <th class="recent-action">Action performed</th>
                            <th class="recent-performed">Performed by</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Kingmingoy</td>
                            <td>Received</td>
                            <td>Clint Rono</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Kingmingoy</td>
                            <td>Received</td>
                            <td>Clint Rono</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Kingmingoy</td>
                            <td>Received</td>
                            <td>Clint Rono</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Kingmingoy</td>
                            <td>Received</td>
                            <td>Clint Rono</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Kingmingoy</td>
                            <td>Received</td>
                            <td>Clint Rono</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Kingmingoy</td>
                            <td>Received</td>
                            <td>Clint Rono</td>
                        </tr>
                         <tr>
                            <td>1</td>
                            <td>Kingmingoy</td>
                            <td>Received</td>
                            <td>Clint Rono</td>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="middle-form">
                <p class="todays-title">TODAY'S DOCUMENTS</p>

                <div class="mt-2 d-flex justify-content-between">
                    <p class="todays-text">Received</p>
                    <p class="todays-num" style="color: #166534;">26</p>
                </div>

                <div class="d-flex justify-content-between">
                    <p class="todays-text">Under review</p>
                    <p class="todays-num" style="color: #92400E;">21</p>
                </div>

                <div class="d-flex justify-content-between">
                    <p class="todays-text">Released</p>
                    <p class="todays-num" style="color: #075985;">5</p>
                </div>

                <div class="d-flex justify-content-between">
                    <p class="todays-text">Returned</p>
                    <p class="todays-num" style="color: #991B1B">2</p>
                </div>
                
            </div>
            <div class="middle-form"></div>
        </div>
    </div>

    
</body>
</html>