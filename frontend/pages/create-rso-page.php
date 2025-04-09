<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login-page.php');  // Redirect if not logged in
    exit();
}

$user_id = $_SESSION['user_id'];  // Retrieve user ID from session
$user_role = $_SESSION['role'];  // Retrieve user role from session
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create RSO</title>
    <link rel="stylesheet" href="../styles/student-dashboard-style.css">
    <link rel="stylesheet" href="../styles/create-rso-style.css"> <!-- for gold theme -->
</head>
<body class="mainpage-background">
    <div class="container">
        <div class="header-container">
            <h1 class="header-text">Create a New RSO</h1>
        </div>

        <!-- RSO Creation Form -->
        <form id="create-rso-form">
            <div>
                <label for="rso_name">RSO Name</label>
                <input type="text" name="rso_name" id="rso_name" required>
            </div>

            <div>
                <label for="rso_description">RSO Description</label>
                <textarea name="rso_description" id="rso_description" rows="4" required></textarea>
            </div>

            <div>
                <label for="university_name">University Name</label>
                <input type="text" name="university_name" id="university_name" placeholder="Enter University Name" required>
            </div>

            <div>
                <label for="admin_email">Admin Email</label>
                <input type="email" name="admin_email" id="admin_email" placeholder="admin@email.com" required>
            </div>

            <div>
                <label for="members">Additional Members (Comma Separated Emails)</label>
                <input type="text" name="members" id="members" placeholder="email1@domain.com, email2@domain.com" required>
            </div>

            <button type="submit" class="login-button">Create RSO</button>
        </form>

        <div id="response-message" style="margin-top: 15px; font-weight: bold;"></div>
    </div>

    <script>
        document.getElementById('create-rso-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const rso_name = document.getElementById('rso_name').value;
            const rso_description = document.getElementById('rso_description').value;
            const university_name = document.getElementById('university_name').value;
            const admin_email = document.getElementById('admin_email').value;
            const membersRaw = document.getElementById('members').value;
            const member_emails = membersRaw.split(',').map(email => email.trim()).filter(email => email !== '');

            const data = {
                name: rso_name,
                description: rso_description,
                university_name: university_name, // <-- send name, not id
                admin_email: admin_email,
                member_emails: member_emails
            };

            try {
                const response = await fetch('../../backend/create_rso.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                const messageBox = document.getElementById('response-message');

                if (result.success) {
                    messageBox.style.color = 'green';
                    messageBox.textContent = result.message;
                    document.getElementById('create-rso-form').reset();

                    // Redirect to the dashboard after successful creation
                    setTimeout(() => {
                        window.location.href = '../pages/student-dashboard.php';  // Adjust the URL to your dashboard page
                    }, 2000);  // Delay for 2 seconds to show the success message
                } else {
                    messageBox.style.color = 'red';
                    messageBox.textContent = result.message;
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('response-message').textContent = 'An error occurred. Please try again.';
            }
        });
    </script>
</body>
</html>
