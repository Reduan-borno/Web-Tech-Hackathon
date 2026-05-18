<?php 
//include "../Model/DatabaseConnection.php";
include "../Model/db.php";
include "../Model/BookingModel.php";
session_start();


$user_id = $_SESSION["user_id"] ?? 1;
$booking_id = $_POST["booking_id"] ?? "";

$hasBookingIdError = true;

if(!$booking_id){
    $_SESSION["bookingiderror"] = "Complete all";
    $hasBookingIdError = true;
}else{
    unset($_SESSION["bookingiderror"]);
    $hasBookingIdError = false;
}

if($hasBookingIdError){
    Header("Location: ../View/bookingroom.php");
}else{
    //Header("Location: ../View/bookingroom.php");
    $db = new db();
    $connection = $db->connection();

    cancelBooking($connection, $booking_id, $user_id);

    Header("Location: ../Controller/myBookingsController.php");
}

?>