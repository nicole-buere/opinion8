<!-- homepage.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Homepage</title>
    <link rel="stylesheet" href="../css/timeline.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/engagement_analytics.css">
    <link rel="stylesheet" href="../css/footer.css">
</head>
<body>
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

    <div class="timeline-box">
        <div class="content">
            <?php
            include('../includes/db.php');

            $query = "SELECT * FROM content ORDER BY date DESC";
            $result = $conn->query($query);

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $postId = $row['id'];
                    $commentsQuery = "SELECT COUNT(*) AS comments_count FROM comments WHERE post_id = ?";
                    $likesQuery = "SELECT COUNT(*) AS likes_count FROM likes WHERE post_id = ?";
                    $pollsQuery = "SELECT COUNT(*) AS polls_count FROM poll_responses WHERE post_id = ?";

                    $commentsStmt = $conn->prepare($commentsQuery);
                    $likesStmt = $conn->prepare($likesQuery);
                    $pollsStmt = $conn->prepare($pollsQuery);

                    $commentsStmt->bind_param('i', $postId);
                    $likesStmt->bind_param('i', $postId);
                    $pollsStmt->bind_param('i', $postId);

                    $commentsStmt->execute();
                    $likesStmt->execute();
                    $pollsStmt->execute();

                    $commentsResult = $commentsStmt->get_result()->fetch_assoc();
                    $likesResult = $likesStmt->get_result()->fetch_assoc();
                    $pollsResult = $pollsStmt->get_result()->fetch_assoc();

                    $commentsCount = $commentsResult['comments_count'];
                    $likesCount = $likesResult['likes_count'];
                    $pollsCount = $pollsResult['polls_count'];
            ?>
                
                <div class="post">
                    <h3><?php echo htmlspecialchars($row['topic']); ?></h3>
                    <p>Date: <?php echo htmlspecialchars($row['date']); ?></p>
                    <p>Type: <?php echo htmlspecialchars($row['type']); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                    <?php if ($row['type'] === 'poll'): ?>
                        <div class="poll-options">
                            <?php
                            $poll_id = $row['id'];
                            $poll_query = "SELECT * FROM poll_options WHERE poll_id = ?";
                            $poll_stmt = $conn->prepare($poll_query);
                            $poll_stmt->bind_param("i", $poll_id);
                            $poll_stmt->execute();
                            $poll_result = $poll_stmt->get_result();

                            while ($poll_option = $poll_result->fetch_assoc()):
                            ?>
                                <div class="poll-option">
                                    <input type="radio" name="poll_<?php echo $poll_id; ?>" value="<?php echo $poll_option['id']; ?>" id="poll_option_<?php echo $poll_option['id']; ?>">
                                    <label for="poll_option_<?php echo $poll_option['id']; ?>"><?php echo htmlspecialchars($poll_option['option_text']); ?></label>
                                </div>
                            <?php endwhile; ?>
                            <button class="vote-button" data-poll-id="<?php echo $poll_id; ?>">Vote</button>
                        </div>
                    <?php endif; ?>
                    <div class="analytics-section">
                        <h2>Engagement Overview</h2>
                        <div class="analytics-item">
                            <span class="analytics-label">Comments:</span>
                            <span class="analytics-value"><?php echo $commentsCount; ?></span>
                        </div>
                        <div class="analytics-item">
                            <span class="analytics-label">Likes:</span>
                            <span class="analytics-value"><?php echo $likesCount; ?></span>
                        </div>
                        <div class="analytics-item">
                            <span class="analytics-label">Poll Responses:</span>
                            <span class="analytics-value"><?php echo $pollsCount; ?></span>
                        </div>
                    </div>
                    <div class="engagement-actions">
                        <button class="view-discussion" data-discussion-id="<?php echo $postId; ?>">View Discussion</button>
                    </div>
                </div>
            <?php endwhile; ?>
            <?php endif; ?>

            <h2>Discussion Topics</h2>
            <?php
            $discussionQuery = "SELECT discussion_id, thumbnail, title, description, date_created FROM discussion";
            $discussionResult = $conn->query($discussionQuery);

            if ($discussionResult->num_rows > 0):
                while ($discussion = $discussionResult->fetch_assoc()):
            ?>
                <div class="discussion">
                    <h3><?php echo htmlspecialchars($discussion['title']); ?></h3>
                    <img src="<?php echo htmlspecialchars($discussion['thumbnail']); ?>" alt="Thumbnail" width="100" height="100">
                    <p><?php echo htmlspecialchars($discussion['description']); ?></p>
                    <p><em>Created on: <?php echo htmlspecialchars($discussion['date_created']); ?></em></p>
                    <button class="view-discussion" data-discussion-id="<?php echo $discussion['discussion_id']; ?>">View Discussion</button>
                </div>
            <?php endwhile; else: ?>
                <p>No discussion topics available.</p>
            <?php endif; ?>

            <?php $conn->close(); ?>
        </div>
    </div>

    <footer>
        <a href="about_us.php">About Us</a> | 
        <a href="contact_us.php">Contact Us</a> | 
        <a href="privacy_policy.php">Privacy Policy</a>
    </footer>

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
