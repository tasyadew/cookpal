<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CookPAL</title>
    <link rel="icon" href="../assets/cookpal.ico" type="image/x-icon">
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="login-bg">
    <div class="form-box">
        <img src="../assets/cookpal.png">
        <div class="button-box">
            <div id="btn"></div>
            <button type="button" class="toggle-btn" onclick="loginBtn()">Log In</button>
            <button type="button" class="toggle-btn" onclick="registerBtn()">Register</button>
        </div>
        <div class="social-icons">
            <button id="googleLogin" class="google-btn">
                <i class="fa-brands fa-google"></i>Continue with Google
            </button>
            <div>OR</div>
        </div>

        <!-- Login Form -->
        <form id="login-form" name="login-form" class="input-group" method="post" action="#"
            enctype="multipart/form-data">
            <input type="email" id="login-email" name="login-email" class="input-field" placeholder="E-mail" autocomplete="email"
                required>
            <input type="password" id="login-password" name="login-password" class="input-field" placeholder="Enter Password" autocomplete="current-password"
                required>
            <input type="checkbox" name="checkbox" class="check-box" onclick="showPass(this, 'login-password', null)"><span>Show Password</span>
            <button type="button" id="loginBtn" name="loginBtn" class="submit-btn">Log In</button>
        </form>

        <!-- Register Form -->
        <form id="register-form" name="register-form" class="input-group" method="post" action="#"
            enctype="multipart/form-data">
            <input type="text" id="register-username" name="register-username" class="input-field" placeholder="Username" autocomplete="username"
                required>
            <input type="email" id="register-email" name="register-email" class="input-field" placeholder="E-mail" autocomplete="email"
                required>
            <input type="password" id="register-password" name="register-password" class="input-field" placeholder="Enter Password" autocomplete="new-password"
                required>
            <input type="password" id="register-reenterpass" name="register-reenterpass" class="input-field" placeholder="Reenter Password" autocomplete="new-password"
                required>
            <input type="checkbox" name="checkbox" class="check-box" onclick="showPass(this, 'register-password', 'register-reenterpass')"><span>Show Password</span>
            <button type="button" id="registerBtn" name="registerBtn" class="submit-btn">Register</button>
        </form>
    </div>
</body>

<script src="../js/login.js"></script>

<!-- Firebase Auth Module -->
<script type="module" src="../js/auth.js"></script>

</html>