<?php
class DatabaseConnection {
   

    public function checkExistingUserByUsername($connection, $tableName, $username) {
        $sql = "SELECT id FROM $tableName WHERE username = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function CreateUser($connection, $tableName, $username, $password, $imagePath) {
        $sql = "INSERT INTO $tableName (username, password, image_path) VALUES (?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $imagePath);
        return $stmt->execute();
    }
}
?>
