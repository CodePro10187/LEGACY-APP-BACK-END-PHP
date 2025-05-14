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

$userId = trim($data["userId"]);

if (!$userId) {
    echo json_encode(["success" => false, "message" => "User ID is required"]);
    exit;
}

// Fetch beneficiaries for the current user
$query = "SELECT b.beneficiary_id, u.prefix, u.first_name, u.last_name, b.relationship, b.added_date
          FROM beneficiaries b
          JOIN users u ON b.beneficiary_id = u.user_id
          WHERE b.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $userId); // Bind user ID

$stmt->execute();
$result = $stmt->get_result();

$beneficiaries = [];
while ($row = $result->fetch_assoc()) {
    $beneficiaries[] = [
        'beneficiaryName' => $row['prefix'] . ' ' . $row['first_name'] . ' ' . $row['last_name'],
        'relationship' => $row['relationship'],
        'sharedCount' => 1,  // You can adjust this value as needed
        'addedDate' => $row['added_date']
    ];
}

echo json_encode(["success" => true, "beneficiaries" => $beneficiaries]);

?>
