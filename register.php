<?php
include 'db_config.php';
$message = "";

if (isset($_POST['self_register'])) {
    $fullname = $conn->real_escape_string($_POST['fullname']);
    $username = $conn->real_escape_string($_POST['username']);
    $email    = $conn->real_escape_string($_POST['email']);
    $phone    = $conn->real_escape_string($_POST['phone']);
    $password = $_POST['password']; 
    $role     = "Sportsman";
    $plan     = $_POST['membership_plan'];
    $tx_ref   = "GSA-" . time() . "-" . rand(100, 999);

    $plans = [
        "Monthly"    => ["price" => 400, "days" => 30],
        "Half-Month" => ["price" => 250,  "days" => 15],
        "One Week"   => ["price" => 150,    "days" => 7],
        "One Day"    => ["price" => 30,  "days" => 1]
    ];

    $days_to_add = $plans[$plan]['days'];
    $amount      = $plans[$plan]['price'];
    $expiry = date('Y-m-d H:i:s', strtotime("+$days_to_add days"));

    // 1. Handle File Upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    $file_ext = strtolower(pathinfo($_FILES["profile_pic"]["name"], PATHINFO_EXTENSION));
    $photo_path = $target_dir . $username . "_" . time() . "." . $file_ext;
    
    if (!move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $photo_path)) {
        $photo_path = "uploads/default.png";
    }

    // 2. SAVE TO DATABASE (Recounted placeholders: 10)
    $sql = "INSERT INTO users (fullname, username, password, email, phone, role, profile_pic, tx_ref, payment_status, membership_plan, membership_expiry) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'unpaid', ?, ?)";
    
    $stmt = $conn->prepare($sql);

    // ስህተት ካለ እዚህ ጋር ያሳውቀናል
    if (!$stmt) {
        die("Database Error: " . $conn->error); 
    }

    $stmt->bind_param("ssssssssss", $fullname, $username, $password, $email, $phone, $role, $photo_path, $tx_ref, $plan, $expiry);

    if ($stmt->execute()) {
        header("Location: fake_payment.php?tx_ref=$tx_ref&amount=$amount");
        exit();
    } else {
        $message = "Registration failed: Username or Email might already exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GSA Academy | Membership Registration</title>
    <style>
        :root { --primary: #1e3799; --accent: #27ae60; --white: #ffffff; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin:0; padding: 20px 0; }
        .reg-container { background: white; padding: 30px; border-radius: 15px; width: 100%; max-width: 450px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .error-banner { background: #ffebee; color: #c62828; padding: 10px; border-radius: 5px; margin-bottom: 10px; font-size: 14px; border: 1px solid #ef9a9a; }
        label { font-size: 13px; font-weight: bold; color: var(--primary); display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; outline: none; }
        .btn-submit { background: var(--accent); color: white; border: none; width: 100%; padding: 15px; border-radius: 8px; font-size: 16px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-submit:hover { background: #219150; transform: translateY(-2px); }
        .plan-hint { font-size: 11px; color: #666; margin-top: -10px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="reg-container">
        <h2 style="text-align:center; color:var(--primary); margin-bottom: 5px;">Join GSA Academy</h2>
        <p style="text-align:center; color:#666; font-size: 14px; margin-bottom: 25px;">Select a plan to start training</p>
        
        <?php if($message != ""): ?>
            <div class="error-msg" style="color: red; text-align: center; margin-bottom: 10px;"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- IMPORTANT: enctype="multipart/form-data" added below -->
        <form action="" method="POST" enctype="multipart/form-data">
            <label>Profile Image</label>
            <input type="file" name="profile_pic" required>

            <label>Choose Membership Plan</label>
            <select name="membership_plan" required>
                <option value="One Day">One Day (24 Hours) - 30 ETB</option>
                <option value="One Week">One Week (7 Days) - 150 ETB</option>
                <option value="Half-Month">Half-Month (15 Days) - 250 ETB</option>
                <option value="Monthly">Monthly (30 Days) - 400 ETB</option>
            </select>
            <p class="plan-hint">Note: Your expiry date is calculated immediately upon registration.</p>

            <label>Full Name</label>
            <input type="text" name="fullname" placeholder="name" required>

            <div style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="your email" required>
                </div>
                <div style="flex: 1;">
                    <label>Phone</label>
                    <input type="tel" name="phone" placeholder="0911..." required>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div style="flex: 1;">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
            </div>

            <button type="submit" name="self_register" class="btn-submit">Pay & Complete Registration</button>
        </form>
    </div>
</body>
</html>