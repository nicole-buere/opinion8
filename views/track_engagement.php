<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Discussions</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/engagement_analytics.css">
</head>
<body>
    <!-- header -->
    <div class="header">
        <!-- logo -->
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <!-- home button -->
        <a href="admin_homepage.php" class="button primary">Home</a>
        <!-- search bar -->
        <div class="search-container">
            <div class="search-bar-wrapper">
                <img src="../assets/search icon.png" alt="search icon" class="search-icon">
                <input type="text" placeholder="What are you looking for?" class="search-bar">
                <a href="search.php" class="search-link">
                    <img src="../assets/advance search filter.png" alt="filter-icon" class="filter-icon">
                </a>
            </div>
        </div>
        <!-- dropdown menu in profile button  -->
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" title="User Menu">
                <img src="../assets/user.png" alt="user-icon" class="icon user-icon">
            </a>
            <div class="dropdown-menu">
                <a href="profile.php">View My Profile</a>
                <a href="user_settings.php">User Settings</a>
                <a href="index.php">Logout</a>
            </div>
        </div>
    </div>
    
    <!-- Analytics Section -->
    <div class="analytics-container">
        <h1>Discussions Analytics</h1>
        <p class="timestamp">As of <span id="current-timestamp"></span></p>
        <div class="analytics-section" id="discussions-list">
            <!-- Discussion items will be populated here -->
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const currentTimestamp = document.getElementById('current-timestamp');
            const discussionsList = document.getElementById('discussions-list');

            // Set current timestamp
            currentTimestamp.textContent = new Date().toLocaleString();

            // Fetch discussions data
            fetch('../actions/track_engagement_action.php')
                .then(response => response.json())
                .then(data => {
                    data.forEach(discussion => {
                        const discussionItem = document.createElement('div');
                        discussionItem.classList.add('analytics-item-box');
                        discussionItem.innerHTML = `
                            <div><span class="analytics-label">Discussion ID:</span> <span class="analytics-value">${discussion.discussion_id}</span></div>
                            <div><span class="analytics-label">Title:</span> <span class="analytics-value">${discussion.title}</span></div>
                            <div><span class="analytics-label">Number of participants:</span> <span class="analytics-value">${discussion.participant_count}</span></div>
                            <div><span class="analytics-label">Poll result:</span> <span class="analytics-value">${discussion.poll_result}</span></div>
                            <div><span class="analytics-label">Number of comments:</span> <span class="analytics-value">${discussion.comment_count}</span></div>
                        `;
                        discussionsList.appendChild(discussionItem);
                    });
                })
                .catch(error => {
                    console.error('Error fetching discussions data:', error);
                });
        });

        // script for user drop down menu
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
                
            // Toggle dropdown menu on click
            dropdownToggle.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
                console.log('Dropdown menu visibility:', dropdownMenu.style.display); // Debugging
            });

            // Close dropdown menu if clicking outside of it
            document.addEventListener('click', function(event) {
                if (!dropdownToggle.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>