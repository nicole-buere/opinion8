<?php
// Includes the database connection
include 'includes/db.php';

// Fetch data from discussion table
$sql = "SELECT discussion_id, thumbnail, title, description, date_created FROM discussion";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Create an array to hold the data
    $discussions = array();

    // Fetch data and store it in the array
    while($row = $result->fetch_assoc()) {
        $discussions[] = $row;
    }

    // Output data in JSON format
    echo json_encode($discussions);
} else {
    echo json_encode(array());
}

$conn->close();
?>
