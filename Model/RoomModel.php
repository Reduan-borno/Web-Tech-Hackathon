<?php
class RoomModel {

    public function GetAllRooms($connection, $tableName) {
        $sql = "SELECT r.*, rt.name AS room_type_name 
                FROM $tableName r 
                JOIN room_types rt ON r.room_type_id = rt.id";
        return $connection->query($sql);
    }

    public function CreateRoom($connection, $tableName, $roomNumber, $floor, $roomTypeId, $status) {
        $check = $connection->prepare("SELECT id FROM $tableName WHERE room_number = ?");
        $check->bind_param("s", $roomNumber);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows > 0) return false;

        $sql = "INSERT INTO $tableName (room_number, floor, room_type_id, status) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("siis", $roomNumber, $floor, $roomTypeId, $status);
        return $stmt->execute();
    }

    public function UpdateRoom($connection, $tableName, $id, $roomNumber, $floor, $roomTypeId, $status) {
        $sql = "UPDATE $tableName SET room_number=?, floor=?, room_type_id=?, status=? WHERE id=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("siisi", $roomNumber, $floor, $roomTypeId, $status, $id);
        return $stmt->execute();
    }

    public function DeleteRoom($connection, $tableName, $id) {
        $check = $connection->prepare("SELECT id FROM bookings WHERE room_id=? AND check_in_date > CURDATE()");
        $check->bind_param("i", $id);
        $check->execute();
        $result = $check->get_result();
        if ($result->num_rows > 0) return false;

        $sql = "DELETE FROM $tableName WHERE id=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function ToggleRoomStatus($connection, $tableName, $id) {
        $sql = "SELECT status FROM $tableName WHERE id=?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $newStatus = ($result['status'] === 'available') ? 'maintenance' : 'available';

        $update = $connection->prepare("UPDATE $tableName SET status=? WHERE id=?");
        $update->bind_param("si", $newStatus, $id);
        if ($update->execute()) return $newStatus;
        return false;
    }
}
?>
