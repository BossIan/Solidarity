<?php
include "db.php"; 

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['category'])) {
    $category = $data['category'];

    $stmt = $conn->prepare("DELETE FROM categories WHERE name = ?");
    $stmt->bind_param("s", $category);

    if ($stmt->execute()) {
        echo "Success! Category deleted.";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid input!";
}
?>