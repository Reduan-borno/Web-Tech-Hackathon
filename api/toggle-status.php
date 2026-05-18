<?php
header('Content-Type: application/json');

include "../models/db.php";
include "../models/RoomModel.php";
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$roomId = $input['room_id'] ?? null;

if (!$roomId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid room ID']);
    exit;
}

$database = new db();
$connection = $database->connection();

$newStatus = toggleRoomStatus($connection, $roomId);

if ($newStatus !== false) {
    echo json_encode([
        'success' => true,
        'message' => 'Room status updated',
        'new_status' => $newStatus,
        'badge_text' => ucfirst($newStatus),
        'badge_class' => $newStatus === 'available' ? 'badge-success' : 'badge-danger'
    ]);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Room not found']);
}
?>
