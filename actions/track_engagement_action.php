<?php
include('../includes/db.php');

// Fetch all discussions
$discussionsQuery = "SELECT discussion_id, title FROM discussion";
$discussionsStmt = $conn->prepare($discussionsQuery);
$discussionsStmt->execute();
$discussionsResult = $discussionsStmt->get_result();
$discussions = [];

while ($discussion = $discussionsResult->fetch_assoc()) {
    $discussion_id = $discussion['discussion_id'];

    // Count number of participants (users who commented or voted)
    $participantsQuery = "
        SELECT COUNT(DISTINCT user_id) AS participant_count
        FROM (
            SELECT user_id FROM comments WHERE discussion_id = ?
            UNION
            SELECT user_id FROM discussion_votes WHERE discussion_id = ?
        ) AS participants
    ";
    $participantsStmt = $conn->prepare($participantsQuery);
    $participantsStmt->bind_param("ii", $discussion_id, $discussion_id);
    $participantsStmt->execute();
    $participants = $participantsStmt->get_result()->fetch_assoc();

    // Fetch vote counts
    $votesQuery = "
        SELECT
            (SELECT COUNT(*) FROM discussion_votes WHERE discussion_id = ? AND vote_type = 'pro') AS pro_votes,
            (SELECT COUNT(*) FROM discussion_votes WHERE discussion_id = ? AND vote_type = 'anti') AS anti_votes
    ";
    $votesStmt = $conn->prepare($votesQuery);
    $votesStmt->bind_param("ii", $discussion_id, $discussion_id);
    $votesStmt->execute();
    $votes = $votesStmt->get_result()->fetch_assoc();
    $vote_result = $votes ? "{$votes['pro_votes']} Pro vs {$votes['anti_votes']} Anti" : "No votes";

    // Count number of comments
    $commentsQuery = "SELECT COUNT(*) AS comment_count FROM comments WHERE discussion_id = ?";
    $commentsStmt = $conn->prepare($commentsQuery);
    $commentsStmt->bind_param("i", $discussion_id);
    $commentsStmt->execute();
    $comments = $commentsStmt->get_result()->fetch_assoc();

    $discussions[] = [
        'discussion_id' => $discussion_id,
        'title' => $discussion['title'],
        'participant_count' => $participants['participant_count'],
        'vote_result' => $vote_result,
        'comment_count' => $comments['comment_count'],
    ];
}

echo json_encode($discussions);

$conn->close();
?>
