<?php
include "db.php"; 

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['category']) && isset($data['id'])) {
    $category = $data['category'];
    $id = $data['id'];
    $stmt = $conn->prepare("UPDATE questions SET category = ? WHERE category_id = ?");
    $stmt->bind_param("si", $category, $id);
    $stmt->execute();
    
    $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
    $stmt->bind_param("si", $category,  $id);

    if ($stmt->execute()) {
        echo "Success! Category edited.";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid input!";
}
?>