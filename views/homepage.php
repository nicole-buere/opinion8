<!-- homepage of the site (for user) -->
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Homepage</title>
    <link rel="stylesheet" href="../css/timeline.css">
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
    <div class="header">
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <div class="search-container">
            <div class="search-bar-wrapper">
                <img src="../assets/search icon.png" alt="search-icon" class="search-icon">
                <input type="text" placeholder="Search..." class="search-bar">
                <a href="search.php" class="search-link">
                    <img src="../assets/advance search filter.png" alt="filter-icon" class="filter-icon">
                </a>
            </div>
        </div>
        <img src="../assets/user.png" alt="user-icon" class="icon user-icon">
    </div>


    <div class= "timeline-box">
        <div class="content">
            <?php
            // Fetch debates, comments, and polls from the database
            include('../includes/db.php');

            $query = "SELECT * FROM content ORDER BY date DESC";
            $result = $conn->query($query);

            if ($result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
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
                </div>
            <?php endwhile; else: ?>
                <p>No content available.</p>
            <?php endif; ?>
            <?php $conn->close(); ?>
        </div>
    </div>
</body>
</html>
