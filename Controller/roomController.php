<?php
include "../models/db.php";
include "../models/RoomModel.php";
include "../models/RoomTypeModel.php";
session_start();

// Check if user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../views/auth/login.php');
    exit;
}

$database = new db();
$connection = $database->connection();

$action = $_GET['action'] ?? 'list';
$errors = [];
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? 'list';
    
    if ($action == 'create' || $action == 'edit') {
        // Validate input
        $roomTypeId = intval($_POST['room_type_id'] ?? 0);
        $roomNumber = trim($_POST['room_number'] ?? '');
        $floor = intval($_POST['floor'] ?? 0);
        $status = $_POST['status'] ?? 'available';
        $roomId = $_POST['room_id'] ?? null;
        
        // Validation
        if (empty($roomNumber)) {
            $errors['room_number'] = "Room number is required";
        } elseif ($action == 'create' && roomNumberExists($connection, $roomNumber)) {
            $errors['room_number'] = "Room number already exists";
        } elseif ($action == 'edit' && roomNumberExists($connection, $roomNumber, $roomId)) {
            $errors['room_number'] = "Room number already exists";
        }
        
        if ($roomTypeId <= 0) {
            $errors['room_type_id'] = "Please select a room type";
        } else {
            $roomType = getRoomTypeById($connection, $roomTypeId);
            if (!$roomType) {
                $errors['room_type_id'] = "Invalid room type";
            }
        }
        
        if ($floor < 0) {
            $errors['floor'] = "Floor must be a non-negative integer";
        }
        
        if (!in_array($status, ['available', 'booked', 'maintenance'])) {
            $errors['status'] = "Invalid status";
        }
        
        if (empty($errors)) {
            if ($action == 'create') {
                if (createRoom($connection, $roomTypeId, $roomNumber, $floor, $status)) {
                    header('Location: roomController.php?action=list&success=1');
                    exit;
                } else {
                    $errors['general'] = "Failed to create room";
                }
            } elseif ($action == 'edit' && $roomId) {
                if (updateRoom($connection, $roomId, $roomTypeId, $roomNumber, $floor, $status)) {
                    header('Location: roomController.php?action=list&success=1');
                    exit;
                } else {
                    $errors['general'] = "Failed to update room";
                }
            }
        }
    } elseif ($action == 'delete') {
        $roomId = $_POST['room_id'] ?? null;
        if ($roomId && deleteRoom($connection, $roomId)) {
            header('Location: roomController.php?action=list&success=1');
            exit;
        } else {
            // Show alert popup and redirect back
            echo "<script>
                alert('Cannot delete this room (future bookings may exist)');
                window.location.href = 'roomController.php?action=list';
            </script>";
            exit;
        }
    }
}

// Get room for edit
$room = null;
if ($action == 'edit' && isset($_GET['id'])) {
    $room = getRoomById($connection, $_GET['id']);
    if (!$room) {
        header('Location: roomController.php');
        exit;
    }
}

// Get all room types for dropdown
$allRoomTypes = getAllRoomTypes($connection);

// Get all rooms with occupancy for listing
$rooms = getAllRoomsWithOccupancy($connection);

include "../views/room.php";
?>
