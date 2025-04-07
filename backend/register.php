<?php
header("Content-Type: application/json");
include 'db.php'; // ✅ Your actual DB connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $name = $input['name'];
    $email = $input['email'];
    $password = password_hash($input['password'], PASSWORD_DEFAULT);
    $role = $input['role'] ?? null; // default to student if not provided
    $university = $input['university'];

    // Get UniversityID
    $stmt = $conn->prepare("SELECT UniversityID FROM Universities WHERE Name = ?");
    $stmt->bind_param("s", $university);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(400);
        echo json_encode(["error" => "University not found"]);
        exit;
    }

    $univID = $result->fetch_assoc()['UniversityID'];

    // Insert into Users
    $stmt = $conn->prepare("INSERT INTO Users (Name, Email, Password, UniversityID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $password, $univID);

    if (!$stmt->execute()) {
        http_response_code(500);
        echo json_encode(["error" => "User registration failed", "details" => $stmt->error]);
        exit;
    }

    $uid = $conn->insert_id;

    // If role is Admin or SuperAdmin, insert into respective table
    if ($role === 'Admin') {
        $stmt = $conn->prepare("INSERT INTO Admins (UID) VALUES (?)");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
    } elseif ($role === 'SuperAdmin') {
        $stmt = $conn->prepare("INSERT INTO SuperAdmins (UID) VALUES (?)");
        $stmt->bind_param("i", $uid);
        $stmt->execute();
    }

    echo json_encode(["message" => "User registered", "uid" => $uid]);
}
?>