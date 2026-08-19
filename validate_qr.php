<?php
include 'db_config.php';

if (isset($_GET['code'])) {
    $qr_content = $_GET['code'];
    $parts = explode('_', $qr_content);
    $username = $parts[0];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $is_expired = (strtotime($user['membership_expiry']) < time());

        echo '<div style="background: white; padding: 20px; border-radius: 15px; border: 3px solid '.($is_expired ? "#e74c3c" : "#27ae60").'; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 250px;">';
        echo '<img src="'.$user['profile_pic'].'" style="width: 150px; height: 150px; border-radius: 10px; object-fit: cover; margin-bottom: 15px; border: 2px solid #ddd;">';
        echo '<h2 style="margin: 0; font-size: 18px;">'.htmlspecialchars($user['fullname']).'</h2>';
        echo '<p style="color: '.($is_expired ? "#e74c3c" : "#27ae60").'; font-weight: bold; margin: 10px 0;">'.($is_expired ? "❌ EXPIRED" : "✅ ACTIVE").'</p>';
        echo '<p style="font-size: 11px; color: #999;">ID: '.$user['username'].'</p>';
        echo '</div>';
    } else {
        echo '<p style="color: red; font-weight: bold;">🚨 Invalid QR Code: User not found.</p>';
    }
}
?>