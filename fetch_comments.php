<?php
include('../includes/db.php');

$discussion_id = isset($_GET['discussion_id']) ? intval($_GET['discussion_id']) : 0;

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

if ($commentsResult->num_rows > 0):
    while ($comment = $commentsResult->fetch_assoc()):
        $comment_id = $comment['id'];
        $username = htmlspecialchars($comment['username']);
        $comment_text = htmlspecialchars($comment['comment']);
        $upvote_count = $comment['upvote_count'];
        $downvote_count = $comment['downvote_count'];
?>
    <div class="comment">
        <p><strong><?php echo $username; ?>:</strong> <?php echo nl2br($comment_text); ?></p>
        <div class="comment-actions">
            <button class="reply-button" data-comment-id="<?php echo $comment_id; ?>">Reply</button>
            <button class="upvote-button" data-comment-id="<?php echo $comment_id; ?>">Upvote (<?php echo $upvote_count; ?>)</button>
            <button class="downvote-button" data-comment-id="<?php echo $comment_id; ?>">Downvote (<?php echo $downvote_count; ?>)</button>
            <button class="report-button" data-comment-id="<?php echo $comment_id; ?>">Report</button>
        </div>
        <div class="replies" id="replies-<?php echo $comment_id; ?>">
            <!-- Replies will be dynamically loaded here -->
        </div>
    </div>
<?php endwhile; else: ?>
    <p>No comments yet.</p>
<?php endif; ?>

<?php
$commentsStmt->close();
$conn->close();
?>
