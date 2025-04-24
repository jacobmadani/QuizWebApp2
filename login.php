<?php
if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(['error' => 'Missing email or password']);
    exit();
}
$email = $data['email'];
$password = $data['password'];
try {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user || !password_verify($password, $user['password'])) {
        echo json_encode(['error' => 'Invalid email or password']);
        exit();
    }
    unset($user['password']);
    echo json_encode([
        'message' => 'Login successful',
        'user' => $user
    ]);
} catch(PDOException $e) {
    echo json_encode(['error' => 'Login failed: ' . $e->getMessage()]);
}
?>