<!-- when advanced search is clicked page will navigate to this (advance search page) -->
<!DOCTYPE html>
<html>
<head>
    <title>Opinion8 - Search</title>
    <link rel="stylesheet" href="../css/search.css">
</head>
<body>
    <div class="search-box">
        <div class="greeting">
            <h1>Opinion8</h1>
            <h2>Search</h2>
            <p>Enter your search criteria below</p>
        </div>

        <div class="search-form">
            <form action="../actions/search_action.php" method="get">
                <label for="topic">Topic</label>
                <input type="text" name="topic" id="topic" placeholder="Enter topic">
                <br>
                <label for="date_from">Date From</label>
                <input type="date" name="date_from" id="date_from">
                <br>
                <label for="date_to">Date To</label>
                <input type="date" name="date_to" id="date_to">
                <br>
                <label for="type">Type</label>
                <select name="type" id="type">
                    <option value="">All</option>
                    <option value="debate">Debate</option>
                    <option value="comment">Comment</option>
                    <option value="poll">Poll</option>
                </select>
                <br>
                <input type="submit" value="Search" id="search">
            </form>
        </div>
    </div>
</body>
</html>
