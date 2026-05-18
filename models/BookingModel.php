?php

function createBooking($connection, $user_id, $room_id, $checkin, $checkout, $total_price){
    $sql = "INSERT INTO bookings 
            (user_id, room_id, checkin_date, checkout_date, total_price, status) 
            VALUES (?, ?, ?, ?, ?, 'pending')";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iissd", $user_id, $room_id, $checkin, $checkout, $total_price);

    if($stmt->execute()){
        return $connection->insert_id;
    }else{
        return false;
    }
}


function getUserBookings($connection, $user_id){
    $sql = "SELECT 
                b.id,
                rt.name AS room_type,
                r.room_number,
                b.checkin_date,
                b.checkout_date,
                b.total_price,
                b.status
            FROM bookings b
            INNER JOIN rooms r ON b.room_id = r.id
            INNER JOIN room_types rt ON r.room_type_id = rt.id
            WHERE b.user_id = ?
            ORDER BY b.created_at DESC";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $bookings = [];

    while($row = $result->fetch_assoc()){
        $bookings[] = $row;
    }

    return $bookings;
}


function cancelBooking($connection, $booking_id, $user_id){
    $sql = "UPDATE bookings
            SET status = 'cancelled'
            WHERE id = ?
            AND user_id = ?
            AND status IN ('pending', 'confirmed')
            AND checkin_date > DATE_ADD(CURDATE(), INTERVAL 1 DAY)";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $user_id);

    if($stmt->execute()){
        return true;
    }else{
        return false;
    }
}


function getBookingById($connection, $booking_id, $user_id){
    $sql = "SELECT 
                b.id,
                rt.name AS room_type,
                b.checkin_date,
                b.checkout_date,
                b.total_price,
                b.status
            FROM bookings b
            INNER JOIN rooms r ON b.room_id = r.id
            INNER JOIN room_types rt ON r.room_type_id = rt.id
            WHERE b.id = ?
            AND b.user_id = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 1){
        return $result->fetch_assoc();
    }else{
        return false;
    }
}

?>