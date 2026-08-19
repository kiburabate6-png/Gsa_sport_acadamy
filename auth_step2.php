<?php
include 'db_config.php';
$code = $_GET['code'];
$user_id = $_SESSION['temp_login_id'];

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ? AND qr_code = ?");
$stmt->bind_param("is", $user_id, $code);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if($user) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['role'] = $user['role'];
    header("Location: " . ($user['role'] == 'Admin' ? "admin.php" : "sportsman_dashboard.php"));
} else {
    header("Location: login.php?error=qr");
}