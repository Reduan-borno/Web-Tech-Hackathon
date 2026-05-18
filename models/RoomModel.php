<?php

function getAvailableRoomTypes($connection, $checkin, $checkout, $guests){
    $sql = "SELECT 
                rt.id,
                rt.name,
                rt.description,
                rt.price_per_night,
                rt.max_capacity,
                rt.amenities
            FROM room_types rt
            WHERE rt.max_capacity >= ?
            AND EXISTS (
                SELECT 1
                FROM rooms r
                WHERE r.room_type_id = rt.id
                AND r.status = 'available'
                AND r.id NOT IN (
                    SELECT b.room_id
                    FROM bookings b
                    WHERE b.status IN ('pending', 'confirmed', 'checked_in')
                    AND NOT (
                        b.checkout_date <= ? OR b.checkin_date >= ?
                    )
                )
            )";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iss", $guests, $checkin, $checkout);
    $stmt->execute();

    $result = $stmt->get_result();
    $rooms = [];

    while($row = $result->fetch_assoc()){
        $rooms[] = $row;
    }

    return $rooms;
}


function getAvailableRoomByType($connection, $room_type_id, $checkin, $checkout){
    $sql = "SELECT r.id
            FROM rooms r
            WHERE r.room_type_id = ?
            AND r.status = 'available'
            AND r.id NOT IN (
                SELECT b.room_id
                FROM bookings b
                WHERE b.status IN ('pending', 'confirmed', 'checked_in')
                AND NOT (
                    b.checkout_date <= ? OR b.checkin_date >= ?
                )
            )
            LIMIT 1";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iss", $room_type_id, $checkin, $checkout);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 1){
        return $result->fetch_assoc();
    }else{
        return false;
    }
}


function getRoomTypeById($connection, $room_type_id){
    $sql = "SELECT id, name, price_per_night, amenities
            FROM room_types
            WHERE id = ?";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $room_type_id);
    $stmt->execute();

    $result = $stmt->get_result();

    if($result->num_rows == 1){
        return $result->fetch_assoc();
    }else{
        return false;
    }
}

?>