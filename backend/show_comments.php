<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$eventID = $data['event_id'];

$stmt = $conn->prepare("SELECT CommentID, UID, CommentText, Timestamp FROM Comments WHERE EventID = ?");
$stmt->bind_param("i", $eventID);
$stmt->execute();
$result = $stmt->get_result();

$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}

echo json_encode(["success" => true, "comments" => $comments]);
?>