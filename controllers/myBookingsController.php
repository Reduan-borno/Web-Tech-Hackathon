<?php 
include "../Model/db.php";
include "../Model/BookingModel.php";
session_start();

$user_id = $_SESSION["user_id"] ?? 1;

$db = new db();
$connection = $db->connection();

$bookings = getUserBookings($connection, $user_id);

$_SESSION["myBookings"] = $bookings;

Header("Location: ../View/bookingroom.php");
?>
