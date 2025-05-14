<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $schoolName = isset($_POST['schoolName']) ? trim($_POST['schoolName']) : null;

    if (empty($name) || empty($email) || empty($password)) {
        die("Error: Name, email, and password are required.");
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO accounts (name, email, password, schoolName) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $schoolName);

    if ($stmt->execute()) {
        echo 'what';
        header("Location: ../index.php"); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>