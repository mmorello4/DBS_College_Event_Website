<!-- signup.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../styles/signup-style.css">
    <script src="../scripts/home-page-script.js"></script>
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

        <form action="#" method="POST">
            <!-- Email Input -->
            <label for="email" class="login-label">Email</label>
            <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required>

             <!-- Username Input -->
            <label for="username" class="login-label">Username</label>
            <input type="text" id="username" name="username" class="input-field" placeholder="Enter your username" required>

            <!-- Password Field -->
            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password" required>
                <span class="toggle-password" onclick="togglePassword()">👁</span>
            </div>

            <!-- Sign Up Button -->
            <button type="submit" class="signup-button">Sign Up</button>

            <!-- Login Link -->
            <p><a href="home-page.php" class="signup-link">Already registered? Log in</a></p>
        </form>
    </div>
    <!-- End Sign Up Section -->

</body>
</html>
