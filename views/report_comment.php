<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    $comment_id = $_POST['comment_id'];
    $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
    $user_id = $_SESSION['user_id']; // Retrieve the actual logged-in user's ID

    if (!empty($reason) && $user_id) {
        // Insert report into the database
        $stmt = $conn->prepare("INSERT INTO comment_reports (comment_id, user_id, reason) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $comment_id, $user_id, $reason);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Error reporting comment."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Invalid input or user not logged in."]);
    }

    $conn->close();
}
?>
