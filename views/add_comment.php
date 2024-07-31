<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $discussion_id = isset($_POST['discussion_id']) ? intval($_POST['discussion_id']) : 0;

    if (!empty($comment) && $discussion_id > 0) {
        $user_id = 1; // For testing, set a default user_id (You should replace this with the actual logged-in user ID)

        // Insert comment into the database
        $insertQuery = "INSERT INTO comments (discussion_id, user_id, comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iis", $discussion_id, $user_id, $comment);

        if ($stmt->execute()) {
            echo "Comment added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid input.";
    }

    $conn->close();
}
?>
