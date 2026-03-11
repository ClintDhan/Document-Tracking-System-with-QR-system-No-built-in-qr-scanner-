<?php 
session_start();
require_once 'db.php'; 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="asset/bootstrap-5.3.8-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="asset/style/style.css">

</head>
<body>

<div class='login-container'>
    <div class='login-form'>
        <div class='login-centered'>
            <p style='font-weight: 900; color: #188A0E; font-size: 30px; line-height: 0.8;'>Change password</p>
            <p style='font-size: 15px; color: #47D63A; font-weight: bold;'>Please change the password provided by your admin</p>
        <form action="operation/change-pass.php" method="post" class='form-flx'>             

                <div class="input-position">
                    <input type="password" name="newPass" placeholder="Enter new password" class='mt-3'>
                    <span 
    onclick="togglePassword(this)"
  >
    <i class="bi bi-eye"></i>
  </span>
                </div>

                <div class="input-position">
                    <input  type="password" name="rePass" placeholder="Re-enter new password" class='mt-3'>
                    <span 
    onclick="togglePassword(this)"
  >
    <i class="bi bi-eye"></i>
  </span>
                </div>
                <button type="submit" name="submit">Proceed</button>
        </form>

        </div>
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
        icon.classList.replace("bi-eye", "bi-eye-slash");
    } else {
        input.type = "password";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    }
}
</script>
</body>
</html>