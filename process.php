<?php
include 'db.php';

// --- A. ADD SPORT SCHEDULE (The part you are missing) ---
if (isset($_POST['add_schedule'])) {
    $activity = mysqli_real_escape_string($conn, $_POST['activity']);
    $date = $_POST['sport_date'];
    $time = $_POST['sport_time'];
    $venue = mysqli_real_escape_string($conn, $_POST['venue']);

    $sql = "INSERT INTO schedules (activity, sport_date, sport_time, venue) 
            VALUES ('$activity', '$date', '$time', '$venue')";
    
    if ($conn->query($sql)) {
        header("Location: dashboard.php?msg=Schedule Created Successfully!");
    } else {
        // This will help you see if there is a database error
        die("Error: " . $conn->error); 
    }
}

// --- B. ADMIN REGISTER MEMBER ---
if (isset($_POST['add_sportsman'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $expiry = $_POST['expiry_date']; 

    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        header("Location: dashboard.php?error=user_exists");
        exit();
    }

    $sql = "INSERT INTO users (fullname, username, password, membership_expiry) VALUES ('$fullname', '$username', '$password', '$expiry')";
    if ($conn->query($sql)) {
        header("Location: dashboard.php?msg=Member Registered!");
    }
}

// --- C. SELF REGISTRATION (From Home/Register Page) ---
if (isset($_POST['self_register'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $expiry_date = date('Y-m-d H:i:s', strtotime('+30 days'));

    $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
    if ($check->num_rows > 0) {
        header("Location: register.php?error=user_exists");
        exit();
    }

    $sql = "INSERT INTO users (fullname, username, password, membership_expiry) VALUES ('$fullname', '$username', '$password', '$expiry_date')";
    if ($conn->query($sql)) {
        header("Location: register.php?msg=Successfully Registered!");
    }
}

// --- D. DELETE MEMBER ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $conn->query("DELETE FROM users WHERE id = $id");
    header("Location: dashboard.php?msg=Member Removed");
}

// --- E. DELETE SCHEDULE ---
if (isset($_GET['delete_schedule'])) {
    $id = $_GET['delete_schedule'];
    $conn->query("DELETE FROM schedules WHERE id = $id");
    header("Location: dashboard.php?msg=Schedule Deleted");
}
?>