<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// DB connection
$host = "localhost";
$user = "root";
$password = "";
$db = "digilegacy"; // use your DB name

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

// Function to generate a unique personal code
function generatePersonalCode() {
    return strtoupper(uniqid('PC') . bin2hex(random_bytes(2))); // Example: PC6637D12C9A32A1F2
}

$data = $_POST;

if (empty($data)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input."]);
    exit();
}

// Validate required fields
$required = [
    "firstName", "lastName", "prefix", "email", "mobileNumber", "dateOfBirth",
    "occupation", "country", "address1", "address2", "nicPassportNumber",
    "postalCode", "securityQuestion", "answer", "password", "confirmPassword", "bio"
];

foreach ($required as $field) {
    if (empty($data[$field])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing field: $field"]);
        exit();
    }
}

if ($data['password'] !== $data['confirmPassword']) {
    http_response_code(400);
    echo json_encode(["error" => "Passwords do not match."]);
    exit();
}

$passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);

// Generate user_id: U001, U002, ...
$idQuery = $conn->query("SELECT user_id FROM users ORDER BY user_id DESC LIMIT 1");
$nextId = "U001";

if ($idQuery->num_rows > 0) {
    $lastId = $idQuery->fetch_assoc()['user_id'];
    $num = (int)substr($lastId, 1) + 1;
    $nextId = "U" . str_pad($num, 3, "0", STR_PAD_LEFT);
}

// Generate unique personal_code
$personalCode = generatePersonalCode();

$stmt = $conn->prepare("
  INSERT INTO users (
    user_id, first_name, last_name, prefix, email, mobile_number, date_of_birth,
    occupation, country, address1, address2, nic_passport_number, postal_code,
    personal_code, security_question, answer, password_hash, bio
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "ssssssssssssssssss",
  $nextId,
  $data['firstName'],
  $data['lastName'],
  $data['prefix'],
  $data['email'],
  $data['mobileNumber'],
  $data['dateOfBirth'],
  $data['occupation'],
  $data['country'],
  $data['address1'],
  $data['address2'],
  $data['nicPassportNumber'],
  $data['postalCode'],
  $personalCode,
  $data['securityQuestion'],
  $data['answer'],
  $passwordHash,
  $data['bio']
);


if ($stmt->execute()) {
    echo json_encode(["success" => true, "user_id" => $nextId, "personal_code" => $personalCode]);

} else {
    http_response_code(500);
    echo json_encode(["error" => "Registration failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
