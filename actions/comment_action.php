<!-- actions/comment_action.php -->
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $postId = $_POST['post_id'];
    $comment = $_POST['comment'];
    session_start();
    $username = $_SESSION['username'];

    $conn = new mysqli('localhost', 'root', '', 'opinion8');
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "INSERT INTO comments (post_id, username, comment) VALUES ('$postId', '$username', '$comment')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Comment added successfully!";
        header("Location: ../views/timeline.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
}
?>
