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

    // Fetch poll result (assuming one poll per discussion)
    $pollQuery = "
        SELECT choice1, choice2, 
               (SELECT COUNT(*) FROM poll_responses WHERE poll_id = poll.poll_id AND response = 1) AS choice1_votes, 
               (SELECT COUNT(*) FROM poll_responses WHERE poll_id = poll.poll_id AND response = 2) AS choice2_votes 
        FROM poll 
        WHERE poll_id = ?
    ";
    $pollStmt = $conn->prepare($pollQuery);
    $pollStmt->bind_param("i", $discussion_id);
    $pollStmt->execute();
    $poll = $pollStmt->get_result()->fetch_assoc();
    $poll_result = $poll ? "{$poll['choice1']} ({$poll['choice1_votes']} votes) vs {$poll['choice2']} ({$poll['choice2_votes']} votes)" : "No poll";

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
        'poll_result' => $poll_result,
        'comment_count' => $comments['comment_count'],
    ];
}

echo json_encode($discussions);

$conn->close();
?>