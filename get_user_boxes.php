<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "digilegacy");

$userId = $_GET['user_id'];
$stmt = $conn->prepare("SELECT DISTINCT box_id FROM file_boxes WHERE uploaded_by = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$boxes = [];
while ($row = $result->fetch_assoc()) {
  $boxes[] = $row;
}

echo json_encode(["status" => "success", "boxes" => $boxes]);
