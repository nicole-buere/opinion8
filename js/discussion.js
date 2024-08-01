// Assuming the buttons have IDs 'proButton' and 'antiButton'
document.addEventListener('DOMContentLoaded', function() {
    const proButton = document.getElementById('proButton');
    const antiButton = document.getElementById('antiButton');

    if (proButton) {
        proButton.addEventListener('click', function() {
            // Code to handle Pro vote
            handleVote('pro');
        });
    }

    if (antiButton) {
        antiButton.addEventListener('click', function() {
            // Code to handle Anti vote
            handleVote('anti');
        });
    }

    function handleVote(voteType) {
        // Perform AJAX request or form submission to handle vote
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'vote.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                // Update UI with response if needed
            } else {
                // Handle errors
            }
        };
        xhr.send('voteType=' + encodeURIComponent(voteType));
    }
});
