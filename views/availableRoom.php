<html>

<head>
    <title>Available Rooms</title>
    <link rel="stylesheet" href="/EXAM/assets/availableRoomstyle.css">
</head>

<body>
    <h2>Available Rooms</h2>

    <?php
    $checkin = $_GET["checkin"] ?? "";
    $checkout = $_GET["checkout"] ?? "";
    $guests = $_GET["guests"] ?? "";
    ?>

    <form method="get" action="../Controller/availableRooms.php">
        <table>
            <tr>
                <td>Check-in Date:</td>
                <td><input type="date" name="checkin" value="<?php echo $checkin; ?>"></td>
            </tr>

            <tr>
                <td>Check-out Date:</td>
                <td><input type="date" name="checkout" value="<?php echo $checkout; ?>"></td>
            </tr>

            <tr>
                <td>Number of Guests:</td>
                <td><input type="number" name="guests" value="<?php echo $guests; ?>"></td>
            </tr>

            <tr>
                <td></td>
                <td><input type="submit" name="search" value="Search"></td>
            </tr>
        </table>
    </form>

    <br>

    <h3>Your available room will be shown down here</h3>

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

</body>

</html>