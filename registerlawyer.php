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
$db = "digilegacy"; // change to your database

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Database connection failed."]);
    exit();
}

// Required fields
$required = [
    "firstName", "lastName", "prefix", "email", "mobileNumber", "dateOfBirth",
    "country", "address1", "address2", "nicPassportNumber", "postalCode",
    "securityQuestion", "answer", "password", "confirmPassword", "lawFirmName",
    "lawFirmAddress", "professionalLicenseNumber", "licenseExpiryDate", "bio"
];

foreach ($required as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        echo json_encode(["error" => "Missing field: $field"]);
        exit();
    }
}

if ($_POST['password'] !== $_POST['confirmPassword']) {
    http_response_code(400);
    echo json_encode(["error" => "Passwords do not match."]);
    exit();
}

$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Generate custom lawyer_id (L001, L002, ...)
$idQuery = $conn->query("SELECT lawyer_id FROM lawyers ORDER BY lawyer_id DESC LIMIT 1");
$nextId = "L001";

if ($idQuery->num_rows > 0) {
    $lastId = $idQuery->fetch_assoc()['lawyer_id'];
    $num = (int)substr($lastId, 1) + 1;
    $nextId = "L" . str_pad($num, 3, "0", STR_PAD_LEFT);
}

// Handle file upload for document and profile picture
$uploadDir = "Luploads/";
$documentPath = "";
$profilePicturePath = "";

if (isset($_FILES['documentFile']) && $_FILES['documentFile']['error'] === 0) {
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $filename = time() . "_" . basename($_FILES['documentFile']['name']);
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['documentFile']['tmp_name'], $targetPath)) {
        $documentPath = $targetPath;
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Document file upload failed."]);
        exit();
    }
}

if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === 0) {
    $profileUploadDir = "Luploads/profile_pictures/";
    if (!is_dir($profileUploadDir)) {
        mkdir($profileUploadDir, 0777, true);
    }
    $profilePictureName = time() . "_" . basename($_FILES['profilePicture']['name']);
    $profileTargetPath = $profileUploadDir . $profilePictureName;

    if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $profileTargetPath)) {
        $profilePicturePath = $profileTargetPath;
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Profile picture upload failed."]);
        exit();
    }
}

// Insert into DB
$stmt = $conn->prepare("
  INSERT INTO lawyers (
    lawyer_id, first_name, last_name, prefix, email, mobile_number, date_of_birth,
    country, address1, address2, nic_passport_number, postal_code,
    security_question, answer, password_hash, law_firm_name,
    law_firm_address, professional_license_number, license_expiry_date,
    bio, document_path, profile_picture_url
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
  "ssssssssssssssssssssss",
  $nextId,
  $_POST['firstName'],
  $_POST['lastName'],
  $_POST['prefix'],
  $_POST['email'],
  $_POST['mobileNumber'],
  $_POST['dateOfBirth'],
  $_POST['country'],
  $_POST['address1'],
  $_POST['address2'],
  $_POST['nicPassportNumber'],
  $_POST['postalCode'],
  $_POST['securityQuestion'],
  $_POST['answer'],
  $passwordHash,
  $_POST['lawFirmName'],
  $_POST['lawFirmAddress'],
  $_POST['professionalLicenseNumber'],
  $_POST['licenseExpiryDate'],
  $_POST['bio'],
  $documentPath,
  $profilePicturePath
);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "lawyer_id" => $nextId]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Registration failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
