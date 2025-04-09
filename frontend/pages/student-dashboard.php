<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login-page.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../styles/student-dashboard-style.css">
    <link rel="stylesheet" href="../styles/login-style.css">
    <script>
        const userRole = '<?php echo $user_role; ?>';
        const userId = <?php echo $user_id; ?>;

        async function fetchRSOs() {
            try {
                const response = await fetch('../../backend/get_rsos.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ uid: userId }),
                });
                const data = await response.json();
                displayRSOs(data);
            } catch (error) {
                console.error('Error fetching RSOs:', error);
            }
        }

        function displayRSOs(data) {
            const rsoContainer = document.getElementById('rso-container');
            rsoContainer.innerHTML = '';

            if (data.success && data.rsos.length > 0) {
                const rsoTable = document.createElement('table');
                rsoTable.innerHTML = `
                    <thead>
                        <tr>
                            <th>RSO Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody id="rso-table-body">
                    </tbody>
                `;
                const tableBody = rsoTable.querySelector('#rso-table-body');

                data.rsos.forEach(rso => {
                    const rsoRow = document.createElement('tr');
                    rsoRow.innerHTML = `
                        <td>${rso.name}</td>
                        <td>${rso.description}</td>
                    `;
                    tableBody.appendChild(rsoRow);
                });

                rsoContainer.appendChild(rsoTable);
            } else {
                rsoContainer.innerHTML = '<p class="no-content-message">You are not a member of any RSOs.</p>';
            }
        }

        async function searchRSOs() {
            const query = document.getElementById('rso-search').value.trim();
            if (!query) {
                alert('Please enter a search term!');
                return;
            }

            try {
                const response = await fetch('../../backend/search_rso.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ query: query, uid: userId }),
                });
                const data = await response.json();
                displaySearchResults(data);
            } catch (error) {
                console.error('Error searching RSOs:', error);
            }
        }

        function displaySearchResults(data) {
            const resultsContainer = document.getElementById('search-results');
            resultsContainer.innerHTML = '';

            if (data.success && data.rsos.length > 0) {
                const searchTable = document.createElement('table');
                searchTable.innerHTML = `
                    <thead>
                        <tr>
                            <th>RSO Name</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody id="search-table-body">
                    </tbody>
                `;
                const tableBody = searchTable.querySelector('#search-table-body');

                const rso = data.rsos[0];
                const rsoRow = document.createElement('tr');
                rsoRow.innerHTML = `
                    <td>${rso.name}</td>
                    <td>${rso.description}</td>
                `;
                tableBody.appendChild(rsoRow);

                resultsContainer.appendChild(searchTable);
            } else {
                resultsContainer.innerHTML = '<p class="no-content-message">No RSOs found matching your search.</p>';
            }
        }

        async function fetchEventsByType(eventType) {
            try {
                const response = await fetch('../../backend/get_events.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ type: eventType, uid: userId }),
                });
                const events = await response.json();
                displayEvents(events, eventType);
            } catch (error) {
                console.error('Error fetching events:', error);
            }
        }

        function displayEvents(events, eventType) {
            const eventContainer = document.getElementById('events-table-body');
            eventContainer.innerHTML = '';

            if (events.length === 0) {
                eventContainer.innerHTML = `<tr><td colspan="4" class="no-content-message">No ${eventType} events found.</td></tr>`;
            } else {
                events.forEach(event => {
                    const eventRow = document.createElement('tr');
                    eventRow.innerHTML = ` 
                        <td>${event.title}</td>
                        <td>${event.description}</td>
                        <td>${event.event_time} to ${event.end_time}</td>
                        <td>${event.location}</td>
                        <td><button onclick="showComments(${event.event_id})">Show Comments</button></td>
                    `;

                    eventContainer.appendChild(eventRow);
                });
            }
        }
        
        function showComments(eventId) {
            window.location.href = `display-comments.php?event_id=${eventId}`;
        }

        function showCreateEventButton() {
            if (userRole === 'Admin') {
                document.getElementById('create-event-button-container').innerHTML = ` 
                    <button class="create-event-button" onclick="window.location.href='create-event-page.php'">Create New Event</button>
                `;
            }
        }

        async function changeEventTab(eventType) {
            fetchEventsByType(eventType);
        }

        window.onload = function() {
            fetchRSOs();
            fetchEventsByType('public');
            showCreateEventButton();
        };
    </script>
</head>
<body class="mainpage-background">
    <div class="header-container">
        <h1 class="header-text">Student Event Dashboard</h1>
        <button class="logout-button" onclick="window.location.href='../../backend/logout.php'">Logout</button>
    </div>

    <div class="container">
        <div class="section">
            <h2>Search RSOs:</h2>
            <div class="rso-search-container">
                <input type="text" id="rso-search" placeholder="Search RSOs...">
                <button onclick="searchRSOs()">Search</button>
            </div>
            <div class="create-rso">
                <button class="create-rso-button" onclick="window.location.href='create-rso-page.php'">Create New RSO</button>
            </div>
            <div id="search-results"></div>
        </div>

        <div class="section">
            <h2>Your RSOs:</h2>
            <div id="rso-container"></div>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="events-table-body"></tbody>
            </table>

            <div id="create-event-button-container"></div>
        </div>
    </div>
</body>
</html>
