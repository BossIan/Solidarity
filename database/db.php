<?php
// $user = "u349700752_solidarity"; 
// $pass = "w>9w#M]xBdgB"; 
// $host = "bulsusolidarityscale.com ";
// $dbname = "u349700752_solidarity"; 
$host = "localhost";

$user = "root"; 
$dbname = "solidarity"; 
$pass = ""; 
$conn = new mysqli($host, $user, $pass, $dbname);
session_start();
$showLogin = true;
if (isset($_COOKIE['remember_me_email']) && isset($_COOKIE['remember_me_password'])) {
    $email = $_COOKIE['remember_me_email'];
    $password = $_COOKIE['remember_me_password']; 
    $stmt = $conn->prepare("SELECT id, name, password FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $showLogin = false;
    }
}
if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
    $showLogin = false;
}
?>
