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