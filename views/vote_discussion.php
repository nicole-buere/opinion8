<!-- This will let logged in user vote in a discussion topic -->
<?php
session_start();
include('../includes/db.php');

$data = json_decode(file_get_contents('php://input'), true);
$discussion_id = isset($data['discussion_id']) ? intval($data['discussion_id']) : 0;
$vote_type = isset($data['vote_type']) ? $data['vote_type'] : '';

if ($vote_type !== 'pro' && $vote_type !== 'anti') {
    echo json_encode(['error' => 'Invalid vote type.']);
    exit();
}

$user_id = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

if ($user_id > 0) {
    // Remove any existing vote from this user
    $removeVoteQuery = "DELETE FROM discussion_votes WHERE discussion_id = ? AND user_id = ?";
    $stmt = $conn->prepare($removeVoteQuery);
    $stmt->bind_param("ii", $discussion_id, $user_id);
    $stmt->execute();

    // Insert new vote
    $insertVoteQuery = "INSERT INTO discussion_votes (discussion_id, user_id, vote_type) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertVoteQuery);
    $stmt->bind_param("iis", $discussion_id, $user_id, $vote_type);
    $stmt->execute();

    // Get updated vote counts
    $countQuery = "
        SELECT
            SUM(CASE WHEN vote_type = 'pro' THEN 1 ELSE 0 END) AS pro_votes,
            SUM(CASE WHEN vote_type = 'anti' THEN 1 ELSE 0 END) AS anti_votes
        FROM discussion_votes
        WHERE discussion_id = ?
    ";
    $stmt = $conn->prepare($countQuery);
    $stmt->bind_param("i", $discussion_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    echo json_encode([
        'pro_votes' => $result['pro_votes'],
        'anti_votes' => $result['anti_votes'],
    ]);
} else {
    echo json_encode(['error' => 'User not logged in.']);
}
?>
