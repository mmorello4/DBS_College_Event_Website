<?php
session_start();

$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Comments</title>
    <link rel="stylesheet" href="../styles/display-comments-style.css">
</head>
<body class="mainpage-background">
    <div class="header-container">
        <h1 class="header-text">Event Comments</h1>
        <button class="logout-button" onclick="window.location.href='../../backend/logout.php'">Logout</button>
    </div>

    <div class="container">
        <div class="section">
            <h2>Comments for Event #<?php echo $event_id; ?></h2>
            <div id="comments-container">
                <p class="no-content-message">Loading comments...</p>
            </div>
        </div>

        <!-- Add Comment Section -->
        <div class="add-comment-section">
            <h3>Add a Comment</h3>
            <textarea id="comment-input" class="comment-input" placeholder="Write your comment..."></textarea>
            <button class="add-comment-button" onclick="addComment()">Add Comment</button>
        </div>

        <!-- Back to Dashboard Button -->
        <div style="text-align: center; margin-top: 20px;">
            <button class="back-button" onclick="window.location.href='student-dashboard.php'">← Back to Dashboard</button>
        </div>

    </div>

    <script>
        const eventId = <?php echo $event_id; ?>;

        const userId = <?php echo $user_id; ?>;
        localStorage.setItem('user_id', userId); // Sync session to frontend

        async function addComment() {
            console.log("user_id from localStorage:", localStorage.getItem('user_id'));
            const commentContent = document.getElementById('comment-input').value;
            if (!commentContent) return;

            const res = await fetch('../../backend/add_comment.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    event_id: eventId,
                    uid: parseInt(userId),  // Pass the UID
                    comment_text: commentContent
                })
            });

            const data = await res.json();
            if (data.success) {
                document.getElementById('comment-input').value = '';
                loadComments();
            } else {
                alert(data.message || 'Failed to add comment');
            }
        }

        async function loadComments() {
            const res = await fetch('../../backend/show_comments.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ event_id: eventId })
            });

            const data = await res.json();
            const container = document.getElementById('comments-container');
            container.innerHTML = '';

            const userId = localStorage.getItem('user_id');

            if (data.success && data.comments.length > 0) {
                data.comments.forEach(comment => {
                    const commentBox = document.createElement('div');
                    commentBox.className = 'comment-box';

                    commentBox.innerHTML = `
                        <p>${comment.CommentText}</p>
                        <small>${new Date(comment.Timestamp).toLocaleString()}</small>
                        ${parseInt(comment.UID) === parseInt(userId) ? `
                            <div style="margin-top: 10px;">
                                <button onclick="editComment(${comment.CommentID}, '${comment.CommentText.replace(/'/g, "\\'")}')">Edit</button>
                                <button onclick="deleteComment(${comment.CommentID})">Delete</button>
                            </div>
                        ` : ''}
                    `;

                    container.appendChild(commentBox);
                });
            } else {
                container.innerHTML = '<p class="no-content-message">No comments available for this event.</p>';
            }
        }

        async function editComment(commentId, currentText) {
            const newText = prompt("Edit your comment:", currentText);
            if (newText && newText !== currentText) {
                const res = await fetch('../../backend/edit_comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        comment_id: commentId,
                        uid: parseInt(localStorage.getItem('user_id')),
                        comment_text: newText
                    })
                });

                const data = await res.json();
                if (data.success) {
                    loadComments();
                } else {
                    alert(data.message || 'Failed to update comment');
                }
            }
        }

        async function deleteComment(commentId) {
            if (confirm("Are you sure you want to delete this comment?")) {
                const res = await fetch('../../backend/delete_comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        comment_id: commentId,
                        uid: parseInt(localStorage.getItem('user_id'))
                    })
                });

                const data = await res.json();
                if (data.success) {
                    loadComments();
                } else {
                    alert(data.message || 'Failed to delete comment');
                }
            }
        }


        window.onload = loadComments;
    </script>
</body>
</html>
