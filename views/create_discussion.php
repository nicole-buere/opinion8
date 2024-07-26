<!-- page when admin wants to create a debate discussion -->
<!DOCTYPE html>
<html>
<head>
    <title>Create a Discussion</title>
    <link rel="stylesheet" href="../css/create_discussion.css">
    <link rel="stylesheet" href="../css/header.css">
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
        <a href="user_settings.php" title="User Settings">  
            <img src="../assets/user.png" alt="user-icon" class="icon user-icon">
        </a>
    </div>

    <!-- main content -->
    <div class="content-box">
        <div class="greeting">
            <h1>Create a Discussion</h1>
            <h2>Let's set the stage for a great debate!</h2>
        </div>

        <!-- forms -->
        <form action="../actions/create_discussion_action.php" method="post">
            <div class="form-group">
                <label for="thumbnail">Thumbnail</label>
                <input type="url" id="thumbnail" name="thumbnail" placeholder="Enter the image address" required>
            </div>

            <div class="form-group">
                <label for="title">Discussion Title</label>
                <input type="text" id="title" name="title" placeholder="Add a title" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="4" placeholder="Add a brief description of what the topic is all about." required></textarea>
            </div>

            <div class="form-group">
                <label for="poll_title">Poll Title</label>
                <input type="text" id="poll_title" name="poll_title" placeholder="Add a poll title">
            </div>

            <div class="form-group">
                <label for="choice1">Choice 1</label>
                <input type="text" id="choice1" name="choice1" placeholder="I agree">
            </div>

            <div class="form-group">
                <label for="choice2">Choice 2</label>
                <input type="text" id="choice2" name="choice2" placeholder="I disagree">
            </div>

            <div class="form-actions">
                <button type="submit">Create Discussion</button>
                <button type="button" class="cancel" onclick="window.location.href='admin_homepage.php'">Cancel</button>
            </div>
        </form>      
    </div>
</body>
</html>