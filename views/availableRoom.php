<?php
session_start();

$checkinError = $_SESSION["checkinerror"] ?? "";
$checkoutError = $_SESSION["checkouterror"] ?? "";
$guestsError = $_SESSION["guestserror"] ?? "";


$checkin = $_SESSION["checkin"] ?? "";
$checkout = $_SESSION["checkout"] ?? "";
$guests = $_SESSION["guests"] ?? "";

unset($_SESSION["checkinerror"]);
unset($_SESSION["checkouterror"]);
unset($_SESSION["guestserror"]);

unset($_SESSION["checkin"]);
unset($_SESSION["checkout"]);
unset($_SESSION["guests"]);
?>

<html>

<head>
    <title>Available Rooms</title>
    <link rel="stylesheet" href="../assets/availableRoomstyle.css">
</head>

<body>
    <h2>Available Rooms</h2>

    <a href="bookingroom.php">Back</a>
    <br><br>

    <form method="get" action="../Controller/availableRooms.php">
        <table>
            <tr>
                <td>Check-in Date:</td>
                <td><input type="date" name="checkin" value="<?php echo htmlspecialchars($checkin); ?>"></td>
                <td><?php echo $checkinError; ?></td>
            </tr>

            <tr>
                <td>Check-out Date:</td>
                <td><input type="date" name="checkout" value="<?php echo htmlspecialchars($checkout); ?>"></td>
                <td><?php echo $checkoutError; ?></td>
            </tr>

            <tr>
                <td>Number of Guests:</td>
                <td><input type="number" name="guests" value="<?php echo htmlspecialchars($guests); ?>"></td>
                <td><?php echo $guestsError; ?></td>
            </tr>

            <tr>
                <td></td>
                <td><input type="submit" name="search" value="Search"></td>
            </tr>
        </table>
    </form>

    <br>

    <h3>Your available room will be shown down here</h3>

    <!-- Available room data will be shown from database -->
       <div id="roomResult">
    <table border="1">
        <tr>
            <th>Room Type</th>
            <th>Amenities</th>
            <th>Price Per Night</th>
            <th>Total Price</th>
            <th>Action</th>
        </tr>

        <tr>
            <td colspan="5">Available room data will be shown from database</td>
        </tr>
    </table>
</div>

    <input type="hidden" id="checkin" value="<?php echo htmlspecialchars($checkin); ?>">
    <input type="hidden" id="checkout" value="<?php echo htmlspecialchars($checkout); ?>">
    <input type="hidden" id="guests" value="<?php echo htmlspecialchars($guests); ?>">

    <script src="../ajax/availableRooms.js"></script>
</body>

</html>