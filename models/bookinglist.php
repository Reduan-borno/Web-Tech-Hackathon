<?php
class BookingModel {

    private $db;

    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getBookings(array $filters = []): array {
        $sql = "
            SELECT
                b.id                AS booking_id,
                u.name              AS guest_name,
                r.room_number,
                rt.name             AS room_type,
                b.checkin_date,
                b.checkout_date,
                b.total_price,
                b.status
            FROM bookings b
            JOIN users      u  ON b.user_id      = u.id
            JOIN rooms      r  ON b.room_id       = r.id
            JOIN room_types rt ON r.room_type_id  = rt.id
            WHERE 1=1
        ";

        $params = [];
        $types  = '';

        if (!empty($filters['status']) && is_array($filters['status'])) {
            $allowed      = ['pending', 'confirmed', 'checked_in', 'completed', 'cancelled'];
            $clean        = array_values(array_filter($filters['status'],
                                fn($s) => in_array($s, $allowed)));
            if ($clean) {
                $placeholders = implode(',', array_fill(0, count($clean), '?'));
                $sql         .= " AND b.status IN ($placeholders)";
                foreach ($clean as $s) { $params[] = $s; $types .= 's'; }
            }
        }

        if (!empty($filters['from'])) {
            $sql .= ' AND b.checkin_date >= ?';
            $params[] = $filters['from'];
            $types   .= 's';
        }
        if (!empty($filters['to'])) {
            $sql .= ' AND b.checkin_date <= ?';
            $params[] = $filters['to'];
            $types   .= 's';
        }

        $sql .= ' ORDER BY b.created_at DESC';

        $stmt = $this->db->prepare($sql);
        if ($params) $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result   = $stmt->get_result();
        $bookings = [];
        while ($row = $result->fetch_assoc()) $bookings[] = $row;
        $stmt->close();

        return $bookings;
    }

    public function getBookingById(int $id): ?array {
        $sql = "
            SELECT
                b.id                AS booking_id,
                u.name              AS guest_name,
                u.email             AS guest_email,
                u.phone             AS guest_phone,
                r.room_number,
                rt.name             AS room_type,
                b.checkin_date,
                b.checkout_date,
                b.total_price,
                b.status,
                b.actual_checkin,
                b.created_at
            FROM bookings b
            JOIN users      u  ON b.user_id      = u.id
            JOIN rooms      r  ON b.room_id       = r.id
            JOIN room_types rt ON r.room_type_id  = rt.id
            WHERE b.id = ?
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $row ?: null;
    }

     public function updateStatus(int $id, string $status): bool {
        $allowed = ['pending', 'confirmed', 'checked_in', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) return false;

        $sql  = 'UPDATE bookings SET status = ? WHERE id = ?';
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        $ok   = $stmt->execute();
        $stmt->close();
        return $ok;
    }
    

    