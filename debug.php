<?php
/**
 * Task 2 Debug Tool
 * Helps diagnose amenities and delete protection issues
 */

session_start();

// Only allow admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Please login as admin first.";
    exit;
}

include "models/db.php";
include "models/RoomModel.php";
include "models/RoomTypeModel.php";

$database = new db();
$connection = $database->connection();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Task 2 Debug Tool</title>
    <style>
        body {
            font-family: Arial;
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .section {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        h2 {
            color: #667eea;
        }
        .check {
            margin: 15px 0;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid;
        }
        .check.success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .check.error {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f5f5f5;
            font-weight: bold;
        }
        code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
        pre {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
    </style>
</head>
<body>
    <h1>🔍 Task 2 Debug Tool</h1>";

// ===== TEST 1: Room Types Amenities =====
echo "<div class='section'>
    <h2>Test 1: Room Types & Amenities</h2>";

$roomTypes = getAllRoomTypes($connection);

if (empty($roomTypes)) {
    echo "<div class='check error'>✗ No room types found. Create some first.</div>";
} else {
    echo "<div class='check success'>✓ Found " . count($roomTypes) . " room type(s)</div>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Amenities (Raw)</th><th>Amenities (Decoded)</th></tr>";
    
    foreach ($roomTypes as $rt) {
        $raw = htmlspecialchars($rt['amenities']);
        $decoded = decodeAmenities($rt['amenities']);
        $decodedStr = implode(', ', $decoded);
        echo "<tr>";
        echo "<td>" . $rt['id'] . "</td>";
        echo "<td>" . htmlspecialchars($rt['name']) . "</td>";
        echo "<td><code>" . $raw . "</code></td>";
        echo "<td>" . htmlspecialchars($decodedStr) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

echo "</div>";

// ===== TEST 2: Rooms & Delete Protection =====
echo "<div class='section'>
    <h2>Test 2: Rooms & Delete Protection</h2>";

$rooms = getAllRoomsWithOccupancy($connection);

if (empty($rooms)) {
    echo "<div class='check error'>✗ No rooms found. Create some first.</div>";
} else {
    echo "<div class='check success'>✓ Found " . count($rooms) . " room(s)</div>";
    
    echo "<h3>Rooms with Active Bookings</h3>";
    $result = $connection->query("
        SELECT r.id, r.room_number, r.floor, r.status, 
               COUNT(b.id) as booking_count,
               GROUP_CONCAT(b.status) as booking_statuses,
               MAX(b.checkout_date) as latest_checkout
        FROM rooms r
        LEFT JOIN bookings b ON r.id = b.room_id 
        WHERE b.status IN ('Confirmed', 'Checked-In') AND b.checkout_date > CURDATE()
        GROUP BY r.id
    ");
    
    if ($result && $result->num_rows > 0) {
        echo "<table>";
        echo "<tr><th>Room #</th><th>Floor</th><th>Status</th><th>Bookings</th><th>Booking Status</th><th>Latest Checkout</th><th>Protected?</th></tr>";
        
        while ($row = $result->fetch_assoc()) {
            $checkQuery = $connection->prepare("
                SELECT COUNT(*) as count FROM bookings 
                WHERE room_id = ? AND status IN ('Confirmed', 'Checked-In') AND checkout_date > CURDATE()
            ");
            $checkQuery->bind_param("i", $row['id']);
            $checkQuery->execute();
            $checkResult = $checkQuery->get_result();
            $checkRow = $checkResult->fetch_assoc();
            $isProtected = $checkRow['count'] > 0 ? '✓ YES' : '✗ NO';
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['room_number']) . "</td>";
            echo "<td>" . $row['floor'] . "</td>";
            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
            echo "<td>" . $row['booking_count'] . "</td>";
            echo "<td>" . htmlspecialchars($row['booking_statuses']) . "</td>";
            echo "<td>" . htmlspecialchars($row['latest_checkout']) . "</td>";
            echo "<td>" . $isProtected . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='check error'>⚠ No rooms with active future bookings found.</div>";
        echo "<p>To test delete protection, create a booking with checkout_date in the future.</p>";
    }
}

echo "</div>";

// ===== TEST 3: Database Queries =====
echo "<div class='section'>
    <h2>Test 3: Raw Database Queries</h2>";

echo "<h3>Check Delete Protection Query</h3>";
echo "<p>Run this query to check if delete protection works:</p>";
echo "<pre>SELECT r.id, r.room_number, COUNT(b.id) as protected_bookings
FROM rooms r
LEFT JOIN bookings b ON r.id = b.room_id 
WHERE b.status IN ('Confirmed', 'Checked-In') AND b.checkout_date > CURDATE()
GROUP BY r.id;</pre>";

echo "</div>";

// ===== TEST 4: Function Code Review =====
echo "<div class='section'>
    <h2>Test 4: Code Review</h2>";

echo "<h3>deleteRoom() Function</h3>";
echo "<p>The deleteRoom() function should:</p>";
echo "<ol>";
echo "<li>Check for bookings with checkout_date > CURDATE()</li>";
echo "<li>Return false if any found</li>";
echo "<li>Set error message in controller</li>";
echo "<li>Display error to user</li>";
echo "</ol>";

echo "<p><strong>Current behavior:</strong></p>";
echo "<div class='check warning'>";
echo "If delete is still happening:<br>";
echo "1. Check that deleteRoom() returns FALSE correctly<br>";
echo "2. Check that controller catches the error<br>";
echo "3. Check that error message displays in view<br>";
echo "</div>";

echo "</div>";

// ===== TEST 5: Manual Delete Test =====
echo "<div class='section'>
    <h2>Test 5: Manual Test - Create Test Data</h2>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_test_booking'])) {
    $roomId = $_POST['room_id'] ?? null;
    $userId = $_SESSION['user_id'];
    
    if ($roomId) {
        $checkinDate = date('Y-m-d'); // Today
        $checkoutDate = date('Y-m-d', strtotime('+2 days')); // 2 days from now
        
        $sql = "INSERT INTO bookings (user_id, room_id, checkin_date, checkout_date, total_price, status) 
                VALUES (?, ?, ?, ?, 300, 'Confirmed')";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iiss", $userId, $roomId, $checkinDate, $checkoutDate);
        
        if ($stmt->execute()) {
            echo "<div class='check success'>✓ Test booking created for room $roomId</div>";
            echo "<p>Now try to delete this room - it should fail with error message.</p>";
        } else {
            echo "<div class='check error'>✗ Error creating test booking: " . $stmt->error . "</div>";
        }
    }
}

// Show available rooms to create booking for
$rooms = $connection->query("SELECT id, room_number FROM rooms LIMIT 10");
if ($rooms && $rooms->num_rows > 0) {
    echo "<form method='POST'>";
    echo "<label>Create test booking for room: </label>";
    echo "<select name='room_id'>";
    while ($row = $rooms->fetch_assoc()) {
        echo "<option value='" . $row['id'] . "'>Room " . htmlspecialchars($row['room_number']) . "</option>";
    }
    echo "</select>";
    echo "<button type='submit' name='create_test_booking'>Create Test Booking</button>";
    echo "</form>";
}

echo "</div>";

echo "</body>
</html>";
?>
