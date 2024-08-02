<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Manage Discussion</title>
    <link rel="stylesheet" href="../css/manage.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
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

    <div class="main-content">
        <h2>Discussion Lists</h2>
        <div class="discussion-list">
            <?php
            session_start();
            include '../includes/db.php';

            // Handle update and delete operations
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if (isset($_POST['update'])) {
                    // Handle update
                    $discussion_id = $_POST['discussion_id'];
                    $thumbnail = $_POST['thumbnail'];
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    
                    $update_sql = "UPDATE discussion SET thumbnail=?, title=?, description=? WHERE discussion_id=?";
                    $stmt = $conn->prepare($update_sql);
                    $stmt->bind_param("sssi", $thumbnail, $title, $description, $discussion_id);
                    $stmt->execute();
                    echo '<meta http-equiv="refresh" content="0">';
                } elseif (isset($_POST['delete'])) {
                    // Handle delete
                    $discussion_id = $_POST['discussion_id'];
                    
                    $delete_sql = "DELETE FROM discussion WHERE discussion_id=?";
                    $stmt = $conn->prepare($delete_sql);
                    $stmt->bind_param("i", $discussion_id);
                    $stmt->execute();
                    echo '<meta http-equiv="refresh" content="0">';
                }
            }

            // Fetch discussions from the database
            $sql = "SELECT * FROM discussion";
            $result = $conn->query($sql);
            while($row = $result->fetch_assoc()) {
                echo '<div class="discussion-item">';
                echo '<div class="thumbnail"><img src="'.$row['thumbnail'].'" alt="thumbnail"></div>';
                echo '<div class="details">';
                echo '<p>Discussion ID: '.$row['discussion_id'].'</p>';
                echo '<p>Title: '.$row['title'].'</p>';
                echo '<p>Description: '.$row['description'].'</p>';
                echo '</div>';
                echo '<div class="actions">';
                echo '<button class="update-btn" data-id="'.$row['discussion_id'].'" data-title="'.$row['title'].'" data-description="'.$row['description'].'" data-thumbnail="'.$row['thumbnail'].'">Update</button>';
                echo '<form method="POST" action="" style="display:inline-block;">';
                echo '<input type="hidden" name="discussion_id" value="'.$row['discussion_id'].'">';
                echo '<button type="submit" name="delete" class="delete-btn">Delete</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <!-- Update Modal -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="updateForm" method="POST" action="">
                <input type="hidden" name="discussion_id" id="modalDiscussionId">
                <label for="modalThumbnail">Thumbnail</label>
                <input type="text" name="thumbnail" id="modalThumbnail">
                <label for="modalTitle">Title</label>
                <input type="text" name="title" id="modalTitle">
                <label for="modalDescription">Description</label>
                <textarea name="description" id="modalDescription"></textarea>
                <button type="submit" name="update">Update</button>
            </form>
        </div>
    </div>

    <script>
        // Modal script
        const modal = document.getElementById("updateModal");
        const span = document.getElementsByClassName("close")[0];
        const updateBtns = document.querySelectorAll('.update-btn');
        const form = document.getElementById('updateForm');

        updateBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('modalDiscussionId').value = btn.getAttribute('data-id');
                document.getElementById('modalThumbnail').value = btn.getAttribute('data-thumbnail');
                document.getElementById('modalTitle').value = btn.getAttribute('data-title');
                document.getElementById('modalDescription').value = btn.getAttribute('data-description');
                modal.style.display = "block";
            });
        });

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <script>
        // script for user drop down menu
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