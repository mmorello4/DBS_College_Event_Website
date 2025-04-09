<?php
include 'db.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$search = strtolower($data["search"] ?? "");
$uid = $data["uid"];

try {
    // Get the user's university ID
    $stmt = $conn->prepare("SELECT UniversityID FROM Users WHERE UID = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) throw new Exception("User not found.");
    $university_id = $result->fetch_assoc()['UniversityID'];

    // Query RSOs that belong to the user's university and match the search string
    if ($search === "") {
        $stmt = $conn->prepare("
            SELECT RSOID, Name, Description
            FROM RSOs
            WHERE UniversityID = ?
        ");
        $stmt->bind_param("i", $university_id);
    } else {
        $search_like = "%" . $search . "%";
        $stmt = $conn->prepare("
            SELECT RSOID, Name, Description
            FROM RSOs
            WHERE UniversityID = ? AND LOWER(Name) LIKE ?
        ");
        $stmt->bind_param("is", $university_id, $search_like);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $rsos = [];
    while ($row = $result->fetch_assoc()) {
        $rsos[] = [
            "rsoid" => $row["RSOID"],
            "name" => $row["Name"],
            "description" => $row["Description"]
        ];
    }

    echo json_encode(["success" => true, "rsos" => $rsos]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
