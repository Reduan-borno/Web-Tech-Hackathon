<?php
session_start();

$booking_id = $_GET["booking_id"] ?? "";
$room_type = $_GET["room_type"] ?? "";
$checkin = $_GET["checkin"] ?? "";
$checkout = $_GET["checkout"] ?? "";
$total_price = $_GET["total_price"] ?? "";
?>

<html>
<head>
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="../assets/confrmstyle.css">
</head>
<body>
    <h2>Booking Confirmation</h2>

    <table border="1">
        <tr>
            <td>Booking ID</td>
            <td><?php echo htmlspecialchars($booking_id); ?></td>
        </tr>

        <tr>
            <td>Room Type</td>
            <td><?php echo htmlspecialchars($room_type); ?></td>
        </tr>

        <tr>
            <td>Check-in Date</td>
            <td><?php echo htmlspecialchars($checkin); ?></td>
        </tr>

        <tr>
            <td>Check-out Date</td>
            <td><?php echo htmlspecialchars($checkout); ?></td>
        </tr>

        <tr>
            <td>Total Price</td>
            <td><?php echo htmlspecialchars($total_price); ?></td>
        </tr>

        <tr>
            <td>Status</td>
            <td>pending</td>
        </tr>
    </table>

    <br>

    <a href="../Controller/myBookingsController.php">Go to My Bookings</a>
    <br>
    <a href="availableRoom.php">Search More Rooms</a>

</body>
</html>