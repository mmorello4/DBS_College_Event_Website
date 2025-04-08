<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../styles/student-dashboard-style.css">
    <link rel="stylesheet" href="../styles/login-style.css"> <!-- for gold theme -->
    <script>
        // Simulated user role (this would come from your backend or session)
        const userRole = 'Admin';  // Change this to test different roles, e.g., 'Student', 'Super Admin'

        // Fetch events tied to RSOs the user is participating in
        async function fetchEvents() {
            try {
                const response = await fetch('../../backend/get_events.php');
                const events = await response.json();
                displayUserEvents(events);
            } catch (error) {
                console.error('Error fetching events:', error);
            }
        }

        // Display the events the user is participating in (public & private)
        function displayUserEvents(events) {
            const eventsContainer = document.getElementById('user-events-container');
            eventsContainer.innerHTML = ''; // Clear previous content

            if (events.length === 0) {
                eventsContainer.innerHTML = '<p>No events found.</p>';
            } else {
                const userEvents = events.filter(event => event.is_public || event.is_private); // Only public and private events
                userEvents.forEach(event => {
                    const eventElement = document.createElement('div');
                    eventElement.classList.add('event');
                    eventElement.innerHTML = `
                        <strong>${event.event_name}</strong> - ${event.description}<br>
                        <span class="contact-info">${event.date} at ${event.time}, ${event.location}</span>
                        <button class="join-button">Join Event</button>
                    `;
                    eventsContainer.appendChild(eventElement);
                });
            }
        }

        window.onload = fetchEvents;

        // Display search results for Events
        function showEventSearchResults(results) {
            const resultContainer = document.getElementById('event-search-results');
            resultContainer.innerHTML = ''; // Clear previous results

            if (results.length === 0) {
                resultContainer.innerHTML = `<p>No events found.</p>`;
            } else {
                const list = results.map(result => `
                    <div class="search-result">
                        <strong>${result.event_name}</strong><br>
                        ${result.description} - ${result.date} at ${result.time}
                        <button class="join-button">Join</button>
                    </div>
                `).join('');
                resultContainer.innerHTML = list;
            }
        }

        // Display the RSOs the user is part of, and their events
        function displayRSOsAndEvents(rsos) {
            const rsoContainer = document.getElementById('rso-container');
            rsoContainer.innerHTML = ''; // Clear previous content

            if (rsos.length === 0) {
                rsoContainer.innerHTML = '<p>You are not part of any RSOs.</p>';
            } else {
                rsos.forEach(rso => {
                    const rsoSection = document.createElement('div');
                    rsoSection.classList.add('rso-section');
                    const rsoTitle = document.createElement('h3');
                    rsoTitle.innerText = `RSO: ${rso.rso_name}`;
                    rsoSection.appendChild(rsoTitle);

                    const eventList = rso.events.map(event => `
                        <div class="event">
                            <strong>${event.event_name}</strong> - ${event.description}
                            <div class="contact-info">${event.date} at ${event.time}, ${event.location}</div>
                            <button class="join-button">Join Event</button>
                        </div>
                    `).join('');
                    rsoSection.innerHTML += eventList;
                    rsoContainer.appendChild(rsoSection);
                });
            }
        }

        // Display search results for RSOs
        function showRSOSearchResults(results) {
            const resultContainer = document.getElementById('rso-search-results');
            resultContainer.innerHTML = ''; // Clear previous results

            if (results.length === 0) {
                resultContainer.innerHTML = `<p>No RSOs found.</p>`;
            } else {
                const list = results.map(result => `
                    <div class="search-result">
                        <strong>${result.rso_name}</strong><br>
                        ${result.description}
                        <button class="join-button">Join</button>
                    </div>
                `).join('');
                resultContainer.innerHTML = list;
            }
        }

        // Show "Create Event" button only if the user is an Admin
        function showCreateEventButton() {
            const createEventButtonContainer = document.getElementById('create-event-button-container');
            if (userRole === 'Admin') {
                createEventButtonContainer.innerHTML = `
                    <button class="create-event-button" onclick="window.location.href='create-event-page.php'">Create New Event</button>
                `;
            }
        }

        // Call this on page load
        window.onload = function() {
            fetchEvents();
            showCreateEventButton();  // Show the button if the user is Admin
        };
    </script>
</head>
<body class="mainpage-background">
    <div class="header-container">
        <h1 class="header-text">Student Event Dashboard</h1>
    </div>

    <div class="container">
        <!-- User's Public and Private Events -->
        <div class="section">
            <h2>Your Events (Public and Private)</h2>
            <div id="user-events-container"></div>
        </div>

        <!-- Search Events Section -->
        <div class="search-bar">
            <label for="event_search" class="search-label">Search Events:</label>
            <input type="text" name="event_search" id="event_search" class="input-field" placeholder="Search events...">
            <div class="button-group">
                <button type="submit" class="search-button" onclick="showEventSearchResults([])">Search</button>
                <button type="reset" class="clear-button">Clear</button>
            </div>
            <div id="event-search-results" class="search-results"></div>

            <!-- Create Event Button (Only for Admins) -->
            <div id="create-event-button-container"></div>
        </div>

        <!-- RSOs the user is part of and their events -->
        <div class="section">
            <h2>Your RSOs and Events</h2>
            <div id="rso-container"></div>
        </div>

        <!-- Search RSOs Section -->
        <div class="search-bar">
            <label for="rso_search" class="search-label">Search RSOs:</label>
            <input type="text" name="rso_search" id="rso_search" class="input-field" placeholder="Search RSOs...">
            <div class="button-group">
                <button type="submit" class="search-button" onclick="showRSOSearchResults([])">Search</button>
                <button type="reset" class="clear-button">Clear</button>
            </div>
            <div id="rso-search-results" class="search-results"></div>

            <!-- Create RSO Button -->
            <div class="create-rso">
                <button class="create-rso-button" onclick="window.location.href='create-rso-page.php'">Create New RSO</button>
            </div>
        </div>
    </div>
</body>
</html>
