<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $discussion_id = isset($_GET['discussion_id']) ? intval($_GET['discussion_id']) : 0;

    // Fetch comments for this discussion
    $commentsQuery = "
        SELECT c.id, c.comment, c.date_created, u.username,
            (SELECT COUNT(*) FROM comment_votes WHERE comment_id = c.id AND vote_type = 'upvote') AS upvote_count,
            (SELECT COUNT(*) FROM comment_votes WHERE comment_id = c.id AND vote_type = 'downvote') AS downvote_count
        FROM comments c
        JOIN userdb u ON c.user_id = u.userID
        WHERE c.discussion_id = ? ORDER BY c.date_created DESC
    ";
    $commentsStmt = $conn->prepare($commentsQuery);
    $commentsStmt->bind_param("i", $discussion_id);
    $commentsStmt->execute();
    $commentsResult = $commentsStmt->get_result();

    $comments = [];
    while ($comment = $commentsResult->fetch_assoc()) {
        $comment_id = $comment['id'];
        
        // Fetch replies for this comment
        $repliesQuery = "
            SELECT cr.id, cr.reply, cr.date_created, u.username
            FROM comment_replies cr
            JOIN userdb u ON cr.user_id = u.userID
            WHERE cr.comment_id = ?
        ";
        $repliesStmt = $conn->prepare($repliesQuery);
        $repliesStmt->bind_param("i", $comment_id);
        $repliesStmt->execute();
        $repliesResult = $repliesStmt->get_result();

        $replies = [];
        while ($reply = $repliesResult->fetch_assoc()) {
            $replies[] = $reply;
        }

        $comment['replies'] = $replies;
        $comments[] = $comment;
    }

    echo json_encode(['comments' => $comments]);
    $conn->close();
}
?>
