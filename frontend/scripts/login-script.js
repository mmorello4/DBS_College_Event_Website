document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");

    form.addEventListener("submit", async function (e) {
        e.preventDefault(); // Stop normal form submission
  
        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;

        const data = {
            email: email,
            password: password
        };

        fetch('../../backend/login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json' // Send JSON content type
            },
            body: JSON.stringify(data) // Convert the data to a JSON string
        })
        .then(response => {
            if (!response.ok) {
                // If the response is not OK, show the error message
                return response.text().then(text => { throw new Error(text); });
            }
            return response.json(); // Parse JSON response
        })
        .then(data => {
            if (data.success) {
                window.location.href = 'student-dashboard.php'; // Redirect to dashboard
            } else {
                alert('Login failed: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred: ' + error.message); // Display an error message
        });
    });
});
