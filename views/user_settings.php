<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

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

$homeUrl = ($role === 'Admin') ? '../views/admin_homepage.php' : '../views/homepage.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" href="../css/user_settings.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter">
</head>
<body>
    <!-- header -->
    <div class="header">
        <!-- logo -->
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <!-- home button -->
        <a href="<?php echo htmlspecialchars($homeUrl); ?>" class="button primary">Home</a>

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
        <div class="heading">
            <h1>Edit your profile</h1>
            <p>Customize your profile to personalize how other users see you across the site.</p>
        </div>

        <form id="user-settings-form" method="POST" action="../actions/user_settings_action.php">
            <div class="info">
                <div class="pfp-edit">
                    <div class="profile-picture-wrapper">
                        <img src="<?php echo htmlspecialchars($profilePicture) ?: '../assets/user.png'; ?>" alt="Profile Picture" id="profile-picture-preview" class="profile-picture">
                    </div>
                    <button type="button" class="change-picture-button" onclick="openChangePictureModal()">Change Profile Picture</button>
                </div>

                <div class="acc-info">
                    <!-- username -->
                    <div class="form-group">
                        <div class="label-and-edit">
                            <label for="username">Username</label>
                            <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="enableEditing('username')">
                        </div>
                        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    </div>
                    <!-- email -->
                    <div class="form-group">
                        <div class="label-and-edit">
                            <label for="email">Email</label>
                            <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="enableEditing('email')">
                        </div>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                    </div>
                    <!-- bio -->
                    <div class="form-group">
                        <div class="label-and-edit">
                            <label for="bio">Bio</label>
                            <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="enableEditing('bio')">
                        </div>
                        <textarea id="bio" name="bio"><?php echo htmlspecialchars($bio); ?></textarea>
                    </div>
                    <!-- interests -->
                    <div class="form-group">
                        <div class="label-and-edit">
                            <label for="interests">Interests</label>
                            <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="enableEditing('interests')">
                        </div>
                        <textarea id="interests" name="interests"><?php echo htmlspecialchars($interests); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="button-container">
                <button type="submit" class="save-changes-button">Save Changes</button>
                <button type="button" class="view-profile-button" onclick="window.location.href='profile.php'">View My Profile</button>
            </div>
        </form>

        <!-- Change Profile Picture Modal -->
        <div id="change-picture-modal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeChangePictureModal()">&times;</span>
                <h2>Change Profile Picture</h2>
                <form id="change-picture-form">
                    <div class="form-group">
                        <label for="profile-picture-url">Profile Picture URL</label>
                        <input type="url" id="profile-picture-url" name="profile_picture_url" placeholder="Enter the image URL" value="<?php echo htmlspecialchars($profilePicture); ?>">
                    </div>
                    <div class="modal-buttons">
                        <button type="button" class="cancel-button" onclick="closeChangePictureModal()">Cancel</button>
                        <button type="button" onclick="updateProfilePicture()">Change Picture</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function enableEditing(id) {
            document.getElementById(id).removeAttribute('disabled');
            document.getElementById(id).focus();
        }

        function openChangePictureModal() {
            document.getElementById('change-picture-modal').style.display = 'flex';
        }

        function closeChangePictureModal() {
            document.getElementById('change-picture-modal').style.display = 'none';
        }

        function updateProfilePicture() {
            const url = document.getElementById('profile-picture-url').value;
            document.getElementById('profile-picture-preview').src = url;
            closeChangePictureModal();
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
        // script for user drop down menu
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
                
            // Toggle dropdown menu on click
            dropdownToggle.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
                console.log('Dropdown menu visibility:', dropdownMenu.style.display); // Debugging
            });

            // Close dropdown menu if clicking outside of it
            document.addEventListener('click', function(event) {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
