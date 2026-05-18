<?php
session_start();

$guestNameError = $_SESSION["guestnameerror"] ?? "";
$phoneError = $_SESSION["phoneerror"] ?? "";
$emailError = $_SESSION["emailerror"] ?? "";
$roomTypeError = $_SESSION["roomtypeerror"] ?? "";
$checkinError = $_SESSION["checkinerror"] ?? "";
$checkoutError = $_SESSION["checkouterror"] ?? "";
$guestsError = $_SESSION["guestserror"] ?? "";

$guest_name = $_SESSION["guest_name"] ?? "";
$phone = $_SESSION["phone"] ?? "";
$email = $_SESSION["email"] ?? "";
$room_type_id = $_SESSION["room_type_id"] ?? ($_GET["room_type_id"] ?? "");
$checkin = $_SESSION["checkin"] ?? ($_GET["checkin"] ?? "");
$checkout = $_SESSION["checkout"] ?? ($_GET["checkout"] ?? "");
$guests = $_SESSION["guests"] ?? ($_GET["guests"] ?? "");
$special_request = $_SESSION["special_request"] ?? "";

unset($_SESSION["guestnameerror"]);
unset($_SESSION["phoneerror"]);
unset($_SESSION["emailerror"]);
unset($_SESSION["roomtypeerror"]);
unset($_SESSION["checkinerror"]);
unset($_SESSION["checkouterror"]);
unset($_SESSION["guestserror"]);

unset($_SESSION["guest_name"]);
unset($_SESSION["phone"]);
unset($_SESSION["email"]);
unset($_SESSION["room_type_id"]);
unset($_SESSION["checkin"]);
unset($_SESSION["checkout"]);
unset($_SESSION["guests"]);
unset($_SESSION["special_request"]);
?>

<html>
    
<head>
    <title>Book Room</title>
    <link rel="stylesheet" href="../assets/bookingfromStyle.css">
</head>

<body>
    <h2>Booking Form</h2>

    <a href="availableRoom.php">Back</a>
    <br><br>

    <form method="post" action="../Controller/confirm_booking.php">

    <table>
        <tr>
            <td>Guest Name</td>
            <td><input type="text" name="guest_name" placeholder="Enter your name" value="<?php echo htmlspecialchars($guest_name); ?>"></td>
            <td><?php echo $guestNameError; ?></td>
        </tr>

        <tr>
            <td>Phone</td>
            <td><input type="text" name="phone" placeholder="Enter phone number" value="<?php echo htmlspecialchars($phone); ?>"></td>
            <td><?php echo $phoneError; ?></td>
        </tr>

        <tr>
            <td>Email</td>
            <td><input type="email" name="email" placeholder="Enter email" value="<?php echo htmlspecialchars($email); ?>"></td>
            <td><?php echo $emailError; ?></td>
        </tr>

        <tr>
            <td>Room Type</td>
            <td>
                <select name="room_type_id">
                    <option value="">Select Room Type</option>
                    <option value="1" <?php if($room_type_id == "1"){ echo "selected"; } ?>>Standard</option>
                    <option value="2" <?php if($room_type_id == "2"){ echo "selected"; } ?>>Deluxe</option>
                    <option value="3" <?php if($room_type_id == "3"){ echo "selected"; } ?>>Suite</option>
                </select>
            </td>
            <td><?php echo $roomTypeError; ?></td>
        </tr>

        <tr>
            <td>Check-in Date</td>
            <td><input type="date" name="checkin" value="<?php echo htmlspecialchars($checkin); ?>"></td>
            <td><?php echo $checkinError; ?></td>
        </tr>

        <tr>
            <td>Check-out Date</td>
            <td><input type="date" name="checkout" value="<?php echo htmlspecialchars($checkout); ?>"></td>
            <td><?php echo $checkoutError; ?></td>
        </tr>

        <tr>
            <td>Number of Guests</td>
            <td><input type="number" name="guests" placeholder="Enter number of guests" value="<?php echo htmlspecialchars($guests); ?>"></td>
            <td><?php echo $guestsError; ?></td>
        </tr>

        <tr>
            <td>Special Request</td>
            <td><textarea name="special_request" placeholder="Write special request"><?php echo htmlspecialchars($special_request); ?></textarea></td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td><input type="submit" name="book" value="Confirm Booking"></td>
            <td></td>
        </tr>
    </table>

    </form>
</body>

</html>