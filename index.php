<?php
include './database/db.php';
$showLogin = true;

if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
    $showLogin = false;
}
$page = $_GET['page'] ?? 'Home';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <title>Solidarity</title>
    <link rel="stylesheet" href="styles/home.css">
    <link rel="stylesheet" href="styles/header.css">
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>
    <div class="header">
        <div class="ellipse"></div>
        <div class="navs">
            <?php if ($showLogin): ?>
                <div><a href="?page=Home" class="<?= $page === 'Home' ? 'active' : '' ?>">Home</a></div>
                <div><a href="?page=About us" class="<?= $page === 'About us' ? 'active' : '' ?>">About Us</a></div>
                <div class="login"><a href="./login.php"> Login</a></div>
                <a class="signup" href="./sign up.php">
                    <div>Sign Up</div>
                </a>
            <?php else: ?>
                <div id="username"> <a href="./database/logout.php">Welcome back! <?php echo $_SESSION['user_name'] ?></a>
                </div>
                <div><a href="?page=Home" class="<?= $page === 'Home' ? 'active' : '' ?>">Home</a></div>
                <div><a href="?page=Survey" class="<?= $page === 'Survey' ? 'active' : '' ?>">Survey</a></div>
                <div><a href="?page=About us" class="<?= $page === 'About us' ? 'active' : '' ?>">About Us</a></div>
                <div><a href="./Question%20Management.php">Dashboard</a></div>
            <?php endif; ?>

        </div>
    </div>

    <div class="App">
        <?php
        if ($page === 'Home') {
            include "pages/home.php";
        } elseif ($page === 'Survey') {
            include "pages/survey.php";
        } elseif ($page === 'About us') {
            include "pages/aboutus.php";
        }
        ?>
    </div>

</body>

</html>