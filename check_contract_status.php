<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
// check_contract_status.php
include 'db_connect.php'; // your database connection file

$lawyer_id = $_GET['lawyer_id'];
$user_id = $_GET['user_id'];

$sql = "SELECT status FROM contracted_users WHERE lawyer_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $lawyer_id, $user_id); // ✅ correct for strings
$stmt->execute();
$result = $stmt->get_result();

$response = ["status" => null];

if ($row = $result->fetch_assoc()) {
    $response['status'] = $row['status'];
}

echo json_encode($response);
?>
