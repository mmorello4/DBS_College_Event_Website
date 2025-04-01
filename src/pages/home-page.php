<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="../styles/home-page-style.css">
    <script src="../scripts/home-page-script.js"></script>
<body class="mainpage-background">

    <!-- Header Section -->
    <div class="header-container">
        <h1 class="header-text">College Event Website</h1>
    </div>
    <!-- End Header Section -->

    <!-- Log in Section -->
    <div class="login-container">
        <h2 class="login-text">Log in</h2>

        <form action="#" method="POST">
            <!-- Username Input -->
            <label for="username" class="login-label">Username</label>
            <input type="text" id="username" name="username" class="input-field" placeholder="Enter your username">

            <!-- Password Field -->
            <label for="password">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" class="input-field" placeholder="Enter your password">
                <span class="toggle-password" onclick="togglePassword()">👁</span>
            </div>

            <!-- Login Button -->
            <button type="submit" class="login-button">Log in</button>

            <!-- Forgot Password -->
            <p><a href="forgot.php" class="forgot-user-pass">Forgot username or password?</a></p>

            <!-- Sign-up Link -->
            <p><a href="signup.php" class="signup-link">Not Registered? Sign Up Now</a></p>
        </form>
    </div>
    <!-- End log in section -->

</body>
</html>
