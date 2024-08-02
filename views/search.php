<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Search</title>
    <link rel="stylesheet" href="../css/search.css">
    <link rel="stylesheet" href="../css/header.css"> <!-- Added for consistent header styles -->
</head>
<body>
    <!-- header -->
    <div class="header">
        <!-- logo -->
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <!-- home button -->
        <a href="<?php echo htmlspecialchars($role === 'admin' ? '../views/admin_homepage.php' : '../views/homepage.php'); ?>" class="button primary">Home</a>

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
        <!-- dropdown menu in profile button -->
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

    <div class="search-box">
        <div class="greeting">
            <h1>Opinion8</h1>
            <h2>Search</h2>
            <p>Enter your search criteria below</p>
        </div>

        <div class="search-form">
            <form action="../actions/search_action.php" method="get">
                <label for="keyword">Keyword</label>
                <input type="text" name="keyword" id="keyword" placeholder="Enter keyword">
                <br>
                <label for="month">Month</label>
                <select name="month" id="month">
                    <option value="">Any</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <br>
                <label for="day">Day</label>
                <input type="number" name="day" id="day" placeholder="Day" min="1" max="31">
                <br>
                <label for="year">Year</label>
                <input type="number" name="year" id="year" placeholder="Year" min="1900" max="2100">
                <br>
                <label for="type">Type</label>
                <select name="type" id="type">
                    <option value="">Any</option>
                    <option value="debate">Debate Topic</option>
                    <option value="comment">Comment</option>
                    <option value="reply">Reply</option>
                </select>
                <br>
                <input type="submit" value="Search" id="search">
            </form>
        </div>
    </div>



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
