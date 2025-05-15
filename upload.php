<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");
$uploadDir = "uploads/";
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

if ($_FILES['file']) {
    $fileName = basename($_FILES["file"]["name"]);
    $uniqueName = time() . "_" . $fileName;
    $targetFile = $uploadDir . $uniqueName;

    $allowedTypes = ['pdf', 'docx', 'jpg', 'png', 'txt'];
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (!in_array($ext, $allowedTypes)) {
        echo json_encode(["success" => false, "message" => "Unsupported file type"]);
        exit;
    }

    if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
        echo json_encode([
            "success" => true,
            "path" => $targetFile,
            "name" => $fileName
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Upload failed"]);
    }
}
