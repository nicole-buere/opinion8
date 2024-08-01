<?php
include('../includes/db.php');
session_start(); // Ensure session is started to access session variables

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : '';
    $discussion_id = isset($_POST['discussion_id']) ? intval($_POST['discussion_id']) : 0;
    $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0; // Fetch actual user ID from session

    if (!empty($comment) && $discussion_id > 0 && $user_id > 0) {
        // Insert comment into the database
        $insertQuery = "INSERT INTO comments (discussion_id, user_id, comment) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("iis", $discussion_id, $user_id, $comment);

        if ($stmt->execute()) {
            // Fetch the new comment
            $comment_id = $stmt->insert_id;
            $commentQuery = "
                SELECT c.id, c.comment, c.date_created, u.username,
                    (SELECT COUNT(*) FROM comment_votes WHERE comment_id = c.id AND vote_type = 'upvote') AS upvote_count,
                    (SELECT COUNT(*) FROM comment_votes WHERE comment_id = c.id AND vote_type = 'downvote') AS downvote_count
                FROM comments c
                JOIN userdb u ON c.user_id = u.userID
                WHERE c.id = ?
            ";
            $commentStmt = $conn->prepare($commentQuery);
            $commentStmt->bind_param("i", $comment_id);
            $commentStmt->execute();
            $comment = $commentStmt->get_result()->fetch_assoc();

            // Fetch replies for the comment if needed
            $repliesQuery = "
                SELECT r.id, r.reply, u.username
                FROM comment_replies r
                JOIN userdb u ON r.user_id = u.userID
                WHERE r.comment_id = ?
            ";
            $repliesStmt = $conn->prepare($repliesQuery);
            $repliesStmt->bind_param("i", $comment_id);
            $repliesStmt->execute();
            $repliesResult = $repliesStmt->get_result();
            $replies = $repliesResult->fetch_all(MYSQLI_ASSOC);
            $comment['replies'] = $replies;

            echo json_encode($comment);
        } else {
            echo json_encode(["error" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Invalid input or user not logged in."]);
    }

    $conn->close();
}
?>
