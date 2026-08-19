<?php
include 'db_config.php';
$u = $_POST['username'];
$p = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $u);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if($user && (password_verify($p, $user['password']) || $p === $user['password'])) {
    $_SESSION['temp_login_id'] = $user['id'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}