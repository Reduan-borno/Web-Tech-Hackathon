<?php

// Create a new room type
function createRoomType($connection, $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities)
{
    $amenitiesJson = json_encode($amenities);
    $sql = "INSERT INTO room_types (name, description, price_per_night, max_capacity, thumbnail_path, amenities) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssidis", $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenitiesJson);
    if ($stmt->execute()) {
        return $connection->insert_id;
    } else {
        return false;
    }
}

// Get all room types
function getAllRoomTypes($connection)
{
    $sql = "SELECT id, name, description, price_per_night, max_capacity, thumbnail_path, amenities FROM room_types ORDER BY name ASC";
    $stmt = $connection->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get room type by ID
function getRoomTypeById($connection, $id)
{
    $sql = "SELECT id, name, description, price_per_night, max_capacity, thumbnail_path, amenities FROM room_types WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    }
    return false;
}

// Update room type
function updateRoomType($connection, $id, $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenities)
{
    $amenitiesJson = json_encode($amenities);
    
    if ($thumbnailPath) {
        $sql = "UPDATE room_types SET name = ?, description = ?, price_per_night = ?, max_capacity = ?, thumbnail_path = ?, amenities = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssidisi", $name, $description, $pricePerNight, $maxCapacity, $thumbnailPath, $amenitiesJson, $id);
    } else {
        $sql = "UPDATE room_types SET name = ?, description = ?, price_per_night = ?, max_capacity = ?, amenities = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssidsi", $name, $description, $pricePerNight, $maxCapacity, $amenitiesJson, $id);
    }
    
    return $stmt->execute();
}

// Delete room type (check if no rooms use it)
function deleteRoomType($connection, $id)
{
    $sql = "SELECT COUNT(*) as count FROM rooms WHERE room_type_id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        return false; // Cannot delete if rooms use this type
    }
    
    $sql = "DELETE FROM room_types WHERE id = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Decode amenities JSON
function decodeAmenities($amenitiesJson)
{
    return json_decode($amenitiesJson, true) ?: [];
}
