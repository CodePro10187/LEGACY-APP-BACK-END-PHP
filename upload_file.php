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
    $visibleTo = $_POST['visible_to'] ?? '[]'; // Assume client sends raw JSON array string
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    // Step 1: Insert or update file_boxes record
    $stmtBox = $conn->prepare("
        INSERT INTO file_boxes (box_id, uploaded_by, visible_to, title, content) 
        VALUES (?, ?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE 
            uploaded_by = VALUES(uploaded_by),
            visible_to = VALUES(visible_to),
            title = VALUES(title),
            content = VALUES(content)
    ");
    $stmtBox->bind_param("sssss", $boxId, $uploadedBy, $visibleTo, $title, $content);
    if (!$stmtBox->execute()) {
        echo json_encode(["status" => "error", "message" => "Failed to insert/update file_boxes"]);
        exit;
    }

    // Step 2: Handle file uploads if any are provided
    if (isset($_FILES['files']) && isset($_FILES['files']['name']) && count($_FILES['files']['name']) > 0) {
        $uploadDir = 'uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        foreach ($_FILES['files']['name'] as $index => $fileName) {
            $fileTmpPath = $_FILES['files']['tmp_name'][$index];
            $uniqueFileName = time() . "_" . basename($fileName);
            $filePath = $uploadDir . $uniqueFileName;

            if (move_uploaded_file($fileTmpPath, $filePath)) {
                // Save file info into file_box_files
                $stmtFile = $conn->prepare("INSERT INTO file_box_files (box_id, file_name, file_path) VALUES (?, ?, ?)");
                $stmtFile->bind_param("sss", $boxId, $fileName, $filePath);
                $stmtFile->execute();
            }
        }
    }

    echo json_encode(["status" => "success", "message" => "Box and files processed successfully"]);
} else {
    echo json_encode(["status" => "invalid_method", "message" => "Invalid request method"]);
}
?>
