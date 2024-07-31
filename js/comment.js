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
            console.log(result);
            // Optionally refresh comments list or update the UI
        });
    });

    document.querySelectorAll('.reply-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm.style.display === 'none' || replyForm.style.display === '') {
                replyForm.style.display = 'block';
            } else {
                replyForm.style.display = 'none';
            }
        });
    });

    document.querySelectorAll('.reply-form-content').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const commentId = this.getAttribute('data-comment-id');
            const formData = new FormData(this);
            formData.append('comment_id', commentId);

            fetch('add_reply.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text()).then(result => {
                console.log(result);
                // Optionally refresh replies list or update the UI
                // Hide reply form and reset form
                document.getElementById(`reply-form-${commentId}`).style.display = 'none';
                this.reset();
            }).catch(error => {
                console.error('Error:', error);
            });
        });
    });

    document.querySelectorAll('.upvote-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            fetch('vote_comment.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `comment_id=${commentId}&vote_type=upvote`
            }).then(response => response.text()).then(result => {
                console.log(result);
                // Optionally update upvote count
            });
        });
    });

    document.querySelectorAll('.downvote-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            fetch('vote_comment.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `comment_id=${commentId}&vote_type=downvote`
            }).then(response => response.text()).then(result => {
                console.log(result);
                // Optionally update downvote count
            });
        });
    });

    document.querySelectorAll('.report-button').forEach(button => {
        button.addEventListener('click', function() {
            const commentId = this.getAttribute('data-comment-id');
            fetch('report_comment.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `comment_id=${commentId}`
            }).then(response => response.text()).then(result => {
                console.log(result);
                // Optionally provide feedback to the user
            });
        });
    });
});
