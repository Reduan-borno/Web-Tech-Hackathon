<?php
session_start();

$bookingIdError = $_SESSION["bookingiderror"] ?? "";
$bookings = $_SESSION["myBookings"] ?? [];

unset($_SESSION["bookingiderror"]);
unset($_SESSION["myBookings"]);
?>

<html>

<head>
    <title>My Bookings</title>
</head>

<body>
    <h2>My Bookings</h2>

    <a href="availableRoom.php">Search Room</a>
    <br><br>

    <?php echo $bookingIdError; ?>

    <table border="1">
        <tr>
            <th>Booking ID</th>
            <th>Room Type</th>
            <th>Room Number</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <tbody id="bookingList">

            <?php
            if(count($bookings) > 0){
                foreach($bookings as $booking){
            ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking["id"]); ?></td>
                        <td><?php echo htmlspecialchars($booking["room_type"]); ?></td>
                        <td><?php echo htmlspecialchars($booking["room_number"]); ?></td>
                        <td><?php echo htmlspecialchars($booking["checkin_date"]); ?></td>
                        <td><?php echo htmlspecialchars($booking["checkout_date"]); ?></td>
                        <td><?php echo htmlspecialchars($booking["total_price"]); ?></td>
                        <td><?php echo htmlspecialchars($booking["status"]); ?></td>
                        <td>
                            <?php
                            if($booking["status"] == "pending" || $booking["status"] == "confirmed"){
                            ?>
                                <form method="post" action="../Controller/cancelBooking.php">
                                    <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking["id"]); ?>">
                                    <input type="submit" value="Cancel">
                                </form>
                            <?php
                            }else{
                                echo "No Action";
                            }
                            ?>
                        </td>
                    </tr>
            <?php
                }
            }else{
            ?>
                <tr>
                    <td colspan="8">No booking data found</td>
                </tr>
            <?php
            }
            ?>

        </tbody>
    </table>

</body>

</html>