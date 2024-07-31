<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - View Discussion</title>
    <link rel="stylesheet" href="../css/discussion.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body>
    <!-- header -->
    <div class="header">
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <a href="../views/homepage.php" class="button primary">Home</a>
        <div class="search-container">
            <div class="search-bar-wrapper">
                <img src="../assets/search icon.png" alt="search icon" class="search-icon">
                <form action="search_discussions.php" method="GET">
                    <input type="text" name="query" placeholder="What are you looking for?" class="search-bar">
                    <button type="submit" class="search-link">
                        <img src="../assets/advance search filter.png" alt="filter-icon" class="filter-icon">
                    </button>
                </form>
            </div>
        </div>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" title="User Menu">
                <img src="../assets/user.png" alt="user-icon" class="icon user-icon">
            </a>
            <div class="dropdown-menu">
                <a href="../views/profile.php">View My Profile</a>
                <a href="../views/user_settings.php">User Settings</a>
                <a href="../views/logout.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="discussion-view">
        <?php
        session_start();
        include('../includes/db.php');

        // Get discussion_id from query string
        $discussion_id = isset($_GET['discussion_id']) ? intval($_GET['discussion_id']) : 0;
        $user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

        // Fetch discussion details
        $discussionQuery = "SELECT * FROM discussion WHERE discussion_id = ?";
        $discussionStmt = $conn->prepare($discussionQuery);
        $discussionStmt->bind_param("i", $discussion_id);
        $discussionStmt->execute();
        $discussion = $discussionStmt->get_result()->fetch_assoc();

        if ($discussion):
        ?>
            <h1><?php echo htmlspecialchars($discussion['title']); ?></h1>
            <img src="<?php echo htmlspecialchars($discussion['thumbnail']); ?>" alt="Thumbnail" width="200" height="200">
            <p><?php echo nl2br(htmlspecialchars($discussion['description'])); ?></p>

            <!-- Comment Section -->
            <div class="comments-section">
                <h2>Comments</h2>
                <form id="comment-form">
                    <textarea name="comment" placeholder="Add a comment..." required></textarea>
                    <button type="submit">Submit Comment</button>
                </form>

                <div id="comments-list">
                    <?php
                    // Fetch comments for this discussion
                    $commentsQuery = "
                        SELECT c.id, c.comment, c.date_created, u.username,
                            (SELECT COUNT(*) FROM comment_votes WHERE comment_id = c.id AND vote_type = 'upvote') AS upvote_count,
                            (SELECT COUNT(*) FROM comment_votes WHERE comment_id = c.id AND vote_type = 'downvote') AS downvote_count,
                            IFNULL((SELECT vote_type FROM comment_votes WHERE comment_id = c.id AND user_id = ? LIMIT 1), '') AS user_vote
                        FROM comments c
                        JOIN userdb u ON c.user_id = u.userID
                        WHERE c.discussion_id = ? ORDER BY c.date_created DESC
                    ";
                    $commentsStmt = $conn->prepare($commentsQuery);
                    $commentsStmt->bind_param("ii", $user_id, $discussion_id);
                    $commentsStmt->execute();
                    $commentsResult = $commentsStmt->get_result();

                    if ($commentsResult->num_rows > 0):
                        while ($comment = $commentsResult->fetch_assoc()):
                            $comment_id = $comment['id'];
                            $username = htmlspecialchars($comment['username']);
                            $comment_text = htmlspecialchars($comment['comment']);
                            $upvote_count = $comment['upvote_count'];
                            $downvote_count = $comment['downvote_count'];
                            $user_vote = $comment['user_vote'];
                    ?>
                        <div class="comment" id="comment-<?php echo $comment_id; ?>">
                            <p><strong><?php echo $username; ?>:</strong> <?php echo nl2br($comment_text); ?></p>
                            <div class="comment-actions">
                                <button class="reply-button" data-comment-id="<?php echo $comment_id; ?>">Reply</button>
                                <button class="upvote-button" data-comment-id="<?php echo $comment_id; ?>" data-vote-type="upvote" <?php echo ($user_vote === 'upvote' ? 'disabled' : ''); ?>>Upvote (<?php echo $upvote_count; ?>)</button>
                                <button class="downvote-button" data-comment-id="<?php echo $comment_id; ?>" data-vote-type="downvote" <?php echo ($user_vote === 'downvote' ? 'disabled' : ''); ?>>Downvote (<?php echo $downvote_count; ?>)</button>
                                <button class="report-button" data-comment-id="<?php echo $comment_id; ?>">Report</button>
                            </div>
                            <div class="reply-form" id="reply-form-<?php echo $comment_id; ?>" style="display:none;">
                                <form class="reply-form-content" data-comment-id="<?php echo $comment_id; ?>">
                                    <textarea name="reply" placeholder="Add a reply..." required></textarea>
                                    <button type="submit">Submit Reply</button>
                                </form>
                            </div>
                            <div class="replies" id="replies-<?php echo $comment_id; ?>">
                                <!-- Replies will be dynamically loaded here -->
                            </div>
                        </div>
                    <?php endwhile; else: ?>
                        <p>No comments yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <p>Discussion not found.</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </div>

    <footer>
        <a href="about_us.php">About Us</a> | 
        <a href="contact_us.php">Contact Us</a> | 
        <a href="privacy_policy.php">Privacy Policy</a>
    </footer>

    <script src="../js/comment.js"></script>
</body>
</html>
