<?php
include "../Model/db.php";
include "../Model/RoomModel.php";
session_start();

$checkin = $_GET["checkin"] ?? "";
$checkout = $_GET["checkout"] ?? "";
$guests = $_GET["guests"] ?? "";
$ajax = $_GET["ajax"] ?? "";

$_SESSION["checkin"] = $checkin;
$_SESSION["checkout"] = $checkout;
$_SESSION["guests"] = $guests;

$hasError = false;

if($checkin == ""){
    $_SESSION["checkinerror"] = "Complete all";
    $hasError = true;
}

if($checkout == ""){
    $_SESSION["checkouterror"] = "Complete all";
    $hasError = true;
}

if($guests == ""){
    $_SESSION["guestserror"] = "Complete all";
    $hasError = true;
}

if($hasError){
    if($ajax == "1"){
        header("Content-Type: application/json");
        echo json_encode([]);
        exit();
    }

    header("Location: ../View/availableRoom.php");
    exit();
}else{
    $db = new db();
    $connection = $db->connection();

    $availableRooms = getAvailableRoomTypes($connection, $checkin, $checkout, $guests);

    $date1 = new DateTime($checkin);
    $date2 = new DateTime($checkout);
    $nights = $date1->diff($date2)->days;

    for($i = 0; $i < count($availableRooms); $i++){
        $availableRooms[$i]["total_price"] = $availableRooms[$i]["price_per_night"] * $nights;
    }

    if($ajax == "1"){
        header("Content-Type: application/json");
        echo json_encode($availableRooms);
        exit();
    }

    $_SESSION["availableRooms"] = $availableRooms;
    $_SESSION["checkin"] = $checkin;
    $_SESSION["checkout"] = $checkout;
    $_SESSION["guests"] = $guests;

    header("Location: ../View/availableRoom.php");
    exit();
}
?>