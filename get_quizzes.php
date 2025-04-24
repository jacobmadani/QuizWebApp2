<?php
try {
    $stmt = $conn->prepare("
        SELECT q.*, u.name as author 
        FROM quizzes q
        JOIN users u ON q.created_by = u.id
    ");
    $stmt->execute();
    $quizzes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode([
        'quizzes' => $quizzes
    ]);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Failed to fetch quizzes: ' . $e->getMessage()]);
}
?>