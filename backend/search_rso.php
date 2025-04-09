<?php
include 'db.php';
header("Content-Type: application/json");

// Decode the incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);
$search = strtolower($data["query"] ?? "");  // Get the search term, default to empty if not provided
$uid = $data["uid"];  // Get the user ID

try {
    // Get the user's university ID
    $stmt = $conn->prepare("SELECT UniversityID FROM Users WHERE UID = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) throw new Exception("User not found.");
    $university_id = $result->fetch_assoc()['UniversityID'];

    // Exact match for the search term (search by exact RSO name)
    $stmt = $conn->prepare("
        SELECT RSOID, Name, Description
        FROM RSOs
        WHERE UniversityID = ? AND LOWER(Name) = ?
        LIMIT 1
    ");
    $stmt->bind_param("is", $university_id, $search);  // Search with exact match

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and prepare the response data
    $rsos = [];
    if ($row = $result->fetch_assoc()) {
        $rsos[] = [
            "rsoid" => $row["RSOID"],
            "name" => $row["Name"],
            "description" => $row["Description"]
        ];
    }

    // Send the response back to the frontend
    echo json_encode(["success" => true, "rsos" => $rsos]);

} catch (Exception $e) {
    // Handle any exceptions or errors
    http_response_code(400);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
