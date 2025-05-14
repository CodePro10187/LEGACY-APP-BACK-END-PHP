<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "digilegacy");

$userId = $_GET['user_id'] ?? '';

if (empty($userId)) {
    echo json_encode(["status" => "error", "message" => "Missing user ID"]);
    exit;
}

$stmt = $conn->prepare("
    SELECT box_id, title 
    FROM file_boxes 
    WHERE uploaded_by = ? 
    OR JSON_CONTAINS(visible_to, ?, '$')
");

$jsonUserId = json_encode($userId); // 👈 Fix the notice
$stmt->bind_param("ss", $userId, $jsonUserId);
$stmt->execute();
$result = $stmt->get_result();

$boxes = [];
while ($row = $result->fetch_assoc()) {
    $boxes[] = $row;
}

echo json_encode(["status" => "success", "boxes" => $boxes]);
?>
