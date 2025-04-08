
<!-- Below is the same file: login.php (HTML starts here) -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>College Event Website</title>
    <link rel="stylesheet" href="../styles/login-style.css">
    <script src="../scripts/login-script.js"></script>
</head>
<body class="mainpage-background">

    <!-- Header Section -->
    <div class="header-container">
        <h1 class="header-text">College Event Website</h1>
    </div>

    <!-- Login Form Section -->
    <div class="login-container">
        <h2 class="login-text">Log in</h2>

        <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

        <form action="login.php" method="POST">
            <label for="username" class="login-label">Username</label>
            <input type="text" id="username" name="username" class="input-field" required>

            <label for="password" class="login-label">Password</label>
            <div class="password-container">
                <input type="password" id="password" name="password" class="input-field" required>
                <span class="toggle-password" onclick="togglePassword()">👁</span>
            </div>

            <button type="submit" class="login-button">Log in</button>
            <p><a href="signup-page.php" class="signup-link">Not Registered? Sign Up</a></p>
        </form>
    </div>
</body>
</html>
