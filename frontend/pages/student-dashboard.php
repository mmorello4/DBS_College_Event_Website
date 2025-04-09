<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../styles/student-dashboard-style.css">
    <link rel="stylesheet" href="../styles/login-style.css"> <!-- for gold theme -->
    <script>
        const userRole = 'Admin';  // Simulated user role

        async function fetchRSOs() {
            try {
                const response = await fetch('../../backend/get_rsos.php');
                const rsos = await response.json();
                displayRSOs(rsos);
            } catch (error) {
                console.error('Error fetching RSOs:', error);
            }
        }

        async function fetchEvents() {
            try {
                const response = await fetch('../../backend/get_events.php');
                const events = await response.json();
                displayEvents(events, 'public'); // Default to public events
            } catch (error) {
                console.error('Error fetching events:', error);
            }
        }

        function displayRSOs(rsos) {
            const rsoContainer = document.getElementById('rso-container');
            rsoContainer.innerHTML = '';
            if (rsos.length === 0) {
                rsoContainer.innerHTML = '<p class="no-content-message">No RSOs available.</p>';
            } else {
                rsos.forEach(rso => {
                    const rsoElement = document.createElement('div');
                    rsoElement.classList.add('rso-section');
                    rsoElement.innerHTML = `
                        <h3>${rso.rso_name}</h3>
                        <p>${rso.description}</p>
                        <button class="join-button">Join</button>
                    `;
                    rsoContainer.appendChild(rsoElement);
                });
            }
        }

        function displayEvents(events, eventType) {
            const eventContainer = document.getElementById('events-table-body');
            eventContainer.innerHTML = '';  // Clear previous events
            const filteredEvents = events.filter(event => event.type === eventType || eventType === 'public');
            if (filteredEvents.length === 0) {
                eventContainer.innerHTML = '<tr><td colspan="5" class="no-content-message">No events found.</td></tr>';
            } else {
                filteredEvents.forEach(event => {
                    const eventRow = document.createElement('tr');
                    eventRow.innerHTML = `
                        <td>${event.event_name}</td>
                        <td>${event.description}</td>
                        <td>${event.date} at ${event.time}</td>
                        <td>${event.location}</td>
                        <td><button class="join-button">Join Event</button></td>
                    `;
                    eventContainer.appendChild(eventRow);
                });
            }
        }

        function showCreateEventButton() {
            if (userRole === 'Admin') {
                document.getElementById('create-event-button-container').innerHTML = `
                    <button class="create-event-button" onclick="window.location.href='create-event-page.php'">Create New Event</button>
                `;
            }
        }

        window.onload = function() {
            fetchRSOs();
            fetchEvents();
            showCreateEventButton();
        };

        function changeEventTab(eventType) {
            fetchEventsByType(eventType);
        }

        function fetchEventsByType(eventType) {
            fetch('../../backend/get_events.php')  // Replace with appropriate filter if necessary
                .then(response => response.json())
                .then(events => displayEvents(events, eventType));
        }
    </script>
</head>
<body class="mainpage-background">
    <div class="header-container">
        <h1 class="header-text">Student Event Dashboard</h1>
    </div>

    <div class="container">
        <div class="section">
            <h2>Your RSOs:</h2>
            <div id="rso-container"></div>
            <div class="create-rso">
                <button class="create-rso-button" onclick="window.location.href='create-rso-page.php'">Create New RSO</button>
            </div>
        </div>

        <div class="section">
            <h2>Events:</h2>
            <div id="events-tabs">
                <button onclick="changeEventTab('public')">Public Events</button>
                <button onclick="changeEventTab('private')">Private Events</button>
                <button onclick="changeEventTab('rso')">RSO Events</button>
            </div>
            <table id="events-table">
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Description</th>
                        <th>Date & Time</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="events-table-body"></tbody>
            </table>
            <div id="create-event-button-container"></div>
        </div>
    </div>
</body>
</html>

