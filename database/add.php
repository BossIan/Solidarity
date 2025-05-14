<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['category'], $data['question'])) {
    $category = $data['category'];
    $question = $data['question'];
    $id = $data['id'];
    try {
        $stmt = $conn->prepare("INSERT INTO questions (category, question, category_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $category, $question, $id);

        if ($stmt->execute()) {
            echo "'Question Successfully added!'";
        } 
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'foreign key constraint fails') !== false) {
            echo "Enter a category!";
        } else {
            echo json_encode(["status" => "error", "message" => $e->getMessage()]);
        }
    }
} else {
    echo "Invalid input!";
}
?>