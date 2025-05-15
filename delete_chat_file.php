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
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$filePath = $data['file_path'] ?? '';
$userId = $data['user_id'] ?? '';
$lawyerId = $data['lawyer_id'] ?? '';

if (!$filePath || !$userId || !$lawyerId) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

// Delete the file from the server
if (file_exists($filePath)) {
    unlink($filePath);
}

// Remove from shared files
$stmt1 = $conn->prepare("DELETE FROM user_lawyer_shared_files WHERE file_path = ? AND user_id = ? AND lawyer_id = ?");
$stmt1->bind_param("sss", $filePath, $userId, $lawyerId);
$stmt1->execute();

// Also remove related chat entry (optional but recommended)
$stmt2 = $conn->prepare("DELETE FROM user_lawyer_chats WHERE message = ? AND user_id = ? AND lawyer_id = ?");
$fileName = basename($filePath);
$stmt2->bind_param("sss", $fileName, $userId, $lawyerId);
$stmt2->execute();

echo json_encode(['success' => true]);
?>