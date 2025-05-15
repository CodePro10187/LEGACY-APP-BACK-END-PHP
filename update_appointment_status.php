<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$appointment_id = $data['appointment_id'];
$status = $data['status'];

$stmt = $conn->prepare("UPDATE user_appointments SET status = ? WHERE appointment_id = ?");
$stmt->bind_param("si", $status, $appointment_id);
$stmt->execute();

echo json_encode(["success" => true]);
?>
