<?php
include 'db_config.php';

// If there is no pending login, kick them back to login page
if (!isset($_SESSION['pending_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['pending_id'];
$query = $conn->query("SELECT * FROM users WHERE id = '$user_id'");
$user = $query->fetch_assoc();

// Finalize Login Logic
if (isset($_POST['confirm_identity'])) {
    // Move from pending to active session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['role'] = $user['role'];
    unset($_SESSION['pending_id']); // Clear pending

    // Redirect to dashboard
    if ($user['role'] === 'Admin') {
        header("Location: admin.php");
    } else {
        header("Location: sportsman_dashboard.php");
    }
    exit();
}

// Fail Login Logic
if (isset($_POST['fail_identity'])) {
    session_destroy();
    header("Location: login.php?error=photo_mismatch");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GSA | Identity Verification</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #2c3e50; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .verify-card { background: white; padding: 40px; border-radius: 15px; width: 350px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .profile-img { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 5px solid #27ae60; margin-bottom: 20px; }
        h2 { color: #2c3e50; margin-bottom: 10px; }
        p { color: #666; margin-bottom: 25px; }
        .btn { width: 100%; padding: 12px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; margin-bottom: 10px; font-size: 16px; }
        .btn-yes { background: #27ae60; color: white; }
        .btn-no { background: #e74c3c; color: white; }
        .btn:hover { opacity: 0.9; }
    </style>
</head>
<body>

<div class="verify-card">
    <h2>Security Check</h2>
    <p>Is this your registered profile photo?</p>
    
    <!-- Show user's profile picture from Database -->
    <img src="<?php echo $user['profile_pic']; ?>" class="profile-img" alt="Profile Picture">
    
    <form method="POST">
        <button type="submit" name="confirm_identity" class="btn btn-yes">Yes, this is me. Login</button>
        <button type="submit" name="fail_identity" class="btn btn-no">No, this is not me</button>
    </form>
</div>

</body>
</html>