document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    commentForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(commentForm);
        formData.append('discussion_id', new URLSearchParams(window.location.search).get('discussion_id'));

        fetch('add_comment.php', {
            method: 'POST',
            body: formData
        }).then(response => response.text()).then(result => {
            // Handle success or failure
            console.log(result);
            location.reload(); // Reload to see the new comment
        }).catch(error => console.error('Error:', error));
    });

    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            // Load and display reply form for this comment
            // Placeholder: You need to implement the functionality to show and handle reply form
        });
    });

    document.querySelectorAll('.upvote-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            fetch('vote_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    comment_id: commentId,
                    vote_type: 'upvote'
                })
            }).then(response => response.text()).then(result => {
                console.log(result);
                location.reload(); // Reload to see the updated vote count
            }).catch(error => console.error('Error:', error));
        });
    });

    document.querySelectorAll('.downvote-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            fetch('vote_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    comment_id: commentId,
                    vote_type: 'downvote'
                })
            }).then(response => response.text()).then(result => {
                console.log(result);
                location.reload(); // Reload to see the updated vote count
            }).catch(error => console.error('Error:', error));
        });
    });

    document.querySelectorAll('.report-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const reason = prompt("Please enter the reason for reporting this comment:");
            if (reason) {
                fetch('report_comment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        comment_id: commentId,
                        reason: reason
                    })
                }).then(response => response.text()).then(result => {
                    console.log(result);
                    alert("Comment reported successfully.");
                }).catch(error => console.error('Error:', error));
            }
        });
    });
});
