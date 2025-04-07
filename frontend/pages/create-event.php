<?php
// Start session to check if the user is logged in (assumed as a student in this case)
session_start();

if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if the student is not logged in
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id']; // Get student ID from session

// Create a database connection
$conn = new mysqli("localhost", "root", "", "rso_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get event data from the form
    $event_name = $_POST['event_name'];
    $event_type = $_POST['event_type'];
    $rso_name = $_POST['rso_name']; // For RSO event
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $event_location = $_POST['event_location'];
    $contact_phone = $_POST['contact_phone'];
    $contact_email = $_POST['contact_email'];

    // Prevent overlapping events (same location, same time)
    $query = "
        SELECT * FROM Events
        WHERE event_date = ? AND event_time = ? AND location = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $event_date, $event_time, $event_location);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "An event is already scheduled at this location and time.";
    } else {
        // Insert the event into the database
        $stmt = $conn->prepare("
            INSERT INTO Events (event_name, event_type, rso_name, event_date, event_time, location, contact_phone, contact_email)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssssss", $event_name, $event_type, $rso_name, $event_date, $event_time, $event_location, $contact_phone, $contact_email);
        $stmt->execute();

        // Redirect to the event dashboard after success
        header("Location: student-dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Event</title>
    <link rel="stylesheet" href="../styles/create-event-style.css">
</head>
<body>
    <div class="container">
        <h1>Create an Event</h1>
        
        <?php if (isset($error_message)): ?>
            <p class="error"><?= $error_message ?></p>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="event_name">Event Name</label>
                <input type="text" name="event_name" id="event_name" required>
            </div>

            <div>
                <label for="event_type">Event Type</label>
                <select name="event_type" id="event_type" required>
                    <option value="RSO">RSO</option>
                    <option value="Private">Private</option>
                    <option value="Public">Public</option>
                </select>
            </div>

            <div>
                <label for="rso_name">RSO (for RSO event)</label>
                <input type="text" name="rso_name" id="rso_name">
            </div>

            <div class="flex-container">
                <div>
                    <label for="event_date">Date</label>
                    <input type="date" name="event_date" id="event_date" required>
                </div>
                <div>
                    <label for="event_time">Time</label>
                    <input type="time" name="event_time" id="event_time" required>
                </div>
            </div>

            <div>
                <label for="event_location">Location</label>
                <input type="text" name="event_location" id="event_location" required>
            </div>

            <div class="flex-container">
                <div>
                    <label for="contact_phone">Contact Phone</label>
                    <input type="tel" name="contact_phone" id="contact_phone" required>
                </div>
                <div>
                    <label for="contact_email">Contact Email</label>
                    <input type="email" name="contact_email" id="contact_email" required>
                </div>
            </div>

            <button type="submit" class="btn">Create Event</button>
        </form>
    </div>
</body>
</html>
