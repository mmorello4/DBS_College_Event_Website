

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../styles/signup-style.css">
    <script src="../scripts/signup-script.js"></script>
</head>
<body class="signup-background">

    <!-- Header Section -->
    <div class="header-container">
        <h1 class="header-text">College Event Website</h1>
    </div>
    <!-- End Header Section -->

    <!-- Sign Up Section -->
    <div class="signup-container">
        <h2 class="signup-text">Sign Up</h2>

        <!-- Start form tag -->
        <form id="signupForm">
        <!-- Name Input -->
            <label for="name" class="login-label">Full Name</label>
            <input type="text" id="name" name="name" class="input-field" placeholder="Enter your full name" required>

            <!-- Email Input -->
            <label for="email" class="login-label">Email</label>
            <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required>

            <!-- Password Field -->
            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password" required>
                <span class="toggle-password" onclick="togglePassword()">👁</span>
            </div>

            <!-- University Input -->
            <label for="university" class="login-label">University</label>
            <input type="text" id="university" name="university" class="input-field" placeholder="Enter your university" required>

            <!-- Sign Up Button -->
            <button type="submit" class="signup-button">Sign Up</button>

            <!-- Login Link -->
            <p><a href="login-page.php" class="signup-link">Already registered? Log in</a></p>
        </form>
        <!-- End form tag -->
    </div>
</body>
</html>
