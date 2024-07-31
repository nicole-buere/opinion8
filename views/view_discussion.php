<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - View Discussion</title>
    <link rel="stylesheet" href="../css/discussion.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script src="../js/comment.js" defer></script> <!-- Ensure comment.js is linked -->
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
                <a href="../views/index.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="discussion-view">
        <?php
        include('../includes/db.php');

        // Get discussion_id from query string
        $discussion_id = isset($_GET['discussion_id']) ? intval($_GET['discussion_id']) : 0;

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
                
                <!-- Comment Form -->
                <form id="comment-form" method="POST" action="add_comment.php">
                    <textarea name="comment" placeholder="Add a comment..." required></textarea>
                    <input type="hidden" name="discussion_id" value="<?php echo htmlspecialchars($discussion_id); ?>">
                    <button type="submit">Submit Comment</button>
                </form>

                <div id="comments-list">
                    <?php
                    // Fetch comments for this discussion
                    $commentsQuery = "SELECT * FROM comments WHERE discussion_id = ? ORDER BY date_created DESC";
                    $commentsStmt = $conn->prepare($commentsQuery);
                    $commentsStmt->bind_param("i", $discussion_id);
                    $commentsStmt->execute();
                    $commentsResult = $commentsStmt->get_result();

                    if ($commentsResult->num_rows > 0):
                        while ($comment = $commentsResult->fetch_assoc()):
                            $comment_id = $comment['id'];
                            $user_id = $comment['user_id'];
                            $userQuery = "SELECT username FROM userdb WHERE userID = ?";
                            $userStmt = $conn->prepare($userQuery);
                            $userStmt->bind_param("i", $user_id);
                            $userStmt->execute();
                            $user = $userStmt->get_result()->fetch_assoc();
                            $username = $user['username'];
                    ?>
                        <div class="comment" id="comment-<?php echo $comment_id; ?>">
                            <p><strong><?php echo htmlspecialchars($username); ?>:</strong> <?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                            <div class="comment-actions">
                                <button class="reply-button" data-comment-id="<?php echo $comment_id; ?>">Reply</button>
                                <button class="upvote-button" data-comment-id="<?php echo $comment_id; ?>">Upvote</button>
                                <button class="downvote-button" data-comment-id="<?php echo $comment_id; ?>">Downvote</button>
                                <button class="report-button" data-comment-id="<?php echo $comment_id; ?>">Report</button>
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
</body>
</html>
