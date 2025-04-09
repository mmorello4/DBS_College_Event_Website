<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$commentID = $data['comment_id'];
$uid = $data['uid'];
$newText = $data['comment_text'];

$stmt = $conn->prepare("SELECT UID FROM Comments WHERE CommentID = ?");
$stmt->bind_param("i", $commentID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['UID'] != $uid) {
    echo json_encode(["success" => false, "message" => "Permission denied."]);
    exit;
}

$stmt = $conn->prepare("UPDATE Comments SET CommentText = ?, Timestamp = CURRENT_TIMESTAMP WHERE CommentID = ?");
$stmt->bind_param("si", $newText, $commentID);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Comment updated."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update comment."]);
}
?>
