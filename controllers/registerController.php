
<?php
include "../models/db.php";
include "../models/UserModel.php";

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $database   = new db();
    $connection = $database->connection();

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $nationality = trim($_POST['nationality'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $emailCheck = emailExists($connection, $email);
    $errors = [];
    
    if (empty($name)) {
        $errors['name'] = 'Full name is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'A valid email address is required.';
    }
    elseif($emailCheck){
        $errors['email'] = 'This email is already registered.';
    }
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required.';
    }
    if (empty($nationality)) {
        $errors['nationality'] = 'Please select your nationality.';
    }
    if (empty($password) || strlen($password) < 8) {
        $errors['password'] = 'Password must be at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors['confirm'] = 'Passwords do not match.';
    }

    if (!empty($errors))
    {
        echo json_encode(['success' => false,'errors' => $errors]);
    }
    else
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $result = createUser($connection, $name, $email, $hashedPassword, $phone, $nationality, "guest");
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'errors' => ['general' => 'Registration failed. Try again.']]);
        }
    }

}
?>

 <!-- /Applications/XAMPP/xamppfiles/htdocs/Web_Tech_Final/Assignmet -->