<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Room Management — Admin Panel</title>
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .form-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-box-title {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="tel"],
        input[type="file"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background: #545b62;
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .error-message {
            color: #dc3545;
            font-size: 12px;
            margin-top: 3px;
        }

        .error-container {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 20px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border-bottom: 2px solid #dee2e6;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }

        tr:hover {
            background: #f5f5f5;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-buttons a,
        .action-buttons button {
            padding: 6px 12px;
            font-size: 12px;
            text-decoration: none;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-buttons a {
            background: #007bff;
            color: white;
        }

        .action-buttons a:hover {
            background: #0056b3;
        }

        .action-buttons button {
            background: #dc3545;
            color: white;
        }

        .action-buttons button:hover {
            background: #c82333;
        }

        .listing-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .listing-section h3 {
            margin-top: 0;
            margin-bottom: 20px;
            color: #333;
        }

        .main-content h2 {
            margin-top: 0;
        }

        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }

        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-badge {
            cursor: pointer;
        }

        .status-badge:hover {
            opacity: 0.8;
        }

        .status-badge.loading {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="page-wrapper">
        <div class="sidebar">
            <p class="panel-brand">Meridian Hotel</p>
            <h3 class="panel-heading">Admin Panel</h3>
            <nav class="sidebar-nav">
                <a href="../views/admin.php" class="nav-item">Dashboard</a>
                <a href="roomtypeController.php" class="nav-item">Room Types</a>
                <a href="roomController.php" class="nav-item active">Rooms</a>
                <a href="../views/auth/login.php" class="nav-item logout">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <h2>Room Management</h2>
            <p class="subtitle">Manage hotel rooms and their status</p>

            <?php if (isset($_GET['success'])): ?>
                <div class="success-message">✓ Operation successful</div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="error-container">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($action == 'list'): ?>
                <div class="listing-section">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3>Rooms</h3>
                        <a href="../controllers/roomController.php?action=create" class="btn btn-primary" style="text-decoration: none;">+ Add Room</a>
                    </div>

                    <?php if (empty($rooms)): ?>
                        <p>No rooms found. <a href="../controllers/roomController.php?action=create">Create one</a></p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Room #</th>
                                    <th>Floor</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Occupancy</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rooms as $r): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($r['room_number']); ?></td>
                                        <td><?php echo $r['floor']; ?></td>
                                        <td><?php echo htmlspecialchars($r['room_type_name']); ?></td>
                                        <td>
                                            <span class="badge status-badge <?php echo $r['status'] === 'available' ? 'badge-success' : 'badge-danger'; ?>" 
                                                  data-room-id="<?php echo $r['id']; ?>"
                                                  onclick="toggleRoomStatus(event, <?php echo $r['id']; ?>)">
                                                <?php echo ucfirst($r['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php 
                                                echo $r['occupancy_status'] === 'Available' ? 'badge-success' : 
                                                    ($r['occupancy_status'] === 'Booked' ? 'badge-warning' : 'badge-danger');
                                            ?>">
                                                <?php echo $r['occupancy_status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="../controllers/roomController.php?action=edit&id=<?php echo $r['id']; ?>">Edit</a>
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure? This cannot be undone if the room has future bookings.');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="room_id" value="<?php echo $r['id']; ?>">
                                                    <button type="submit">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

            <?php elseif ($action == 'create' || $action == 'edit'): ?>
                <div class="form-grid">
                    <div class="form-box">
                        <h3 class="form-box-title"><?php echo $action == 'create' ? 'Create Room' : 'Edit Room'; ?></h3>
                        <form method="POST">
                            <input type="hidden" name="action" value="<?php echo $action; ?>">
                            <?php if ($action == 'edit'): ?>
                                <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="room_number">Room Number*</label>
                                <input type="text" id="room_number" name="room_number" value="<?php echo htmlspecialchars($room['room_number'] ?? $_POST['room_number'] ?? ''); ?>" required>
                                <?php if (isset($errors['room_number'])): ?>
                                    <span class="error-message"><?php echo $errors['room_number']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="floor">Floor*</label>
                                <input type="number" id="floor" name="floor" value="<?php echo htmlspecialchars($room['floor'] ?? $_POST['floor'] ?? '0'); ?>" required>
                                <?php if (isset($errors['floor'])): ?>
                                    <span class="error-message"><?php echo $errors['floor']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="room_type_id">Room Type*</label>
                                <select id="room_type_id" name="room_type_id" required>
                                    <option value="">— Select room type —</option>
                                    <?php foreach ($allRoomTypes as $rt): ?>
                                        <option value="<?php echo $rt['id']; ?>" 
                                            <?php if (($room['room_type_id'] ?? null) == $rt['id']) echo 'selected'; ?>>
                                            <?php echo htmlspecialchars($rt['name']); ?> ($<?php echo number_format($rt['price_per_night'], 2); ?>/night)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($errors['room_type_id'])): ?>
                                    <span class="error-message"><?php echo $errors['room_type_id']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="status">Status*</label>
                                <select id="status" name="status" required>
                                    <option value="available" <?php if (($room['status'] ?? 'available') === 'available') echo 'selected'; ?>>Available</option>
                                    <option value="booked" <?php if (($room['status'] ?? null) === 'booked') echo 'selected'; ?>>Booked</option>
                                    <option value="maintenance" <?php if (($room['status'] ?? null) === 'maintenance') echo 'selected'; ?>>Maintenance</option>
                                </select>
                                <?php if (isset($errors['status'])): ?>
                                    <span class="error-message"><?php echo $errors['status']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary"><?php echo $action == 'create' ? 'Create' : 'Update'; ?> Room</button>
                                <a href="../controllers/roomController.php" class="btn btn-secondary" style="text-decoration: none;">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleRoomStatus(event, roomId) {
            event.preventDefault();
            const badge = event.target;
            
            // Prevent multiple clicks while loading
            if (badge.classList.contains('loading')) return;
            
            badge.classList.add('loading');
            
            fetch(`../api/toggle-status.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ room_id: roomId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    badge.textContent = data.badge_text;
                    badge.className = 'badge status-badge ' + data.badge_class;
                    badge.onclick = function(e) { toggleRoomStatus(e, roomId); };
                } else {
                    alert('Error: ' + data.message);
                    badge.classList.remove('loading');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to toggle status');
                badge.classList.remove('loading');
            });
        }
    </script>
</body>
</html>
