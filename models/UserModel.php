<?php


function createUser($connection,$name,$email,$password,$phone,$nationality,$role){
    $sql = "INSERT INTO users (name,email,password_hash,phone,nationality,role) VALUES (?,?,?,?,?,?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("sssssss", $name, $email, $password, $phone, $nationality,$role);
    if($stmt -> execute()){
        return true;
    }
    else{
        return false;
    }
}

function emailExists($connection, $email, $excludeUserId = null) {
    if ($excludeUserId) {
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("si", $email, $excludeUserId);
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $email);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function loginUser($connection, $email) {
    $sql = "SELECT id, name, phone,nationality, role, password_hash FROM users WHERE email = ?";
    
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 1){
        return $result->fetch_assoc();
    }
    else{
        return false;
    }
}

function saveRememberToken($connection, $userId, $token){
    $sql = "UPDATE users SET remember_token=? WHERE id=?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $token, $userId);
    return $stmt->execute();
}

function updateUserProfile($connection, $userId, $name, $email, $phone, $nationality) {

    $sql = "UPDATE users SET name = ?, email = ?, phone = ?, nationality = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssssi", $name, $email, $phone, $nationality, $userId);
    return $stmt->execute();
}