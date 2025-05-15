<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

include 'db_connect.php';

$lawyer_id = $_GET['lawyer_id'];

$result = $conn->query("SELECT active_status FROM lawyers WHERE lawyer_id = '$lawyer_id'");

if ($row = $result->fetch_assoc()) {
    echo json_encode(['active_status' => (int)$row['active_status']]);
} else {
    echo json_encode(['error' => 'Lawyer not found']);
}
?>
