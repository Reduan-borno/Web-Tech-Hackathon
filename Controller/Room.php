<?php
session_start();
include "../config/DatabaseConnection.php";
include "../models/RoomModel.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();
$model = new RoomModel();

$action = $_GET['action'] ?? 'list';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomNumber = $_POST['room_number'] ?? '';
    $floor = $_POST['floor'] ?? 0;
    $roomTypeId = $_POST['room_type_id'] ?? 0;
    $status = $_POST['status'] ?? '';

    // Validation
    if (empty($roomNumber)) $errors['room_number'] = "Room number is required";
    if ($floor <= 0) $errors['floor'] = "Floor must be a positive integer";
    if ($roomTypeId <= 0) $errors['room_type_id'] = "Room type is required";
    if (!in_array($status, ['available', 'maintenance'])) {
        $errors['status'] = "Invalid status";
    }

    // Save or update
    if (empty($errors)) {
        if ($action === 'edit' && isset($_POST['id'])) {
            $result = $model->UpdateRoom($conn, "rooms", $_POST['id'], $roomNumber, $floor, $roomTypeId, $status);
        } else {
            $result = $model->CreateRoom($conn, "rooms", $roomNumber, $floor, $roomTypeId, $status);
        }

        if ($result === false) {
            $_SESSION['formErrors']['general'] = "Room number already exists.";
            header