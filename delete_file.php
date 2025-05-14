<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$host = 'localhost';
$db = 'digilegacy';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$filePath = $data['file_path'] ?? '';

if (!$filePath) {
    echo json_encode(["status" => "error", "message" => "No file path provided"]);
    exit;
}

// Delete file record from database
$stmt = $conn->prepare("DELETE FROM file_box_files WHERE file_path = ?");
$stmt->bind_param("s", $filePath);
$stmt->execute();

// Delete file from filesystem
if (file_exists($filePath)) {
    unlink($filePath);
}

echo json_encode(["status" => "success"]);
