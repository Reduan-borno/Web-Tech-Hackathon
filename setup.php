<?php
/**
 * Task 2 Setup Script
 * This script helps you set up the admin user and verify the database
 */

include "models/db.php";

$database = new db();
$connection = $database->connection();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Task 2 Setup - Meridian Hotel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .section:last-child {
            border-bottom: none;
        }
        .success {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .credentials {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 4px;
            font-family: monospace;
            margin: 15px 0;
        }
        button {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        button:hover {
            background: #764ba2;
        }
        .code {
            background: #f5f5f5;
            padding: 10px;
            border-radius: 4px;
            margin: 10px 0;
            font-family: monospace;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f5f5f5;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🏨 Meridian Hotel - Task 2 Setup</h1>";

// Check database connection
echo "<div class='section'>
    <h2>1. Database Connection</h2>";

if ($connection) {
    echo "<div class='success'>✓ Database connection successful</div>";
} else {
    echo "<div class='error'>✗ Database connection failed: " . $connection->connect_error . "</div>";
    exit;
}

// Check if tables exist
echo "<h3>Tables Check</h3>";
$tables = ['users', 'room_types', 'rooms', 'bookings'];
foreach ($tables as $table) {
    $result = $connection->query("SHOW TABLES LIKE '$table'");
    if ($result && $result->num_rows > 0) {
        echo "<div class='success'>✓ Table '$table' exists</div>";
    } else {
        echo "<div class='error'>✗ Table '$table' NOT found</div>";
    }
}

echo "</div>";

// Admin User Setup
echo "<div class='section'>
    <h2>2. Admin User Setup</h2>";

// Check if admin user exists
$adminCheck = $connection->query("SELECT id, email, name FROM users WHERE role = 'admin' LIMIT 1");
if ($adminCheck && $adminCheck->num_rows > 0) {
    $admin = $adminCheck->fetch_assoc();
    echo "<div class='success'>✓ Admin user already exists</div>";
    echo "<div class='info'>
        <strong>Existing Admin:</strong><br>
        Email: " . htmlspecialchars($admin['email']) . "<br>
        Name: " . htmlspecialchars($admin['name']) . "
    </div>";
    echo "<p>If you've forgotten the password, you can reset it using the form below.</p>";
}

echo "<h3>Create/Reset Admin User</h3>";
echo "<form method='POST' action='' style='background: #f9f9f9; padding: 20px; border-radius: 4px;'>
    <div style='margin-bottom: 15px;'>
        <label style='display: block; margin-bottom: 5px; font-weight: bold;'>Full Name:</label>
        <input type='text' name='admin_name' value='Admin User' style='width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'>
    </div>
    
    <div style='margin-bottom: 15px;'>
        <label style='display: block; margin-bottom: 5px; font-weight: bold;'>Email:</label>
        <input type='email' name='admin_email' value='admin@hotel.local' style='width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'>
    </div>
    
    <div style='margin-bottom: 15px;'>
        <label style='display: block; margin-bottom: 5px; font-weight: bold;'>Password:</label>
        <input type='password' name='admin_password' placeholder='Enter password (min 8 chars)' style='width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'>
    </div>
    
    <div style='margin-bottom: 15px;'>
        <label style='display: block; margin-bottom: 5px; font-weight: bold;'>Phone:</label>
        <input type='tel' name='admin_phone' value='+1234567890' style='width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'>
    </div>
    
    <div style='margin-bottom: 15px;'>
        <label style='display: block; margin-bottom: 5px; font-weight: bold;'>Nationality:</label>
        <input type='text' name='admin_nationality' value='Bangladesh' style='width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;'>
    </div>
    
    <button type='submit' name='create_admin'>Create/Reset Admin User</button>
</form>";

echo "</div>";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_admin'])) {
    $name = trim($_POST['admin_name'] ?? '');
    $email = trim($_POST['admin_email'] ?? '');
    $password = $_POST['admin_password'] ?? '';
    $phone = trim($_POST['admin_phone'] ?? '');
    $nationality = trim($_POST['admin_nationality'] ?? '');
    
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Full name is required";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }
    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters";
    }
    
    if (!empty($errors)) {
        echo "<div class='error'>";
        foreach ($errors as $error) {
            echo "✗ " . htmlspecialchars($error) . "<br>";
        }
        echo "</div>";
    } else {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        
        // Check if admin exists
        $existingAdmin = $connection->query("SELECT id FROM users WHERE role = 'admin'");
        
        if ($existingAdmin && $existingAdmin->num_rows > 0) {
            // Update existing admin
            $sql = "UPDATE users SET name = ?, email = ?, password_hash = ?, phone = ?, nationality = ? WHERE role = 'admin' LIMIT 1";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $passwordHash, $phone, $nationality);
            if ($stmt->execute()) {
                echo "<div class='success'>✓ Admin user updated successfully</div>";
            } else {
                echo "<div class='error'>✗ Error updating admin user: " . $stmt->error . "</div>";
            }
        } else {
            // Create new admin
            $sql = "INSERT INTO users (name, email, password_hash, phone, nationality, role) VALUES (?, ?, ?, ?, ?, 'admin')";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $passwordHash, $phone, $nationality);
            if ($stmt->execute()) {
                echo "<div class='success'>✓ Admin user created successfully</div>";
            } else {
                echo "<div class='error'>✗ Error creating admin user: " . $stmt->error . "</div>";
            }
        }
        
        echo "<div class='info' style='margin-top: 20px;'>
            <strong>Login Credentials:</strong><br>
            <strong>Email:</strong> " . htmlspecialchars($email) . "<br>
            <strong>Password:</strong> " . htmlspecialchars($password) . "<br><br>
            <a href='views/auth/login.php' style='color: #0c5460; text-decoration: underline;'>Go to Login Page →</a>
        </div>";
    }
}

// Test Data Option
echo "<div class='section'>
    <h2>3. Test Data (Optional)</h2>
    <p>Create sample room types and rooms for testing purposes.</p>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_test_data'])) {
    // Create test room types
    $roomTypes = [
        ['Standard Room', 'Basic room with essential amenities', 80.00, 1, '["WiFi","AC","TV"]'],
        ['Deluxe Room', 'Spacious room with premium amenities', 150.00, 2, '["WiFi","AC","TV","Minibar","Safe"]'],
        ['Suite', 'Luxury suite with all amenities', 250.00, 4, '["WiFi","AC","TV","Minibar","Safe","Bathtub","Balcony"]']
    ];
    
    foreach ($roomTypes as $rt) {
        $sql = "INSERT INTO room_types (name, description, price_per_night, max_capacity, amenities) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ssdis", $rt[0], $rt[1], $rt[2], $rt[3], $rt[4]);
        $stmt->execute();
    }
    
    // Create test rooms
    $rooms = [
        [1, '101', 1, 'available'],
        [1, '102', 1, 'available'],
        [2, '201', 2, 'available'],
        [2, '202', 2, 'available'],
        [3, '301', 3, 'available']
    ];
    
    foreach ($rooms as $r) {
        $sql = "INSERT INTO rooms (room_type_id, room_number, floor, status) VALUES (?, ?, ?, ?)";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iiss", $r[0], $r[1], $r[2], $r[3]);
        $stmt->execute();
    }
    
    echo "<div class='success'>✓ Test data created successfully!</div>";
    echo "<div class='info'>
        Created:<br>
        • 3 room types (Standard, Deluxe, Suite)<br>
        • 5 rooms (101, 102, 201, 202, 301)<br>
    </div>";
}

echo "<form method='POST' action=''>
    <button type='submit' name='create_test_data'>Create Test Data</button>
</form>";

echo "</div>";

// Final instructions
echo "<div class='section'>
    <h2>4. Next Steps</h2>
    <ol style='line-height: 1.8;'>
        <li>Make sure you've created the admin user using the form above</li>
        <li>Go to <strong><a href='views/auth/login.php'>Login Page</a></strong></li>
        <li>Login with your admin email and password</li>
        <li>You'll be redirected to the Admin Dashboard</li>
        <li>Click 'Room Types' to start managing room types</li>
        <li>Click 'Rooms' to manage individual rooms</li>
    </ol>
</div>";

echo "</div>
</body>
</html>";
?>
