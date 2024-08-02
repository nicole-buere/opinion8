<?php
// Include database connection
include '../includes/db.php';

// Fetch discussion data
$query = "SELECT d.discussion_id, d.title as discussion_title,
            (SELECT COUNT(DISTINCT user_id) FROM poll_responses WHERE poll_id = d.discussion_id) AS num_participants,
            (SELECT COUNT(*) FROM comments WHERE discussion_id = d.discussion_id) AS num_comments
          FROM discussion d";
$result = $conn->query($query);

if (!$result) {
    die('Query error: ' . $conn->error);
}

// Initialize an array to store the data
$discussions = [];

while ($row = $result->fetch_assoc()) {
    $discussions[] = $row;
}

// Return the data as a JSON object
header('Content-Type: application/json');
echo json_encode($discussions);

// Close the database connection
$conn->close();
?>
