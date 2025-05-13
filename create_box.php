<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "digilegacy");

$data = json_decode(file_get_contents("php://input"), true);
$box_id = $data['box_id'];
$user_id = $data['user_id'];

$stmt = $conn->prepare("INSERT INTO file_boxes (box_id, uploaded_by) VALUES (?, ?)");
$stmt->bind_param("ss", $box_id, $user_id);
$stmt->execute();

echo json_encode(["status" => "success"]);
