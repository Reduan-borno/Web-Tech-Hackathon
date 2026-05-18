<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === "admin@example.com" && $password === "admin123") {
        $_SESSION['UserType'] = "Admin";
        header("Location: AdminPanel.php");
        exit();
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<form method="POST">
    <label>Email:</label>
    <input type="text" name="email">
    <label>Password:</label>
    <input type="password" name="password">
    <button type="submit">Login</button>
    <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
</form>
