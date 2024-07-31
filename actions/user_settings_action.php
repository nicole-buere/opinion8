<?php
session_start();
include '../includes/db.php';

$userId = $_SESSION['user_id'];

$profile_picture_url = $_POST['profile_picture_url'] ?? null;
$username = $_POST['username'] ?? null;
$email = $_POST['email'] ?? null;
$bio = $_POST['bio'] ?? null;
$interests = $_POST['interests'] ?? null;
$new_password = $_POST['new_password'] ?? null;

$query = "SELECT profile_picture, username, email, bio, interests, password FROM userdb WHERE userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($existing_profile_picture, $existing_username, $existing_email, $existing_bio, $existing_interests, $existing_password);
$stmt->fetch();
$stmt->close();

$profile_picture_url = $profile_picture_url ?: $existing_profile_picture;
$username = $username ?: $existing_username;
$email = $email ?: $existing_email;
$bio = $bio ?: $existing_bio;
$interests = $interests ?: $existing_interests;

if ($new_password) {
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
} else {
    $hashed_password = $existing_password;
}

$query = "UPDATE userdb SET profile_picture = ?, username = ?, email = ?, bio = ?, interests = ?, password = ? WHERE userID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ssssssi', $profile_picture_url, $username, $email, $bio, $interests, $hashed_password, $userId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
$stmt->close();
$conn->close();
?>
 
