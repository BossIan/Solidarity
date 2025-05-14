<?php
include "../db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['category'])) {
    $category = $data['category'];
    $id = $data['id'];
    $question_id = $data['question_id'];

    $stmt = $conn->prepare("UPDATE questions SET category = ?, category_id = ? WHERE question_id = ?");
    $stmt->bind_param("sii", $category, $id, $question_id);

    if ($stmt->execute()) {
        echo "Success! Question edited.";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid input!";
}
?>