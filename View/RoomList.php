<?php
session_start();
$errors = $_SESSION['formErrors'] ?? [];
unset($_SESSION['formErrors']);
?>

<h2>Manage Rooms</h2>

<form action="../controllers/RoomController.php?action=add" method="POST">
    <label>Room Number:</label>
    <input type="text" name="room_number">
    <?php if(isset($errors['room_number'])) echo "<p style='color:red'>{$errors['room_number']}</p>"; ?>

    <label>Floor:</label>
    <input type="number" name="floor">
    <?php if(isset($errors['floor'])) echo "<p style='color:red'>{$errors['floor']}</p>"; ?>

    <label>Room Type:</label>
    <select name="room_type_id">
        <?php while($rt = $roomTypes->fetch_assoc()): ?>
            <option value="<?= $rt['id'] ?>"><?= htmlspecialchars($rt['name']) ?></option>
        <?php endwhile; ?>
    </select>
    <?php if(isset($errors['room_type_id'])) echo "<p style='color:red'>{$errors['room_type_id']}</p>"; ?>

    <label>Status:</label>
    <select name="status">
        <option value="available">Available</option>
        <option value="maintenance">Maintenance</option>
    </select>
    <?php if(isset($errors['status'])) echo "<p style='color:red'>{$errors['status']}</p>"; ?>

    <button type="submit">Save</button>
</form>

<hr>

<h3>Rooms List</h3>
<table border="1">
    <tr>
        <th>Room Number</th><th>Floor</th><th>Type</th><th>Status</th><th>Actions</th>
    </tr>
    <?php while($row = $rooms->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['room_number']) ?></td>
        <td><?= $row['floor'] ?></td>
        <td><?= htmlspecialchars($row['room_type_name']) ?></td>
        <td>
            <span class="badge <?= $row['status'] ?>" 
                  data-room-id="<?= $row['id'] ?>">
                  <?= ucfirst($row['status']) ?>
            </span>
        </td>
        <td>
            <a href="../controllers/RoomController.php?action=edit&id=<?= $row['id'] ?>">Edit</a>
            <a href="../controllers/RoomController.php?action=delete&id=<?= $row['id'] ?>">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<script>
document.querySelectorAll('.badge').forEach(badge => {
    badge.addEventListener('click', function() {
        const roomId = this.dataset.roomId;
        fetch('../controllers/ToggleRoomStatusController.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'room_id=' + roomId
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                this.textContent = data.new_status.charAt(0).toUpperCase() + data.new_status.slice(1);
                this.className = 'badge ' + data.new_status;
            } else {
                alert(data.error);
            }
        });
    });
});
</script>
