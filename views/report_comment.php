<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['comment_id'];
    $reason = $_POST['reason'];
    $user_id = 1; // Example user ID, replace with actual session user ID

    $stmt = $conn->prepare("INSERT INTO comment_reports (comment_id, user_id, reason) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $comment_id, $user_id, $reason);
    if ($stmt->execute()) {
        echo "Comment reported successfully.";
    } else {
        echo "Error reporting comment.";
    }
    $stmt->close();
}
$conn->close();
?>
