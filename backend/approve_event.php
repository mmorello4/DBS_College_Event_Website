<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'];
$eventID = $data['event_id'];

// Check if UID is a superadmin
$check = $conn->prepare("SELECT SuperAdminID FROM SuperAdmins WHERE UID = ?");
$check->bind_param("i", $uid);
$check->execute();
$result = $check->get_result();
$admin = $result->fetch_assoc();

if (!$admin) {
    echo json_encode(["success" => false, "message" => "User is not a superadmin."]);
    exit;
}

$superAdminID = $admin['SuperAdminID'];

// Update public event
$update = $conn->prepare("UPDATE Public_Events SET NeedsApproval = FALSE, ApprovedBy = ? WHERE EventID = ?");
$update->bind_param("ii", $superAdminID, $eventID);

if ($update->execute()) {
    echo json_encode(["success" => true, "message" => "Event approved."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to approve event."]);
}
?>
