<html>

<head>
    <title>Search Room</title>
</head>

<body>
    <h2>Search Available Rooms</h2>

    <form method="get" action="results.php">

        <table>
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
            <tr></tr>
            <tr>
                <td></td>
                <td><input type="submit" name="search" value="Search Rooms" /></td>
            </tr>
        </table>

    </form>
</body>

</html>