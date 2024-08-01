<?php
session_start();
include('../includes/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reply = isset($_POST['reply']) ? $_POST['reply'] : '';
    $comment_id = isset($_POST['comment_id']) ? intval($_POST['comment_id']) : 0;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

    if ($reply && $comment_id && $user_id) {
        $replyQuery = "INSERT INTO comment_replies (comment_id, user_id, reply, date_created) VALUES (?, ?, ?, NOW())";
        $replyStmt = $conn->prepare($replyQuery);
        $replyStmt->bind_param("iis", $comment_id, $user_id, $reply);
        if ($replyStmt->execute()) {
            // Fetch the new reply
            $reply_id = $replyStmt->insert_id;
            $replyFetchQuery = "
                SELECT cr.id, cr.reply, cr.date_created, u.username
                FROM comment_replies cr
                JOIN userdb u ON cr.user_id = u.userID
                WHERE cr.id = ?
            ";
            $replyFetchStmt = $conn->prepare($replyFetchQuery);
            $replyFetchStmt->bind_param("i", $reply_id);
            $replyFetchStmt->execute();
            $reply = $replyFetchStmt->get_result()->fetch_assoc();

            echo json_encode($reply);
        } else {
            echo json_encode(["error" => "Failed to add reply."]);
        }
    } else {
        echo json_encode(["error" => "Invalid input."]);
    }
    $conn->close();
}
?>

