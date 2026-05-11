<?php
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $nationality = trim($_POST['nationality'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    $errors = [];
    
    if (empty($name)) {
        $errors['name'] = 'Full name is required.';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Valid email is required.';
    }
    if (empty($phone)) {
        $errors['phone'] = 'Phone number is required.';
    }
    if (empty($nationality)) {
        $errors['nationality'] = 'Please select your country.';
    }
    if (empty($password) || strlen($password) < 8) {
        $errors['password'] = 'Password at least 8 characters.';
    }
    if ($password !== $confirm) {
        $errors['confirm'] = 'Passwords not match.';
    }

    if (!empty($errors))
    {
        echo json_encode([
        'success' => false,
        'errors' => $errors
      ]);
    }
    else
    {
        echo json_encode([
            'success' => true
        ]);
    }
}
?>