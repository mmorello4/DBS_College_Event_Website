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
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="../styles/superadmin-dashboard-style.css">
</head>
<body class="mainpage-background">
    <div class="header-container">
        <h1 class="header-text">Super Admin Dashboard</h1>
        <button class="logout-button" onclick="window.location.href='../../backend/logout.php'">Logout</button>
    </div>

    <div class="container">
        <!-- Pending Event Approval Section -->
        <div class="section">
            <h2>Events Needing Approval</h2>
            <div id="approval-events"></div>
        </div>

        <!-- Create University Redirect -->
        <div class="section">
            <button class="create-event-button" onclick="window.location.href='create-university-page.php'">
                Create New University
            </button>
        </div>
    </div>

    <script>
        const userId = <?php echo $user_id; ?>;

        async function fetchEventsNeedingApproval() {
            const res = await fetch('../../backend/show_approvals.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ uid: userId })
            });
            const data = await res.json();
            const container = document.getElementById('approval-events');
            container.innerHTML = '';

            if (data.success && data.events.length > 0) {
                const table = document.createElement('table');
                table.innerHTML = `
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Date & Time</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.events.map(event => `
                            <tr>
                                <td>${event.Title}</td>
                                <td>${event.Description}</td>
                                <td>${event.EventTime} to ${event.EndTime}</td>
                                <td>${event.ContactEmail}<br>${event.ContactPhone}</td>
                                <td>${event.Location}</td>
                                <td><button onclick="approveEvent(${event.EventID})">Approve</button></td>
                            </tr>
                        `).join('')}
                    </tbody>
                `;
                container.appendChild(table);
            } else {
                container.innerHTML = "<p class='no-content-message'>No pending public events.</p>";
            }
        }

        async function approveEvent(eventId) {
            const res = await fetch('../../backend/approve_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    uid: userId,         // ✅ Include UID for superadmin check
                    event_id: eventId    // Event to approve
                })
            });

            const result = await res.json();
            if (result.success) {
                alert('Event approved!');
                fetchEventsNeedingApproval(); // Refresh the list
            } else {
                alert('Approval failed: ' + result.message);
            }
        }

        window.onload = fetchEventsNeedingApproval;
    </script>
</body>
</html>
