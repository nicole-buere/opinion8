<?php
// actions/search_action.php

include('../includes/db.php');

$topic = $_GET['topic'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';
$type = $_GET['type'] ?? '';

// Build the query
$query = "SELECT * FROM content WHERE 1=1";

if ($topic) {
    $query .= " AND topic LIKE ?";
    $params[] = "%" . $topic . "%";
    $types .= "s";
}

if ($date_from) {
    $query .= " AND date >= ?";
    $params[] = $date_from;
    $types .= "s";
}

if ($date_to) {
    $query .= " AND date <= ?";
    $params[] = $date_to;
    $types .= "s";
}

if ($type) {
    $query .= " AND type = ?";
    $params[] = $type;
    $types .= "s";
}

// Prepare and execute the statement
$stmt = $conn->prepare($query);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$results = [];
while ($row = $result->fetch_assoc()) {
    $results[] = $row;
}

$stmt->close();
$conn->close();

// Pass results to a view for display
include('../views/search_results.php');
?>
