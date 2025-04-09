<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'SuperAdmin') {
    header('Location: login-page.php');
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create University</title>
    <link rel="stylesheet" href="../styles/create-university-style.css">
</head>
<body class="mainpage-background">
    <div class="header-container">
        <h1 class="header-text">Create University</h1>
        <button class="back-button" onclick="window.location.href='superadmin-dashboard.php'">Back to Dashboard</button>
    </div>

    <div class="container">
        <div class="section">
            <h2>University Information</h2>
            <form id="create-university-form">
                <input type="text" id="university-name" placeholder="University Name" required>
                <input type="text" id="university-domain" placeholder="Domain (e.g., university.edu)" required>
                <input type="text" id="university-location" placeholder="Location" required>
                <input type="number" id="university-students" placeholder="Number of Students">
                <input type="text" id="university-picture" placeholder="Picture URL (optional)">
                <button type="submit">Create</button>
            </form>
        </div>
    </div>

    <script>
        const userId = <?php echo $user_id; ?>;

        document.getElementById("create-university-form").addEventListener("submit", async (e) => {
            e.preventDefault();

            const name = document.getElementById("university-name").value;
            const domain = document.getElementById("university-domain").value;
            const location = document.getElementById("university-location").value;
            const number_of_students = document.getElementById("university-students").value;
            const picture_url = document.getElementById("university-picture").value;

            try {
                const res = await fetch('../../backend/create_university.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        uid: userId,
                        name,
                        domain,
                        location,
                        number_of_students,
                        picture_url
                    })
                });

                const data = await res.json();
                if (data.success) {
                    alert("University created successfully!");
                    // Auto redirect to superadmin-dashboard.php after 2 seconds
                    setTimeout(() => {
                        window.location.href = 'superadmin-dashboard.php';
                    }, 2000);
                } else {
                    alert("Error: " + data.message);
                }
            } catch (error) {
                alert("An error occurred. Please try again.");
                console.error(error);
            }
        });
    </script>
</body>
</html>
