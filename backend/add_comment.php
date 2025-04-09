<?php
// add_comment.php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'];
$eventID = $data['event_id'];
$commentText = $data['comment_text'];
$rating = 1; 

$stmt = $conn->prepare("INSERT INTO Comments (EventID, UID, CommentText, Rating) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iisi", $eventID, $uid, $commentText, $rating);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Comment added successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add comment."]);
}
?>