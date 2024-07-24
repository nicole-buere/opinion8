<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Admin Homepage</title>
    <link rel="stylesheet" href="../css/admin_home.css">
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
        <a href="user_settings.php" title="User Settings">  
            <img src="../assets/user.png" alt="user-icon" class="icon user-icon">
        </a>
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

        <!-- admin's tasks -->
        <div class="admin-tasks">
            <h2> What’s your next step? Choose an action to drive the debate forward! </h2>

            <!-- divider for creation of post -->
            <div class="task-item create-post">
                <div class="icon">
                    <img src="../assets/create.png" alt="create icon">
                </div>
                <div class="desc">
                    <h3>Create a Discussion</h3>
                    <p>Curate a discussion that ignites minds and sparks meaningful engagement.</p>
                </div>
                <div class="icon-button">
                    <img src="../assets/go-button.png" alt="create icon">
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
    </div>
</body>
</html>

test