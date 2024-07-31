<?php
include '../includes/db.php';

$searchTerm = isset($_GET['query']) ? $_GET['query'] : '';
if ($searchTerm) {
    $searchTerm = "%" . $conn->real_escape_string($searchTerm) . "%";
    $sql = "SELECT discussion_id, thumbnail, title, description, date_created FROM discussion WHERE title LIKE ? OR description LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display the search results
    if ($result->num_rows > 0) {
        while ($discussion = $result->fetch_assoc()) {
            echo '<div class="discussion">';
            echo '<h3>' . htmlspecialchars($discussion['title']) . '</h3>';
            echo '<img src="' . htmlspecialchars($discussion['thumbnail']) . '" alt="Thumbnail" width="100" height="100">';
            echo '<p>' . htmlspecialchars($discussion['description']) . '</p>';
            echo '<p><em>Created on: ' . htmlspecialchars($discussion['date_created']) . '</em></p>';
            echo '</div>';
        }
    } else {
        echo '<p>No discussions found for "' . htmlspecialchars($_GET['query']) . '".</p>';
    }
} else {
    echo '<p>Please enter a search term.</p>';
}

$conn->close();
?>
