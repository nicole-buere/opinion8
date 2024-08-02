<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    die('User not logged in.');
}

$userId = $_SESSION['user_id'];
$query = "SELECT username, role FROM userdb WHERE userID = ?";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}

$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($username, $role);
$stmt->fetch();
$stmt->close();

$homeUrl = ($role === 'Admin') ? '../views/admin_homepage.php' : '../views/homepage.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Analytics</title>
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="track_engagement.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <!-- header -->
    <div class="header">
        <!-- logo -->
        <img src="../assets/alt_logo.png" alt="alt-logo" class="alt-logo">
        <!-- home button -->
        <a href="<?php echo htmlspecialchars($homeUrl); ?>" class="button primary">Home</a>

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
    
    <div class="container">
        <h1>Discussion Analytics</h1>
        <p class="timestamp">Current Timestamp: <span id="current-timestamp"></span></p>
        <div class="analytics" id="analytics">
            <!-- Content will be populated by JavaScript -->
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function fetchDiscussions() {
                console.log("Fetching discussions..."); // Debug output
                $.ajax({
                    url: 'track_engagement_action.php',
                    method: 'GET',
                    success: function(data) {
                        console.log("Data fetched: ", data); // Debug output
                        const discussions = JSON.parse(data);
                        console.log("Parsed discussions: ", discussions); // Debug output
                        let html = '';
                        discussions.forEach(discussion => {
                            html += `
                                <div class="discussion-summary">
                                    <h2>${discussion.discussion_title}</h2>
                                    <p><strong>Discussion ID:</strong> ${discussion.discussion_id}</p>
                                    <p><strong>Performance Summary:</strong></p>
                                    <div class="summary-details">
                                        <div class="summary-item">
                                            <p>Number of Participants:</p>
                                            <p class="count">${discussion.num_participants}</p>
                                        </div>
                                        <div class="summary-item">
                                            <p>Number of Comments:</p>
                                            <p class="count">${discussion.num_comments}</p>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        $('#analytics').html(html);
                        $('#current-timestamp').text(new Date().toLocaleString());
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error fetching data:', textStatus, errorThrown);
                    }
                });
            }

            fetchDiscussions();
            setInterval(fetchDiscussions, 60000); // Refresh data every minute
        });
    </script>
</body>
</html>
