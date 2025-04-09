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

        <!-- Create University Section -->
        <div class="section">
            <h2>Create University</h2>
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

        async function fetchEventsNeedingApproval() {
            const res = await fetch('../../backend/show_approvals.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ uid: userId })
            });
            const data = await res.json();
            if (data.success) {
                const container = document.getElementById('approval-events');
                container.innerHTML = '';
                if (data.events.length === 0) {
                    container.innerHTML = "<p class='no-content-message'>No pending public events.</p>";
                    return;
                }

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
            }
        }

        async function approveEvent(eventId) {
            const res = await fetch('../../backend/approve_event.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ event_id: eventId })
            });
            const result = await res.json();
            if (result.success) {
                alert('Event approved!');
                fetchEventsNeedingApproval();
            } else {
                alert('Approval failed: ' + result.message);
            }
        }

        document.getElementById("create-university-form").addEventListener("submit", async (e) => {
            e.preventDefault();

            const name = document.getElementById("university-name").value;
            const domain = document.getElementById("university-domain").value;
            const location = document.getElementById("university-location").value;
            const number_of_students = document.getElementById("university-students").value;
            const picture_url = document.getElementById("university-picture").value;

            const res = await fetch('../../backend/create_university.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
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
                alert("University created!");
                e.target.reset();
            } else {
                alert("Error: " + data.message);
            }
        });


        window.onload = fetchEventsNeedingApproval;
    </script>
</body>
</html>
