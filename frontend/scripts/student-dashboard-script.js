// Function to handle the "Join Event" or "Join RSO" button click
async function handleJoinButtonClick(event, isEvent) {
    const button = event.target;
    const rsoID = button.getAttribute('data-rso-id'); // Get the RSO ID from the button's data attribute
    const uid = 1; // Replace with the actual logged-in user's UID

    const endpoint = isEvent ? 'join_event.php' : 'join_rso.php'; // Use the appropriate endpoint

    // Prepare the data to send to the backend
    const requestData = {
        uid: uid,
        rsoid: rsoID
    };

    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(requestData)
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message); // Show success message
            button.disabled = true; // Disable the button to prevent joining multiple times
        } else {
            alert(result.message); // Show failure message
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Something went wrong while joining the RSO.');
    }
}

// Add event listeners to all Join buttons dynamically created for each event/RSO
function addJoinButtonListeners() {
    // Find all the join buttons for RSOs
    const joinButtons = document.querySelectorAll('.join-button');

    joinButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const isEvent = button.classList.contains('event-join'); // Check if it's an event button
            handleJoinButtonClick(event, !isEvent); // If it's not an event, it's an RSO
        });
    });
}

// This function will be triggered after the user is shown the events or RSOs
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
                    <button class="join-button event-join" data-rso-id="${rso.rso_id}">Join Event</button>
                </div>
            `).join('');
            rsoSection.innerHTML += eventList;
            rsoContainer.appendChild(rsoSection);
        });
    }

    addJoinButtonListeners(); // Add event listeners for the join buttons
}
