<?php
session_start();

// Check if the user is logged in and if they are a Super Admin
if (!isset($_SESSION['student_id']) || $_SESSION['role'] !== 'Super Admin') {
    // Redirect to login if not logged in or not a Super Admin
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id']; // Get student ID from session
$role = $_SESSION['role']; // Get the role from session (Super Admin)

$conn = new mysqli("localhost", "root", "", "rso_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch universities to associate with RSOs
$query = "SELECT * FROM universities";
$result = $conn->query($query);
$universities = $result->fetch_all(MYSQLI_ASSOC);

// Fetch all events (for Super Admin to view)
$query = "SELECT * FROM events";
$events_result = $conn->query($query);
$events = $events_result->fetch_all(MYSQLI_ASSOC);

// Fetch all RSOs
$query = "SELECT * FROM rsos";
$rsos_result = $conn->query($query);
$rsos = $rsos_result->fetch_all(MYSQLI_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/superadmin-dashboard-style.css">
    <link rel="stylesheet" href="home-page-style.css"> <!-- for gold theme -->
</head>
<body class="mainpage-background">
    <div class="header-container">
        <h1 class="header-text">Super Admin Dashboard</h1>
    </div>

    <div class="container">
        <h2>Create a New University Profile</h2>

        <form method="post" enctype="multipart/form-data">
            <label for="name">University Name:</label>
            <input type="text" id="name" name="name" class="input-field" required>

            <label for="location">Location:</label>
            <input type="text" id="location" name="location" class="input-field" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" class="input-field" rows="4" required></textarea>

            <label for="num_of_students">Number of Students:</label>
            <input type="number" id="num_of_students" name="num_of_students" class="input-field" required>

            <label for="pictures">University Pictures (URLs or file paths):</label>
            <input type="text" id="pictures" name="pictures" class="input-field" placeholder="Comma-separated URLs">

            <button type="submit" name="create_university" class="login-button">Create University Profile</button>
        </form>

        <h2>Actions</h2>
        <p><a href="create-event.php" class="login-button">Create a New Event</a></p>
        <p><a href="create-rso.php" class="login-button">Create a New RSO</a></p>

        <h2>Existing Universities</h2>
        <?php if (empty($universities)): ?>
            <p>No universities available.</p>
        <?php else: ?>
            <?php foreach ($universities as $university): ?>
                <div class="university">
                    <h3><?= htmlspecialchars($university['name']) ?></h3>
                    <p><strong>Location:</strong> <?= htmlspecialchars($university['location']) ?></p>
                    <p><strong>Description:</strong> <?= htmlspecialchars($university['description']) ?></p>
                    <p><strong>Number of Students:</strong> <?= htmlspecialchars($university['num_of_students']) ?></p>
                    <p><strong>Pictures:</strong> <?= htmlspecialchars($university['pictures']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
