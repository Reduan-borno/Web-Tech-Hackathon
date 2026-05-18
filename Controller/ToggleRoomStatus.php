<?php
session_start();
header('Content-Type: application/json');

// Admin authentication check
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit;
}

echo json_encode(['success' => true, 'message' => 'ToggleRoomStatusController initialized']);
?>
