<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS, GET");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "digilegacy");

if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

$userId = $_GET['user_id'] ?? '';

if (!$userId) {
    echo json_encode([]);
    exit;
}

$stmt = $conn->prepare("SELECT u.user_id, u.first_name, u.last_name FROM beneficiaries b JOIN users u ON b.beneficiary_id = u.user_id WHERE b.user_id = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();

$beneficiaries = [];
while ($row = $result->fetch_assoc()) {
    $beneficiaries[] = $row;
}

echo json_encode($beneficiaries);
?>
