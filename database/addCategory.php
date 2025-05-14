<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['category'])) {
    $category = $data['category'];

    $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");

    $stmt->bind_param("s", $category);

    if ($stmt->execute()) {
        $conn->query("ALTER TABLE categories AUTO_INCREMENT = (SELECT MAX(id) + 1 FROM categories)");

        $conn->commit();
        echo "Success! Category added.";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid input!";
}
?>