<?php
include 'db.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'];
$rsoid = $data['rsoid'];

// Check if already a member
$stmt = $conn->prepare("SELECT * FROM RSO_Members WHERE UID = ? AND RSOID = ?");
$stmt->bind_param("ii", $uid, $rsoid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "User is already a member of this RSO."]);
    exit;
}

// Add member
$stmt = $conn->prepare("INSERT INTO RSO_Members (UID, RSOID) VALUES (?, ?)");
$stmt->bind_param("ii", $uid, $rsoid);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Successfully joined the RSO."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to join RSO."]);
}
?>