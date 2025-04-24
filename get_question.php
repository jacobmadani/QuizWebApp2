<?php
if (!isset($_GET['quiz_id'])) {
    echo json_encode(['error' => 'Missing quiz ID']);
    exit();
}
$quizId = $_GET['quiz_id'];
try {
    $stmt = $conn->prepare("SELECT * FROM questions WHERE quiz_id = ?");
    $stmt->execute([$quizId]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($questions as &$question) {
        $stmt = $conn->prepare("SELECT id, text FROM options WHERE question_id = ?");
        $stmt->execute([$question['id']]);
        $question['options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $conn->prepare("SELECT id FROM options WHERE question_id = ? AND is_correct = TRUE");
        $stmt->execute([$question['id']]);
        $correct = $stmt->fetch(PDO::FETCH_ASSOC);
        $question['correct_answer'] = $correct ? $correct['id'] : null;
    }
    echo json_encode([
        'questions' => $questions
    ]);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch questions: ' . $e->getMessage()]);
}
?>