<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$schedule_id = $data['schedule_id'] ?? null;

if (!$schedule_id) {
    echo json_encode(['error' => 'Missing schedule_id']);
    exit;
}

$stmt = $conn->prepare("DELETE FROM lawyer_schedule WHERE schedule_id = ?");
$stmt->bind_param("i", $schedule_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to delete schedule']);
}
?>
