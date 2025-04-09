<?php
include 'db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$uid = $data['uid'] ?? null;

if (!$uid) {
    echo json_encode(["success" => false, "message" => "User ID is required."]);
    exit;
}

try {
    $stmt = $conn->prepare("
        SELECT R.RSOID, R.Name, R.Description
        FROM RSO_Members M
        JOIN RSOs R ON M.RSOID = R.RSOID
        WHERE M.UID = ?
    ");
    $stmt->bind_param("i", $uid);
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
    echo json_encode(["success" => false, "message" => "Server error."]);
}
?>
