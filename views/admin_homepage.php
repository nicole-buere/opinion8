<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Admin Homepage</title>
    <link rel="stylesheet" href="../css/admin_home.css">
    <link rel="stylesheet" href="../css/header.css">
</head>

<body>
    <!-- header -->
    <div class="header">
        <!-- logo -->
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <!-- home button -->
        <a href="../views/admin_homepage.php" class="button primary">Home</a>
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
                <a href="../views/profile.php">View My Profile</a>
                <a href="../views/user_settings.php">User Settings</a>
                <a href="../views/index.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="content-box">
        <div class="greeting-box">
            <div class="greet-text">
                <h1>Hello Admin!</h1>
                <p>You are the curator of our dynamic debates and the guardian of our site.
                    <br>Dive in, spark discussions, and keep the conversation flowing!</p>
            </div>
            <div class="art">
                <img src="../assets/greet-art.png" alt="greeting-art">
            </div>
        </div>

        <div class="intro">
            <h2>What is your next step? Choose an action to drive the debate forward!</h2>
        </div>

        <!-- admin's tasks -->
        <div class="task-item create-post">
            <div class="icon">
                <img src="../assets/create.png" alt="create icon">
            </div>
            <div class="desc">
                <h3>Create a Discussion</h3>
                <p>Curate a discussion that ignites minds and sparks meaningful engagement.</p>
            </div>
            <div class="icon-button">
                <a href="create_discussion.php" class="go-button">
                    <img src="../assets/go-button.png" alt="Go button">
                </a>
            </div>
        </div>

        <div class="task-item manage-post">
            <div class="icon">
                <img src="../assets/manage.png" alt="manage icon">
            </div>
            <div class="desc">
                <h3>Manage Discussion</h3>
                <p>Take control of your discussions: delete, edit, and add updates.</p>
            </div>
            <div class="icon-button">
                <a href="manage_discussion.php" class="go-button">
                    <img src="../assets/go-button.png" alt="Go button">
                </a>
            </div>
        </div>

        <div class="task-item track-engagement">
            <div class="icon">
                <img src="../assets/analytics.png" alt="analytics icon">
            </div>
            <div class="desc">
                <h3>Track Engagement</h3>
                <p>Discover the impact of your posts through detailed engagement and interaction metrics.</p>
            </div>
            <div class="icon-button">
                <a href="track_engagement.php" class="go-button">
                    <img src="../assets/go-button.png" alt="Go button">
                </a>
            </div>
        </div>

    </div>

    <script> 
    // always add this script for user menu dropdown to work (in header) for all pages
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