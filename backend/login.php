<?php
header("Content-Type: application/json");
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $email = $input['email'];
    $password = $input['password'];

    $stmt = $conn->prepare("SELECT UID, Password, UniversityID FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid email or password"]);
        exit;
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['Password'])) {
        http_response_code(401);
        echo json_encode(["error" => "Invalid email or password"]);
        exit;
    }

    $uid = $user['UID'];

    // Determine role
    $role = 'Student';
    $checkAdmin = $conn->prepare("SELECT * FROM Admins WHERE UID = ?");
    $checkAdmin->bind_param("i", $uid);
    $checkAdmin->execute();
    if ($checkAdmin->get_result()->num_rows > 0) {
        $role = 'Admin';
    }

    $checkSuper = $conn->prepare("SELECT * FROM SuperAdmins WHERE UID = ?");
    $checkSuper->bind_param("i", $uid);
    $checkSuper->execute();
    if ($checkSuper->get_result()->num_rows > 0) {
        $role = 'SuperAdmin';
    }

    $_SESSION['uid'] = $uid;
    $_SESSION['university_id'] = $user['UniversityID'];
    $_SESSION['role'] = $role;

    echo json_encode([
        "message" => "Login successful",
        "uid" => $uid,
        "role" => $role,
        "university_id" => $user['UniversityID']
    ]);
}
?>