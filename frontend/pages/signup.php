<!-- signup.php -->
<?php
// Connect to your database (edit credentials as needed)
$conn = new mysqli("localhost", "root", "", "rso_events");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Secure password hashing
    $university = $_POST["university"];

    // Optional: Check if username or email already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
    $check->bind_param("ss", $email, $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or Email already exists');</script>";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (name, email, username, password, university) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $username, $password, $university);
        
        if ($stmt->execute()) {
            header("Location: login.php"); // ✅ Redirect to login page
            exit();
        } else {
            echo "<script>alert('Error creating account');</script>";
        }
        $stmt->close();
    }

    $check->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../styles/signup-style.css">
    <script src="../scripts/login-script.js"></script>
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
        <form action="signup.php" method="POST">
            <!-- Name Input -->
            <label for="name" class="login-label">Full Name</label>
            <input type="text" id="name" name="name" class="input-field" placeholder="Enter your full name" required>

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

            <!-- University Input -->
            <label for="university" class="login-label">University</label>
            <input type="text" id="university" name="university" class="input-field" placeholder="Enter your university" required>

            <!-- Sign Up Button -->
            <button type="submit" class="signup-button">Sign Up</button>

            <!-- Login Link -->
            <p><a href="login.php" class="signup-link">Already registered? Log in</a></p>
        </form>
        <!-- End form tag -->
    </div>

</body>
</html>
