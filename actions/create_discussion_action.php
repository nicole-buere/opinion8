<?php
session_start();
include('../includes/db.php');

// Check if the user is logged in and has the 'Admin' role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../views/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate user inputs
    $thumbnail = filter_var($_POST['thumbnail'], FILTER_SANITIZE_URL);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);

    // Ensure that the required fields are provided
    if (empty($thumbnail) || empty($title) || empty($description)) {
        echo "All fields are required.";
        exit();
    }

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO discussion (thumbnail, title, description, date_created) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $thumbnail, $title, $description);

    if ($stmt->execute()) {
        // Redirect to the admin homepage or another page after successful insertion
        header("Location: ../views/admin_homepage.php");
        exit();
    } else {
        echo "Error: Could not create the discussion.";
    }

    $stmt->close();
    $conn->close();
}
?>