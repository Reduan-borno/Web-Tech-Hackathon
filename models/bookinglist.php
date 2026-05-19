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