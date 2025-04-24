<?php
if (!isset($data['quiz_id']) || !isset($data['text']) || !isset($data['options']) || !is_array($data['options'])) {
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}
$quizId = $data['quiz_id'];
$text = $data['text'];
$options = $data['options'];
try {
    $conn->beginTransaction();
    $stmt = $conn->prepare("INSERT INTO questions (quiz_id, text) VALUES (?, ?)");
    $stmt->execute([$quizId, $text]);
    $questionId = $conn->lastInsertId();
    foreach ($options as $option) {
        $isCorrect = isset($option['is_correct']) ? (bool)$option['is_correct'] : false;
        $stmt = $conn->prepare("INSERT INTO options (question_id, text, is_correct) VALUES (?, ?, ?)");
        $stmt->execute([$questionId, $option['text'], $isCorrect]);
    }
    $conn->commit();
    echo json_encode([
        'message' => 'Question created successfully',
        'question_id' => $questionId
    ]);
} catch(PDOException $e) {
    $conn->rollBack();
    echo json_encode(['error' => 'Question creation failed: ' . $e->getMessage()]);
}
?>