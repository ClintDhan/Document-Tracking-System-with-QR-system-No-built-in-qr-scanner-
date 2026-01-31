<?php require_once 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/style/style.css">
</head>
<body>

<div class='login-container'>
    <div class='login-form'>
        <div class='login-centered'>
            <p style='font-weight: 900; color: #188A0E; font-size: 30px; line-height: 0.8;'>Welcome Back!</p>
            <p style='font-size: 15px; color: #47D63A; font-weight: bold;'>Please input your details</p>
                <form action="operation/checklogin.php" method='post' class='form-flx'>
                    <input class='login-centered-input' type="text" name='name' placeholder='Enter username' required>
                    <input class='login-centered-input m-3' type="password" name='password' placeholder='Enter password' required>
                    <button class='login-centered-input'>Log in</button>
                </form>
        </div>
    </div>
</div>

<script src='asset/bootstrap-5.3.8-dist/js/bootstrap.min.js'></script>
</body>
</html>

