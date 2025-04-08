
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../styles/student-dashboard-style.css">
    <link rel="stylesheet" href="home-page-style.css"> <!-- for gold theme -->
</head>
<body class="mainpage-background">
    <div class="header-container">
        <h1 class="header-text">Student Event Dashboard</h1>
    </div>

    <div class="container">
        <form method="get" class="search-bar">
            <input type="text" name="search" class="input-field" placeholder="Search events..." value="<?= htmlspecialchars($searchTerm) ?>">
            <button type="submit" class="login-button">Search</button>
        </form>

        <?php if (empty($eventsByRSO)): ?>
            <p>No events found.</p>
        <?php else: ?>
            <?php foreach ($eventsByRSO as $rsoName => $events): ?>
                <div class="section">
                    <h2 class="section-title"><?= htmlspecialchars($rsoName) ?></h2>
                    <?php foreach ($events as $event): ?>
                        <div class="event">
                            <strong><?= htmlspecialchars($event['event_name']) ?></strong> - <?= htmlspecialchars($event['description']) ?><br>
                            <span class="contact-info"><?= htmlspecialchars($event['date']) ?> at <?= htmlspecialchars($event['time']) ?>, <?= htmlspecialchars($event['location']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Create or Join RSO Section -->
        <div class="create-join-rso">
            <!-- Create a New RSO Button -->
            <a href="create-rso.php" class="btn">Create a New RSO</a>

            <!-- Join an Existing RSO -->
            <form method="POST" class="join-rso-form">
                <label for="rso_name">Join an Existing RSO:</label>
                <input type="text" name="rso_name" placeholder="Enter RSO Name" required>
                <button type="submit" name="join_rso" class="btn">Join</button>
            </form>
        </div>
    </div>

    <?php
    // Handle RSO Join Request
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['join_rso'])) {
        $rso_name = $_POST['rso_name'];

        // Check if RSO exists
        $checkRSOQuery = "SELECT rso_id FROM RSOs WHERE rso_name = ?";
        $stmt = $conn->prepare($checkRSOQuery);
        $stmt->bind_param("s", $rso_name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Join the RSO
            $stmt->bind_result($rso_id);
            $stmt->fetch();

            // Check if the student is already part of the RSO
            $checkStudentQuery = "SELECT * FROM Student_RSO WHERE student_id = ? AND rso_id = ?";
            $stmt = $conn->prepare($checkStudentQuery);
            $stmt->bind_param("ii", $student_id, $rso_id);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                // Add the student to the RSO
                $insertQuery = "INSERT INTO Student_RSO (student_id, rso_id) VALUES (?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("ii", $student_id, $rso_id);

                if ($stmt->execute()) {
                    echo "<script>alert('Successfully joined the RSO.');</script>";
                } else {
                    echo "<script>alert('Error joining the RSO: " . $stmt->error . "');</script>";
                }
            } else {
                echo "<script>alert('You are already a member of this RSO.');</script>";
            }
        } else {
            echo "<script>alert('RSO does not exist.');</script>";
        }
    }
    ?>

</body>
</html>
