<?php
header("Content-Type: application/json");

// DB connection
include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$name = $data["name"];
$description = $data["description"];
$university_id = $data["university_id"];
$admin_email = $data["admin_email"];
$member_emails = $data["member_emails"];


// 1. Get UID of admin
$stmt = $conn->prepare("SELECT UID FROM Users WHERE Email = ?");
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$stmt->bind_result($admin_uid);
if (!$stmt->fetch()) {
    echo json_encode(["success" => false, "message" => "Admin email not found in Users table."]);
    exit;
}
$stmt->close();

// 2. Get UIDs of additional members
$member_uids = [];
foreach ($member_emails as $email) {
    $stmt = $conn->prepare("SELECT UID FROM Users WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($uid);
    if ($stmt->fetch()) {
        $member_uids[] = $uid;
    } else {
        echo json_encode(["success" => false, "message" => "Member email not found: $email"]);
        exit;
    }
    $stmt->close();
}

// 3. Check total members (admin + 4 others = 5)
if (count($member_uids) < 4) {
    echo json_encode(["success" => false, "message" => "You need at least 5 total members including the admin."]);
    exit;
}

// Check if RSO name already exists
$stmt = $conn->prepare("SELECT RSOID FROM RSOs WHERE Name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["success" => false, "message" => "An RSO with this name already exists. Please choose another name."]);
    exit;
}
$stmt->close();

// 4. Create the RSO
$stmt = $conn->prepare("INSERT INTO RSOs (Name, Description, CreatedBy, UniversityID) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssii", $name, $description, $admin_uid, $university_id);
if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "RSO creation failed: " . $stmt->error]);
    exit;
}
$rso_id = $conn->insert_id;
$stmt->close();

// 5. Add all members to RSO_Members
$insert_member = $conn->prepare("INSERT INTO RSO_Members (UID, RSOID) VALUES (?, ?)");
$uids_to_insert = array_merge([$admin_uid], $member_uids);
foreach ($uids_to_insert as $uid) {
    $insert_member->bind_param("ii", $uid, $rso_id);
    $insert_member->execute();
}
$insert_member->close();


// 6. Promote admin to Admins table (only if not already there)
$check_admin = $conn->prepare("SELECT AdminID FROM Admins WHERE UID = ?");
$check_admin->bind_param("i", $admin_uid);
$check_admin->execute();
$check_admin->store_result();


if ($check_admin->num_rows === 0) {
    $check_admin->close();
    $insert_admin = $conn->prepare("INSERT INTO Admins (UID) VALUES (?)");
    $insert_admin->bind_param("i", $admin_uid);
    $insert_admin->execute();
    $insert_admin->close();
} else {
    $check_admin->close();
}


echo json_encode(["success" => true, "message" => "RSO created and members added successfully."]);
?>
