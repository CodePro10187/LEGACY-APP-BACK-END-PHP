<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);

// Check input validity
if (!isset($data['lawyer_id']) || !isset($data['user_id'])) {
    echo json_encode(["message" => "Invalid input"]);
    exit();
}

$lawyer_id = $data['lawyer_id'];
$user_id = $data['user_id'];

try {
    // Check if entry already exists
    $sql = "SELECT * FROM contracted_users WHERE lawyer_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $lawyer_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Insert new row with default status "pending"
        $insert_sql = "INSERT IGNORE INTO contracted_users (lawyer_id, user_id) VALUES (?, ?)";

        $insert_stmt = $conn->prepare($insert_sql);
if (!$insert_stmt) {
    echo json_encode(["message" => "SQL prepare failed", "error" => $conn->error]);
    exit();
}
if (!$insert_stmt->execute()) {
    echo json_encode(["message" => "Execution failed", "error" => $insert_stmt->error]);
    exit();
}
        $insert_stmt->bind_param("ss", $lawyer_id, $user_id);
        $insert_stmt->execute();

        echo json_encode(["message" => "Request sent"]);
    } else {
        echo json_encode(["message" => "Request already exists"]);
    }
} catch (Exception $e) {
    http_response_code(500); // Optional but good practice
    echo json_encode([
        "message" => "Server error",
        "error" => $e->getMessage(),  // ✅ Helps identify exact error
    ]);
}

?>
