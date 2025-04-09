<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Event Website</title>
    <link rel="stylesheet" href="../styles/login-style.css">
</head>
<body class="mainpage-background">

    <!-- Header Section -->
    <div class="header-container">
        <h1 class="header-text">College Event Website</h1>
    </div>

    <!-- Login Form Section -->
    <div class="login-container">
        <h2 class="login-text">Log in</h2>

        <!-- Error message container (hidden by default) -->
        <p id="error-message" style="color:red; display:none;"></p>
        
        <form id="loginForm" onsubmit="event.preventDefault(); loginUser();">
            <label for="email" class="login-label">Email</label>
            <input type="email" id="email" name="email" class="input-field" required>

            <label for="password" class="login-label">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" class="input-field" required>
                <span class="toggle-password" onclick="togglePassword()">👁</span>
            </div>

            <button type="submit" class="login-button">Log in</button>
            <p><a href="signup-page.php" class="signup-link">Not Registered? Sign Up</a></p>
        </form>

    </div>

    <script>
        // Handle toggling password visibility
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.textContent = "🙈";
            } else {
                passwordField.type = "password";
                toggleIcon.textContent = "👁";
            }
        }

        // Login AJAX function
        function loginUser() {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            // Prepare data for sending
            const loginData = {
                email: email,
                password: password
            };

            // Make the AJAX request
            fetch('../../backend/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(loginData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // On success, save user data in localStorage (or sessionStorage)
                    localStorage.setItem('user_id', data.uid);
                    localStorage.setItem('role', data.role);
                    localStorage.setItem('university_id', data.university_id);

                    // Redirect to the dashboard
                    window.location.href = 'student-dashboard.php';  // Or redirect based on role
                } else {
                    // Display error message if login failed
                    document.getElementById('error-message').textContent = data.error;
                    document.getElementById('error-message').style.display = 'block';
                }
            })
            .catch(error => {
                // Handle any errors from the AJAX request
                document.getElementById('error-message').textContent = "An error occurred. Please try again.";
                document.getElementById('error-message').style.display = 'block';
            });
        }
    </script>

</body>
</html>
