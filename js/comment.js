document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('comment-form');
    commentForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(commentForm);
        formData.append('discussion_id', new URLSearchParams(window.location.search).get('discussion_id'));
        
        fetch('add_comment.php', {
            method: 'POST',
            body: formData
        }).then(response => response.json()).then(result => {
            if (!result.error) {
                const commentHtml = `
                    <div class="comment">
                        <p><strong>${result.username}:</strong> ${result.comment.replace(/\n/g, '<br>')}</p>
                        <div class="comment-actions">
                            <button class="reply-button" data-comment-id="${result.id}">Reply</button>
                            <button class="upvote-button" data-comment-id="${result.id}">Upvote (${result.upvote_count})</button>
                            <button class="downvote-button" data-comment-id="${result.id}">Downvote (${result.downvote_count})</button>
                            <button class="report-button" data-comment-id="${result.id}">Report</button>
                        </div>
                        <div class="reply-form" id="reply-form-${result.id}" style="display:none;">
                            <form class="reply-form-content" data-comment-id="${result.id}">
                                <textarea name="reply" placeholder="Add a reply..." required></textarea>
                                <button type="submit">Submit Reply</button>
                            </form>
                        </div>
                        <div class="replies" id="replies-${result.id}">
                            <!-- Replies will be dynamically loaded here -->
                        </div>
                    </div>
                `;
                document.getElementById('comments-list').insertAdjacentHTML('afterbegin', commentHtml);
                commentForm.reset();
            } else {
                console.error(result.error);
            }
        }).catch(error => {
            console.error('Error:', error);
        });
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('reply-button')) {
            const commentId = event.target.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.style.display = replyForm.style.display === 'none' || replyForm.style.display === '' ? 'block' : 'none';
        }
    });

    document.addEventListener('submit', function(event) {
        if (event.target.classList.contains('reply-form-content')) {
            event.preventDefault();
            const commentId = event.target.getAttribute('data-comment-id');
            const formData = new FormData(event.target);
            formData.append('comment_id', commentId);

            fetch('add_reply.php', {
                method: 'POST',
                body: formData
            }).then(response => response.json()).then(result => {
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
            }).catch(error => {
                console.error('Error:', error);
            });
        }
    });

    // Additional event listeners for upvote, downvote, and report buttons...

});
