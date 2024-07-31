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
        // Update existing vote
        $stmt = $conn->prepare("UPDATE comment_votes SET vote_type = ? WHERE comment_id = ? AND user_id = ?");
        $stmt->bind_param("sii", $vote_type, $comment_id, $user_id);
    } else {
        // Insert new vote
        $stmt = $conn->prepare("INSERT INTO comment_votes (comment_id, user_id, vote_type) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $comment_id, $user_id, $vote_type);
    }

    if ($stmt->execute()) {
        echo "Vote registered successfully.";
    } else {
        echo "Error registering vote.";
    }
    $stmt->close();
}
$conn->close();
?>
