<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Admin Homepage</title>
    <link rel="stylesheet" href="../css/admin_home.css">
    <link rel="stylesheet" href="../css/header.css">
    <style>
        /* CSS styles for dropdown menu */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-menu a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }

        /* Ensure existing CSS styles are maintained */
        /* Add any additional styles here if needed */
    </style>
</head>
<body>
    <!-- header -->
    <div class="header">
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <a href="admin_homepage.php" class="home-link">Home</a>

        <div class="search-container">
            <div class="search-bar-wrapper">
                <img src="../assets/search icon.png" alt="search-icon" class="search-icon">
                <input type="text" placeholder="Search..." class="search-bar">
                <a href="search.php" class="search-link">
                    <img src="../assets/advance search filter.png" alt="filter-icon" class="filter-icon">
                </a>
            </div>
        </div>
        <div class="dropdown">
            <a href="#" class="dropdown-toggle" title="User Menu">
                <img src="../assets/user.png" alt="user-icon" class="icon user-icon">
            </a>
            <div class="dropdown-menu">
                <a href="profile.php">View My Profile</a>
                <a href="user_settings.php">User Settings</a>
                <a href="logout.php">Logout</a>
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
                <p>Take control of your discussions: delete, edit posts, and add updates.</p>
            </div>
            <div class="icon-button">
                <img src="../assets/go-button.png" alt="manage icon">
            </div>
        </div>

        <div class="task-item view-analytics">
            <div class="icon">
                <img src="../assets/analytics.png" alt="analytics icon">
            </div>
            <div class="desc">
                <h3>Track Engagement</h3>
                <p>Discover the impact of your posts through detailed engagement and interaction metrics.</p>
            </div>
            <div class="icon-button">
                <img src="../assets/go-button.png" alt="analytics icon">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            // Toggle dropdown menu on click
            dropdownToggle.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
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