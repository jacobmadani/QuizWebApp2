<?php
if (!isset($data['question_id'])) {
    echo json_encode(['error' => 'Missing question ID']);
    exit();
}
$questionId = $data['question_id'];
try {
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$questionId]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Question deleted successfully']);
    } else {
        echo json_encode(['error' => 'Question not found']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => 'Question deletion failed: ' . $e->getMessage()]);
}
?>