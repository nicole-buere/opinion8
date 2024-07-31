<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply = isset($_POST['reply']) ? $_POST['reply'] : '';
    $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    if ($reply && $comment_id && $user_id) {
        $replyQuery = "INSERT INTO comment_replies (comment_id, user_id, reply, date_created) VALUES (?, ?, ?, NOW())";
        $replyStmt = $conn->prepare($replyQuery);
        $replyStmt->bind_param("iis", $comment_id, $user_id, $reply);
        if ($replyStmt->execute()) {
            echo "Reply added successfully.";
        } else {
            echo "Failed to add reply.";
        }
    } else {
        echo "Invalid input.";
    }
    $conn->close();
}
?>
