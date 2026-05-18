<?php

// Create a new room
function createRoom($connection, $roomTypeId, $roomNumber, $floor, $status)
{
    $sql = "INSERT INTO rooms (room_type_id, room_number, floor, status) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iiss", $roomTypeId, $roomNumber, $floor, $status);
    if ($stmt->execute()) {
        return $connection->insert_id;
    } else {
        return false;
    }
}

// Get all rooms with room type info and occupancy
function getAllRoomsWithOccupancy($connection)
{
    $sql = "SELECT 
                r.id,
                r.room_number,
                r.floor,
                r.status,
                rt.id as room_type_id,
                rt.name as room_type_name,
                rt.price_per_night,
                CASE 
                    WHEN r.status = 'maintenance' THEN 'Maintenance'
                    WHEN r.status = 'booked' THEN 'Booked'
                    WHEN b.id IS NOT NULL AND b.status IN ('Confirmed', 'Checked-In') 
                         AND b.checkin_date <= CURDATE() 
                         AND b.checkout_date >= CURDATE() THEN 'Booked'
                    ELSE 'Available'
                END as occupancy_status
            FROM rooms r
            LEFT JOIN room_types rt ON r.room_type_id = rt.id
            LEFT JOIN bookings b ON r.id = b.room_id 
                AND b.status IN ('Confirmed', 'Checked-In')
                AND b.checkin_date <= CURDATE()
                AND b.checkout_date >= CURDATE()
            ORDER BY r.floor ASC, r.room_number ASC";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get room by ID
function getRoomById($connection, $id)
{
    $sql = "SELECT r.id, r.room_number, r.floor, r.status, r.room_type_id, rt.name as room_type_name
            FROM rooms r
            LEFT JOIN room_types rt ON r.room_type_id = rt.id
            WHERE r.id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    }
    return false;
}

// Update room
function updateRoom($connection, $id, $roomTypeId, $roomNumber, $floor, $status)
{
    $sql = "UPDATE rooms SET room_type_id = ?, room_number = ?, floor = ?, status = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("iissi", $roomTypeId, $roomNumber, $floor, $status, $id);
    return $stmt->execute();
}

// Delete room (check if no future bookings and not booked)
function deleteRoom($connection, $id)
{
    // Check if room is marked as booked
    $checkStatus = "SELECT status FROM rooms WHERE id = ?";
    $stmt = $connection->prepare($checkStatus);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    
    if ($room && $room['status'] === 'booked') {
        return false; // Cannot delete if room is marked as booked
    }
    
    $sql = "SELECT COUNT(*) as count FROM bookings 
            WHERE room_id = ? AND status IN ('Confirmed', 'Checked-In') AND checkout_date > CURDATE()";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        return false; // Cannot delete if future bookings exist
    }
    
    $sql = "DELETE FROM rooms WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Check if room number is unique
function roomNumberExists($connection, $roomNumber, $excludeRoomId = null)
{
    if ($excludeRoomId) {
        $sql = "SELECT id FROM rooms WHERE room_number = ? AND id != ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $roomNumber, $excludeRoomId);
    } else {
        $sql = "SELECT id FROM rooms WHERE room_number = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $roomNumber);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

// Toggle room status between 'available' and 'maintenance'
function toggleRoomStatus($connection, $id)
{
    $room = getRoomById($connection, $id);
    if (!$room) {
        return false;
    }
    
    $newStatus = $room['status'] === 'available' ? 'maintenance' : 'available';
    $sql = "UPDATE rooms SET status = ? WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("si", $newStatus, $id);
    if ($stmt->execute()) {
        return $newStatus;
    }
    return false;
}

// Get available room of a specific type for a date range
function getAvailableRoomOfType($connection, $roomTypeId, $checkinDate, $checkoutDate)
{
    $sql = "SELECT r.id FROM rooms r
            WHERE r.room_type_id = ? AND r.status = 'available'
            AND r.id NOT IN (
                SELECT room_id FROM bookings 
                WHERE status IN ('Confirmed', 'Checked-In')
                AND (
                    (checkin_date < ? AND checkout_date > ?)
                    OR (checkin_date >= ? AND checkin_date < ?)
                    OR (checkout_date > ? AND checkout_date <= ?)
                )
            )
            LIMIT 1";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("issssss", $roomTypeId, $checkoutDate, $checkinDate, $checkinDate, $checkoutDate, $checkinDate, $checkoutDate);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    return false;
}
