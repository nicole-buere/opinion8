<?php
// includes/db.php

$servername = "localhost";
<<<<<<< Updated upstream
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
=======
$username = "root"; 
$password = ""; // no password by default
>>>>>>> Stashed changes
$dbname = "opinion8_db"; // The database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

