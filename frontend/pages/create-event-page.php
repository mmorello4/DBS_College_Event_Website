
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
