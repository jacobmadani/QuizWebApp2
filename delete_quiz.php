<?php
if (!isset($data['quiz_id'])) {
    echo json_encode(['error' => 'Missing quiz ID']);
    exit();
}
$quizId = $data['quiz_id'];
try {
    $stmt = $conn->prepare("DELETE FROM quizzes WHERE id = ?");
    $stmt->execute([$quizId]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Quiz deleted successfully']);
    } else {
        echo json_encode(['error' => 'Quiz not found']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => 'Quiz deletion failed: ' . $e->getMessage()]);
}
?>