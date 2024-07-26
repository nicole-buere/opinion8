<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Homepage</title>
    <link rel="stylesheet" href="../css/timeline.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/engagement_analytics.css">
    <link rel="stylesheet" href="../css/footer.css"> <!-- Add this line for footer styles -->
</head>
<body>
    <div class="header">
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <a href="homepage.php" class="home-link">Home</a>
        <div class="search-container">
            <div class="search-bar-wrapper">
                <img src="../assets/search icon.png" alt="search icon" class="search-icon">
                <input type="text" placeholder="Search..." class="search-bar">
                <a href="search.php" class="search-link">
                    <img src="../assets/advance search filter.png" alt="filter-icon" class="filter-icon">
                </a>
            </div>
        </div>
        <img src="../assets/user.png" alt="user-icon" class="icon user-icon">
    </div>

    <div class="timeline-box">
        <div class="content">
            <?php
            // Fetch debates, comments, and polls from the database
            include('../includes/db.php');

            $query = "SELECT * FROM content ORDER BY date DESC";
            $result = $conn->query($query);

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
                    $postId = $row['id'];
    
                    // Fetch the number of comments, likes, and poll engagements
                    // Added PHP Code to Fetch Engagement Data
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
                        <!-- Display poll options -->
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
                    <!-- Added HTML to Display Engagement Analytics -->
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
                </div>
            <?php endwhile; else: ?>
                <p>No content available.</p>
            <?php endif; ?>
            <?php $conn->close(); ?>
        </div>
    </div>

    <footer>
        <a href="about_us.php">About Us</a> | 
        <a href="contact_us.php">Contact Us</a> | 
        <a href="privacy_policy.php">Privacy Policy</a>
    </footer>
</body>
</html>
