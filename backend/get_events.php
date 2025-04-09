<?php
include 'db.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$type = strtolower($data["type"]); // "rso", "private", or "public"
$uid = $data["uid"];

$response = [];

try {
    if (!in_array($type, ["rso", "private", "public"])) {
        throw new Exception("Invalid event type.");
    }

    // Get user university (for private events)
    $university_stmt = $conn->prepare("SELECT UniversityID FROM Users WHERE UID = ?");
    $university_stmt->bind_param("i", $uid);
    $university_stmt->execute();
    $university_result = $university_stmt->get_result();
    if ($university_result->num_rows === 0) throw new Exception("User not found.");
    $university_id = $university_result->fetch_assoc()['UniversityID'];

    if ($type === "public") {
        $query = "
            SELECT E.Title, E.Description, E.EventTime, E.EndTime, L.Description AS Location, 
                   E.ContactPhone, E.ContactEmail
            FROM Public_Events P
            JOIN Events E ON P.EventID = E.EventID
            JOIN Locations L ON E.LocationID = L.LocationID
            WHERE P.NeedsApproval = FALSE
            ORDER BY E.EventTime ASC
        ";
        $stmt = $conn->prepare($query);
    }

    elseif ($type === "private") {
        $query = "
            SELECT E.Title, E.Description, E.EventTime, E.EndTime, L.Description AS Location, 
                   E.ContactPhone, E.ContactEmail
            FROM Private_Events P
            JOIN Events E ON P.EventID = E.EventID
            JOIN Locations L ON E.LocationID = L.LocationID
            WHERE P.UniversityID = ?
            ORDER BY E.EventTime ASC
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $university_id);
    }

    elseif ($type === "rso") {
        $query = "
            SELECT E.Title, E.Description, E.EventTime, E.EndTime, L.Description AS Location, 
                   E.ContactPhone, E.ContactEmail, R.Name AS RSO_Name
            FROM RSO_Members M
            JOIN RSOs R ON M.RSOID = R.RSOID
            JOIN RSO_Events RE ON R.RSOID = RE.RSOID
            JOIN Events E ON RE.EventID = E.EventID
            JOIN Locations L ON E.LocationID = L.LocationID
            WHERE M.UID = ?
            ORDER BY E.EventTime ASC
        ";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $uid);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $event = [
            "title" => $row["Title"],
            "description" => $row["Description"],
            "event_time" => $row["EventTime"],
            "end_time" => $row["EndTime"],
            "location" => $row["Location"],
            "contact_phone" => $row["ContactPhone"],
            "contact_email" => $row["ContactEmail"]
        ];

        if (isset($row["RSO_Name"])) {
            $event["rso_name"] = $row["RSO_Name"];
        }

        $response[] = $event;
    }

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
