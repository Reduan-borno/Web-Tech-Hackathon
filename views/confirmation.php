<html>
<head>
    <title>Booking Confirmation</title>
     <link rel="stylesheet" href="../assets/confrmstyle.css">
</head>
<body>
    <h2>Booking Confirmation</h2>

    <?php
        $booking_id = $_GET["booking_id"] ?? "";
        $room_type = $_GET["room_type"] ?? "";
        $checkin = $_GET["checkin"] ?? "";
        $checkout = $_GET["checkout"] ?? "";
        $total_price = $_GET["total_price"] ?? "";
    ?>

    <table border="1">
        <tr>
            <td>Booking ID</td>
            <td><?php echo $booking_id; ?></td>
        </tr>

        <tr>
            <td>Room Type</td>
            <td><?php echo $room_type; ?></td>
        </tr>

        <tr>
            <td>Check-in Date</td>
            <td><?php echo $checkin; ?></td>
        </tr>

        <tr>
            <td>Check-out Date</td>
            <td><?php echo $checkout; ?></td>
        </tr>

        <tr>
            <td>Total Price</td>
            <td><?php echo $total_price; ?></td>
        </tr>

        <tr>
            <td>Status</td>
            <td>Pending</td>
        </tr>
    </table>

    <br>

    <a href="myBookings.php">Go to My Bookings</a>
    <br>
    <a href="search.php">Search More Rooms</a>

</body>
</html>