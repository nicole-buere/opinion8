<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Homepage</title>
    <link rel="stylesheet" href="../css/timeline.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/engagement_analytics.css">
    <link rel="stylesheet" href="../css/footer.css">
    <style>
        .discussion-container {
            display: flex;
            flex-direction: column;
            gap: 20px; /* Increased gap between discussion boxes */
        }
        .discussion {
            border: 1px solid #ddd;
            border-radius: 10px; /* Slightly more rounded corners */
            padding: 20px; /* Increased padding for more space inside the box */
            background-color: #f9f9f9;
            display: flex;
            gap: 20px; /* Increased gap between thumbnail and content */
        }
        .discussion img {
            width: 150px; /* Increased width */
            height: 150px; /* Increased height */
            object-fit: cover;
            border-radius: 10px; /* More rounded thumbnail corners */
        }
        .discussion-content {
            flex: 1;
        }
        .discussion h3 {
            font-size: 24px; /* Larger font size for the title */
            margin: 0;
            color: #333;
        }
        .discussion p {
            margin: 10px 0;
            color: #555;
            font-size: 16px; /* Larger font size for the description */
        }
        .discussion-date {
            color: #777;
            font-size: 16px; /* Consistent font size with the description */
        }
        .view-discussion {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px; /* Slightly increased padding */
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 16px; /* Larger font size for the button */
        }
        .view-discussion:hover {
            background-color: #0056b3;
        }
    </style>
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
    <link rel="stylesheet" href="../css/user_home.css">

<div class="content-box">
    <div class="greeting-box">
        <div class="greet-text">
            <h1>Welcome!</h1>
            <p>Dive into a world of ideas at Opinion8. Share your voice, spark a debate.
                <br>Connect, share, and grow together!</p>
        </div>
        <div class="art">
            <img src="../assets/greet-art.png" alt="greeting-art">
        </div>
    </div>

    <div class="timeline-box">
        
        <div class="content">
            <!-- Other content like posts can be fetched and displayed here -->

            <h2>Discussion Topics</h2>
            <div class="discussion-container">
                <?php
                include('../includes/db.php');

                $discussionQuery = "SELECT discussion_id, thumbnail, title, description, date_created FROM discussion";
                $discussionResult = $conn->query($discussionQuery);

                if ($discussionResult->num_rows > 0):
                    while ($discussion = $discussionResult->fetch_assoc()):
                ?>
                    <div class="discussion">
                        <img src="<?php echo htmlspecialchars($discussion['thumbnail']); ?>" alt="Thumbnail">
                        <div class="discussion-content">
                            <h3><?php echo htmlspecialchars($discussion['title']); ?></h3>
                            <p><?php echo htmlspecialchars($discussion['description']); ?></p>
                            <p class="discussion-date"><em>Created on: <?php echo htmlspecialchars($discussion['date_created']); ?></em></p>
                            <a href="view_discussion.php?discussion_id=<?php echo $discussion['discussion_id']; ?>" class="view-discussion">View Discussion</a>
                        </div>
                    </div>
                <?php endwhile; else: ?>
                    <p>No discussion topics available.</p>
                <?php endif; ?>

                <?php $conn->close(); ?>
            </div>
        </div>
    </div>

    <footer>
        <a href="about_us.php">About Us</a> | 
        
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
        });
    </script>
</body>
</html>
