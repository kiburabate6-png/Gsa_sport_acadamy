<?php
// 1. Database Connection Configuration
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "gsa_db"; // Your database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Process Form Data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Secure the data from users (prevent SQL Injection)
    $name    = $conn->real_escape_string($_POST['name']);
    $email   = $conn->real_escape_string($_POST['email']);
    $message = $conn->real_escape_string($_POST['message']);

    // 3. Insert into the 'contact_messages' table
    $sql = "INSERT INTO contact_messages (name, email, message) 
            VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        
        // 4. Send Email Notification
        // Note: Replace info@gsasports.com with your actual email
        $to      = "info@gsasports.com"; 
        $subject = "New GSA Sport Academy Inquiry from: $name";
        $body    = "Hello GSA Admin,\n\nYou have a new message:\n\n" .
                   "Name: $name\n" .
                   "Email: $email\n" .
                   "Message: $message\n\n" .
                   "Check your database for more details.";
        
        $headers = "From: " . $email;

        // mail() works on live servers. On Localhost it won't send but will save to DB.
        @mail($to, $subject, $body, $headers);

        // 5. Success Message & Redirect back to home
        echo "<script>
                alert('Success! Your message has been saved in our database and sent to our team.');
                window.location.href = 'index.php'; 
              </script>";
              
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>