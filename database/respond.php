<?php
include 'db.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_start();

    foreach ($_POST['choice'] as $question_id => $response) {
        $_SESSION['responses'][$question_id] = intval($response);
    }
    $isLast = isset($_POST['is_last_category']) && $_POST['is_last_category'] === 'true';
    if ($isLast && !isset($_POST['action'])) {
        $account_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO responses (account_id, question_id, response_data) VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE response_data = VALUES(response_data)");
        foreach ($_SESSION['responses'] as $question_id => $response_value) {
            $stmt->bind_param("iii", $account_id, $question_id, $response_value);
            $stmt->execute();
        }
        unset($_SESSION['responses']);
    }
    if ($_POST['action'] == 'next') {
        header("Location: ../" . $_POST['nextLink']);
    } else {
        header("Location: ../" . $_POST['prevLink']);

    }
} else {
    echo 'Error';
}
?>