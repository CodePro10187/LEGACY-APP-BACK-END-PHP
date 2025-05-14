<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "digilegacy");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'];

if (empty($user_id)) {
    echo json_encode(["status" => "error", "message" => "user_id is required"]);
    exit;
}

$box_id = uniqid("box_"); // Generate a unique box_id

$stmt = $conn->prepare("INSERT INTO file_boxes (box_id, uploaded_by) VALUES (?, ?)");
$stmt->bind_param("ss", $box_id, $user_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "box_id" => $box_id]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to insert data"]);
}

$stmt->close();
$conn->close();
?>
