<?php
header("Content-Type: application/json");
include 'db.php';

// Assuming you have a session with the student ID
session_start();
$student_id = $_SESSION['uid'];  // Assuming the student ID is stored in session

// Query to get events that the student is a part of
$sql = "
    SELECT e.event_name, e.description, e.date, e.time, e.location
    FROM Events e
    JOIN Student_RSO sr ON e.rso_id = sr.rso_id
    WHERE sr.student_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);
?>
