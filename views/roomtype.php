<?php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Room Type Management — Admin Panel</title>
    <link rel="stylesheet" href="../assets/admin.css">
    <style>
        .amenity-icon {
            display: inline-block;
            width: 24px;
            height: 24px;
            margin-right: 8px;
            text-align: center;
            line-height: 24px;
            font-size: 14px;
            background: #e8e8e8;
            border-radius: 3px;
            title: attr(data-amenity);
        }

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

        .amenities-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 15px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
        }

        .checkbox-item input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }

        .checkbox-item label {
            margin-bottom: 0;
            font-weight: normal;
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

        .thumbnail-img {
            max-width: 50px;
            max-height: 50px;
            border-radius: 4px;
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

        .icon-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
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
                <a href="roomtypeController.php" class="nav-item active">Room Types</a>
                <a href="roomController.php" class="nav-item">Rooms</a>
                <a href="../views/auth/login.php" class="nav-item logout">Logout</a>
            </nav>
        </div>

        <div class="main-content">
            <h2>Room Type Management</h2>
            <p class="subtitle">Create and manage hotel room types</p>

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
                        <h3>Room Types</h3>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="action" value="create">
                            <button type="submit" class="btn btn-primary" formaction="../controllers/roomtypeController.php?action=create">+ Add Room Type</button>
                        </form>
                    </div>

                    <?php if (empty($roomTypes)): ?>
                        <p>No room types found. <a href="../controllers/roomtypeController.php?action=create">Create one</a></p>
                    <?php else: ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Name</th>
                                    <th>Price/Night</th>
                                    <th>Capacity</th>
                                    <th>Amenities</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($roomTypes as $rt): ?>
                                    <tr>
                                        <td>
                                            <?php if ($rt['thumbnail_path']): ?>
                                                <img src="<?php echo htmlspecialchars($rt['thumbnail_path']); ?>" alt="<?php echo htmlspecialchars($rt['name']); ?>" class="thumbnail-img">
                                            <?php else: ?>
                                                <span style="color: #999;">No image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($rt['name']); ?></td>
                                        <td>$<?php echo number_format($rt['price_per_night'], 2); ?></td>
                                        <td><?php echo $rt['max_capacity']; ?> guests</td>
                                        <td>
                                            <div class="icon-grid">
                                                <?php foreach ($rt['amenities'] as $amenity): ?>
                                                    <span class="amenity-icon" title="<?php echo htmlspecialchars($amenity); ?>">
                                                        <?php
                                                        $icons = [
                                                            'WiFi' => '📶',
                                                            'AC' => '❄️',
                                                            'TV' => '📺',
                                                            'Minibar' => '🍷',
                                                            'Safe' => '🔒',
                                                            'Bathtub' => '🛁',
                                                            'Balcony' => '🪟'
                                                        ];
                                                        echo $icons[$amenity] ?? '✓';
                                                        ?>
                                                    </span>
                                                <?php endforeach; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="../controllers/roomtypeController.php?action=edit&id=<?php echo $rt['id']; ?>">Edit</a>
                                                <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure?');">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="room_type_id" value="<?php echo $rt['id']; ?>">
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
                        <h3 class="form-box-title"><?php echo $action == 'create' ? 'Create Room Type' : 'Edit Room Type'; ?></h3>
                        <form method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="action" value="<?php echo $action; ?>">
                            <?php if ($action == 'edit'): ?>
                                <input type="hidden" name="room_type_id" value="<?php echo $roomType['id']; ?>">
                            <?php endif; ?>

                            <div class="form-group">
                                <label for="name">Room Type Name*</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($roomType['name'] ?? $_POST['name'] ?? ''); ?>" required>
                                <?php if (isset($errors['name'])): ?>
                                    <span class="error-message"><?php echo $errors['name']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="description">Description*</label>
                                <textarea id="description" name="description" required><?php echo htmlspecialchars($roomType['description'] ?? $_POST['description'] ?? ''); ?></textarea>
                                <?php if (isset($errors['description'])): ?>
                                    <span class="error-message"><?php echo $errors['description']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="price_per_night">Price Per Night ($)*</label>
                                <input type="number" id="price_per_night" name="price_per_night" step="0.01" value="<?php echo htmlspecialchars($roomType['price_per_night'] ?? $_POST['price_per_night'] ?? ''); ?>" required>
                                <?php if (isset($errors['price_per_night'])): ?>
                                    <span class="error-message"><?php echo $errors['price_per_night']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="max_capacity">Max Capacity*</label>
                                <input type="number" id="max_capacity" name="max_capacity" value="<?php echo htmlspecialchars($roomType['max_capacity'] ?? $_POST['max_capacity'] ?? ''); ?>" required>
                                <?php if (isset($errors['max_capacity'])): ?>
                                    <span class="error-message"><?php echo $errors['max_capacity']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label for="thumbnail">Thumbnail Image (JPEG/PNG, ≤ 2MB)</label>
                                <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png">
                                <?php if (isset($errors['thumbnail'])): ?>
                                    <span class="error-message"><?php echo $errors['thumbnail']; ?></span>
                                <?php endif; ?>
                                <?php if ($roomType && $roomType['thumbnail_path']): ?>
                                    <p style="font-size: 12px; color: #666; margin-top: 5px;">Current: <img src="<?php echo htmlspecialchars($roomType['thumbnail_path']); ?>" alt="Current" class="thumbnail-img"></p>
                                <?php endif; ?>
                            </div>

                            <div class="form-group">
                                <label>Amenities*</label>
                                <div class="amenities-group">
                                    <?php foreach ($amenitiesOptions as $amenity): ?>
                                        <div class="checkbox-item">
                                            <input type="checkbox" id="amenity_<?php echo $amenity; ?>" name="amenity_<?php echo $amenity; ?>" 
                                                <?php 
                                                $checkedAmenities = [];
                                                // For edits, use database data
                                                if ($action == 'edit' && $roomType) {
                                                    $checkedAmenities = decodeAmenities($roomType['amenities']);
                                                }
                                                // For creates with validation errors, use POST data
                                                if ($action == 'create' && !empty($errors) && isset($_POST['amenity_' . $amenity])) {
                                                    $checkedAmenities[] = $amenity;
                                                }
                                                if (in_array($amenity, $checkedAmenities)) {
                                                    echo 'checked';
                                                }
                                                ?>>
                                            <label for="amenity_<?php echo $amenity; ?>"><?php echo $amenity; ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php if (isset($errors['amenities'])): ?>
                                    <span class="error-message"><?php echo $errors['amenities']; ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="btn-group">
                                <button type="submit" class="btn btn-primary"><?php echo $action == 'create' ? 'Create' : 'Update'; ?> Room Type</button>
                                <a href="../controllers/roomtypeController.php" class="btn btn-secondary" style="text-decoration: none;">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
