<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false); // for IE
header("Pragma: no-cache");
header("Expires: 0");
header("Content-Type: application/json");
include 'db_connect.php';

$data = json_decode(file_get_contents("php://input"), true);
$lawyer_id = $data['lawyer_id'];
$active_status = $data['active_status'];

$stmt = $conn->prepare("UPDATE lawyers SET active_status = ? WHERE lawyer_id = ?");
$stmt->bind_param("is", $active_status, $lawyer_id);
$stmt->execute();

echo json_encode(['success' => true]);
?>
