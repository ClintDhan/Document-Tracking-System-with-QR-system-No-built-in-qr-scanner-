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
            <p>Welcome Back!</p>
            <p>Please input your details</p>
                <form action="operation/checklogin.php" method='post'>
                    <input class='login-centered-input' type="text" name='name' placeholder='Enter username'>
                    <input class='login-centered-input mt-2' type="password" name='password' placeholder='Enter password'>
                    <button class='login-centered-input mt-4'>Log in</button>
                </form>
        </div>
    </div>
</div>

<script src='asset/bootstrap-5.3.8-dist/js/bootstrap.min.js'></script>
</body>
</html>

