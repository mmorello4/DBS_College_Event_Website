<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in by verifying the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Fetch user data from session variables
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../styles/dashboard-style.css">
</head>
<body class="dashboard-background">

    <!-- Header Section -->
    <div class="header-container">
        <h1 class="header-text">Welcome to Your Dashboard</h1>
    </div>

    <!-- Dashboard Content Section -->
    <div class="dashboard-container">
        <h2 class="greeting-text">Hello, <?php echo htmlspecialchars($username); ?>!</h2>
        <p class="user-info">User ID: <?php echo htmlspecialchars($user_id); ?></p>
        <p class="user-info">Username: <?php echo htmlspecialchars($username); ?></p>

        <div class="action-buttons">
            <a href="profile.php" class="action-button">View Profile</a>
            <a href="logout.php" class="action-button">Logout</a>
        </div>
    </div>

</body>
</html>
