<?php
include './database/db.php';
if (isset($_SESSION['user_name']) && isset($_SESSION['user_id'])) {
    echo $_SESSION['user_name'];
    header("Location: ./index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solidarity</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="stylesheet" href="./styles/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div class="flex">
        <div style="width: 70%"></div>
        <div class="loginDiv">
            <div class="flex login">
                <a href="./" style="display: flex;align-content: center;"><img src="logoLogin.png"
                        style="height:inherit" alt=""></a>
                <div class="logoText">
                    <p><strong>Bulacan State University</strong> <br>
                        <i>Solidarity Scale</i>
                    </p>
                </div>
            </div>
            <div class="loginMain">
                <h2>LOGIN</h2>
                <form action="./database/login.php" method="POST">

                    <div class="input" style="margin-top: 1rem;">
                        <label for="email">Email</label>
                        <input id="email" name="email" placeholder="Email or phone number" type="email">
                    </div>
                    <div class="input">
                        <label for="password">Password</label>
                        <div style="margin-top: 1rem; display:flex">
                            <input id="password" name="password" placeholder="Enter password" type="password">


                            <svg onclick="togglePassword(this)" id="eye" class='eye' transform="translate(0,0)"
                                id="Hide" width="24" height="24" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M19.85 7.95773L16.08 11.7277C16.13 11.9877 16.16 12.2477 16.16 12.5177C16.16 14.6777 14.4 16.4277 12.25 16.4277C11.98 16.4277 11.72 16.3977 11.46 16.3477H11.45L8.27002 19.5277C9.48002 20.0177 10.83 20.3177 12.25 20.3177C17.65 20.3177 22 16.0577 22 12.5177C22 10.9977 21.19 9.34773 19.85 7.95773Z"
                                    fill="#000000"></path>
                                <path fill-rule="evenodd" fill="#4D4D4D" clip-rule="evenodd"
                                    d="M8.34 12.5177C8.34 10.3577 10.09 8.60775 12.25 8.60775C13.01 8.60775 13.73 8.82775 14.33 9.21775L13.23 10.3177C12.93 10.1777 12.6 10.1077 12.25 10.1077C10.92 10.1077 9.84 11.1877 9.84 12.5177C9.84 12.8677 9.91 13.1977 10.05 13.4977L8.94 14.6077C8.56 14.0077 8.34 13.2877 8.34 12.5177ZM11.06 14.6177L14.35 11.3377L15.43 10.2477L18.73 6.95775L18.72 6.94775L20.42 5.24775C20.72 4.95775 20.72 4.47775 20.42 4.18775C20.13 3.89775 19.65 3.89775 19.36 4.18775L17.44 6.10775C15.94 5.25775 14.16 4.71775 12.25 4.71775C6.85 4.71775 2.5 8.98775 2.5 12.5177C2.5 14.3677 3.69 16.4177 5.6 17.9477L3.59 19.9577C3.29 20.2577 3.29 20.7277 3.59 21.0177C3.73 21.1677 3.93 21.2377 4.12 21.2377C4.31 21.2377 4.5 21.1677 4.65 21.0177L5.72 19.9577L6.85 18.8177H6.86L11.06 14.6177Z"
                                    fill="#000000"></path>
                            </svg>
                            <svg onclick="togglePassword(this)" id="eyeclose" class="eye close"
                                transform="translate(32,0)" id="Show" width="24" height="24" viewBox="0 0 25 25"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12.25 10.1917C10.92 10.1917 9.83804 11.2737 9.83804 12.6037C9.83804 13.9337 10.92 15.0157 12.25 15.0157C13.58 15.0157 14.662 13.9337 14.662 12.6037C14.662 11.2737 13.58 10.1917 12.25 10.1917Z"
                                    fill="#000000"></path>
                                <path fill-rule="evenodd" fill="#4D4D4D" clip-rule="evenodd"
                                    d="M12.25 16.516C10.093 16.516 8.33805 14.761 8.33805 12.604C8.33805 10.447 10.093 8.692 12.25 8.692C14.407 8.692 16.162 10.447 16.162 12.604C16.162 14.761 14.407 16.516 12.25 16.516ZM12.25 4.802C6.84805 4.802 2.49805 9.07 2.49805 12.604C2.49805 16.138 6.84805 20.406 12.25 20.406C17.652 20.406 22.002 16.138 22.002 12.604C22.002 9.07 17.652 4.802 12.25 4.802Z"
                                    fill="#000000"></path>
                            </svg>
                        </div>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="rememberMe">
                            <label class="switch">
                                <input id="rememberMe" name="remember_me" type="checkbox">
                                <span class="slider round"></span>
                            </label>
                            <label style="margin-left: 10px" for="rememberMe">Remember Me</label>

                        </div>

                        <div class="forgotPassword">
                            <a style="color: #742616; cursor: pointer;">Forgot Password?</a>
                        </div>
                    </div>
                    <button type="submit" class="signInBtn">
                        Sign in
                    </button>
                </form>

                <p style="text-align: center">Don't have an account? <a style="color: #742616;"
                        href="./sign up.php">Sign up now</a></p>
            </div>
        </div>
    </div>
</body>
<script>
    function togglePassword(e) {

        document.getElementById('eyeclose').classList.toggle("close");

        document.getElementById('eye').classList.toggle("close");
        let passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text";
        } else {
            passwordField.type = "password";
        }
    }
</script>

</html>