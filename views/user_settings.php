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

$homeUrl = ($role === 'admin') ? '../views/admin_homepage.php' : '../views/homepage.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" href="../css/user_settings.css">
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
    <!-- header -->
    <div class="header">
        <!-- logo -->
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <!-- home button -->
        <a href="<?php echo htmlspecialchars($homeUrl); ?>" class="button primary">Home</a>
 
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

    <div class="settings-container">
        <h1>User Settings</h1>
        <div id="message" class="message" style="display:none;"></div>
        <form id="user-settings-form" method="POST" action="../actions/user_settings_action.php">
            <input type="hidden" name="action" value="save_changes">
            <div class="form-group">
                <label for="profile-picture-url">Profile Picture URL</label>
                <div class="profile-picture-wrapper">
                    <img src="<?php echo htmlspecialchars($profilePicture) ?: '../assets/user.png'; ?>" alt="Profile Picture" id="profile-picture-preview" class="profile-picture">
                    <div class="change-picture-overlay">
                        <span class="change-picture-button">Change Picture</span>
                    </div>
                </div>
                <input type="url" id="profile-picture-url" name="profile_picture_url" placeholder="Enter the image URL" value="<?php echo htmlspecialchars($profilePicture); ?>" disabled>
                <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="enableEditing('profile-picture-url')">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" disabled>
                <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="enableEditing('username')">
            </div>
            <div class="form-group">
                <label for="role">User Role</label>
                <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($role); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
                <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="enableEditing('email')">
            </div>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" disabled><?php echo htmlspecialchars($bio); ?></textarea>
                <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="enableEditing('bio')">
            </div>
            <div class="form-group">
                <label for="interests">Interests</label>
                <textarea id="interests" name="interests" disabled><?php echo htmlspecialchars($interests); ?></textarea>
                <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="enableEditing('interests')">
            </div>
            <button type="submit" class="save-changes-button">Save Changes</button>
        </form>
        <form action="profile.php">
            <button type="submit" class="view-profile-button">View My Profile</button>
        </form>
        <button type="button" class="change-password-button" onclick="openChangePassword()">Change Password</button>
    </div>

    <div id="change-password-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeChangePassword()">&times;</span>
            <h2>Change Password</h2>
            <form id="change-password-form">
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <input type="password" id="new-password" name="new_password">
                </div>
                <div class="modal-buttons">
                    <button type="button" class="cancel-button" onclick="closeChangePassword()">Cancel</button>
                    <button type="button" onclick="changePassword()">Change Password</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('profile-picture-url').addEventListener('input', function(event) {
            const url = event.target.value;
            document.getElementById('profile-picture-preview').src = url;
        });

        function enableEditing(id) {
            document.getElementById(id).removeAttribute('disabled');
            document.getElementById(id).focus();
        }

        function openChangePassword() {
            document.getElementById('change-password-modal').style.display = 'flex';
        }

        function closeChangePassword() {
            document.getElementById('change-password-modal').style.display = 'none';
        }

        document.getElementById('user-settings-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(document.getElementById('user-settings-form'));

            fetch('../actions/user_settings_action.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.status === 'success') {
                      document.getElementById('message').innerText = 'Changes saved successfully.';
                      document.getElementById('message').style.display = 'block';
                      setTimeout(function() {
                          location.reload();
                      }, 2000);
                  } else {
                      document.getElementById('message').innerText = 'Failed to save changes.';
                      document.getElementById('message').style.display = 'block';
                  }
              });
        });

        function changePassword() {
            var formData = new FormData(document.getElementById('change-password-form'));
            formData.append('action', 'change_password');

            fetch('../actions/user_settings_action.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.status === 'success') {
                      alert('Password changed successfully.');
                      closeChangePassword();
                  }
              });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            dropdownToggle.addEventListener('click', function(event) {
                event.preventDefault();
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
                console.log('Dropdown menu visibility:', dropdownMenu.style.display);
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
