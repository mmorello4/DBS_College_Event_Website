<?php
header("Content-Type: application/json");
include 'db.php';

$sql = "SELECT * FROM Events";
$result = $conn->query($sql);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);
?>