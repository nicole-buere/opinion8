<?php
include '../includes/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $userId = $_SESSION['user_id'];

    switch ($action) {
        case 'save_edit':
            $field = $_POST['field'];
            $newValue = $_POST['new_value'];
            $query = "UPDATE userdb SET $field = ? WHERE userID = ?";
            $stmt = $conn->prepare($query);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param('si', $newValue, $userId);
            $stmt->execute();
            echo json_encode(['status' => 'success']);
            break;

        case 'change_password':
            $newPassword = password_hash($_POST['new_password'], PASSWORD_BCRYPT);
            $query = "UPDATE userdb SET password = ? WHERE userID = ?";
            $stmt = $conn->prepare($query);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param('si', $newPassword, $userId);
            $stmt->execute();
            echo json_encode(['status' => 'success']);
            break;

        case 'save_profile_picture':
            if (isset($_FILES['profile_picture'])) {
                $profilePicture = $_FILES['profile_picture'];
                $targetDir = "../uploads/";
                $targetFile = $targetDir . basename($profilePicture['name']);
                if (move_uploaded_file($profilePicture['tmp_name'], $targetFile)) {
                    $query = "UPDATE userdb SET profile_picture = ? WHERE userID = ?";
                    $stmt = $conn->prepare($query);
                    if ($stmt === false) {
                        die('Prepare failed: ' . htmlspecialchars($conn->error));
                    }
                    $stmt->bind_param('si', $targetFile, $userId);
                    $stmt->execute();
                    echo json_encode(['status' => 'success']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to upload image.']);
                }
            }
            break;

        // Add more cases as needed
    }
}
?>
