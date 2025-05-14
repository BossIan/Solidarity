<?php
include './db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $rememberMe = isset($_POST['remember_me']);
    if (empty($email) || empty($password)) {
        die("Error: Email and password are required.");
    }

    $stmt = $conn->prepare("SELECT id, name, password, role FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId, $name, $hashedPassword, $role);
        $stmt->fetch();
        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $name;
            if ($rememberMe) {
                setcookie("remember_me_name", $name, time() + (30 * 24 * 60 * 60), "/");
                setcookie("remember_me_email", $email, time() + (30 * 24 * 60 * 60), "/");
            } else {
                setcookie("remember_me_name", "", time() - 3600, "/");
                setcookie("remember_me_email", "", time() - 3600, "/");
            }
            if ($role == 'admin') {
                header("Location: ../Question%20Management.php");
                exit();
            }
            header("Location: ../index.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
}
?>