<?php
include 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = ucwords(strtolower(trim($_POST['email'])));
    $password = $_POST['password'];
    $schoolName = isset($_POST['schoolName']) ? trim($_POST['schoolName']) : null;

    if (empty($name) || empty($email) || empty($password)) {
        echo "<script>
        alert('Error: Name, email, and password are required.');
        window.location.href = '../sign up.php';
        </script>";
    }

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO accounts (name, email, password, schoolName) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $hashedPassword, $schoolName);

    try {
    if ($stmt->execute()) {
        header("Location: ../index.php"); 
        exit();
    } }
    catch (mysqli_sql_exception $e) {
        if ($e-> getCode() === 1062) {
        echo "<script>
        alert('Error: This email is already registered.');
        window.location.href = '../sign up.php';
        </script>";
    } else {
        $err = htmlspecialchars($e->getMessage(), ENT_QUOTES);
        echo "<script>
            alert('Database error: $safeMsg');
            window.location.href = '../sign up.php';
        </script>";
    }
    }

    $stmt->close();
}
?>