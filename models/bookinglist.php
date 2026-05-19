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

    public function checkIn(int $bookingId): array {
        // Verify booking exists and is in 'confirmed' state
        $chk  = "SELECT id, status, checkin_date FROM bookings WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($chk);
        $stmt->bind_param('i', $bookingId);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row)                               return ['ok' => false, 'msg' => 'Booking not found.'];
        if ($row['status'] !== 'confirmed')      return ['ok' => false, 'msg' => 'Booking must be in Confirmed state to check in.'];
        if ($row['checkin_date'] !== date('Y-m-d')) return ['ok' => false, 'msg' => 'Check-in is only allowed on the scheduled check-in date.'];

        $sql  = "UPDATE bookings SET status = 'checked_in', actual_checkin = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $bookingId);
        $ok   = $stmt->execute();
        $stmt->close();

        return $ok
            ? ['ok' => true,  'msg' => 'Checked in successfully.']
            : ['ok' => false, 'msg' => 'Database error during check-in.'];
    }

    public function checkOut(int $bookingId): array {
        $chk  = "SELECT id, room_id, status FROM bookings WHERE id = ? LIMIT 1";
        $stmt = $this->db->prepare($chk);
        $stmt->bind_param('i', $bookingId);
        $stmt->execute();
        $row  = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$row)                           return ['ok' => false, 'msg' => 'Booking not found.'];
        if ($row['status'] !== 'checked_in') return ['ok' => false, 'msg' => 'Booking must be in Checked-In state to check out.'];

        
        $sql  = "UPDATE bookings SET status = 'completed' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i', $bookingId);
        $stmt->execute();
        $stmt->close();

        
        $sql2  = "UPDATE rooms SET status = 'available' WHERE id = ?";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param('i', $row['room_id']);
        $stmt2->execute();
        $stmt2->close();

        return ['ok' => true, 'msg' => 'Checked out. Room is now available.'];
    }

    public function getSummaryStats(): array {
        $sql    = "
            SELECT
                COUNT(*)                                              AS total,
                SUM(status = 'confirmed')                             AS confirmed,
                SUM(status = 'pending')                               AS pending,
                SUM(status = 'checked_in')                            AS checked_in,
                SUM(status = 'cancelled')                             AS cancelled,
                COALESCE(SUM(CASE WHEN status != 'cancelled'
                                  THEN total_price END), 0)           AS revenue
            FROM bookings
        ";
        $result = $this->db->query($sql);
        return $result->fetch_assoc() ?? [];
    }

    public function getTodayArrivals(): array {
        $sql = "
            SELECT
                b.id        AS booking_id,
                u.name      AS guest_name,
                r.room_number
            FROM bookings b
            JOIN users u ON b.user_id = u.id
            JOIN rooms r ON b.room_id = r.id
            WHERE b.checkin_date = CURDATE()
              AND b.status = 'confirmed'
            ORDER BY b.id
        ";
        $result = $this->db->query($sql);
        $rows   = [];
        while ($row = $result->fetch_assoc()) $rows[] = $row;
        return $rows;
    }

    