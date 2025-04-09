

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
    </div>

    <script>
        const eventId = <?php echo $event_id; ?>;

        async function loadComments() {
            const res = await fetch('../../backend/get_comments.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ event_id: eventId })
            });

            const data = await res.json();
            const container = document.getElementById('comments-container');
            container.innerHTML = '';

            if (data.success && data.comments.length > 0) {
                data.comments.forEach(comment => {
                    const commentBox = document.createElement('div');
                    commentBox.className = 'comment-box';
                    commentBox.innerHTML = `
                        <h3>${comment.username}</h3>
                        <p>${comment.content}</p>
                        <small>${new Date(comment.timestamp).toLocaleString()}</small>
                    `;
                    container.appendChild(commentBox);
                });
            } else {
                container.innerHTML = '<p class="no-content-message">No comments available for this event.</p>';
            }
        }

        window.onload = loadComments;
    </script>
</body>
</html>