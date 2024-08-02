<?php
include '../includes/db.php';

$searchTerm = isset($_GET['query']) ? $_GET['query'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Results - Opinion8</title>
    <link rel="stylesheet" href="../css/timeline.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/engagement_analytics.css">
    <link rel="stylesheet" href="../css/footer.css">
    <style>
        .discussion-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .discussion {
            background-color: #fff;
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            display: flex;
            gap: 20px;
        }
        .discussion img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
        }
        .discussion-content {
            flex: 1;
        }
        .discussion h3 {
            font-size: 18px;
            margin: 0;
        }
        .discussion p {
            margin: 10px 0;
        }
        .discussion-date {
            color: #777;
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
                    <input type="text" name="query" placeholder="What are you looking for?" class="search-bar" value="<?php echo htmlspecialchars($searchTerm); ?>">
                    <button type="submit" class="search-link">
                        <img src="../assets/advance_search_filter.png" alt="filter-icon" class="filter-icon">
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
        <h2>Search Results</h2>
        <div class="discussion-container">
            <?php
            if ($searchTerm) {
                $searchTerm = "%" . $conn->real_escape_string($searchTerm) . "%";
                $sql = "SELECT discussion_id, thumbnail, title, description, date_created FROM discussion WHERE title LIKE ? OR description LIKE ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ss', $searchTerm, $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0):
                    while ($discussion = $result->fetch_assoc()):
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
                <p>No discussions found for "<?php echo htmlspecialchars($searchTerm); ?>".</p>
            <?php endif;
                $stmt->close();
            } else {
                echo '<p>Please enter a search term.</p>';
            }
            $conn->close();
            ?>
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
        });
    </script>
</body>
</html>
