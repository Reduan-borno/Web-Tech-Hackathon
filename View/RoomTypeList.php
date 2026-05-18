<?php
session_start();
$errors = $_SESSION['formErrors'] ?? [];
unset($_SESSION['formErrors']);
?>

<h2>Manage Room Types</h2>

<form action="../controllers/RoomTypeController.php?action=add" method="POST" enctype="multipart/form-data">
    <label>Name:</label>
    <input type="text" name="name" value="">
    <?php if(isset($errors['name'])) echo "<p style='color:red'>{$errors['name']}</p>"; ?>

    <label>Description:</label>
    <textarea name="description"></textarea>
    <?php if(isset($errors['description'])) echo "<p style='color:red'>{$errors['description']}</p>"; ?>

    <label>Price per night:</label>
    <input type="number" name="price_per_night" step="0.01">
    <?php if(isset($errors['price_per_night'])) echo "<p style='color:red'>{$errors['price_per_night']}</p>"; ?>

    <label>Max capacity:</label>
    <input type="number" name="max_capacity">
    <?php if(isset($errors['max_capacity'])) echo "<p style='color:red'>{$errors['max_capacity']}</p>"; ?>

    <label>Amenities:</label>
    <input type="checkbox" name="amenities[]" value="WiFi"> WiFi
    <input type="checkbox" name="amenities[]" value="AC"> AC
    <input type="checkbox" name="amenities[]" value="TV"> TV

    <label>Thumbnail:</label>
    <input type="file" name="thumbnail">
    <?php if(isset($errors['thumbnail'])) echo "<p style='color:red'>{$errors['thumbnail']}</p>"; ?>

    <button type="submit">Save</button>
</form>

<hr>

<h3>Room Types List</h3>
<table border="1">
    <tr>
        <th>Name</th><th>Description</th><th>Price</th><th>Capacity</th><th>Amenities</th><th>Thumbnail</th><th>Actions</th>
    </tr>
    <?php while($row = $roomTypes->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td><?= $row['price_per_night'] ?></td>
        <td><?= $row['max_capacity'] ?></td>
        <td><?= implode(", ", json_decode($row['amenities'], true)) ?></td>
        <td><img src="../<?= $row['thumbnail_path'] ?>" width="80"></td>
        <td>
            <a href="../controllers/RoomTypeController.php?action=edit&id=<?= $row['id'] ?>">Edit</a>
            <a href="../controllers/RoomTypeController.php?action=delete&id=<?= $row['id'] ?>">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
