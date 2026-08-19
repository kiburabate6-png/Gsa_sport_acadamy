z<?php
$conn = new mysqli("localhost", "root", "", "gsa_db");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
?>