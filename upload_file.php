<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
    echo json_encode(["status" => "error", "message" => "DB connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boxId = $_POST['box_id'] ?? '';
    $uploadedBy = $_POST['uploaded_by'] ?? '';
    $visibleTo = json_encode($_POST['visible_to'] ?? []);
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!isset($_FILES['files'])) {
        echo json_encode(["status" => "error", "message" => "No files uploaded"]);
        exit;
    }

    $uploadDir = 'uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    foreach ($_FILES['files']['name'] as $index => $fileName) {
        $fileTmpPath = $_FILES['files']['tmp_name'][$index];
        $filePath = $uploadDir . time() . "_" . basename($fileName);

        if (move_uploaded_file($fileTmpPath, $filePath)) {
            $stmt = $conn->prepare("INSERT INTO file_box_files (box_id, file_name, file_path, uploaded_by, visible_to, title, content) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $boxId, $fileName, $filePath, $uploadedBy, $visibleTo, $title, $content);
            $stmt->execute();
        }
    }

    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "invalid_method"]);
}
?>
