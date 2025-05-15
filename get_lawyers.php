<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Include DB connection
require_once("db_connect.php");

// Prepare SQL query
$sql = "SELECT 
            lawyer_id AS id,
            CONCAT(prefix, ' ', first_name, ' ', last_name) AS name,
            bio,
            profile_picture_url,
            active_status
        FROM lawyers";

// Execute query
$result = $conn->query($sql);

// Check for results
if ($result->num_rows > 0) {
    $lawyers = array();

    while ($row = $result->fetch_assoc()) {
        $lawyers[] = $row;
    }

    echo json_encode($lawyers);
} else {
    echo json_encode([]);
}

$conn->close();
?>
