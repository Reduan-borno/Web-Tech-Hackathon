<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard — Meridian Hotel</title>
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .dashboard-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .dashboard-card h3 {
            color: #667eea;
            margin-bottom: 10px;
        }

        .dashboard-card .number {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .dashboard-card p {
            color: #666;
            font-size: 14px;
        }

        .dashboard-card a {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .dashboard-card a:hover {
            background: #764ba2;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="sidebar">
            <p class="panel-brand">Meridian Hotel</p>
            <h3 class="panel-heading">Admin Panel</h3>
            <nav class="sidebar-nav">
                <a href="admin.php" class="nav-item active">Dashboard</a>
                <a href="../controllers/roomtypeController.php" class="nav-item">Room Types</a>
                <a href="../controllers/roomController.php" class="nav-item">Rooms</a>
                <a href="auth/login.php" class="nav-item logout">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <h2>Admin Dashboard</h2>
            <p class="subtitle">Welcome to the Meridian Hotel management system</p>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <h3>Room Types</h3>
                    <p>Manage room types and amenities</p>
                    <a href="../controllers/roomtypeController.php">Manage Room Types</a>
                </div>

                <div class="dashboard-card">
                    <h3>Rooms</h3>
                    <p>Manage individual rooms and occupancy</p>
                    <a href="../controllers/roomController.php">Manage Rooms</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
