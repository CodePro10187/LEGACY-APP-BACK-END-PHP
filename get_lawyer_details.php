<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once("db_connect.php");

$lawyerId = $_GET['lawyer_id'] ?? '';

if (!$lawyerId) {
    echo json_encode(["error" => "Lawyer ID is required"]);
    exit;
}

$response = [];

// Get Lawyer Details
$lawyerQuery = $conn->prepare("SELECT lawyer_id, CONCAT(prefix, ' ', first_name, ' ', last_name) AS name, email, mobile_number, bio, profile_picture_url FROM lawyers WHERE lawyer_id = ?");
$lawyerQuery->bind_param("s", $lawyerId);
$lawyerQuery->execute();
$lawyerResult = $lawyerQuery->get_result();
$response['lawyer'] = $lawyerResult->fetch_assoc();

// Get Schedule
$scheduleQuery = $conn->prepare("SELECT schedule_id, date, starting_time, ending_time FROM lawyer_schedule WHERE lawyer_id = ?");
$scheduleQuery->bind_param("s", $lawyerId);
$scheduleQuery->execute();
$scheduleResult = $scheduleQuery->get_result();
$response['schedule'] = $scheduleResult->fetch_all(MYSQLI_ASSOC);

// Get Appointments
$apptQuery = $conn->prepare("SELECT appointment_id, user_id, date, starting_time, ending_time, status FROM user_appointments WHERE lawyer_id = ?");
$apptQuery->bind_param("s", $lawyerId);
$apptQuery->execute();
$apptResult = $apptQuery->get_result();
$response['appointments'] = $apptResult->fetch_all(MYSQLI_ASSOC);

$conn->close();
echo json_encode($response);
?>
