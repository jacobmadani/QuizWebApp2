<?php
if (!isset($data['title']) || !isset($data['description']) || !isset($data['user_id'])) {
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}
$title = $data['title'];
$description = $data['description'];
$userId = $data['user_id'];
try {
    $stmt = $conn->prepare("INSERT INTO quizzes (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->execute([$title, $description, $userId]);
    $quizId = $conn->lastInsertId();
    echo json_encode([
        'message' => 'Quiz created successfully',
        'quiz_id' => $quizId
    ]);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Quiz creation failed: ' . $e->getMessage()]);
}
?>