<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $discussion_id = isset($_GET['discussion_id']) ? intval($_GET['discussion_id']) : 0;

    // Fetch vote counts
    $query = "
        SELECT
            (SELECT COUNT(*) FROM discussion_votes WHERE discussion_id = ? AND vote_type = 'pro') AS pro_votes,
            (SELECT COUNT(*) FROM discussion_votes WHERE discussion_id = ? AND vote_type = 'anti') AS anti_votes
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $discussion_id, $discussion_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'Unable to fetch vote counts.']);
    }

    $conn->close();
}
?>
