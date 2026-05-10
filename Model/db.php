<?php
class db {
    function connection() {
        $connection = new mysqli("localhost", "root", "", "hotel");
        if ($connection->connect_error) {