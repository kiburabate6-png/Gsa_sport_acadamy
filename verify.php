<?php
include 'db_config.php';

if (isset($_GET['tx_ref'])) {
    $tx_ref = $conn->real_escape_string($_GET['tx_ref']);
    $method = isset($_GET['method']) ? $_GET['method'] : 'Unknown';

    // Update user to 'paid'
    $sql = "UPDATE users SET payment_status = 'paid' WHERE tx_ref = '$tx_ref'";

    if ($conn->query($sql)) {
        echo "<script>
            alert('Payment via $method Successful! Welcome to GSA Academy.');
            window.location.href = 'login.php';
        </script>";
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    header("Location: register.php");
}
?>