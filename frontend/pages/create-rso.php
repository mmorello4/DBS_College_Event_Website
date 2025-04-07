<?php
// Start the session and check if the student is logged in
/*session_start();

if (!isset($_SESSION['student_id'])) {
    // Redirect to login page if the student is not logged in
    header('Location: login.php');
    exit();
}

$student_id = $_SESSION['student_id']; // Get student ID from session

$conn = new mysqli("localhost", "root", "", "rso_events");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get RSO data from the form submission
    $rso_name = $_POST['rso_name'];
    $rso_description = $_POST['rso_description'];
    $university_name = $_POST['university_name'];
    $admin_email = $_POST['admin_email'];
    $members_emails = isset($_POST['members']) ? $_POST['members'] : [];

    // Insert new RSO into the RSOs table
    $stmt = $conn->prepare("INSERT INTO RSOs (rso_name, description, admin_email) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $rso_name, $rso_description, $admin_email);
    $stmt->execute();
    $rso_id = $conn->insert_id; // Get the ID of the newly created RSO

    // Link the student to the newly created RSO
    $stmt = $conn->prepare("INSERT INTO Student_RSO (student_id, rso_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $rso_id);
    $stmt->execute();

    // Optionally insert members' emails into the RSO_Members table
    foreach ($members_emails as $member_email) {
        $stmt = $conn->prepare("INSERT INTO RSO_Members (rso_id, email) VALUES (?, ?)");
        $stmt->bind_param("is", $rso_id, $member_email);
        $stmt->execute();
    }

    // Redirect to the student dashboard after success
    header("Location: student-dashboard.php");
    exit();
}

// Fetch universities for dropdown selection (if needed)
$universities = [];
$result = $conn->query("SELECT university_name FROM Universities");
while ($row = $result->fetch_assoc()) {
    $universities[] = $row['university_name'];
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create RSO</title>
    <link rel="stylesheet" href="../styles/student-dashboard-style.css">
    <link rel="stylesheet" href="../styles/create-rso-style.css"> <!-- for gold theme -->
</head>
<body class="mainpage-background">
    <div class="header-container">
        <h1 class="header-text">Create a New RSO</h1>
    </div>

    <div class="container">
        <!-- RSO Creation Form -->
        <form method="post" action="create-rso.php">
            <div>
                <label for="rso_name">RSO Name</label>
                <input type="text" name="rso_name" id="rso_name" required>
            </div>

            <div>
                <label for="rso_description">RSO Description</label>
                <textarea name="rso_description" id="rso_description" rows="4" required></textarea>
            </div>

            <div>
                <label for="university_name">University</label>
                <select name="university_name" id="university_name" required>
                    <option value="">Select University</option>
                    <?php foreach ($universities as $university): ?>
                        <option value="<?= htmlspecialchars($university) ?>"><?= htmlspecialchars($university) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label for="admin_email">Admin Email</label>
                <input type="email" name="admin_email" id="admin_email" value="<?= $_SESSION['email'] ?>" required readonly>
            </div>

            <div>
                <label for="members">Additional Members (Comma Separated Emails)</label>
                <input type="text" name="members" id="members" placeholder="email1@domain.com, email2@domain.com">
            </div>

            <button type="submit" class="login-button">Create RSO</button>
        </form>
    </div>
</body>
</html>
