<?php
require_once 'db.php';
$data = json_decode(file_get_contents('php://input'), true);
if (!isset($data['question_id']) || !isset($data['text']) || !isset($data['options']) || !is_array($data['options'])) {
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}
$questionId = $data['question_id'];
$text = $data['text'];
$options = $data['options'];
try {
    $conn->beginTransaction();
    $stmt = $conn->prepare("UPDATE questions SET text = ? WHERE id = ?");
    $stmt->execute([$text, $questionId]);
    $stmt = $conn->prepare("DELETE FROM options WHERE question_id = ?");
    $stmt->execute([$questionId]);
    foreach ($options as $option) {
        $isCorrect = isset($option['is_correct']) ? (bool)$option['is_correct'] : false;
        $stmt = $conn->prepare("INSERT INTO options (question_id, text, is_correct) VALUES (?, ?, ?)");
        $stmt->execute([$questionId, $option['text'], $isCorrect]);
    }
    $conn->commit();
    echo json_encode(['message' => 'Question updated successfully']);
} catch(PDOException $e) {
    $conn->rollBack();
    echo json_encode(['error' => 'Question update failed: ' . $e->getMessage()]);
}
?>