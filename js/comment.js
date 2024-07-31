document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    const commentsList = document.getElementById('comments-list');

    // Function to render a comment
    function renderComment(comment) {
        const commentHtml = `
            <div class="comment" id="comment-${comment.id}">
                <p><strong>${comment.username}:</strong> ${comment.comment.replace(/\n/g, '<br>')}</p>
                <div class="comment-actions">
                    <button class="reply-button" data-comment-id="${comment.id}">Reply</button>
                    <button class="upvote-button" data-comment-id="${comment.id}">Upvote (${comment.upvote_count})</button>
                    <button class="downvote-button" data-comment-id="${comment.id}">Downvote (${comment.downvote_count})</button>
                    <button class="report-button" data-comment-id="${comment.id}">Report</button>
                </div>
                <div class="reply-form" id="reply-form-${comment.id}" style="display:none;">
                    <form class="reply-form-content" data-comment-id="${comment.id}">
                        <textarea name="reply" placeholder="Add a reply..." required></textarea>
                        <button type="submit">Submit Reply</button>
                    </form>
                </div>
                <div class="replies" id="replies-${comment.id}">
                    <!-- Replies will be dynamically loaded here -->
                </div>
            </div>
        `;
        commentsList.insertAdjacentHTML('beforeend', commentHtml);

        // Render the replies
        comment.replies.forEach(reply => {
            const replyHtml = `
                <div class="reply">
                    <p><strong>${reply.username}:</strong> ${reply.reply.replace(/\n/g, '<br>')}</p>
                </div>
            `;
            document.getElementById(`replies-${comment.id}`).insertAdjacentHTML('beforeend', replyHtml);
        });
    }

    // Load comments and their replies when the page loads
    const discussionId = new URLSearchParams(window.location.search).get('discussion_id');
    fetch(`get_comments.php?discussion_id=${discussionId}`)
        .then(response => response.json())
        .then(data => {
            // Clear previous comments
            commentsList.innerHTML = '';

            // Render new comments
            data.comments.forEach(renderComment);
        });

    // Handle comment form submission
    commentForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(commentForm);
        formData.append('discussion_id', discussionId);

        fetch('add_comment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (!result.error) {
                renderComment(result);
                commentForm.reset();
            } else {
                console.error(result.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Toggle reply form visibility
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('reply-button')) {
            const commentId = event.target.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.style.display = replyForm.style.display === 'none' || replyForm.style.display === '' ? 'block' : 'none';
        }
    });

    // Handle reply form submission
    document.addEventListener('submit', function(event) {
        if (event.target.classList.contains('reply-form-content')) {
            event.preventDefault();
            const commentId = event.target.getAttribute('data-comment-id');
            const formData = new FormData(event.target);
            formData.append('comment_id', commentId);

            fetch('add_reply.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                if (!result.error) {
                    const replyHtml = `
                        <div class="reply">
                            <p><strong>${result.username}:</strong> ${result.reply.replace(/\n/g, '<br>')}</p>
                        </div>
                    `;
                    document.getElementById(`replies-${commentId}`).insertAdjacentHTML('beforeend', replyHtml);
                    event.target.reset();
                    document.getElementById(`reply-form-${commentId}`).style.display = 'none';
                } else {
                    console.error(result.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });

    // Upvote and downvote handling
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('upvote-button') || event.target.classList.contains('downvote-button')) {
            const commentId = event.target.getAttribute('data-comment-id');
            const voteType = event.target.classList.contains('upvote-button') ? 'upvote' : 'downvote';

            fetch('vote_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'comment_id': commentId,
                    'vote_type': voteType
                })
            })
            .then(response => response.json())
            .then(result => {
                if (!result.error) {
                    // Update the vote counts in the UI
                    const upvoteButton = document.querySelector(`.upvote-button[data-comment-id="${commentId}"]`);
                    const downvoteButton = document.querySelector(`.downvote-button[data-comment-id="${commentId}"]`);

                    upvoteButton.textContent = `Upvote (${result.upvote_count})`;
                    downvoteButton.textContent = `Downvote (${result.downvote_count})`;
                } else {
                    console.error(result.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });

    // Report comment handling
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('report-button')) {
            const commentId = event.target.getAttribute('data-comment-id');

            fetch('report_comment.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'comment_id': commentId
                })
            })
            .then(response => response.json())
            .then(result => {
                if (!result.error) {
                    alert('Comment reported successfully.');
                } else {
                    console.error(result.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });
});
