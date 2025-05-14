<?php
include "db.php"; 
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['ids']) && is_array($data['ids'])) {
    $ids = $data['ids'];
    
    
    foreach ($ids as $id) {
        $stmt = $conn->prepare("UPDATE questions SET status = 'Active' WHERE question_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    
    echo "Success! Archived " . count($ids) . " records.";
} else {
    echo "No data received!";
}
?>
