<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

include 'db_connect.php';

// Decode the JSON payload
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['lawyer_id']) || !isset($data['user_id'])) {
    echo json_encode(["message" => "Invalid input", "data_received" => $data]);
    exit();
}

$lawyer_id = $data['lawyer_id'];
$user_id = $data['user_id'];

try {
    // Check if the request already exists
    $sql = "SELECT * FROM contracted_users WHERE lawyer_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo json_encode(["message" => "Prepare failed (SELECT)", "error" => $conn->error]);
        exit();
    }

    $stmt->bind_param("ss", $lawyer_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        // Insert new request
        $insert_sql = "INSERT INTO contracted_users (lawyer_id, user_id) VALUES (?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);

        if (!$insert_stmt) {
            echo json_encode(["message" => "Prepare failed (INSERT)", "error" => $conn->error]);
            exit();
        }

        $insert_stmt->bind_param("ss", $lawyer_id, $user_id);

        if (!$insert_stmt->execute()) {
            echo json_encode(["message" => "Execution failed", "error" => $insert_stmt->error]);
            exit();
        }

        echo json_encode(["message" => "Request sent successfully"]);
    } else {
        echo json_encode(["message" => "Request already exists"]);
    }

} catch (Exception $e) {
    echo json_encode(["message" => "Server error", "error" => $e->getMessage()]);
}
?>
