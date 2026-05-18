<?php
include "../models/db.php";
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

// Available amenities
$amenitiesOptions = ['WiFi', 'AC', 'TV', 'Minibar', 'Safe', 'Bathtub', 'Balcony'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? 'list';
    
    if ($action == 'create' || $action == 'edit') {
        // Validate input
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $pricePerNight = floatval($_POST['price_per_night'] ?? 0);
        $maxCapacity = intval($_POST['max_capacity'] ?? 0);
        $roomTypeId = $_POST['room_type_id'] ?? null;
        
        // Validation
        if (empty($name)) {
            $errors['name'] = "Room type name is required";
        }
        if (empty($description)) {
            $errors['description'] = "Description is required";
        }
        if ($pricePerNight <= 0) {
            $errors['price_per_night'] = "Price must be a positive number";
        }
        if ($maxCapacity <= 0) {
            $errors['max_capacity'] = "Max capacity must be a positive number";
        }
        
        // Handle file upload
        $thumbnailPath = null;
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
            $file = $_FILES['thumbnail'];
            $allowedMimes = ['image/jpeg', 'image/png'];
            $maxSize = 2 * 1024 * 1024; // 2 MB
            
            if (!in_array($file['type'], $allowedMimes)) {
                $errors['thumbnail'] = "Only JPEG and PNG files are allowed";
            } elseif ($file['size'] > $maxSize) {
                $errors['thumbnail'] = "File size must not exceed 2 MB";
            } else {
                // Create upload directory if it doesn't exist
                $uploadDir = "../public/uploads/rooms/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($file['name']);
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($file['tmp_name'], $filePath)) {
                    $thumbnailPath = "public/uploads/rooms/" . $fileName;
                } else {
                    $errors['thumbnail'] = "Failed to upload file";
                }
            }
        }
        
        // Get selected amenities
        $amenities = [];
        foreach ($amenitiesOptions as $amenity) {
            if (isset($_POST['amenity_' . $amenity])) {
                $amenities[] = $amenity;
            }
        }
        
        if (empty($amenities)) {
            $errors['amenities'] = "Select at least one amenity";
        }
        
        if (empty($errors)) {
            if ($action == 'create') {
                if (createRoomType($connection, $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities)) {
                    $success = true;
                    header('Location: roomtypeController.php?action=list&success=1');
                    exit;
                } else {
                    $errors['general'] = "Failed to create room type";
                }
            } elseif ($action == 'edit' && $roomTypeId) {
                if (updateRoomType($connection, $roomTypeId, $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities)) {
                    header('Location: roomtypeController.php?action=list&success=1');
                    exit;
                } else {
                    $errors['general'] = "Failed to update room type";
                }
            }
        }
    } elseif ($action == 'delete') {
        $roomTypeId = $_POST['room_type_id'] ?? null;
        if ($roomTypeId && deleteRoomType($connection, $roomTypeId)) {
            header('Location: roomtypeController.php?action=list&success=1');
            exit;
        } else {
            $errors['general'] = "Cannot delete this room type (rooms may be using it)";
        }
    }
}

// Get room type for edit
$roomType = null;
if ($action == 'edit' && isset($_GET['id'])) {
    $roomType = getRoomTypeById($connection, $_GET['id']);
    if (!$roomType) {
        header('Location: roomtypeController.php');
        exit;
    }
}

// Get all room types for listing
$roomTypes = getAllRoomTypes($connection);

// Decode amenities for listing
foreach ($roomTypes as $key => $rt) {
    $roomTypes[$key]['amenities'] = decodeAmenities($rt['amenities']);
}
unset($key, $rt);

include "../views/roomtype.php";
?>
