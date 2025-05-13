<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

$conn = new mysqli("localhost", "root", "", "digilegacy");

$boxId = $_GET['box_id'];

$boxStmt = $conn->prepare("SELECT title, content, visible_to FROM file_boxes WHERE box_id = ?");
$boxStmt->bind_param("s", $boxId);
$boxStmt->execute();
$boxResult = $boxStmt->get_result()->fetch_assoc();

$fileStmt = $conn->prepare("SELECT file_name, file_path FROM file_box_files WHERE box_id = ?");
$fileStmt->bind_param("s", $boxId);
$fileStmt->execute();
$fileResult = $fileStmt->get_result();

$files = [];
while ($row = $fileResult->fetch_assoc()) {
  $files[] = $row;
}

echo json_encode([
  "status" => "success",
  "box" => $boxResult,
  "files" => $files,
]);
?>
