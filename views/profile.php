<?php
session_start();
include '../includes/db.php';

$userId = $_SESSION['user_id'];
$query = "SELECT username, role, email, bio, interests, profile_picture FROM userdb WHERE userID = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($username, $role, $email, $bio, $interests, $profilePicture);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opinion8 - Profile Page</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
    <!-- header -->
    <div class="header">
        <!-- logo -->
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <!-- home button -->
        <a href="<?php echo htmlspecialchars($role === 'admin' ? '../views/admin_homepage.php' : '../views/homepage.php'); ?>" class="button primary">Home</a>

        <!-- search bar -->
        <div class="search-container">
            <div class="search-bar-wrapper">
                <img src="../assets/search icon.png" alt="search icon" class="search-icon">
                <input type="text" placeholder="What are you looking for?" class="search-bar">
                <a href="search.php" class="search-link">
                    <img src="../assets/advance search filter.png" alt="filter-icon" class="filter-icon">
                </a>
            </div>
        </div>
        <!-- dropdown menu in profile button  -->
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" title="User Menu">
                <img src="../assets/user.png" alt="user-icon" class="icon user-icon">
            </a>
            <div class="dropdown-menu">
                <a href="../views/profile.php">View My Profile</a>
                <a href="../views/user_settings.php">User Settings</a>
                <a href="../views/index.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-picture-wrapper">
            <img src="<?php echo htmlspecialchars($profilePicture) ?: '../assets/user.png'; ?>" alt="Profile Picture" class="profile-picture">
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="role">User role: Ex: admin</label>
            <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($role); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
        </div>
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" disabled><?php echo htmlspecialchars($bio); ?></textarea>
        </div>
        <div class="form-group">
            <label for="interests">Interests</label>
            <textarea id="interests" name="interests" disabled><?php echo htmlspecialchars($interests); ?></textarea>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            dropdownToggle.addEventListener('click', function(event) {
                event.preventDefault(); 
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
            });

            document.addEventListener('click', function(event) {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
