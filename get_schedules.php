<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
include 'db_connect.php';

$lawyer_id = $_GET['lawyer_id'];
$result = $conn->query("SELECT * FROM lawyer_schedule WHERE lawyer_id = '$lawyer_id' ORDER BY date, starting_time");

$schedules = [];
while ($row = $result->fetch_assoc()) {
    $schedules[] = $row;
}

echo json_encode($schedules);
?>
