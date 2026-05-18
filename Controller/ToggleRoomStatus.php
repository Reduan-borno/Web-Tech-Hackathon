<?php
session_start();
header("Content-Type: application/json");
include "../config/DatabaseConnection.php";
include "../models/RoomModel.php";

// Admin check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(["success" => false, "error" => "Unauthorized"]);
    exit;
}

// Method check
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Method not allowed"]);
    exit;
}

$roomId = $_POST['room_id'] ?? null;
if (!$roomId) {
    echo json_encode(["success" => false, "error" => "Room ID required"]);
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();
$model = new RoomModel();

$result = $model->ToggleRoomStatus($conn, "rooms", $roomId);

if ($result) {
    echo json_encode(["success" => true, "new_status" => $result]);
} else {
    echo json_encode(["success" => false, "error" => "Failed to toggle status"]);
}
?>
