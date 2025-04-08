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
        <form method="post" action="create-rso.php">
            <div>
                <label for="rso_name">RSO Name</label>
                <input type="text" name="rso_name" id="rso_name" required>
            </div>

            <div>
                <label for="rso_description">RSO Description</label>
                <textarea name="rso_description" id="rso_description" rows="4" required></textarea>
            </div>

            <div>
                <label for="university_name">University</label>
                <input type="text" name="University Name" id="university_name" placeholder="University">
            </div>

            <div>
                <label for="admin_email">Admin Email</label>
                <input type="email" name="admin_email" id="admin_email" placeholder="admin@email.com">
            </div>

            <div>
                <label for="members">Additional Members (Comma Separated Emails)</label>
                <input type="text" name="members" id="members" placeholder="email1@domain.com, email2@domain.com">
            </div>

            <button type="submit" class="login-button">Create RSO</button>
        </form>
    </div>
</body>
</html>
