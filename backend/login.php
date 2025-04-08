<?php
header("Content-Type: application/json");
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get raw POST data as JSON
    $input = json_decode(file_get_contents('php://input'), true);

    // Check if the email and password are set
    if (!isset($input['email']) || !isset($input['password'])) {
        http_response_code(400); // Bad Request
        echo json_encode(["error" => "Email and password are required"]);
        exit;
    }

    $email = $input['email'];
    $password = $input['password'];

    // Prepare SQL statement to check if the email exists in the database
    $stmt = $conn->prepare("SELECT UID, Password, UniversityID FROM users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if no user found with that email
    if ($result->num_rows === 0) {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "Invalid email or password"]);
        exit;
    }

    // Get user data from the result
    $user = $result->fetch_assoc();

    // Verify the password
    if (!password_verify($password, $user['Password'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(["error" => "Invalid email or password"]);
        exit;
    }

    $uid = $user['UID'];

    // Determine the user role
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

    // Store session information
    $_SESSION['uid'] = $uid;
    $_SESSION['university_id'] = $user['UniversityID'];
    $_SESSION['role'] = $role;

    // Return a success response
    echo json_encode([
        "success" => true,  // Include success flag
        "message" => "Login successful",
        "uid" => $uid,
        "role" => $role,
        "university_id" => $user['UniversityID']
    ]);
}
?>
