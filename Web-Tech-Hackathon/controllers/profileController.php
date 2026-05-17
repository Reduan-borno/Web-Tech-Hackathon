<?php
session_start();
include "../models/db.php";
include "../models/UserModel.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/auth/login.php");
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new db();
    $connection = $database->connection();

    $name        = trim($_POST['name'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $phone       = trim($_POST['phone'] ?? '');
    $nationality = trim($_POST['nationality'] ?? '');

    $errors = [];

    if (empty($name))
        $errors['name'] = 'Full name is required.';

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    } else {
       
        if (emailExists($connection, $email, $userId)) {
            $errors['email'] = 'This email is already registered.';
        }
    }

    if (empty($phone))
        $errors['phone'] = 'Phone number is required.';

    if (empty($nationality))
        $errors['nationality'] = 'Please select your nationality.';

    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }

    $result = updateUserProfile($connection, $userId, $name, $email, $phone, $nationality);

    if ($result) {
        $_SESSION['name']        = $name;
        $_SESSION['email']       = $email;
        $_SESSION['phone']       = $phone;
        $_SESSION['nationality'] = $nationality;
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'errors' => ['general' => 'Profile update failed. Try again.']]);
    }
    exit;
}
?>