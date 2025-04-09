<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'];

// Confirm superadmin role
$check = $conn->prepare("SELECT SuperAdminID FROM SuperAdmins WHERE UID = ?");
$check->bind_param("i", $uid);
$check->execute();
$result = $check->get_result();
$super = $result->fetch_assoc();

if (!$super) {
    echo json_encode(["success" => false, "message" => "User is not a superadmin."]);
    exit;
}

// Fetch public events needing approval
$query = "
SELECT e.EventID, e.Title, e.Description, e.EventTime, e.EndTime, e.ContactPhone, e.ContactEmail, l.Description AS Location
FROM Public_Events p
JOIN Events e ON p.EventID = e.EventID
JOIN Locations l ON e.LocationID = l.LocationID
WHERE p.NeedsApproval = TRUE
ORDER BY e.EventTime
";

$events = $conn->query($query);
$response = [];

while ($row = $events->fetch_assoc()) {
    $response[] = $row;
}

echo json_encode(["success" => true, "events" => $response]);
?>
