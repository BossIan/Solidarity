<?php
include './database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./index.php"); 
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT role FROM accounts WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    if ($user['role'] === 'admin') {
    } else {
        header("Location: ./index.php"); 
    }
} else {
    header("Location: ./index.php");
}
?>