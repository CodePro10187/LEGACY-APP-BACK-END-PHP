<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

$stmt = $conn->prepare("INSERT INTO lawyer_schedule (lawyer_id, date, starting_time, ending_time) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $data['lawyer_id'], $data['date'], $data['starting_time'], $data['ending_time']);
$stmt->execute();

echo json_encode(["success" => true]);
?>
