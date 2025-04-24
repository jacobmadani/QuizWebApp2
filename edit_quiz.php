<?php
if (!isset($data['quiz_id']) || !isset($data['title']) || !isset($data['description'])) {
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}
$quizId = $data['quiz_id'];
$title = $data['title'];
$description = $data['description'];
try {
    $stmt = $conn->prepare("UPDATE quizzes SET title = ?, description = ? WHERE id = ?");
    $stmt->execute([$title, $description, $quizId]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Quiz updated successfully']);
    } else {
        echo json_encode(['error' => 'Quiz not found or no changes made']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => 'Quiz update failed: ' . $e->getMessage()]);
}
?>