<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['comment_id'];
    $vote_type = $_POST['vote_type'];
    $user_id = 1; // Example user ID, replace with actual session user ID

    // Check if the user has already voted
    $stmt = $conn->prepare("SELECT * FROM comment_votes WHERE comment_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $comment_id, $user_id);
    $stmt->execute();
    $voteResult = $stmt->get_result();
    $existingVote = $voteResult->fetch_assoc();

    if ($existingVote) {
        // If the user has already voted the same type, remove the vote
        if ($existingVote['vote_type'] === $vote_type) {
            $stmt = $conn->prepare("DELETE FROM comment_votes WHERE comment_id = ? AND user_id = ?");
            $stmt->bind_param("ii", $comment_id, $user_id);
        } else {
            // Update existing vote to the new vote type
            $stmt = $conn->prepare("UPDATE comment_votes SET vote_type = ? WHERE comment_id = ? AND user_id = ?");
            $stmt->bind_param("sii", $vote_type, $comment_id, $user_id);
        }
    } else {
        // Insert new vote
        $stmt = $conn->prepare("INSERT INTO comment_votes (comment_id, user_id, vote_type) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $comment_id, $user_id, $vote_type);
    }

    if ($stmt->execute()) {
        // Get the updated vote counts
        $countQuery = $conn->prepare("
            SELECT 
                (SELECT COUNT(*) FROM comment_votes WHERE comment_id = ? AND vote_type = 'upvote') AS upvote_count,
                (SELECT COUNT(*) FROM comment_votes WHERE comment_id = ? AND vote_type = 'downvote') AS downvote_count
        ");
        $countQuery->bind_param("ii", $comment_id, $comment_id);
        $countQuery->execute();
        $counts = $countQuery->get_result()->fetch_assoc();
        echo json_encode($counts);
    } else {
        echo json_encode(["error" => "Error registering vote."]);
    }
    $stmt->close();
}
$conn->close();
?>
