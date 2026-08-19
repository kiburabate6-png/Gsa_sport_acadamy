<?php 
include 'db_config.php';
$error = "";

// Check for error messages coming from auth_process.php
if(isset($_GET['error'])){
    if($_GET['error'] == "1") {
        $error = "Invalid Username or Password";
    } elseif($_GET['error'] == "expired") {
        $error = "Your membership has EXPIRED. Please contact Admin or Register again.";
    } elseif($_GET['error'] == "unpaid") {
        $error = "Payment required! Please complete your payment to log in.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GSA Academy | Member Login</title>
    <style>
        :root { --primary: #1e3799; --accent: #27ae60; --danger: #e74c3c; }
        body { 
            font-family: 'Segoe UI', sans-serif; 
            background: #2c3e50; 
            display: flex; justify-content: center; align-items: center; 
            height: 100vh; margin: 0; 
        }
        .login-card { 
            background: white; padding: 40px; border-radius: 12px; 
            width: 350px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.3); 
        }
        h2 { color: var(--primary); margin-bottom: 20px; text-transform: uppercase; }
        input { 
            width: 100%; padding: 12px; margin: 10px 0; 
            border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; 
        }
        .btn-login { 
            background: var(--accent); color: white; border: none; 
            width: 100%; padding: 14px; border-radius: 6px; 
            cursor: pointer; font-weight: bold; font-size: 16px; transition: 0.3s;
        }
        .btn-login:hover { background: #219150; transform: translateY(-2px); }
        .error-msg { 
            background: #fdeaea; color: var(--danger); 
            padding: 10px; border-radius: 5px; font-size: 13px; 
            margin-bottom: 15px; border: 1px solid #fabebb;
            font-weight: bold;
        }
        .links { margin-top: 20px; }
        a { text-decoration: none; color: #3498db; font-size: 13px; display: block; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="login-card">
        <div style="background: var(--primary); color: white; display: inline-block; padding: 5px 15px; border-radius: 8px; margin-bottom: 10px; font-weight: bold;">GSA ACADEMY</div>
        <h2>Member Login</h2>

        <!-- Show Error Message -->
        <?php if($error != ""): ?>
            <div class="error-msg">🚨 <?php echo $error; ?></div>
        <?php endif; ?>

        <form action="auth_process.php" method="POST">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login" class="btn-login">SIGN IN</button>
        </form>

        <div class="links">
            <a href="register.php">New Sportsman? Join Now</a>
            <a href="index.php" style="color: #7f8c8d;">← Back to Home</a>
        </div>
    </div>
</body>
</html>