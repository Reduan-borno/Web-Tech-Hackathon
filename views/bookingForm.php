<html>

<head>
    <title>Book Room</title>

   <link rel="stylesheet" href="../assets/bookingfromStyle.css">
</head>

<body>
    <h2>Booking Form</h2>

    <form method="post" action="confirm_booking.php">

        <table>
            <tr>
                <td>Guest Name</td>
                <td><input type="text" name="guest_name" placeholder="Enter your name" /></td>
            </tr>

            <tr>
                <td>Phone</td>
                <td><input type="text" name="phone" placeholder="Enter phone number" /></td>
            </tr>

            <tr>
                <td>Email</td>
                <td><input type="email" name="email" placeholder="Enter email" /></td>
            </tr>

            <tr>
                <td>Room Type</td>
                <td>
                    <select name="room_type_id">
                        <option value="">Select Room Type</option>
                        <option value="1">Standard</option>
                        <option value="2">Deluxe</option>
                        <option value="3">Suite</option>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Check-in Date</td>
                <td><input type="date" name="checkin" /></td>
            </tr>

            <tr>
                <td>Check-out Date</td>
                <td><input type="date" name="checkout" /></td>
            </tr>

            <tr>
                <td>Number of Guests</td>
                <td><input type="number" name="guests" placeholder="Enter number of guests" /></td>
            </tr>

            <tr>
                <td>Special Request</td>
                <td><textarea name="special_request" placeholder="Write special request"></textarea></td>
            </tr>

            <tr>
                <td></td>
                <td><input type="submit" name="book" value="Confirm Booking" /></td>
            </tr>
        </table>

    </form>
</body>

</html>