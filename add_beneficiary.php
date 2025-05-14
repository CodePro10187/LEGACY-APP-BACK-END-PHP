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
}

$beneficiaryNIC = trim($data["beneficiaryNIC"]);
$beneficiaryPersonalCode = trim($data["beneficiaryPersonalCode"]);
$relationship = trim($data["relationship"]);
$userId = trim($data["userId"]);
$addedDate = date("Y-m-d");

// Validate required fields
if (!$beneficiaryNIC || !$beneficiaryPersonalCode || !$relationship || !$userId) {
    echo json_encode(["success" => false, "message" => "Missing fields"]);
    exit;
}

// Check if NIC and Personal Code exist in the users table
$checkBeneficiary = $conn->prepare("SELECT user_id FROM users WHERE nic_passport_number = ? AND personal_code = ?");
$checkBeneficiary->bind_param("ss", $beneficiaryNIC, $beneficiaryPersonalCode);
$checkBeneficiary->execute();
$result = $checkBeneficiary->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Beneficiary NIC and Personal Code not found in registered users."]);
    exit;
}

// Insert beneficiary record
$stmt = $conn->prepare("INSERT INTO beneficiaries (user_id, beneficiary_id, relationship, added_date) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssis", $userId, $beneficiaryNIC, $relationship, $addedDate);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Beneficiary added successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add beneficiary"]);
}
?>
