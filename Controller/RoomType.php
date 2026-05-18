
<?php
session_start();
include "../config/DatabaseConnection.php";
include "../models/RoomTypeModel.php";


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../views/login.php");
    exit;
}

$db = new DatabaseConnection();
$conn = $db->openConnection();
$model = new RoomTypeModel();

$action = $_GET['action'] ?? 'list';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price_per_night'] ?? 0;
    $capacity = $_POST['max_capacity'] ?? 0;
    $amenities = isset($_POST['amenities']) ? json_encode($_POST['amenities']) : '[]';
    $thumbnail = '';

    // Validation
    if (empty($name)) $errors['name'] = "Name is required";
    if (empty($description)) $errors['description'] = "Description is required";
    if ($price <= 0) $errors['price_per_night'] = "Price must be positive";
    if ($capacity <= 0) $errors['max_capacity'] = "Capacity must be positive";

    // File upload validation
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['size'] > 0) {
        $file = $_FILES['thumbnail'];
        $maxSize = 2 * 1024 * 1024; // 2MB
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, ['image/jpeg', 'image/png'])) {
            $errors['thumbnail'] = "Only JPEG and PNG files allowed";
        }
        if ($file['size'] > $maxSize) {
            $errors['thumbnail'] = "File size must be less than 2MB";
        }

        if (empty($errors)) {
            $filename = uniqid() . "_" . basename($file['name']);
            $uploadPath = "../public/uploads/rooms/" . $filename;
            if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                $thumbnail = "public/uploads/rooms/" . $filename;
            } else {
                $errors['thumbnail'] = "Failed to upload file";
            }
        }
    }

    // Save or update
    if (empty($errors)) {
        if ($action === 'edit' && isset($_POST['id'])) {
            $model->UpdateRoomType($conn, "room_types", $_POST['id'], $name, $description, $price, $capacity, $thumbnail, $amenities);
        } else {
            $model->CreateRoomType($conn, "room_types", $name, $description, $price, $capacity, $thumbnail, $amenities);
        }
        header("Location: RoomTypeController.php?action=list&success=1");
        exit;
    } else {
        $_SESSION['formErrors'] = $errors;
        header("Location: ../views/RoomTypeList.php");
        exit;
    }
}

// Delete
if ($action === 'delete' && isset($_GET['id'])) {
    $model->DeleteRoomType($conn, "room_types", $_GET['id']);
    header("Location: RoomTypeController.php?action=list&success=1");
    exit;
}

// Fetch data for listing
$roomTypes = $model->GetAllRoomTypes($conn, "room_types");
include "../views/RoomTypeList.php";
?>
