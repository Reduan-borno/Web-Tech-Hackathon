<?php
session_start();
include "../models/db.php";
include "../models/UserModel.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database   = new db();
    $connection = $database->connection();

    $roomType        = trim($_POST['preferred_room_type_id'] ?? '');
    $specialRequests = trim($_POST['special_requests'] ?? '');

    $errors = [];

    if (empty($roomType)) {
        $errors['preferred_room_type_id'] = 'Please select a room type.';
    }

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    $result = updateUserPreferences($connection, $userId, $roomType, $specialRequests);

    if ($result) {
        $_SESSION['preferred_room_type_id'] = $roomType;
        $_SESSION['special_requests']        = $specialRequests;

        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'errors'  => ['general' => 'Preferences update failed. Try again.']
        ]);
    }
    exit;
}
