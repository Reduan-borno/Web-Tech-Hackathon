<?php
class db {
    function connection() {
        $connection = new mysqli("localhost", "root", "", "hotel");
        if ($connection->connect_error) {
            die("Connection failed: " . $connection->connect_error);
        }
        return $connection;
    }
}
?>