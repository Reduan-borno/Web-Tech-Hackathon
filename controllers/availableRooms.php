<?php
session_start();

$checkin = $_GET["checkin"] ?? "";
$checkout = $_GET["checkout"] ?? "";
$guests = $_GET["guests"] ?? "";

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
    header("Location: ../View/availableRoom.php");
    exit();
}else{
    header("Location: ../View/availableRoom.php?checkin=".$checkin."&checkout=".$checkout."&guests=".$guests);
    exit();
}
?>