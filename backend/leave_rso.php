<?php
include 'db.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'];
$rsoid = $data['rsoid'];

// Check if the user is the creator of the RSO
$stmt = $conn->prepare("SELECT CreatedBy FROM RSOs WHERE RSOID = ?");
$stmt->bind_param("i", $rsoid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "RSO not found."]);
    exit;
}

$createdBy = $result->fetch_assoc()['CreatedBy'];
if ($createdBy == $uid) {
    echo json_encode(["success" => false, "message" => "You cannot leave an RSO you created."]);
    exit;
}

// Check if the user is a member of the RSO
$stmt = $conn->prepare("SELECT * FROM RSO_Members WHERE UID = ? AND RSOID = ?");
$stmt->bind_param("ii", $uid, $rsoid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "User is not a member of this RSO."]);
    exit;
}

// Remove the user from the RSO
$stmt = $conn->prepare("DELETE FROM RSO_Members WHERE UID = ? AND RSOID = ?");
$stmt->bind_param("ii", $uid, $rsoid);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Successfully left the RSO."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to leave the RSO."]);
}
?>