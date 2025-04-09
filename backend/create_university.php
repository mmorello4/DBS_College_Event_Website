<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'];
$name = $data['name'];
$domain = $data['domain'];
$location = $data['location'];
$students = isset($data['number_of_students']) ? $data['number_of_students'] : null;
$picture = isset($data['picture_url']) ? $data['picture_url'] : null;

// Check role
$stmt = $conn->prepare("SELECT SuperAdminID FROM SuperAdmins WHERE UID = ?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "User is not a superadmin."]);
    exit;
}

// Validate domain
if (!str_ends_with($domain, ".edu")) {
    echo json_encode(["success" => false, "message" => "Domain must end in .edu"]);
    exit;
}

// Insert university
$insert = $conn->prepare("INSERT INTO Universities (Name, Domain, Location, NumberOfStudents, PictureURL) VALUES (?, ?, ?, ?, ?)");
$insert->bind_param("sssds", $name, $domain, $location, $students, $picture);

if ($insert->execute()) {
    echo json_encode(["success" => true, "message" => "University created successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to create university."]);
}
?>
