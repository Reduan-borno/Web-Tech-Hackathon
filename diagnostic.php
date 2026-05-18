<?php
/**
 * Task 2 Diagnostic Tool
 * Helps troubleshoot login and dashboard access issues
 */

session_start();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Task 2 Diagnostic Tool</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 30px auto;
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
        }
        .check {
            margin: 20px 0;
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
        .check.warning {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }
        .check.info {
            background: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
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
        .button-group {
            margin: 20px 0;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        a, button {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        a:hover, button:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class='container'>
        <h1>🔍 Task 2 Diagnostic Tool</h1>";

include "models/db.php";

// 1. Database Connection
echo "<div class='section'><h2>1. Database Connection</h2>";
$database = new db();
$connection = $database->connection();

if ($connection && !$connection->connect_error) {
    echo "<div class='check success'>✓ Database connection successful</div>";
} else {
    echo "<div class='check error'>✗ Database connection failed</div>";
    if ($connection) {
        echo "<p>Error: " . htmlspecialchars($connection->connect_error) . "</p>";
    }
    exit;
}

// 2. Session Status
echo "</div><div class='section'><h2>2. Session Status</h2>";
if (isset($_SESSION['user_id'])) {
    echo "<div class='check success'>✓ Session is active</div>";
    echo "<pre>";
    echo "User ID: " . htmlspecialchars($_SESSION['user_id']) . "\n";
    echo "Name: " . htmlspecialchars($_SESSION['name'] ?? 'Not set') . "\n";
    echo "Role: " . htmlspecialchars($_SESSION['role'] ?? 'Not set') . "\n";
    echo "</pre>";
} else {
    echo "<div class='check error'>✗ No active session - Please login first</div>";
    echo "<div class='button-group'>";
    echo "<a href='views/auth/login.php'>Go to Login</a>";
    echo "</div>";
}

// 3. Check Admin Users
echo "</div><div class='section'><h2>3. Admin Users in Database</h2>";
$result = $connection->query("SELECT id, name, email, role FROM users WHERE role = 'admin'");
if ($result && $result->num_rows > 0) {
    echo "<div class='check success'>✓ Admin users exist (" . $result->num_rows . " found)</div>";
    echo "<table style='width: 100%; border-collapse: collapse;'>";
    echo "<tr style='background: #f5f5f5;'><th style='padding: 10px; text-align: left; border-bottom: 1px solid #ddd;'>ID</th><th style='padding: 10px; text-align: left; border-bottom: 1px solid #ddd;'>Name</th><th style='padding: 10px; text-align: left; border-bottom: 1px solid #ddd;'>Email</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td style='padding: 10px; border-bottom: 1px solid #ddd;'>" . htmlspecialchars($row['email']) . "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='check error'>✗ No admin users found in database</div>";
    echo "<div class='button-group'>";
    echo "<a href='setup.php'>Go to Setup Page</a>";
    echo "</div>";
}

// 4. Check Room Types Table
echo "</div><div class='section'><h2>4. Room Types Table</h2>";
$result = $connection->query("SELECT COUNT(*) as count FROM room_types");
if ($result) {
    $row = $result->fetch_assoc();
    $count = $row['count'];
    if ($count > 0) {
        echo "<div class='check success'>✓ Room types exist (" . $count . " found)</div>";
    } else {
        echo "<div class='check warning'>⚠ No room types created yet</div>";
        echo "<p>You can create room types after logging in to the admin dashboard.</p>";
    }
} else {
    echo "<div class='check error'>✗ Error querying room_types table</div>";
}

// 5. Check Rooms Table
echo "</div><div class='section'><h2>5. Rooms Table</h2>";
$result = $connection->query("SELECT COUNT(*) as count FROM rooms");
if ($result) {
    $row = $result->fetch_assoc();
    $count = $row['count'];
    if ($count > 0) {
        echo "<div class='check success'>✓ Rooms exist (" . $count . " found)</div>";
    } else {
        echo "<div class='check warning'>⚠ No rooms created yet</div>";
    }
} else {
    echo "<div class='check error'>✗ Error querying rooms table</div>";
}

// 6. Check Files
echo "</div><div class='section'><h2>6. Required Files</h2>";
$files = [
    'models/RoomTypeModel.php',
    'models/RoomModel.php',
    'controllers/roomtypeController.php',
    'controllers/roomController.php',
    'api/toggle-status.php',
    'views/roomtype.php',
    'views/room.php',
    'views/admin.php',
    'assets/admin.css',
    'public/uploads/rooms/'
];

foreach ($files as $file) {
    $path = $file;
    if (file_exists($path)) {
        echo "<div class='check success'>✓ " . htmlspecialchars($file) . "</div>";
    } else {
        echo "<div class='check error'>✗ " . htmlspecialchars($file) . " - NOT FOUND</div>";
    }
}

// 7. PHP Version and Extensions
echo "</div><div class='section'><h2>7. System Information</h2>";
echo "<div class='check info'>";
echo "PHP Version: " . phpversion() . "<br>";
echo "MySQLi Extension: " . (extension_loaded('mysqli') ? '✓ Installed' : '✗ Not installed') . "<br>";
echo "JSON Extension: " . (extension_loaded('json') ? '✓ Installed' : '✗ Not installed') . "<br>";
echo "</div>";

// 8. Troubleshooting
echo "</div><div class='section'><h2>8. Troubleshooting</h2>";
echo "<div class='check info'>";
echo "<strong>If you're having issues, try:</strong><br><br>";
echo "1. <strong>Not logged in?</strong><br>";
echo "   → Go to <a href='views/auth/login.php' style='color: #0c5460; text-decoration: underline;'>Login Page</a><br>";
echo "   → Use setup.php to create admin user if needed<br><br>";
echo "2. <strong>Admin user doesn't exist?</strong><br>";
echo "   → Go to <a href='setup.php' style='color: #0c5460; text-decoration: underline;'>Setup Page</a><br>";
echo "   → Create/reset admin user<br><br>";
echo "3. <strong>Blank/white page?</strong><br>";
echo "   → Check browser console (F12) for errors<br>";
echo "   → Check server error logs in XAMPP<br><br>";
echo "4. <strong>Redirected to login?</strong><br>";
echo "   → Session might have expired<br>";
echo "   → Clear browser cookies and login again<br><br>";
echo "5. <strong>CSS not loading?</strong><br>";
echo "   → Clear browser cache (Ctrl+Shift+Delete)<br>";
echo "   → Check that assets/admin.css exists<br>";
echo "</div>";

// 9. Direct Actions
echo "</div><div class='section'><h2>9. Quick Actions</h2>";
echo "<div class='button-group'>";
echo "<a href='views/auth/login.php'>→ Go to Login</a>";
echo "<a href='setup.php'>→ Go to Setup</a>";
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    echo "<a href='views/admin.php'>→ Go to Admin Dashboard</a>";
    echo "<a href='controllers/roomtypeController.php'>→ Room Types</a>";
    echo "<a href='controllers/roomController.php'>→ Rooms</a>";
}
echo "</div>";

echo "</div>
</body>
</html>";
?>
