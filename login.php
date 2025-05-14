<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

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

$data = json_decode(file_get_contents("php://input"), true);

$email = $conn->real_escape_string($data["email"]);
$password = $data["password"];

// 1. Check in users table
$stmt = $conn->prepare("SELECT user_id, password_hash FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password_hash'])) {
        $userData = $conn->query("SELECT * FROM users WHERE user_id = '{$row['user_id']}'")->fetch_assoc();
        unset($userData['password_hash']); // For security

        // Add personal_code to the response
        $userData['personal_code'] = $row['personal_code']; // Include personal_code

        echo json_encode([
            "success" => true,
            "userType" => "user",
            "user" => $userData
        ]);
        exit();
    }
}

// 2. Check in lawyers table
$stmt = $conn->prepare("SELECT lawyer_id, password_hash FROM lawyers WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password_hash'])) {
        $lawyerData = $conn->query("SELECT * FROM lawyers WHERE lawyer_id = '{$row['lawyer_id']}'")->fetch_assoc();
        unset($lawyerData['password_hash']); // For security

        // Add personal_code to the response (if available in the lawyers table)
        $lawyerData['personal_code'] = $row['personal_code']; // Include personal_code

        echo json_encode([
            "success" => true,
            "userType" => "lawyer",
            "user" => $lawyerData
        ]);
        exit();
    }
}

http_response_code(401);
echo json_encode(["error" => "Invalid email or password."]);
