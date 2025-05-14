<?php
session_start();
session_destroy(); 
setcookie("remember_me_name", "", time() - 3600, "/");
setcookie("remember_me_email", "", time() - 3600, "/");
header("Location: ../index.php"); 
exit();
?>