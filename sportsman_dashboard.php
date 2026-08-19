<?php
// 1. Include config and check login
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Setup Variables
$my_id = $_SESSION['user_id'];
$now = date('Y-m-d H:i:s');

// 3. Get My Data from Database
$user_query = $conn->query("SELECT * FROM users WHERE id = $my_id");
$me = $user_query->fetch_assoc();

// 4. Check Membership Status
$is_expired = false;
if ($me && !empty($me['membership_expiry'])) {
    if (strtotime($me['membership_expiry']) < strtotime($now)) {
        $is_expired = true;
    }
}

// 5. Get All Schedules
$schedules = $conn->query("SELECT * FROM schedules ORDER BY sport_date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GSA | Sportsman Dashboard</title>
    <style>
        :root { --primary: #1e3799; --accent: #27ae60; --danger: #e74c3c; }
        body { font-family: 'Segoe UI', sans-serif; background: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; background: var(--primary); color: white; padding: 15px 25px; border-radius: 10px; }
        .card { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); margin-top: 20px; }
        .status-box { padding: 15px; border-radius: 8px; font-weight: bold; text-align: center; margin-bottom: 20px; }
        .active { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .expired { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #eee; }
        th { background: #f8f9fa; }
        .logout-btn { background: var(--danger); color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; font-weight: bold; }
        
        /* QR Code Styling */
        .qr-section { text-align: center; margin-top: 30px; padding: 20px; border-top: 1px solid #eee; }
        .qr-image { border: 5px solid #fff; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 10px; }
    </style>
</head>
<body>

<div class="container">
    <!-- FIND THIS PART IN YOUR SPORTSMAN DASHBOARD CODE -->
<div class="header">
    <div style="display: flex; align-items: center; gap: 15px;">
         <!-- FIX: Show Profile Picture with fallback -->
         <?php 
            $my_img = !empty($me['profile_pic']) ? $me['profile_pic'] : 'uploads/default.png';
         ?>
        <img src="<?php echo $my_img; ?>" 
             width="55" height="55" 
             style="border-radius: 50%; object-fit: cover; border: 2px solid white;"
             onerror="this.src='uploads/default.png'">
             
        <h2 style="margin:0;">Welcome, <?php echo htmlspecialchars($me['fullname']); ?></h2>
    </div>
    <a href="index.php" class="logout-btn">Logout</a>
</div>
    <div class="card">
        <!-- MEMBERSHIP STATUS -->
        <?php if ($is_expired): ?>
            <div class="status-box expired">❌ YOUR MEMBERSHIP HAS EXPIRED. Please renew to continue training.</div>
        <?php else: ?>
            <div class="status-box active">✅ YOUR MEMBERSHIP IS ACTIVE</div>
        <?php endif; ?>

        <div style="display: flex; justify-content: space-around; text-align: center; margin-bottom: 20px;">
            <div>
                <small style="color: #666;">Registration Date (Start)</small><br>
                <strong><?php echo date('M d, Y', strtotime($me['created_at'])); ?></strong>
            </div>
            <div>
                <small style="color: #666;">Membership Expiry</small><br>
                <strong style="color: <?php echo $is_expired ? 'red' : 'green'; ?>;">
                    <?php 
                        if(!empty($me['membership_expiry'])) {
                            echo date('M d, Y', strtotime($me['membership_expiry']));
                        } else {
                            echo "Not Set";
                        }
                    ?>
                </strong>
            </div>
        </div>

        <!-- NEW: THE QR ENTRY PASS SECTION -->
        <div class="qr-section">
            <h3 style="margin-top:0; color:var(--primary);">My Digital Entry Pass</h3>
            <?php if(!empty($me['qr_code'])): ?>
                <img src="<?php echo $me['qr_code']; ?>" width="180" class="qr-image">
                <p><small style="color:#7f8c8d;">Present this QR code to the Admin camera for quick access verification.</small></p>
            <?php else: ?>
                <p style="color:var(--danger);">QR Code not generated. Please contact Admin.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- SCHEDULES TABLE -->
    <div class="card">
        <h3>📅 Upcoming Training & Event Schedules</h3>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Venue</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($schedules && $schedules->num_rows > 0) {
                    while($row = $schedules->fetch_assoc()) {
                        echo "<tr>
                                <td><strong>" . htmlspecialchars($row['activity']) . "</strong></td>
                                <td>" . date('M d, Y', strtotime($row['sport_date'])) . "</td>
                                <td>" . $row['sport_time'] . "</td>
                                <td>" . htmlspecialchars($row['venue']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' style='text-align:center; color:#999; padding: 30px;'>No upcoming schedules found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>