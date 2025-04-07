<?php
// Admin Dashboard Script

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login if not an admin
    exit();
}

$admin_id = $_SESSION['admin_id']; // Get admin ID from session

// Database connection
$conn = new mysqli("localhost", "root", "", "rso_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch events for the admin
$query = "
    SELECT E.event_id, R.rso_name, E.event_name, E.event_category, E.description, E.date, E.time, E.location, E.latitude, E.longitude, E.contact_phone, E.contact_email
    FROM Events E
    JOIN RSOs R ON E.rso_id = R.rso_id
    WHERE E.admin_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $admin_id);
$stmt->execute();
$result = $stmt->get_result();

$eventsByRSO = [];
while ($row = $result->fetch_assoc()) {
    $eventsByRSO[$row['rso_name']][] = $row;
}

// Handle event creation form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_event'])) {
    // Collect form data
    $event_name = $_POST['event_name'];
    $event_category = $_POST['event_category'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $contact_phone = $_POST['contact_phone'];
    $contact_email = $_POST['contact_email'];

    // Insert new event into the database
    $insertQuery = "
        INSERT INTO Events (admin_id, event_name, event_category, description, date, time, location, latitude, longitude, contact_phone, contact_email)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("isssssssssss", $admin_id, $event_name, $event_category, $description, $date, $time, $location, $latitude, $longitude, $contact_phone, $contact_email);

    if ($stmt->execute()) {
        echo "<script>alert('Event created successfully!');</script>";
    } else {
        echo "<script>alert('Error creating event: " . $stmt->error . "');</script>";
    }
}

// Close DB connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/admin-dashboard-style.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places&callback=initMap" async defer></script>
</head>
<body class="mainpage-background">

    <!-- Header Section -->
    <div class="header-container">
        <h1 class="header-text">Admin Event Dashboard</h1>
    </div>

    <!-- Admin Dashboard Navigation Section -->
    <div class="navigation-container">
        <a href="create-rso.php" class="btn">Create RSO</a> <!-- Redirect to RSO creation page -->
        <a href="create-event.php" class="btn">Create Event</a> <!-- Redirect to Event creation page -->
    </div>

    <!-- Admin Event Creation Section -->
    <div class="form-container">
        <h2>Create Event</h2>
        <form action="admin-dashboard.php" method="POST">
            <!-- Event Name -->
            <label for="event_name">Event Name</label>
            <input type="text" name="event_name" required>

            <!-- Event Category -->
            <label for="event_category">Event Category</label>
            <select name="event_category" required>
                <option value="Workshops">Workshops</option>
                <option value="Meetups">Meetups</option>
                <option value="Seminars">Seminars</option>
                <option value="Conferences">Conferences</option>
                <!-- Add more categories as needed -->
            </select>

            <!-- Event Description -->
            <label for="description">Event Description</label>
            <textarea name="description" rows="4" required></textarea>

            <!-- Event Date & Time -->
            <label for="date">Event Date</label>
            <input type="date" name="date" required>
            
            <label for="time">Event Time</label>
            <input type="time" name="time" required>

            <!-- Event Location (Map API Integration) -->
            <label for="location">Event Location</label>
            <input type="text" name="location" id="location" required placeholder="Select from map">
            <button type="button" onclick="openMap()">Set Location on Map</button>

            <!-- Latitude and Longitude -->
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <!-- Contact Info -->
            <label for="contact_phone">Contact Phone</label>
            <input type="text" name="contact_phone" required>

            <label for="contact_email">Contact Email</label>
            <input type="email" name="contact_email" required>

            <!-- Submit Button -->
            <button type="submit" name="create_event">Create Event</button>
        </form>
    </div>

    <!-- Existing Events Section -->
    <div class="events-container">
        <h2>Events by RSO</h2>

        <?php if (empty($eventsByRSO)): ?>
            <p>No events available.</p>
        <?php else: ?>
            <?php foreach ($eventsByRSO as $rsoName => $events): ?>
                <div class="section">
                    <h3 class="section-title"><?= htmlspecialchars($rsoName) ?></h3>
                    <?php foreach ($events as $event): ?>
                        <div class="event">
                            <strong><?= htmlspecialchars($event['event_name']) ?></strong> (<?= htmlspecialchars($event['event_category']) ?>)<br>
                            <?= htmlspecialchars($event['description']) ?><br>
                            <span class="contact-info"><?= htmlspecialchars($event['date']) ?> at <?= htmlspecialchars($event['time']) ?>, <?= htmlspecialchars($event['location']) ?></span><br>
                            Contact: <?= htmlspecialchars($event['contact_phone']) ?> | <?= htmlspecialchars($event['contact_email']) ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        // Initialize Google Maps API for location selection
        function initMap() {
            const input = document.getElementById('location');
            const autocomplete = new google.maps.places.Autocomplete(input);

            autocomplete.addListener('place_changed', function() {
                const place = autocomplete.getPlace();
                if (place.geometry) {
                    document.getElementById('latitude').value = place.geometry.location.lat();
                    document.getElementById('longitude').value = place.geometry.location.lng();
                }
            });
        }

        // Open Google Maps for selecting location
        function openMap() {
            window.open('https://www.google.com/maps', '_blank');
        }
    </script>

</body>
</html>
