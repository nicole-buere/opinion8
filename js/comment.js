document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    
    if (commentForm) {
        commentForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(commentForm);
            formData.append('discussion_id', new URLSearchParams(window.location.search).get('discussion_id'));
            fetch('add_comment.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(result => {
                console.log(result);
                // Optionally refresh the comments list
            });
        });
    }

    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.createElement('form');
            replyForm.innerHTML = `
                <textarea name="reply" placeholder="Add a reply..." required></textarea>
                <button type="submit">Submit Reply</button>
                <input type="hidden" name="comment_id" value="${commentId}">
            `;
            replyForm.addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(replyForm);
                fetch('add_reply.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.text()).then(result => {
                    console.log(result);
                    // Optionally refresh the replies list
                });
            });
            this.parentNode.appendChild(replyForm);
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
            });
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
            });
        });
    });

    document.querySelectorAll('.report-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const reason = prompt('Please enter the reason for reporting this comment:');
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
                });
            }
        });
    });
});
