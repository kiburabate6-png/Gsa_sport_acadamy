<?php
include 'db_config.php'; 

// 1. SECURITY CHECK
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];
$fullname = $_SESSION['fullname'];
$today_date = date('Y-m-d'); 
$now = date('Y-m-d H:i:s');

// 2. DASHBOARD ANALYTICS LOGIC (Calculated at top)
$total_members = $conn->query("SELECT COUNT(*) as c FROM users WHERE role = 'Sportsman'")->fetch_assoc()['c'];
$active_members = $conn->query("SELECT COUNT(*) as c FROM users WHERE membership_expiry > '$now' AND role = 'Sportsman'")->fetch_assoc()['c'];
$expired_count = $conn->query("SELECT COUNT(*) as c FROM users WHERE membership_expiry < '$now' AND role = 'Sportsman'")->fetch_assoc()['c'];
$today_sched = $conn->query("SELECT COUNT(*) as c FROM schedules WHERE sport_date = '$today_date'")->fetch_assoc()['c'];

// 3. FETCH DATA
$all_members = $conn->query("SELECT * FROM users WHERE role = 'Sportsman' ORDER BY created_at DESC");
$all_schedules = $conn->query("SELECT * FROM schedules ORDER BY sport_date ASC, sport_time ASC");
$all_messages = $conn->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$admin_data = $conn->query("SELECT * FROM users WHERE id = '$admin_id'")->fetch_assoc();

// 4. HANDLE ADMIN PROFILE UPDATE
if (isset($_POST['update_admin_profile'])) {
    $new_name = $_POST['admin_fullname'];
    $new_user = $_POST['admin_username'];
    $new_pass = $_POST['admin_password'];
    $current_photo = $admin_data['profile_pic'];

    if (!empty($_POST['admin_captured_image'])) {
        $imgData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $_POST['admin_captured_image']);
        $current_photo = "uploads/admin_" . time() . ".png";
        file_put_contents($current_photo, base64_decode($imgData));
    } elseif (!empty($_FILES['admin_photo']['name'])) {
        $photo_name = $_FILES['admin_photo']['name'];
        $current_photo = "uploads/" . time() . "_" . $photo_name;
        move_uploaded_file($_FILES['admin_photo']['tmp_name'], $current_photo);
    }

    if (!empty($new_pass)) {
        $hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET fullname=?, username=?, password=?, profile_pic=? WHERE id=?");
        $stmt->bind_param("ssssi", $new_name, $new_user, $hashed_pass, $current_photo, $admin_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET fullname=?, username=?, profile_pic=? WHERE id=?");
        $stmt->bind_param("sssi", $new_name, $new_user, $current_photo, $admin_id);
    }
    if ($stmt->execute()) { $_SESSION['fullname'] = $new_name; header("Location: admin.php?msg=Profile Updated"); }
    exit;
}

// 5. HANDLE REGISTRATION (Sportsman Entry)
if(isset($_POST['self_register'])) {
    $fullname_reg = $_POST['fullname'];
    $username_reg = $_POST['username'];
    $plan = $_POST['membership_plan']; 
    $password_reg = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $expiry = date('Y-m-d H:i:s', strtotime('+30 days'));
    $photo_path = "uploads/default.png"; 

    if(!empty($_POST['captured_image'])) {
        $imgData = str_replace(['data:image/png;base64,', ' '], ['', '+'], $_POST['captured_image']);
        $photo_path = "uploads/athlete_" . time() . ".png";
        file_put_contents($photo_path, base64_decode($imgData));
    }

    $qr_text = $username_reg . "_GSA_" . bin2hex(random_bytes(2)); 
    $stmt = $conn->prepare("INSERT INTO users (fullname, username, password, profile_pic, qr_code, role, created_at, membership_expiry, membership_plan) VALUES (?, ?, ?, ?, ?, 'Sportsman', NOW(), ?, ?)");
    $stmt->bind_param("sssssss", $fullname_reg, $username_reg, $password_reg, $photo_path, $qr_text, $expiry, $plan);
    
    if($stmt->execute()) { header("Location: admin.php?msg=Athlete Registered"); } 
    exit;
}

// 6. SCHEDULE LOGIC
if(isset($_POST['add_schedule'])) {
    $activity = $_POST['activity']; $sport_date = $_POST['sport_date']; $sport_time = $_POST['sport_time']; $venue = $_POST['venue'];
    if ($sport_date < $today_date) { header("Location: admin.php?msg=Error: Past Date!"); exit; }
    $conn->query("INSERT INTO schedules (activity, sport_date, sport_time, venue) VALUES ('$activity','$sport_date','$sport_time','$venue')");
    header("Location: admin.php?msg=Schedule Added"); exit;
}

// 7. DELETE LOGIC
if(isset($_GET['delete_id'])) { $id = $_GET['delete_id']; $conn->query("DELETE FROM users WHERE id='$id'"); header("Location: admin.php?msg=Member removed"); exit; }
if(isset($_GET['delete_schedule'])) { $id = $_GET['delete_schedule']; $conn->query("DELETE FROM schedules WHERE id='$id'"); header("Location: admin.php?msg=Schedule removed"); exit; }
if(isset($_GET['delete_msg'])) { $id = $_GET['delete_msg']; $conn->query("DELETE FROM contact_messages WHERE id='$id'"); header("Location: admin.php?msg=Message Removed"); exit; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GSA Admin | Control Panel</title>
<script src="https://unpkg.com/html5-qrcode"></script>
<style>
    :root { --primary: #1e3799; --accent: #27ae60; --danger: #e74c3c; --dark: #2c3e50; --white: #ffffff; }
    body { font-family: 'Segoe UI', sans-serif; margin:0; display:flex; background:#f0f2f5; color: #333; }
    
    .sidebar { width:240px; background: var(--dark); color:white; height:100vh; padding:20px; position:fixed; box-sizing: border-box;}
    .sidebar h2 { border-bottom: 1px solid #555; padding-bottom: 15px; color: var(--accent); text-align: center; font-size: 1.2rem; }
    .sidebar button { width:100%; padding:12px; margin:5px 0; border:none; background:#34495e; color:white; cursor:pointer; border-radius:6px; text-align:left; transition: 0.3s;}
    .sidebar button:hover { background: var(--primary); transform: translateX(5px); }
    .sidebar .active-btn { background: var(--primary); border-left: 4px solid var(--accent); }

    .container { margin-left:240px; padding:30px; width:calc(100% - 240px); }
    
    /* DASHBOARD CARDS STYLING */
    .dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
    .stat-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 15px; }
    .stat-card h4 { margin: 0; color: #666; font-size: 0.8rem; text-transform: uppercase; }
    .stat-card p { margin: 5px 0 0; font-size: 1.5rem; font-weight: bold; color: var(--dark); }
    .icon-box { width: 50px; height: 50px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: white; }

    .card { border:none; padding:25px; margin-bottom:25px; border-radius:12px; background:#fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);}
    .section { display:none; }
    
    .search-box { width: 100%; padding: 12px; margin-bottom: 20px; border-radius: 8px; border: 2px solid #ddd; font-size: 1rem; box-sizing: border-box; }
    table { width:100%; border-collapse:collapse; }
    th, td { padding:14px; border-bottom:1px solid #eee; text-align:left; }
    th { background: #f8f9fa; color: #666; font-size: 0.75rem; }
    .user-img { width: 45px; height: 45px; border-radius: 50%; object-fit: cover; }
    
    .btn { padding:8px 12px; border:none; color:white; cursor:pointer; border-radius:5px; font-weight: bold; text-decoration: none; font-size: 11px; }
    .btn-del { background:var(--danger); }
    .btn-add { background:var(--accent); width: 100%; padding: 15px; font-size: 1rem; margin-top: 10px;}
    
    .badge { padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: bold; }
    .bg-success { background: #e8f5e9; color: #2e7d32; }
    .bg-danger { background: #ffebee; color: #c62828; }

    video, .captured-preview { width: 100%; max-width: 320px; border-radius: 10px; margin-top: 10px; }
    input, select { width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 10px; box-sizing: border-box; }
</style>
</head>
<body>

<div class="sidebar">
    <div style="text-align:center; margin-bottom: 15px;">
        <img src="<?php echo $admin_data['profile_pic']; ?>" style="width:70px; height:70px; border-radius:50%; border: 2px solid var(--accent); object-fit:cover;">
        <p style="font-size: 0.8rem; margin-top: 5px;"><?php echo $fullname; ?></p>
    </div>
    <h2>GSA ADMIN</h2>
    <button onclick="showSection('members')" id="btn-members">👥 Athletes</button>
    <button onclick="showSection('register')" id="btn-register">👤 Registration</button>
    <button onclick="showSection('schedule')" id="btn-schedule">📅 Schedules</button>
    <button onclick="showSection('messages')" id="btn-messages">✉️ Inbox (<?php echo $all_messages->num_rows; ?>)</button>
    <button onclick="showSection('scan')" id="btn-scan">📸 Scanner</button>
    <button onclick="showSection('admin_profile')" id="btn-admin_profile">⚙️ My Profile</button>
    <a href="index.php" style="color: #ff7675; display: block; margin-top: 30px; text-decoration: none; text-align: center; font-weight: bold;">LOGOUT</a>
</div>

<div class="container">
    <div id="toast" style="display:none; padding:15px; border-radius:10px; margin-bottom:15px; color: white; text-align: center; font-weight: bold;"></div>

    <!-- 1. ATHLETES SECTION (Stats moved inside here) -->
    <div class="section" id="members">
        
        <!-- DASHBOARD ANALYTICS (Displays ONLY when members section is active) -->
        <div class="dashboard-grid">
            <div class="stat-card">
                <div class="icon-box" style="background: #3498db;">👥</div>
                <div><h4>Total Athletes</h4><p><?php echo $total_members; ?></p></div>
            </div>
            <div class="stat-card">
                <div class="icon-box" style="background: var(--accent);">✔</div>
                <div><h4>Active Now</h4><p><?php echo $active_members; ?></p></div>
            </div>
            <div class="stat-card">
                <div class="icon-box" style="background: var(--danger);">✖</div>
                <div><h4>Expired</h4><p><?php echo $expired_count; ?></p></div>
            </div>
            <div class="stat-card">
                <div class="icon-box" style="background: #f1c40f;">📅</div>
                <div><h4>Today's Sched</h4><p><?php echo $today_sched; ?></p></div>
            </div>
        </div>

        <!-- SEARCH AND TABLE -->
        <div class="card">
            <h3>📋 Athlete Database</h3>
            <input type="text" id="memberSearch" class="search-box" placeholder="Search by name or username..." onkeyup="searchTable()">
            <table id="memberTable">
                <thead><tr><th>Face</th><th>Full Name</th><th>Username</th><th>Plan</th><th>Days Left</th><th>Action</th></tr></thead>
                <tbody>
                    <?php while($row = $all_members->fetch_assoc()): 
                        $diff = strtotime($row['membership_expiry']) - time();
                        $days = ceil($diff / (60 * 60 * 24));
                    ?>
                    <tr>
                        <td><img src="<?php echo $row['profile_pic']; ?>" class="user-img"></td>
                        <td><b><?php echo $row['fullname']; ?></b></td>
                        <td>@<?php echo $row['username']; ?></td>
                        <td><?php echo $row['membership_plan'] ?? 'Standard'; ?></td>
                        <td>
                            <span class="badge <?php echo ($days > 0) ? 'bg-success' : 'bg-danger'; ?>">
                                <?php echo ($days > 0) ? $days . " Days Left" : "Expired"; ?>
                            </span>
                        </td>
                        <td><a href="admin.php?delete_id=<?php echo $row['id'];?>" class="btn btn-del" onclick="return confirm('Delete Member?')">Delete</a></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 2. REGISTRATION SECTION -->
    <div class="card section" id="register">
        <h3>👤 New Athlete Enrollment</h3>
        <form method="POST">
            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                <input type="text" name="fullname" placeholder="Full Name" required>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="membership_plan">
                    <option value="Standard">Standard (30 Days)</option>
                    <option value="Premium">Premium (90 Days)</option>
                    <option value="Elite">Elite (365 Days)</option>
                </select>
            </div>
            <div style="text-align:center; background:#f9f9f9; padding:20px; border-radius:10px; border:2px dashed #ccc;">
                <video id="video" autoplay playsinline></video>
                <img id="captured-preview" class="captured-preview" style="display:none;">
                <input type="hidden" name="captured_image" id="captured_image_input">
                <br>
                <button type="button" id="btn-capture" class="btn" style="background:var(--dark); width:auto; padding: 10px 20px;">📸 Capture Face</button>
            </div>
            <button type="submit" name="self_register" class="btn btn-add">COMPLETE REGISTRATION</button>
        </form>
    </div>

    <!-- 3. ADMIN PROFILE UPDATE -->
    <div class="card section" id="admin_profile">
        <h3>⚙️ Admin Account Settings</h3>
        <form method="POST">
            <input type="text" name="admin_fullname" value="<?php echo $admin_data['fullname']; ?>">
            <input type="text" name="admin_username" value="<?php echo $admin_data['username']; ?>">
            <input type="password" name="admin_password" placeholder="New Password (Leave blank to keep current)">
            <button type="submit" name="update_admin_profile" class="btn btn-add">UPDATE MY PROFILE</button>
        </form>
    </div>

    <!-- 4. SCHEDULES -->
    <div class="card section" id="schedule">
        <h3>📅 Add Schedule</h3>
        <form method="POST">
            <input type="text" name="activity" placeholder="Activity" required>
            <input type="date" name="sport_date" min="<?php echo $today_date; ?>" required>
            <input type="time" name="sport_time" required>
            <input type="text" name="venue" placeholder="Venue" required>
            <button type="submit" name="add_schedule" class="btn btn-add">Save Schedule</button>
        </form>
    </div>

    <!-- 5. MESSAGES -->
    <div class="card section" id="messages">
        <h3>✉️ Inbox Messages</h3>
        <table>
            <?php while($msg = $all_messages->fetch_assoc()): ?>
            <tr>
                <td><b><?php echo $msg['name']; ?></b></td>
                <td><?php echo $msg['message']; ?></td>
                <td><a href="admin.php?delete_msg=<?php echo $msg['id']; ?>" class="btn btn-del">Remove</a></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <!-- 6. SCANNER -->
    <div class="card section" id="scan">
        <h3>📸 Entrance QR Scanner</h3>
        <div id="reader" style="width:100%; border-radius:10px; overflow:hidden;"></div>
        <div id="scan-result" style="margin-top:20px; text-align:center; font-weight:bold;"></div>
    </div>
</div>

<script>
// LIVE SEARCH TABLE
function searchTable() {
    let input = document.getElementById("memberSearch").value.toLowerCase();
    let rows = document.getElementById("memberTable").getElementsByTagName("tr");
    for (let i = 1; i < rows.length; i++) {
        let text = rows[i].innerText.toLowerCase();
        rows[i].style.display = text.includes(input) ? "" : "none";
    }
}

// SECTION SWITCHING
function showSection(id){
    document.querySelectorAll('.section').forEach(s => s.style.display='none');
    document.querySelectorAll('.sidebar button').forEach(b => b.classList.remove('active-btn'));
    document.getElementById(id).style.display='block';
    document.getElementById('btn-' + id).classList.add('active-btn');
    
    // Stop webcam unless in register section
    if(id === 'register') startWebcam(); else stopWebcam();
    if(id === 'scan') startScanner();
}

let stream = null;
async function startWebcam() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: true });
        document.getElementById('video').srcObject = stream;
    } catch(e) { alert("Camera not accessible"); }
}
function stopWebcam() { if(stream) stream.getTracks().forEach(t => t.stop()); }

document.getElementById('btn-capture')?.addEventListener('click', () => {
    const video = document.getElementById('video');
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth; canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    const data = canvas.toDataURL('image/png');
    document.getElementById('captured_image_input').value = data;
    document.getElementById('captured-preview').src = data;
    document.getElementById('captured-preview').style.display = 'block';
    video.style.display = 'none';
});

function startScanner() {
    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 },
        qrCodeMessage => {
            fetch("validate_qr.php?code=" + encodeURIComponent(qrCodeMessage))
            .then(res => res.text()).then(data => { document.getElementById('scan-result').innerHTML = data; });
        }).catch(e => {});
}

window.onload = () => {
    showSection('members'); // Default section
    const params = new URLSearchParams(window.location.search);
    if(params.has('msg')) {
        const t = document.getElementById('toast');
        const m = params.get('msg');
        t.innerText = m;
        t.style.backgroundColor = m.includes('Error') ? "#e74c3c" : "#27ae60";
        t.style.display='block';
        setTimeout(() => t.style.display='none', 4000);
    }
};
</script>
</body>
</html>