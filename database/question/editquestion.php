<?php
include "../db.php"; 

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['question'])) {
    $question = $data['question'];
    $id = $data['id'];

    $stmt = $conn->prepare("UPDATE questions SET question = ? WHERE question_id = ?");
    $stmt->bind_param("si", $question, $id);

    if ($stmt->execute()) {
        echo "Success! Question edited.";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid input!";
}
?>