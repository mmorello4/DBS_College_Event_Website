<!-- forgot.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../styles/forgot-style.css">
    <script src="../scripts/home-page-script.js"></script>
</head>
<body class="forgot-background">

    <!-- Header Section -->
    <div class="header-container">
        <h1 class="header-text">College Event Website</h1>
    </div>
    <!-- End Header Section -->

    <!-- Forgot Password Section -->
    <div class="forgot-container">
        <h2 class="forgot-text">Forgot Password</h2>

        <form action="#" method="POST">
            <!-- Email Input -->
            <label for="email" class="login-label">Enter your email address</label>
            <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required>

            <!-- Reset Button -->
            <button type="submit" class="reset-button">Reset Password</button>

            <!-- Login Link -->
            <p><a href="home-page.php" class="forgot-link">Remember your password? Log in</a></p>
        </form>
    </div>
    <!-- End Forgot Password Section -->

</body>
</html>
