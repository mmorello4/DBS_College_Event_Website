<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$commentID = $data['comment_id'];
$uid = $data['uid'];

$stmt = $conn->prepare("SELECT UID FROM Comments WHERE CommentID = ?");
$stmt->bind_param("i", $commentID);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['UID'] != $uid) {
    echo json_encode(["success" => false, "message" => "Permission denied."]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM Comments WHERE CommentID = ?");
$stmt->bind_param("i", $commentID);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Comment deleted."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to delete comment."]);
}
?>
