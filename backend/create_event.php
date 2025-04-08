<?php
include 'db.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

// Extract and sanitize input
$title = $data["Event_Name"];
$description = $data["Event_Description"];
$type = $data["Type"];
$rso_name = $data["RSO"] ?? null;
$date = $data["Date"];
$time = $data["Time"];
$location_desc = $data["Location"];
$contact_phone = $data["Contact_Phone"];
$contact_email = $data["Contact_Email"];
$created_by = $data["User_ID"];

// Combine date and time to MySQL DATETIME
$event_time = date("Y-m-d H:i:s", strtotime($date . " " . $time));

$conn->begin_transaction();

try {
    // 1. Handle location: insert if it doesn't exist
    $stmt = $conn->prepare("SELECT LocationID FROM Locations WHERE Description = ?");
    $stmt->bind_param("s", $location_desc);
    $stmt->execute();
    $stmt->bind_result($location_id);
    if (!$stmt->fetch()) {
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO Locations (Description, Name, Latitude, Longitude) VALUES (?, ?, NULL, NULL)");
        $stmt->bind_param("ss", $location_desc, $location_desc);
        $stmt->execute();
        $location_id = $stmt->insert_id;
    } else {
        $stmt->close();
    }

    // 2. Permission check
    if ($type === "RSO") {
        if (!$rso_name) throw new Exception("RSO name is required for RSO events.");
        $stmt = $conn->prepare("SELECT RSOID, CreatedBy FROM RSOs WHERE Name = ?");
        $stmt->bind_param("s", $rso_name);
        $stmt->execute();
        $stmt->bind_result($rso_id, $rso_creator);
        if (!$stmt->fetch()) throw new Exception("RSO not found.");
        if ($rso_creator != $created_by) {
            $stmt->close();  
            throw new Exception("Only the RSO creator can create events for this RSO.");
        }
        $stmt->close();
    } else {
        // Must be an admin
        $stmt = $conn->prepare("SELECT UID FROM Admins WHERE UID = ?");
        $stmt->bind_param("i", $created_by);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 0) throw new Exception("Only admins can create this type of event.");
        $stmt->close();
    }

    // 3. Insert event into Events table
    $stmt = $conn->prepare("
        INSERT INTO Events (Title, Description, EventTime, LocationID, ContactPhone, ContactEmail, CreatedBy, CategoryID)
        VALUES (?, ?, ?, ?, ?, ?, ?, NULL)
    ");
    $stmt->bind_param("sssissi", $title, $description, $event_time, $location_id, $contact_phone, $contact_email, $created_by);
    $stmt->execute();
    $event_id = $stmt->insert_id;
    $stmt->close();

    // 4. Insert into subtype table
    if ($type === "RSO") {
        $stmt = $conn->prepare("INSERT INTO RSO_Events (EventID, RSOID) VALUES (?, ?)");
        $stmt->bind_param("ii", $event_id, $rso_id);
        $stmt->execute();
    } elseif ($type === "Private") {
        $stmt = $conn->prepare("SELECT UniversityID FROM Users WHERE UID = ?");
        $stmt->bind_param("i", $created_by);
        $stmt->execute();
        $stmt->bind_result($university_id);
        $stmt->fetch();
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO Private_Events (EventID, UniversityID) VALUES (?, ?)");
        $stmt->bind_param("ii", $event_id, $university_id);
        $stmt->execute();
    } elseif ($type === "Public") {
        $stmt = $conn->prepare("INSERT INTO Public_Events (EventID, NeedsApproval) VALUES (?, TRUE)");
        $stmt->bind_param("i", $event_id);
        $stmt->execute();
    } else {
        throw new Exception("Invalid event type.");
    }

    $conn->commit();
    echo json_encode(["success" => true, "message" => "Event created successfully."]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
