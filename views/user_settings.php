<?php
session_start();
include '../includes/db.php'; // Ensure the correct path

$userId = $_SESSION['user_id'];
$query = "SELECT username, role, email, bio, interests FROM userdb WHERE userID = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($username, $role, $email, $bio, $interests);
$stmt->fetch();
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
    <div class="header">
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <a href="admin_homepage.php" class="home-link">Home</a>
        <div class="search-container">
            <div class="search-bar-wrapper">
                <img src="../assets/search icon.png" alt="search-icon" class="search-icon">
                <input type="text" placeholder="Search..." class="search-bar">
                <a href="search.php" class="search-link">
                    <img src="../assets/advance search filter.png" alt="filter-icon" class="filter-icon">
                </a>
            </div>
        </div>
        <a href="user_settings.php" title="User Settings">
            <img src="../assets/user.png" alt="user-icon" class="icon user-icon">
        </a>
    </div>

    <div class="settings-container">
        <h1>User Settings</h1>
        <form id="user-settings-form" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile-picture">Profile Picture</label>
                <div class="profile-picture-wrapper">
                    <img src="../assets/user.png" alt="Profile Picture" id="profile-picture-preview" class="profile-picture">
                    <div class="change-picture-overlay">
                        <button type="button" class="change-picture-button" onclick="document.getElementById('profile-picture').click()">Change Picture</button>
                        <input type="file" id="profile-picture" name="profile_picture" accept="image/*" style="display:none;">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" disabled>
                <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="openEditModal('username', '<?php echo htmlspecialchars($username); ?>')">
            </div>
            <div class="form-group">
                <label for="role">User Role</label>
                <input type="text" id="role" name="role" value="<?php echo htmlspecialchars($role); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" disabled>
                <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="openEditModal('email', '<?php echo htmlspecialchars($email); ?>')">
            </div>
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" disabled><?php echo htmlspecialchars($bio); ?></textarea>
                <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="openEditModal('bio', '<?php echo htmlspecialchars($bio); ?>')">
            </div>
            <div class="form-group">
                <label for="interests">Interests</label>
                <textarea id="interests" name="interests" disabled><?php echo htmlspecialchars($interests); ?></textarea>
                <img src="../assets/pen.png" alt="Edit" class="edit-icon" onclick="openEditModal('interests', '<?php echo htmlspecialchars($interests); ?>')">
            </div>
            <button type="submit" class="save-changes-button">Save Changes</button>
        </form>
        <button type="button" class="change-password-button" onclick="openChangePassword()">Change Password</button>
    </div>

    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit <span id="edit-field-name"></span></h2>
            <form id="edit-form">
                <input type="hidden" id="field" name="field">
                <input type="text" id="new-value" name="new_value">
                <div class="modal-buttons">
                    <button type="button" class="cancel-button" onclick="closeEditModal()">Cancel</button>
                    <button type="button" onclick="saveEdit()">Save</button>
                </div>
            </form>
        </div>
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
        document.getElementById('profile-picture').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profile-picture-preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        });

        function openEditModal(field, value) {
            document.getElementById('field').value = field;
            document.getElementById('new-value').value = value;
            document.getElementById('edit-field-name').textContent = field;
            document.getElementById('edit-modal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        function saveEdit() {
            var formData = new FormData(document.getElementById('edit-form'));
            formData.append('action', 'save_edit');

            fetch('user_settings_action.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json())
              .then(data => {
                  if (data.status === 'success') {
                      location.reload();
                  }
              });
        }

        function openChangePassword() {
            document.getElementById('change-password-modal').style.display = 'flex';
        }

        function closeChangePassword() {
            document.getElementById('change-password-modal').style.display = 'none';
        }

        function changePassword() {
            var formData = new FormData(document.getElementById('change-password-form'));
            formData.append('action', 'change_password');

            fetch('user_settings_action.php', {
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
    </script>
</body>
</html>
