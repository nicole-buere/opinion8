<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - View Discussion</title>
    <link rel="stylesheet" href="../css/discussion.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link rel="stylesheet" href="../css/comment.css">
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

            <!-- Voting Section -->
            <div class="voting-section">
                <h2>Vote on this Discussion</h2>
                <button class="pro-vote-button" data-discussion-id="<?php echo $discussion_id; ?>">Pro</button>
                <button class="anti-vote-button" data-discussion-id="<?php echo $discussion_id; ?>">Anti</button>
                <div id="vote-status">
                    <p>Pro Votes: <span id="pro-vote-count">0</span></p>
                    <p>Anti Votes: <span id="anti-vote-count">0</span></p>
                </div>
            </div>

            <!-- Comment Section -->
            <div class="comments-section">
                <h2>Comments</h2>
                <form id="comment-form">
                    <textarea name="comment" placeholder="Add a comment..." required></textarea>
                    <input type="hidden" name="discussion_id" value="<?php echo $discussion_id; ?>">
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
                        <div class="comment-container" id="comment-<?php echo $comment_id; ?>">
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
    
    <script src="../js/discussion.js"></script>
    <script src="../js/comment.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const proVoteButton = document.querySelector('.pro-vote-button');
        const antiVoteButton = document.querySelector('.anti-vote-button');
        const proVoteCount = document.getElementById('pro-vote-count');
        const antiVoteCount = document.getElementById('anti-vote-count');

        // Load vote counts on page load
        fetch('get_vote_counts.php?discussion_id=' + proVoteButton.getAttribute('data-discussion-id'))
            .then(response => response.json())
            .then(data => {
                proVoteCount.textContent = data.pro_votes;
                antiVoteCount.textContent = data.anti_votes;
            });

        proVoteButton.addEventListener('click', function() {
            handleVote('pro');
        });

        antiVoteButton.addEventListener('click', function() {
            handleVote('anti');
        });

        function handleVote(voteType) {
            const discussionId = proVoteButton.getAttribute('data-discussion-id');

            fetch('vote_discussion.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ discussion_id: discussionId, vote_type: voteType }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    proVoteCount.textContent = data.pro_votes;
                    antiVoteCount.textContent = data.anti_votes;
                    proVoteButton.disabled = (voteType === 'pro');
                    antiVoteButton.disabled = (voteType === 'anti');
                }
            });
        }
    });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            dropdownToggle.addEventListener('click', function(event) {
                event.preventDefault();
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
            });

            document.addEventListener('click', function(event) {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });

            document.querySelectorAll('.view-discussion').forEach(button => {
                button.addEventListener('click', function() {
                    const discussionId = this.getAttribute('data-discussion-id');
                    window.location.href = `view_discussion.php?discussion_id=${discussionId}`;
                });
            });

            document.querySelectorAll('.vote-button').forEach(button => {
                button.addEventListener('click', function() {
                    const pollId = this.getAttribute('data-poll-id');
                    const selectedOption = document.querySelector(`input[name="poll_${pollId}"]:checked`);
                    if (selectedOption) {
                        const optionId = selectedOption.value;
                        // Handle the voting logic here
                        console.log(`Voted for option ID: ${optionId} in poll ID: ${pollId}`);
                    } else {
                        alert("Please select an option to vote.");
                    }
                });
            });
        });
    </script>
</body>
</html>
