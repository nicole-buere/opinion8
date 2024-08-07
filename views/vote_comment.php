<!-- This will let logged in user vote in a comment -->
<?php
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['comment_id'];
    $vote_type = $_POST['vote_type'];
    session_start();
    $user_id = $_SESSION['user_id']; // Retrieve the actual logged-in user's ID

    // Check if the user has already voted
    $stmt = $conn->prepare("SELECT * FROM comment_votes WHERE comment_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $comment_id, $user_id);
    $stmt->execute();
    $voteResult = $stmt->get_result();
    $existingVote = $voteResult->fetch_assoc();

    if ($existingVote) {
        // User has already voted, so update their existing vote
        if ($existingVote['vote_type'] === $vote_type) {
            // If the new vote type is the same as the existing one, ignore
            echo json_encode(["error" => "You have already voted this way."]);
        } else {
            // Update the existing vote
            $stmt = $conn->prepare("UPDATE comment_votes SET vote_type = ? WHERE comment_id = ? AND user_id = ?");
            $stmt->bind_param("sii", $vote_type, $comment_id, $user_id);

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
                echo json_encode(["error" => "Error updating vote."]);
            }
        }
    } else {
        // Insert new vote
        $stmt = $conn->prepare("INSERT INTO comment_votes (comment_id, user_id, vote_type) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $comment_id, $user_id, $vote_type);

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
    }

    $stmt->close();
}
$conn->close();
?>
