<!-- views/search_results.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Search Results</title>
    <link rel="stylesheet" href="../css/search.css">
</head>
<body>
    <div class="results-box">
        <div class="greeting">
            <h1>Opinion8</h1>
            <h2>Search Results</h2>
            <p>Here are the results of your search:</p>
        </div>

        <div class="results">
            <?php if (empty($results)): ?>
                <p>No results found.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($results as $result): ?>
                        <li>
                            <h3><?php echo htmlspecialchars($result['topic']); ?></h3>
                            <p>Date: <?php echo htmlspecialchars($result['date']); ?></p>
                            <p>Type: <?php echo htmlspecialchars($result['type']); ?></p>
                            <p><?php echo htmlspecialchars($result['content']); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
