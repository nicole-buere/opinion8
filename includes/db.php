<?php
// includes/db.php

$servername = "localhost";
$username = "root";
$password = ""; // Assuming default XAMPP setup with no password
$dbname = "opinion8";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
