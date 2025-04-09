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
    <title>Create Event</title>
    <link rel="stylesheet" href="../styles/create-event-style.css">
</head>
<body>
    <div class="container">
        <h1>Create an Event</h1>
        
        <!-- Response message -->
        <div id="response-message" style="margin-top: 15px; font-weight: bold;"></div>

        <!-- Event Creation Form -->
        <form id="create-event-form">
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
                    <label for="event_date">Start Date</label>
                    <input type="date" name="event_date" id="event_date" required>
                </div>
                <div>
                    <label for="event_end_date">End Date</label>
                    <input type="date" name="event_end_date" id="event_end_date" required>
                </div>
            </div>

            <div class="flex-container">
                <div>
                    <label for="event_time">Start Time</label>
                    <input type="time" name="event_time" id="event_time" required>
                </div>
                <div>
                    <label for="event_end_time">End Time</label>
                    <input type="time" name="event_end_time" id="event_end_time" required>
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

    <script>
        document.getElementById('create-event-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            // Collect form data
            const event_name = document.getElementById('event_name').value;
            const event_type = document.getElementById('event_type').value;
            const rso_name = document.getElementById('rso_name').value;
            const event_date = document.getElementById('event_date').value;
            const event_end_date = document.getElementById('event_end_date').value;
            const event_time = document.getElementById('event_time').value;
            const event_end_time = document.getElementById('event_end_time').value;
            const event_location = document.getElementById('event_location').value;
            const contact_phone = document.getElementById('contact_phone').value;
            const contact_email = document.getElementById('contact_email').value;

            const data = {
                Event_Name: event_name,
                Event_Description: '',
                Type: event_type,
                RSO: rso_name,
                Date: event_date,
                Time: event_time,
                End_Date: event_end_date,
                End_Time: event_end_time,
                Location: event_location,
                Contact_Phone: contact_phone,
                Contact_Email: contact_email
            };

            try {
                const response = await fetch('../../backend/create_event.php', {
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
                    document.getElementById('create-event-form').reset();

                    // Redirect to the dashboard after successful creation
                    setTimeout(() => {
                        // Redirect to the student dashboard
                        window.location.href = '../pages/student-dashboard.php';
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
