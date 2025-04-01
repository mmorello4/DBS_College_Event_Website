<?php
header("Content-Type: application/json");
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data["Title"]) || !isset($data["Description"]) || !isset($data["EventTime"]) || !isset($data["LocationID"]) || !isset($data["CreatedBy"])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO Events (Title, Description, EventTime, LocationID, CreatedBy) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $data["Title"], $data["Description"], $data["EventTime"], $data["LocationID"], $data["CreatedBy"]);

if ($stmt->execute()) {
    echo json_encode(["success" => "Event created"]);
} else {
    echo json_encode(["error" => $stmt->error]);
}
?>