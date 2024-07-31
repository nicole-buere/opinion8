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

            echo json_encode($comment);
        } else {
            echo json_encode(["error" => "Error: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Invalid input."]);
    }

    $conn->close();
}
?>
