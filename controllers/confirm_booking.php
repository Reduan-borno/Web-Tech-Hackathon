<?php 
//include "../Model/DatabaseConnection.php";
include "../Model/db.php";
include "../Model/RoomModel.php";
include "../Model/BookingModel.php";
session_start();

$guest_name = $_POST["guest_name"] ?? "";
$phone = $_POST["phone"] ?? "";
$email = $_POST["email"] ?? "";
$room_type_id = $_POST["room_type_id"] ?? "";
$checkin = $_POST["checkin"] ?? "";
$checkout = $_POST["checkout"] ?? "";
$guests = $_POST["guests"] ?? "";
$special_request = $_POST["special_request"] ?? "";
//
$user_id = $_SESSION["user_id"] ?? 1;

$hasGuestNameError = true;
$hasPhoneError = true;
$hasEmailError = true;
$hasRoomTypeError = true;
$hasCheckinError = true;
$hasCheckoutError = true;
$hasGuestsError = true;

if(!$guest_name){
    $_SESSION["guestnameerror"] = "Complete all";
    $hasGuestNameError = true;
}else{
    unset($_SESSION["guestnameerror"]);
    $hasGuestNameError = false;
}

if(!$phone){
    $_SESSION["phoneerror"] = "Complete all";
    $hasPhoneError = true;
}else{
    unset($_SESSION["phoneerror"]);
    $hasPhoneError = false;
}

if(!$email){
    $_SESSION["emailerror"] = "Complete all";
    $hasEmailError = true;
}else{
    unset($_SESSION["emailerror"]);
    $hasEmailError = false;
}

if(!$room_type_id){
    $_SESSION["roomtypeerror"] = "Complete all";
    $hasRoomTypeError = true;
}else{
    unset($_SESSION["roomtypeerror"]);
    $hasRoomTypeError = false;
}

if(!$checkin){
    $_SESSION["checkinerror"] = "Complete all";
    $hasCheckinError = true;
}else{
    unset($_SESSION["checkinerror"]);
    $hasCheckinError = false;
}

if(!$checkout){
    $_SESSION["checkouterror"] = "Complete all";
    $hasCheckoutError = true;
}else{
    unset($_SESSION["checkouterror"]);
    $hasCheckoutError = false;
}

if(!$guests){
    $_SESSION["guestserror"] = "Complete all";
    $hasGuestsError = true;
}else{
    unset($_SESSION["guestserror"]);
    $hasGuestsError = false;
}

if($hasGuestNameError || $hasPhoneError || $hasEmailError || $hasRoomTypeError || $hasCheckinError || $hasCheckoutError || $hasGuestsError){
    $_SESSION["guest_name"] = $guest_name;
    $_SESSION["phone"] = $phone;
    $_SESSION["email"] = $email;
    $_SESSION["room_type_id"] = $room_type_id;
    $_SESSION["checkin"] = $checkin;
    $_SESSION["checkout"] = $checkout;
    $_SESSION["guests"] = $guests;
    $_SESSION["special_request"] = $special_request;

    Header("Location: ../View/bookingForm.php");
}else{
   //Header("Location: ../View/confirmation.php");
    $db = new db();
    $connection = $db->connection();

    $roomType = getRoomTypeById($connection, $room_type_id);
    $availableRoom = getAvailableRoomByType($connection, $room_type_id, $checkin, $checkout);

    if($roomType && $availableRoom){
        $date1 = new DateTime($checkin);
        $date2 = new DateTime($checkout);
        $nights = $date1->diff($date2)->days;

        $total_price = $roomType["price_per_night"] * $nights;

        $booking_id = createBooking($connection, $user_id, $availableRoom["id"], $checkin, $checkout, $total_price);

        if($booking_id){
            Header("Location: ../View/confirmation.php?booking_id=".$booking_id."&room_type=".$roomType["name"]."&checkin=".$checkin."&checkout=".$checkout."&total_price=".$total_price);
        }else{
            Header("Location: ../View/bookingForm.php");
        }
    }else{
        $_SESSION["roomtypeerror"] = "Complete all";
        Header("Location: ../View/bookingForm.php");
    }
}

?>