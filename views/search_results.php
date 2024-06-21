<!-- views/search_results.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Search Results</title>
    <link rel="stylesheet" href="../css/search.css">
</head>
<body>
    <div class="results-box">
        <div class="header">
            <h1>Opinion8</h1>
            <h2>Search Results</h2>
            <p>Here are the results of your search:</p>
            <a href="timeline.php" class="back-link">Back to Timeline</a>
        </div>

        <div class="results">
            <?php if (empty($results)): ?>
                <p>No results found.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($results as $result): ?>
                        <li class="result-item">
                            <h3><?php echo htmlspecialchars($result['topic']); ?></h3>
                            <p>Date: <?php echo htmlspecialchars($result['date']); ?></p>
                            <p>Type: <?php echo htmlspecialchars($result['type']); ?></p>
                            <p><?php echo nl2br(htmlspecialchars($result['content'])); ?></p>
                            <?php if ($result['type'] === 'poll'): ?>
                                <!-- Display poll options -->
                                <div class="poll-options">
                                    <?php
                                    $poll_id = $result['id'];
                                    $poll_query = "SELECT * FROM poll_options WHERE poll_id = ?";
                                    $poll_stmt = $conn->prepare($poll_query);
                                    $poll_stmt->bind_param("i", $poll_id);
                                    $poll_stmt->execute();
                                    $poll_result = $poll_stmt->get_result();

                                    while ($poll_option = $poll_result->fetch_assoc()):
                                    ?>
                                        <div class="poll-option">
                                            <input type="radio" name="poll_<?php echo $poll_id; ?>" value="<?php echo $poll_option['id']; ?>" id="poll_option_<?php echo $poll_option['id']; ?>">
                                            <label for="poll_option_<?php echo $poll_option['id']; ?>"><?php echo htmlspecialchars($poll_option['option_text']); ?></label>
                                        </div>
                                    <?php endwhile; ?>
                                    <button class="vote-button" data-poll-id="<?php echo $poll_id; ?>">Vote</button>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
