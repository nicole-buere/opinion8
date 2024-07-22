<?php
// actions/register_action.php

include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Input validation
    if (empty($email) || empty($username) || empty($password) || empty($confirm_password) || empty($role)) {
        header("Location: ../views/register.php?error=All fields are required.");
        exit();
    }

    if ($password !== $confirm_password) {
        header("Location: ../views/register.php?error=Passwords do not match.");
        exit();
    }

    // Ensure the password length is not more than 14 characters
    if (strlen($password) > 14) {
        header("Location: ../views/register.php?error=Password should not exceed 14 characters.");
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Check if the username or email already exists
    $stmt = $conn->prepare("SELECT * FROM userdb WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Username or email already exists
        header("Location: ../views/register.php?error=Username or email already exists. Please choose another.");
        exit();
    } else {
        // Insert new user into the database
        $stmt = $conn->prepare("INSERT INTO userdb (email, username, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $email, $username, $hashed_password, $role);
        
        if ($stmt->execute()) {
            // Registration successful, redirect to login page
            header("Location: ../views/index.php");
            exit();
        } else {
            header("Location: ../views/register.php?error=Error: " . $stmt->error);
            exit();
        }
    }

    $stmt->close();
}

$conn->close();
?>

