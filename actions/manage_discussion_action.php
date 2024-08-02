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
        header("Location: manage_discussion_action.php");
    } elseif (isset($_POST['delete'])) {
        // Handle delete
        $discussion_id = $_POST['discussion_id'];
        
        $delete_sql = "DELETE FROM discussion WHERE discussion_id=?";
        $stmt = $conn->prepare($delete_sql);
        $stmt->bind_param("i", $discussion_id);
        $stmt->execute();
        header("Location: manage_discussion_action.php");
    }
}

// Fetch discussions from the database
$sql = "SELECT * FROM discussion";
$result = $conn->query($sql);

// Fetch a single discussion for editing
if (isset($_GET['edit'])) {
    $discussion_id = $_GET['edit'];
    $edit_sql = "SELECT * FROM discussion WHERE discussion_id=?";
    $stmt = $conn->prepare($edit_sql);
    $stmt->bind_param("i", $discussion_id);
    $stmt->execute();
    $edit_result = $stmt->get_result();
    $edit_discussion = $edit_result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Discussions</title>
    <link rel="stylesheet" href="../css/manage.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/footer.css">
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this discussion?");
        }
    </script>
</head>
<body>
    <div class="header">
        <!-- logo and navigation omitted for brevity -->
    </div>
    <div class="container">
        <h2>Manage Discussions</h2>
        
        <?php if (isset($edit_discussion)) { ?>
        <h3>Edit Discussion</h3>
        <form method="POST" action="manage_discussion_action.php">
            <input type="hidden" name="discussion_id" value="<?php echo $edit_discussion['discussion_id']; ?>">
            <div>
                <label for="thumbnail">Thumbnail:</label>
                <input type="text" id="thumbnail" name="thumbnail" value="<?php echo $edit_discussion['thumbnail']; ?>">
            </div>
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo $edit_discussion['title']; ?>">
            </div>
            <div>
                <label for="description">Description:</label>
                <textarea id="description" name="description"><?php echo $edit_discussion['description']; ?></textarea>
            </div>
            <button type="submit" name="update">Update</button>
        </form>
        <?php } ?>

        <h3>Discussions List</h3>
        <table>
            <tr>
                <th>Thumbnail</th>
                <th>Title</th>
                <th>Description</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['thumbnail']; ?></td>
                <td><?php echo $row['title']; ?></td>
                <td><?php echo $row['description']; ?></td>
                <td><?php echo $row['date_created']; ?></td>
                <td>
                    <a href="manage_discussion_action.php?edit=<?php echo $row['discussion_id']; ?>">Update</a>
                    <form method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                        <input type="hidden" name="discussion_id" value="<?php echo $row['discussion_id']; ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

    <script>
        // script for user drop down menu
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            dropdownToggle.addEventListener('click', function(event) {
                event.preventDefault();
                const isVisible = dropdownMenu.style.display === 'block';
                dropdownMenu.style.display = isVisible ? 'none' : 'block';
                console.log('Dropdown menu visibility:', dropdownMenu.style.display);
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
