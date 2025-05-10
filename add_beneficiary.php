<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$data = json_decode(file_get_contents("php://input"), true);
$host = "localhost";
$user = "root";
$password = "";
$db = "digilegacy"; // Your DB

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Handle preflight request (browser sends OPTIONS first)
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
} // contains DB credentials

$data = json_decode(file_get_contents("php://input"), true);

$name = trim($data["name"]);
$personalCode = trim($data["personalCode"]);
$relationship = trim($data["relationship"]);
$sharedCount = 1;
$addedDate = date("Y-m-d");

// Validate required fields
if (!$name || !$personalCode || !$relationship) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

// Check if NIC exists in the registered users table
$checkNIC = $conn->prepare("SELECT user_id FROM users WHERE nic_passport_number = ?");
$checkNIC->bind_param("s", $name);
$checkNIC->execute();
$result = $checkNIC->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "NIC not found in registered users."]);
    exit;
}

// Insert beneficiary record
$stmt = $conn->prepare("INSERT INTO beneficiaries (nic, personal_code, relationship, shared_count, added_date) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssis", $name, $personalCode, $relationship, $sharedCount, $addedDate);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Beneficiary added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add beneficiary"]);
}
