<?php
include "../models/db.php";
include "../models/UserModel.php";
session_start();

$database = new db();
$connection = $database->connection();

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $uemail = $_POST["email"];
    $upass = $_POST["password"];
    $remember_me = 0;

    $user = loginUser($connection,$uemail);
    $remember_me = isset($_POST["remember_me"]);

    if($user && password_verify($upass, $user['password_hash'])){
        $_SESSION['user_id']= $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        if($remember_me){
            $token = bin2hex(random_bytes(32));
            setcookie("remember_token", $token, time() + (86400*30), "/");
            saveRememberToken($connection, $user['id'], $token);
        }

        if($user['role'] == "admin"){
            header('Location: ../views/admin.php');
            exit;
        }
        else{
            header('Location: ../views/user.php'); 
            exit;
        }
    }
    else{
        echo "Invalid email or password";
        exit;
    }
   
}

?>