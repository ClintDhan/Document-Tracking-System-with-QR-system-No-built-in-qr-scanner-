<?php 
session_start();
require_once 'db.php'; 

if (isset($_SESSION['user_id']) && isset($_GET['redirect'])) {
    header("Location: " . $_GET['redirect']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="../asset/img/log.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/style/style.css">
</head>
<body>
<?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $_SESSION['error']; ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
<div class='login-container'>
    <div class="logo-container">
        <div class="logo-form">
            <div class="logo-blue">
                <img src="asset/img/log.png" class="logo-img" alt="">
            </div>
            <div>
                <p class="logo-title">MAYOR'S OFFICE DTS</p>
                <p class="logo-sub">DOCUMENT TRACKING SYSTEM</p>
            </div>

        </div>

    </div>
    <div class='login-form'>
        <div class='login-centered'>
            <p style='font-weight: 900; color: #2B308A; font-size: 30px; line-height: 0.8;'>WELCOME!</p>
            <p style='font-size: 15px; color: #AAB1CE; font-weight: 400;'>Please input your details</p>
        <form action="operation/checklogin.php" method="post" class='form-flx'>
                <!-- hidden input to carry redirect -->
                <input type="hidden" name="redirect" value="<?= isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : '' ?>">
                <input type="text" name="name" placeholder="Username" maxlength="15">
                <div class="input-position mt-3">
                    <input type="password" name="password" placeholder="Password">
                     <span 
                        onclick="togglePassword(this)"
                    >
                    <i class="bi bi-eye-slash toggle-icon"></i>
                </div>
                <button type="submit">Log in</button>
        </form>

        </div>
    </div>
    <div class="copyright">
        <p>
        © 2026 
        <a href="https://www.facebook.com/GodSonClintoy/" target="_blank">CLNTY</a> 
        - Designed by 
        <a href="https://example.com">April Jhea Logroño</a>
        </p>    
    </div>
</div>

<script src='asset/bootstrap-5.3.8-dist/js/bootstrap.min.js'></script>
<script>
    function togglePassword(iconSpan) {
    const container = iconSpan.parentElement;
    const input = container.querySelector("input");
    const icon = iconSpan.querySelector("i");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    } else {
        input.type = "password";
        icon.classList.replace("bi-eye", "bi-eye-slash");
    }
}
</script>
</body>
</html>

