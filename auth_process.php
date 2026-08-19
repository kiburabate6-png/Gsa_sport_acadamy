<?php
include 'db_config.php';
session_start();

if (isset($_POST['login'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    // 1. Fetch user
    $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // 2. Check Password
        if ($password === $user['password']) {
            
            // 3. ONLY for Sportsmen: Check Expiry and Payment
            if ($user['role'] == 'Sportsman') {
                
                // Check Payment Status
                if ($user['payment_status'] == 'unpaid') {
                    header("Location: login.php?error=unpaid");
                    exit();
                }

                // Check Expiry Date
                $now = time();
                $expiry = strtotime($user['membership_expiry']);

                if ($now > $expiry) {
                    header("Location: login.php?error=expired");
                    exit();
                }
            }

            // 4. If all good, start session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'Admin') {
                header("Location: admin.php");
            } else {
                header("Location: sportsman_dashboard.php");
            }
            exit();

        } else {
            header("Location: login.php?error=1"); // Wrong password
        }
    } else {
        header("Location: login.php?error=1"); // User not found
    }
}
?>