<!-- views/timeline.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Timeline</title>
    <link rel="stylesheet" href="../css/timeline.css">
</head>
<body>
    <div class="timeline-box">
        <div class="timeline-header">
            <h1>Timeline</h1>
        </div>
        
        <div class="timeline-content">
            <!-- Fetch and display timeline posts from the database -->
            <?php
            session_start();
            $conn = new mysqli('localhost', 'root', '', 'opinion8');
            
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "SELECT * FROM posts ORDER BY created_at DESC";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='post'>";
                    echo "<h2>" . $row["title"] . "</h2>";
                    echo "<p>" . $row["content"] . "</p>";
                    echo "<p><small>Posted by: " . $row["username"] . " on " . $row["created_at"] . "</small></p>";
                    echo "<div class='comments'>";
                    
                    $postId = $row["id"];
                    $commentSql = "SELECT * FROM comments WHERE post_id='$postId' ORDER BY created_at DESC";
                    $commentResult = $conn->query($commentSql);
                    
                    if ($commentResult->num_rows > 0) {
                        while($commentRow = $commentResult->fetch_assoc()) {
                            echo "<div class='comment'>";
                            echo "<p>" . $commentRow["username"] . ": " . $commentRow["comment"] . "</p>";
                            echo "<div class='comment-actions'>";
                            echo "<a href='#'>Reply</a> | ";
                            echo "<a href='#'>Upvote</a> | ";
                            echo "<a href='#'>Downvote</a>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>No comments yet.</p>";
                    }
                    
                    echo "</div>";
                    echo "<form action='../actions/comment_action.php' method='post'>";
                    echo "<input type='hidden' name='post_id' value='" . $postId . "'>";
                    echo "<input type='text' name='comment' placeholder='Add a comment'>";
                    echo "<input type='submit' value='Comment'>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No posts available.</p>";
            }
            
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
