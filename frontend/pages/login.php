<?php
session_start();
require_once("../../backend/db.php"); // Adjust path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $username_db, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $id;
                $_SESSION["username"] = $username_db;
                $_SESSION["role"] = $role;

                if ($role == 'student') {
                    header("Location: student-dashboard.php");
                } elseif ($role == 'admin') {
                    header("Location: admin-dashboard.php");
                } elseif ($role == 'superadmin') {
                    header("Location: superadmin-dashboard.php");
                }
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }
    } else {
        $error = "Database error.";
    }
    $stmt->close();
    $conn->close();
}
?>

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
            <p><a href="signup.php" class="signup-link">Not Registered? Sign Up</a></p>
        </form>
    </div>
</body>
</html>
